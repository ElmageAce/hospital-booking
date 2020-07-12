<template>
    <div class="white-box">
        <div class="text-center">
            <img :src="user.avatar || default_avatar"
                 alt="user" class="img-circle img-responsive mx-auto" width="128">
        </div>
        <div class="user-btm-box">
            <!-- .row -->
            <div class="row text-center m-t-10">
                <div class="col-md-12"><strong>Name</strong>
                    <p>{{ user.name }}</p>
                </div>
            </div>
            <!-- /.row -->
            <hr>
            <!-- .row -->
            <div class="row text-center m-t-10">

                <div class="col-md-12"><strong>Email ID</strong>
                    <p>{{ user.email }}</p>
                </div>
            </div>
            <!-- /.row -->
            <hr>
            <!-- .row -->
            <div class="row text-center m-t-10">
                <div class="col-md-6 b-r"><strong>Date of Birth</strong>
                    <p>{{ user.dob }}</p>
                </div>
                <div class="col-md-6"><strong>Phone</strong>
                    <p>{{ user.phone_number }}</p>
                </div>
            </div>
            <!-- /.row -->
            <hr>
            <!-- .row -->
            <div class="row text-center m-t-10">
                <div class="col-md-12"><strong>Address</strong>
                    <p>{{ user.address }}</p>
                </div>
            </div>
            <hr>
            <div v-if="!editing" class="row text-center m-t-10">
                <div class="col-12">
                    <a v-if="user.id" :href="`/profile/edit/${user.id}`"
                       class="btn btn-block btn-info btn-rounded">Edit Profile</a>
                </div>
            </div>
            <!-- /.row -->

        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';

    export default {
        name: "MiniProfileComponent",
        methods: {

            ...mapActions({
                getUserData: 'users/getUserData'
            }),

        },
        computed: {

            ...mapGetters({
                requesting: 'users/requesting',
                default_avatar: 'users/default_avatar',
                editing: 'users/editing',
                user: 'users/user'
            }),

        }
    }
</script>

<style scoped>

</style>
