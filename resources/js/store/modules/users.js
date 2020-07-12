
const state = {
    user: {},
    form: {
        id: null,
        name: '',
        avatar: null,
        dob: '',
        phone: '',
        address: ''
    },
    patients: [],
    doctors: [],
    requesting: false,
    updating: false,
    editing: false,
    has_error: false,
    is_auth_user: false,
    swal: null,
    errors: [],
    default_avatar: '/assets/images/avatars/default_avatar.jpg',
};



const getters = {
    user: state => state.user,
    doctors: state => state.doctors,
    editing: state => state.editing,
    updating: state => state.updating,
    requesting: state => state.requesting,
    errors: state => state.errors,
    form: state => state.form,
    sweet_alert: state => state.swal,
    default_avatar: state => state.default_avatar,
};



const actions = {

    async getUserData({ commit }, id){
        let error = false;
        const url = `/profile/json/user/${id}`;

        commit('setRequesting', true);

        try {

            const response = await fetch(url);

            if(response.status !== 200) error = true;

            const json = await response.json();

            if(!error && json.status === 'success') {
                commit('setUserData', json.data);
                commit('setFormData', json.data);
            }

        } catch (e) {
            console.error(e);
        }

        commit('setRequesting', false);

    },

    async getDoctors({ commit }){
        let error = false;
        const url = `/profile/json/doctors`;

        commit('setRequesting', true);

        try {

            const response = await fetch(url);

            if(response.status !== 200) error = true;

            const json = await response.json();

            if(!error && json.status === 'success') {
                commit('setDoctorsData', json.data);
            }

        } catch (e) {
            console.error(e);
        }

        commit('setRequesting', false);

    },

    async updateUserData({ commit, getters }, id){
        let error = false;
        const url = `/profile/update`;

        const form_obj = getters.form;
        form_obj.id = id;
        console.log(form_obj);
        if(getters.requesting) return false;

        try {
            const formData = new FormData();

            formData.append('_method', 'PATCH');

            for (const property in form_obj){
                if(form_obj.hasOwnProperty(property) && form_obj[property])
                    formData.append(property, form_obj[property]);
            }

            commit('setErrors', []);
            commit('setRequesting', true);
            commit('setUpdating', true);

            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': window.Laravel.csrfToken,
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
                getters.sweet_alert.fire({
                    title: "Oops!",
                    text: json.message || "There was an error updating your profile!",
                    icon: "error",
                    confirmButtonColor: '#01c0c8',
                    confirmButtonText: "Okay"
                });

            }

            if(json.status === 'success'){

                commit('setUserData', json.data);
                commit('setFormData', json.data);
                // Sweet alert
                getters.sweet_alert.fire({
                    title: "Success!",
                    text: json.message,
                    icon: "success",
                    confirmButtonColor: '#01c0c8',
                    confirmButtonText: "Okay"
                });
            }

        } catch (e) {
            console.error(e);
        }

        commit('setRequesting', false);
        commit('setUpdating', false);
    },

    getSwal({ commit }, swal){
        commit('setSwal', swal);
    },

    editProfile({ commit }){
        commit('setEditing', true);
    },
};



const mutations = {

    setRequesting: (state, status) => (state.requesting = !!status),

    setUpdating: (state, status) => (state.updating = !!status),

    setUserData: (state, value) => (state.user = value),

    setEditing: (state, status) => (state.editing = !!status),

    setErrors: (state, errors) => (state.errors = errors),

    setSwal: (state, swal) => (state.swal = swal),

    setFormData: (state, user) => {
        state.form = {
            id: user.id,
            name: user.name,
            avatar: null,
            dob: user.birth_date,
            phone: user.phone_number,
            address: user.address
        };
    },

    setDoctorsData: (state, doctors) => (state.doctors = doctors),

    updateFormName: (state, name) => (state.form.name = name),
    updateFormDate: (state, dob) => (state.form.dob = dob),
    updatePhone: (state, phone) => (state.form.phone = phone),
    updateAddress: (state, address) => (state.form.address = address),
    updateAvatar: (state, avatar) => (state.form.avatar = avatar),

};


export default {
    namespaced: true,
    state, getters, actions, mutations
}
