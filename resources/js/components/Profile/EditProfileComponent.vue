<template>

    <!-- .row -->
    <div class="row">

        <div class="col-md-4 col-xs-12 hidden-sm hidden-xs">

            <mini-profile></mini-profile>

        </div>

        <div class="col-md-8 col-xs-12">
            <div class="white-box">

                <a :href="`/profile/${user_id}`" class="btn btn-rounded btn-info float-left">
                    <i class="fa fa-backward"></i>
                    Back
                </a>

                <h3 class="page-title text-center mb-5">User Details</h3>

                <form @submit.prevent="updateUserData(user_id)" class="form-material form-horizontal">

                    <div class="text-center mb-5">
                        <img :src="avatar_url || user.avatar || default_avatar"
                             alt="user" class="img-circle img-responsive mx-auto" width="128">
                    </div>

                    <div class="form-group">
                        <div class="col-12 text-center">
                            <button @click.prevent="clickUploadButton" type="button"
                                    class="btn btn-outline btn-rounded btn-info">
                                <i class="fa fa-upload"></i>
                                Select Photo
                            </button>
                            <input @change="selectImage" type="file" name="avatar" id="avatar" class="d-none" accept="image/*">
                            <div v-show="errors['avatar']" class="text-danger mt-2">{{ errors['avatar'] }}</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" class="form-control"
                                   v-model="name"
                                   placeholder="Enter your name" autofocus>
                            <div v-show="errors['name']" class="text-danger">{{ errors['name'] }}</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6">
                            <label class="col-md-12" for="date">Date of Birth</label>
                            <input type="date" id="date" name="date" class="form-control"
                                   v-model="dob"
                                   placeholder="enter your birth date">
                            <div v-show="errors['dob']" class="text-danger">{{ errors['dob'] }}</div>
                        </div>

                        <div class="col-md-6">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" class="form-control"
                                   v-model="phone"
                                   placeholder="enter your phone number">
                            <div v-show="errors['phone']" class="text-danger">{{ errors['phone'] }}</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12">
                            <label for="address">Address</label>
                            <textarea id="address" name="address" class="form-control"
                                      v-model="address" rows="3"></textarea>
                            <div v-show="errors['address']" class="text-danger">{{ errors['address'] }}</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <button type="submit" :disabled="requesting" class="btn btn-info btn-block btn-rounded waves-effect waves-light">
                                <i v-if="requesting && updating" class="fa fa-cog"></i>
                                {{ requesting && updating ? 'Updating' : 'Update' }}
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
    <!-- /.row -->

</template>

<script>
    import { mapGetters, mapActions } from 'vuex';

    export default {
        name: "EditProfileComponent",
        props: {
            user_id: Number
        },
        data(){
            return {
                avatar_url: '',
            }
        },
        methods: {

            clickUploadButton(){
                document.querySelector('#avatar').click();
            },

            selectImage(event){
                const file = event.target.files[0];

                if(this.avatar_url) URL.revokeObjectURL(this.avatar_url);
                this.avatar_url = URL.createObjectURL(file);

                this.$store.commit('users/updateAvatar', file);
            },

            ...mapActions({

                getUserData: 'users/getUserData',

                updateUserData: 'users/updateUserData',

                editProfile: 'users/editProfile',

                getSwal: 'users/getSwal'
            }),

        },
        computed: {

            name: {
                get () {
                    return this.$store.state.users.form.name
                },
                set (value) {
                    this.$store.commit('users/updateFormName', value)
                }
            },
            dob: {
                get () {
                    return this.$store.state.users.form.dob
                },
                set (value) {
                    this.$store.commit('users/updateFormDate', value)
                }
            },
            phone: {
                get () {
                    return this.$store.state.users.form.phone
                },
                set (value) {
                    this.$store.commit('users/updatePhone', value)
                }
            },
            address: {
                get () {
                    return this.$store.state.users.form.address
                },
                set (value) {
                    this.$store.commit('users/updateAddress', value)
                }
            },

            ...mapGetters({

                requesting: 'users/requesting',

                updating: 'users/updating',

                errors: 'users/errors',

                editing: 'users/editing',

                default_avatar: 'users/default_avatar',

                user: 'users/user',

                form: 'users/form'
            }),

        },
        created() {
            this.getUserData(this.user_id);
            this.editProfile();
            this.getSwal(this.$swal);
        }
    }
</script>

<style scoped>

</style>
