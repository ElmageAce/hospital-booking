<template>

    <div class="row">

        <div v-for="doctor in doctors" :key="doctor.id" class="col-xs-12 col-sm-6 col-md-4">

            <div class="white-box doctors">
                <div @click="selectDoctor(doctor)" class="row">
                    <div class="col-4 text-center">
                        <a :href="`/profile/${doctor.id}`">
                            <img :src="doctor.avatar || default_avatar" width="96" alt="user" class="img-circle img-responsive">
                        </a>
                    </div>
                    <div class="col-8">
                        <h3 class="box-title m-b-0">{{ doctor.name }}</h3>
                        <small>Cardio</small>

                        <address>
                            {{ doctor.address }}
                            <br/><br/>
                            Tel: {{ doctor.phone_number }}
                        </address>

                    </div>

                </div>
            </div>

        </div>

    </div>

</template>

<script>

    import { mapGetters, mapActions } from 'vuex';

    export default {
        name: "DoctorsComponent",
        props: {
            user_id: Number
        },
        methods: {

            ...mapActions({
                getUserData: 'users/getUserData',
                getDoctors: 'users/getDoctors'
            }),

            selectDoctor(doctor){
                this.$store.commit('appointments/setSelectedDoctor', doctor);
            }

        },
        computed: {

            ...mapGetters({
                requesting: 'users/requesting',
                default_avatar: 'users/default_avatar',
                selected_doctor: 'appointments/selected_doctor',
                doctors: 'users/doctors',
                user: 'users/user'
            }),

        }
    }
</script>

<style scoped>
    .doctors {
        cursor: pointer;
    }
    .doctors:hover {
        border: #0b67cd 1px solid;
    }
</style>
