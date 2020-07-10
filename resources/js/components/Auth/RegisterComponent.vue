<template>

    <div class="login-box login-sidebar">
        <div class="white-box">
            <form @submit.prevent="register" class="form-horizontal form-material" id="loginform">

                <app-logo></app-logo>

                <div class="form-group m-t-20">
                    <div class="col-xs-12">
                        <label for="name" hidden>Name</label>
                        <input class="form-control" id="name" type="text" v-model="details.name" required placeholder="Name">
                        <div v-show="errors.name" class="text-danger">{{ errors.name }}</div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-12">
                        <label class="control-label" for="role" hidden>Designation</label>
                        <select @change="updateRole" id="role" class="form-control">
                            <option value="" selected disabled>Select Designation</option>
                            <option v-for="role in roles" :key="role.id" :value="role.id">
                                {{ role.name }}
                            </option>
                        </select>
                        <div v-show="errors.role" class="text-danger">{{ errors.role }}</div>
                    </div>
                </div>

                <div class="form-group ">
                    <div class="col-xs-12">
                        <label for="email" hidden>Email</label>
                        <input class="form-control" id="email" type="email" v-model="details.email" required placeholder="Email">
                        <div v-show="errors.email" class="text-danger">{{ errors.email }}</div>
                    </div>
                </div>

                <div class="form-group ">
                    <div class="col-xs-12">
                        <label for="password" hidden>Password</label>
                        <input class="form-control" id="password" type="password" v-model="details.password" required placeholder="Password">
                        <div v-show="errors.password" class="text-danger">{{ errors.password }}</div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-12">
                        <label for="password-confirm" hidden>Confirm Password</label>
                        <input class="form-control" id="password-confirm" type="password" v-model="details.password_confirmation" required placeholder="Confirm Password">
                        <div v-show="errors.password_confirmation" class="text-danger">{{ errors.password_confirmation }}</div>
                    </div>
                </div>

                <div class="form-group text-center m-t-20">
                    <div class="col-xs-12">
                        <button type="submit" :disabled="requesting"
                                class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light">
                            <i v-show="requesting && registering" class="fa fa-cog fa-spin"></i>
                            {{ requesting && registering ? 'Signing Up' : 'Sign Up'}}
                        </button>
                    </div>
                </div>

                <div class="form-group m-b-0">
                    <div class="col-sm-12 text-center">
                        <p>Already have an account? <a :href="'/login'" class="text-primary m-l-5"><b>Sign In</b></a></p>
                    </div>
                </div>

            </form>
        </div>
    </div>

</template>

<script>

    export default {
        name: "RegisterComponent",
        data() {
            return {
                details: {
                    name: '',
                    email: '',
                    role: null,
                    password: '',
                    password_confirmation: '',
                    remember: false
                },
                errors: {
                    name: null,
                    email: null,
                    role: null,
                    password: null,
                    password_confirmation: null
                },
                theme_color: '#01c0c8',
                roles: [],
                hasError: false,
                requesting: false,
                registering: false
            }
        },
        methods: {

            updateRole(event){
                this.details.role = +event.target.value;
            },

            async getRoles(){
                this.requesting = true;

                try {
                    const response = await fetch('/roles/json');

                    if(response.status !== 200){
                        this.hasError = true;
                    }

                    const json = await response.json();

                    if(json.status === 'success'){
                        this.roles = json.data;
                    } else if(this.hasError) {
                        this.$swal.fire({
                            title: "Oops!",
                            text: json.message || "There was an error, please try again!",
                            type: "error",
                            confirmButtonColor: this.theme_color,
                            confirmButtonText: "Okay"
                        });
                    }

                } catch (e) {
                    console.error(`Error: ${e}`);
                }
                
                this.requesting = false;
            },

            async register(){
                
                if(this.requesting || this.registering) return false;

                this.hasError = false;
                this.errors = [];

                this.requesting = true;
                this.registering = true;
                
                try {
                    
                    const response = await fetch('/register', {
                        method: 'post',
                        body: JSON.stringify(this.details),
                        headers: {
                            'Content-type': 'application/json',
                            'X-CSRF-TOKEN': window.Laravel.csrfToken,
                            "Accept": "application/json, text-plain, */*",
                            "X-Requested-With": "XMLHttpRequest",
                        },
                        credentials: "same-origin"
                    });
                    
                    if (response.status === 422){
                        this.hasError = true;
                    } else {
                        window.location.href = '/email/verify';
                    }

                    const json = await response.json();

                    if(this.hasError){

                        if(json.errors) {

                            const errorKeys = Object.keys(json.errors);

                            errorKeys.forEach((key) => {
                                this.errors[key] = json.errors[key][0];
                            });

                        }

                        this.$swal.fire({
                            title: "Oops!",
                            text: json.message || "There was an error processing your registration!",
                            type: "error",
                            confirmButtonColor: this.theme_color,
                            confirmButtonText: "Okay"
                        });

                    }
                    
                } catch (e) {
                    console.error(`Error: ${e}`);
                }

                this.requesting = false;
                this.registering = false;
            }
        },
        created() {
            this.getRoles();
        }
    }
</script>

<style scoped>

</style>
