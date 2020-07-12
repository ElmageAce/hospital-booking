<template>

    <!-- .row -->
    <div class="row">

        <div class="col-md-4 col-xs-12">

            <mini-profile></mini-profile>

        </div>

        <div class="col-md-8 col-xs-12">
            <div class="white-box">

                <div v-if="user.appointments" class="row">
                    <div class="col-md-6 col-xs-12 text-center">
                        <p class="text-success">
                            <i class="ti-calendar"></i> Appointments
                        </p>
                        <h1>{{ user.appointments.length }}</h1>
                    </div>
                    <div class="col-md-6 col-xs-12 text-center">
                        <p class="text-blue">
                            <i class="fa fa-user-md"></i> Doctors Visited
                        </p>
                        <h1>{{ doctors_visited }}</h1>
                    </div>
                </div>

                <div v-if="user.schedules" class="row">
                    <div class="col-md-6 col-xs-12 text-center">
                        <p class="text-success">
                            <i class="ti-calendar"></i> Schedules
                        </p>
                        <h1>{{ user.schedules.length }}</h1>
                    </div>
                    <div class="col-md-6 col-xs-12 text-center">
                        <p class="text-blue">
                            <i class="fa fa-user-md"></i> Patients
                        </p>
                        <h1>{{ patients_available }}</h1>
                    </div>
                </div>

                <hr>

                <h4 class="m-t-30">Latest Checkup Report</h4>

                <p class="m-t-30">
                    Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.
                    In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo.
                    Nullam dictum felis eu pede mollis pretium. Integer tincidunt.Cras dapibus.
                    Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo
                    ligula, porttitor eu, consequat vitae, eleifend ac, enim.
                </p>


            </div>
        </div>
    </div>
    <!-- /.row -->

</template>

<script>
    import { mapGetters, mapActions } from 'vuex';

    export default {
        name: "ProfileComponent",
        props: {
            user_id: Number,
            profile_id: Number
        },
        methods: {

            ...mapActions({
                getUserData: 'users/getUserData'
            }),

        },
        computed: {

            doctors_visited(){
                const doctors = [];
                if(this.user.appointments){
                    this.user.appointments.forEach(booking => {

                        if(doctors.indexOf(booking.doctor_id) === -1)
                            doctors.push(booking.doctor_id);
                    });
                }

                return doctors.length;
            },

            patients_available(){
                const patients = [];
                if(this.user.schedules){
                    this.user.schedules.forEach(booking => {

                        if(patients.indexOf(booking.patient_id) === -1)
                            patients.push(booking.patient_id);
                    });
                }

                return patients.length;
            },

            ...mapGetters({
                requesting: 'users/requesting',
                editing: 'users/editing',
                default_avatar: 'users/default_avatar',
                user: 'users/user'
            }),

        },
        created() {
            this.getUserData(this.profile_id);
        }
    }
</script>

<style scoped>

</style>
