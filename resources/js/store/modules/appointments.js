const state = {
    appointment: {
        patient: null,
        doctor: null,
        title: '',
        desc: '',
        date: ''
    },
    selected_appointment: {
        doctor: {}
    },
    appointments: [],
    selected_doctor: null,
    viewing_appointment: false,
    requesting: false,
    booking: false,
    swal: null,
    errors: [],
};


const getters = {
    selected_doctor: state => state.selected_doctor,
    appointments: state => state.appointments,
    selected_appointment: state => state.selected_appointment,
    appointment: state => state.appointment,
    requesting: state => state.requesting,
    booking: state => state.booking,
    viewing_appointment: state => state.viewing_appointment,
    swal: state => state.swal,
    errors: state => state.errors,
};


const actions = {

    async getAppointments({ commit, getters }){
        if(getters.requesting) return false;

        let error = false;
        const url = '/appointments/json/get';
        commit('setRequesting', true);

        try {

            const response = await fetch(url);

            if(response.status !== 200) error = true;

            const json = await response.json();

            if(!error && json.status === 'success') {
                commit('setAppointment', json.data);
            }

        } catch (e) {
            console.error(e);
        }

        commit('setRequesting', false);
    },

    async bookAppointment({ commit, getters }, user_id){
        if(getters.requesting || getters.booking) return false;

        let error = false;
        // SET patient id
        commit('setPatient', user_id);
        // SET selected doctors ID
        commit('setDoctor', getters.selected_doctor.id);

        commit('setErrors', []);
        commit('setRequesting', true);
        commit('setBooking', true);

        try {
            const response = await fetch('/appointments', {
                method: 'post',
                body: JSON.stringify(getters.appointment),
                headers: {
                    'Content-type': 'application/json',
                    'X-CSRF-TOKEN': window.Laravel.csrfToken,
                    "Accept": "application/json, text-plain, */*",
                    "X-Requested-With": "XMLHttpRequest",
                },
                credentials: "same-origin"
            });

            if(response.status !== 200) error = true;

            const json = await response.json();

            if(error) {

                if(json.errors){
                    const errors = [];

                    const errorKeys = Object.keys(json.errors);

                    errorKeys.forEach((key) => {
                        errors[key] = json.errors[key][0];
                    });
                    commit('setErrors', errors);
                }

                // Sweet alert
                getters.swal.fire({
                    title: "Oops!",
                    text: json.message || "There was an error booking your appointment!",
                    icon: "error",
                    confirmButtonColor: '#01c0c8',
                    confirmButtonText: "Okay"
                });

            }

            if(json.status === 'success'){

                // Sweet alert
                await getters.swal.fire({
                    title: "Success!",
                    text: json.message,
                    icon: "success",
                    confirmButtonColor: '#01c0c8',
                    confirmButtonText: "Okay"
                });

                window.location.href = '/appointments';
            }

        } catch (e) {
            console.error(e);
        }

        commit('setRequesting', false);
        commit('setBooking', false);

    },

    async updateAppointment({ commit, getters }, data){
        if(getters.requesting || getters.booking) return false;

        let error = false;
        const appointment = getters.selected_appointment;
        appointment.status = data.status;
        commit('setErrors', []);
        commit('setRequesting', true);

        try {
            const response = await fetch('/appointments/update', {
                method: 'PATCH',
                body: JSON.stringify({
                    id: appointment.id,
                    patient: data.user_id,
                    doctor: appointment.doctor.id,
                    title: appointment.service,
                    date: appointment.date_string,
                    desc: appointment.desc,
                    status: appointment.status,
                }),
                headers: {
                    'Content-type': 'application/json',
                    'X-CSRF-TOKEN': window.Laravel.csrfToken,
                    "Accept": "application/json, text-plain, */*",
                    "X-Requested-With": "XMLHttpRequest",
                },
                credentials: "same-origin"
            });

            if(response.status !== 200) error = true;

            const json = await response.json();

            if(error) {

                if(json.errors){
                    const errors = [];

                    const errorKeys = Object.keys(json.errors);

                    errorKeys.forEach((key) => {
                        errors[key] = json.errors[key][0];
                    });
                    commit('setErrors', errors);
                }

                // Sweet alert
                getters.swal.fire({
                    title: "Oops!",
                    text: json.message || "There was an error booking your appointment!",
                    icon: "error",
                    confirmButtonColor: '#01c0c8',
                    confirmButtonText: "Okay"
                });

            }

            if(json.status === 'success'){

                // Sweet alert
                await getters.swal.fire({
                    title: "Success!",
                    text: json.message,
                    icon: "success",
                    confirmButtonColor: '#01c0c8',
                    confirmButtonText: "Okay"
                });

            }

            commit('setRequesting', false);

        } catch (e) {
            console.error(e);
        }
    },

    toggleAppointment({ commit, getters }){
        commit('setViewing', !getters.viewing_appointment);
    },

    getSwal({ commit }, swal){
        commit('setSwal', swal);
    },

};


const mutations = {

    setRequesting: (state, status) => (state.requesting = !!status),

    setBooking: (state, status) => (state.booking = !!status),

    setViewing: (state, status) => (state.viewing_appointment = !!status),

    setSelectedDoctor: (state, doctor) => (state.selected_doctor = doctor),

    setSelectedAppointment: (state, appointment) => (state.selected_appointment = appointment),

    setAppointment: (state, appointments) => (state.appointments = appointments),

    setErrors: (state, errors) => (state.errors = errors),

    setSwal: (state, swal) => (state.swal = swal),

    setDoctor: (state, doctor) => (state.appointment.doctor = doctor),
    setPatient: (state, patient) => (state.appointment.patient = patient),
    updateFormTitle: (state, title) => (state.appointment.title = title),
    updateFormDate: (state, date) => (state.appointment.date = date),
    updateFormDesc: (state, desc) => (state.appointment.desc = desc),
};




export default {
    namespaced: true,
    state, getters, actions, mutations
}
