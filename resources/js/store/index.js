import Vuex from 'vuex';
import Vue from 'vue';
import users from './modules/users';
import appointments from './modules/appointments';

// Load Vuex
Vue.use(Vuex);

// Create store
export default new Vuex.Store({
    strict: true,
    modules: {
        users, appointments
    }
});
