<template>

    <div class="login-box login-sidebar">
        <div class="white-box">
            <form class="form-horizontal form-material" @submit.prevent="login" id="loginform">

                <app-logo></app-logo>

                <div class="form-group m-t-40">
                    <div class="col-xs-12">
                        <label for="email" hidden>Email</label>
                        <input class="form-control" id="email" type="email" v-model="details.email" placeholder="Email">
                        <div v-show="errors.email" class="text-danger">{{ errors.email }}</div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-xs-12">
                        <label for="password">Password</label>
                        <input class="form-control" id="password" type="password" v-model="details.password" placeholder="Password">
                        <div v-show="errors.password" class="text-danger">{{ errors.password }}</div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-12">
                        <div class="checkbox checkbox-primary pull-left p-t-0">
                            <input id="checkbox-signup" type="checkbox" v-model="details.remember">
                            <label for="checkbox-signup"> Remember me </label>
                        </div>

                        <a href="/password/reset" class="text-dark pull-right">
                            <i class="fa fa-lock m-r-5"></i> Forgot password
                        </a>
                    </div>
                </div>

                <div class="form-group text-center m-t-20">
                    <div class="col-xs-12">
                        <button type="submit" :disabled="requesting"
                                class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light">
                            <i v-show="requesting && signing_in" class="fa fa-cog fa-spin"></i>
                            {{ requesting && signing_in ? 'Signing In' : 'Sign In'}}
                        </button>
                    </div>
                </div>

                <div class="form-group m-b-0">
                    <div class="col-sm-12 text-center">
                        <p>Don't have an account? <a href="/register" class="text-primary m-l-5"><b>Sign Up</b></a></p>
                    </div>
                </div>

            </form>

        </div>

    </div>

</template>

<script>

    export default {
        name: "LoginComponent",
        data() {
            return {
                details: {
                    email: '',
                    password: '',
                    remember: false
                },
                errors: {
                    email: null,
                    password: null
                },
                theme_color: '#01c0c8',
                hasError: false,
                requesting: false,
                signing_in: false
            }
        },
        methods: {
            async login(){
                if(this.requesting || this.signing_in) return false;

                this.hasError = false;
                this.errors = [];

                this.requesting = true;
                this.signing_in = true;

                try {

                    const response = await fetch('/login', {
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
                        window.location.href = '/home';
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
        }
    }
</script>

<style scoped>

</style>
