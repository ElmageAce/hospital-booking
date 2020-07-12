<template>

    <div class="w-100">

        <doctors-grid v-show="!selected_doctor"></doctors-grid>

        <div v-show="selected_doctor" class="row">
            <div class="col-sm-12">
                <div class="white-box">

                    <button @click.prevent="resetDoctor" type="button" class="btn btn-info btn-rounded float-left">
                        <i class="fa fa-backward"></i>
                        Back
                    </button>
                    <div class="clearfix"></div>
                    <h3 class="box-title my-5 text-center">Appointment Information</h3>

                    <form @submit.prevent="bookAppointment(user_id)" class="form-material form-horizontal">

                        <div class="form-group">

                            <div class="col-xs-12 col-md-6 mb-5">
                                <label for="title">Service / Title</label>
                                <select v-model="title" id="title" class="form-control">
                                    <option>Select Service</option>
                                    <option>Routine Checkup</option>
                                    <option>Dental Checkup</option>
                                    <option>Full Body Checkup</option>
                                    <option>ENT Checkup</option>
                                    <option>Heart Checkup</option>
                                    <option>Others</option>
                                </select>
                                <div v-show="errors['title']" class="text-danger">{{ errors['title'] }}</div>
                            </div>

                            <div class="col-xs-12 col-md-6">
                                <label for="date">Date & Time of Appointment</label>
                                <input type="datetime-local" id="date" name="date" v-model="date"
                                       class="form-control" placeholder="Enter date and time of appointment">
                                <div v-show="errors['date']" class="text-danger">{{ errors['date'] }}</div>
                            </div>

                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <label for="desc">Description / Note</label>
                                <textarea v-model="desc" id="desc" class="form-control" rows="5"></textarea>
                                <div v-show="errors['desc']" class="text-danger">{{ errors['desc'] }}</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-12 text-center">
                                <button type="submit" :disabled="requesting" class="btn btn-info btn-rounded waves-effect waves-light m-r-10">
                                    <i v-show="requesting && booking" class="fa fa-spinner fa-spin"></i>
                                    {{ requesting && booking ? 'Booking' : 'Book Appointment' }}
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>

</template>

<script>

    import { mapGetters, mapActions } from 'vuex';

    export default {
        name: "CreateAppointment",
        props: {
            user_id: Number
        },
        methods: {

            ...mapActions({
                getUserData: 'users/getUserData',
                getDoctors: 'users/getDoctors',
                getSwal: 'appointments/getSwal',
                bookAppointment: 'appointments/bookAppointment'
            }),

            resetDoctor(){
                this.$store.commit('appointments/setSelectedDoctor', null);
            },

        },
        computed: {

            title: {
                get () {
                    return this.$store.state.appointments.appointment.title;
                },
                set (value) {
                    this.$store.commit('appointments/updateFormTitle', value)
                }
            },

            date: {
                get () {
                    return this.$store.state.appointments.appointment.date;
                },
                set (value) {
                    this.$store.commit('appointments/updateFormDate', value)
                }
            },

            desc: {
                get () {
                    return this.$store.state.appointments.appointment.desc;
                },
                set (value) {
                    this.$store.commit('appointments/updateFormDesc', value)
                }
            },

            ...mapGetters({
                requesting: 'appointments/requesting',
                booking: 'appointments/booking',
                errors: 'appointments/errors',
                default_avatar: 'users/default_avatar',
                selected_doctor: 'appointments/selected_doctor',
                doctors: 'users/doctors',
                user: 'users/user'
            }),

        },
        created() {
            this.getUserData(this.user_id);
            this.getSwal(this.$swal);
            this.getDoctors();
        }

    }
</script>

<style scoped>

</style>
