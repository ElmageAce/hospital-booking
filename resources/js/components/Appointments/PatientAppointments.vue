<template>

    <div class="row">

        <div class="col-12">
            <div class="white-box mx-0">

                <a href="/appointments/create" class="btn btn-info btn-sm btn-rounded float-right mb-3">
                    <i class="fa fa-calendar"></i>
                    Book
                </a>

                <div v-if="appointments.length > 0" class="table-responsive">
                    <table class="table color-table info-table table-hover">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th class="hidden-xs hidden-sm">Doctor</th>
                                <th>Date</th>
                                <th class="hidden-xs hidden-sm">Period</th>
                                <th class="hidden-xs hidden-sm">Status</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="appointment in appointments" :key="appointment.id">
                                <td>
                                    <h4>{{ appointment.service }}</h4>
                                </td>
                                <td class="hidden-xs hidden-sm">
                                    <a class="text-success" :title="appointment.doctor.name"
                                       :href="`/profile/${appointment.doctor.id}`">
                                        <img :src="appointment.doctor.avatar || default_avatar"
                                             width="48" class="img-circle img-responsive"
                                             :alt="`${appointment.doctor.name} avatar`">
                                    </a>
                                </td>
                                <td>{{ appointment.date }}</td>
                                <td class="hidden-xs hidden-sm">{{ appointment.start_time }} - {{ appointment.stop_time }}</td>
                                <td class="hidden-xs hidden-sm">
                                    <span class="label" :class="setBadgeClass(appointment.status)">
                                        {{ appointment.status }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <button @click="viewAppointment(appointment)" data-toggle="modal" data-target="#appointment-modal"
                                            class="btn btn-sm btn-rounded btn-info mr-2 mb-2">
                                        <i class="fa fa-eye"></i> View
                                    </button>
                                    <button v-show="appointment.status !== 'canceled'"
                                            @click="cancelAppointment(appointment)"
                                            class="btn btn-sm btn-rounded btn-danger mr-2 mb-2">
                                        <i class="fa fa-close"></i> Cancel
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <p v-if="!requesting && appointments.length === 0">You have made no appointments.</p>

                <div v-if="requesting && appointments.length === 0" class="text-center">
                    <i class="fa fa-cog fa-spin"></i>
                </div>

            </div>
        </div>

        <!-- /.modal -->
        <div v-show="viewing_appointment" id="appointment-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">{{ selected_appointment.service }}</h4>
                    </div>
                    <div class="modal-body">
                        <h5 class="text-center">Doctor</h5>
                        <div class="row">
                            <div class="col-xs-12 col-sm-4">
                                <a class="text-success" :title="selected_appointment.doctor.name"
                                   :href="`/profile/${selected_appointment.doctor.id}`">
                                    <img :src="selected_appointment.doctor.avatar || default_avatar"
                                         width="96" class="img-circle img-responsive"
                                         :alt="`${selected_appointment.doctor.name} avatar`">
                                </a>
                            </div>
                            <div class="col-xs-12 col-sm-8">
                                <ul class="list-icons">
                                    <li>
                                        <i class="fa fa-caret-right text-info"></i>
                                        <a class="text-secondary font-bold" :href="`/profile/${selected_appointment.doctor.id}`">
                                            {{ selected_appointment.doctor.name }}
                                        </a>
                                    </li>
                                    <li>
                                        <i class="fa fa-caret-right text-info"></i>
                                        <a class="text-secondary font-bold" :href="`mailto:${selected_appointment.doctor.email}`">
                                            {{ selected_appointment.doctor.email }}
                                        </a>
                                    </li>
                                    <li>
                                        <i class="fa fa-caret-right text-info"></i>
                                        <a class="text-secondary font-bold" :href="`tel:${selected_appointment.doctor.phone_number}`">
                                            {{ selected_appointment.doctor.phone_number }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-md-8">

                                <ul class="list-icons">
                                    <li>
                                        <i class="fa fa-caret-right text-info"></i>
                                        <span class="font-bold">Date:</span>
                                        {{ selected_appointment.date }}
                                    </li>
                                    <li>
                                        <i class="fa fa-caret-right text-info"></i>
                                        <span class="font-bold">Time:</span>
                                        {{ selected_appointment.start_time }} - {{ selected_appointment.stop_time }}
                                    </li>
                                </ul>
                            </div>
                            <div class="col-xs-12 col-md-4">
                                <span class="label" :class="setBadgeClass(selected_appointment.status)">
                                    {{ selected_appointment.status }}
                                </span>
                            </div>
                            <div class="col-12 mt-2">
                                {{ selected_appointment.desc }}
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-rounded waves-effect" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger btn-rounded waves-effect waves-light"
                                v-show="selected_appointment.status !== 'canceled'"
                                @click="cancelAppointment(selected_appointment)">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Button trigger modal -->

    </div>

</template>

<script>
    import { mapGetters, mapActions } from 'vuex';

    export default {
        name: "PatientAppointments",
        props: {
            user_id: Number
        },
        methods: {

            ...mapActions({
                getUserData: 'users/getUserData',
                getSwal: 'appointments/getSwal',
                getAppointments: 'appointments/getAppointments',
                updateAppointment: 'appointments/updateAppointment',
                toggleAppointment: 'appointments/toggleAppointment',
            }),

            setBadgeClass(status){
                if(status === 'completed') return 'label-success';
                if(status === 'pending') return 'label-warning';
                if(status === 'canceled') return 'label-danger';

                return 'label-info';
            },

            viewAppointment(appointment){
                this.$store.commit('appointments/setSelectedAppointment', appointment);
                this.toggleAppointment();
            },

            cancelAppointment(appointment){
                const data = {
                    user_id: this.user_id,
                    status: 'canceled'
                };
                this.$store.commit('appointments/setSelectedAppointment', appointment);
                this.updateAppointment(data);
            }

        },
        computed: {

            ...mapGetters({
                requesting: 'appointments/requesting',
                errors: 'appointments/errors',
                appointments: 'appointments/appointments',
                selected_appointment: 'appointments/selected_appointment',
                viewing_appointment: 'appointments/viewing_appointment',
                default_avatar: 'users/default_avatar',
                user: 'users/user'
            }),

        },
        created() {
            this.getAppointments();
            this.getUserData(this.user_id);
            this.getSwal(this.$swal);
        }
    }
</script>

<style scoped>

</style>
