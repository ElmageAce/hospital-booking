/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.Vue = require('vue');
window.$ = require('../../public/assets/plugins/bower_components/jquery/dist/jquery.min.js');
window.jQuery = require('../../public/assets/plugins/bower_components/jquery/dist/jquery.min.js');
window.Swal = require('sweetalert2');

/**
 * Require Vuex for state management
 * @type {{MutationPayload: MutationPayload; CommitOptions: CommitOptions; Getter: Getter; ModuleTree: ModuleTree; ActionErrorSubscriber: ActionErrorSubscriber; ActionSubscribersObject: ActionSubscribersObject; ActionSubscriber: ActionSubscriber; ActionPayload: ActionPayload; ActionTree: ActionTree; DispatchOptions: DispatchOptions; Store: Store; Module: Module; SubscribeActionOptions: SubscribeActionOptions; Plugin: Plugin; install(Vue: typeof _Vue): void; Mutation: Mutation; StoreOptions: StoreOptions; Action: Action; SubscribeOptions: SubscribeOptions; Commit: Commit; MutationTree: MutationTree; Payload: Payload; ModuleOptions: ModuleOptions; GetterTree: GetterTree; ActionHandler: ActionHandler; ActionObject: ActionObject; ActionContext: ActionContext; Dispatch: Dispatch; CustomVue: CustomVue; MutationMethod: MutationMethod; MapperWithNamespace: MapperWithNamespace; InlineComputed: InlineComputed; NamespacedMappers: NamespacedMappers; mapState: Mapper<Computed> & MapperWithNamespace<Computed> & MapperForState & MapperForStateWithNamespace; ActionMethod: ActionMethod; MapperForMutationWithNamespace: MapperForMutationWithNamespace; MapperForState: MapperForState; mapMutations: Mapper<MutationMethod> & MapperWithNamespace<MutationMethod> & MapperForMutation & MapperForMutationWithNamespace; mapGetters: Mapper<Computed> & MapperWithNamespace<Computed>; createNamespacedHelpers(namespace: string): NamespacedMappers; MapperForActionWithNamespace: MapperForActionWithNamespace; Mapper: Mapper; MapperForMutation: MapperForMutation; Computed: Computed; MapperForAction: MapperForAction; MapperForStateWithNamespace: MapperForStateWithNamespace; mapActions: Mapper<ActionMethod> & MapperWithNamespace<ActionMethod> & MapperForAction & MapperForActionWithNamespace; InlineMethod: InlineMethod; default}}
 */
window.Vuex = require('vuex');

import store from './store';

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('app-logo', require('./components/Fragments/AppLogo.vue').default);
Vue.component('mini-profile', require('./components/Fragments/MiniProfileComponent.vue').default);

Vue.component('login-component', require('./components/Auth/LoginComponent.vue').default);
Vue.component('register-component', require('./components/Auth/RegisterComponent.vue').default);
Vue.component('user-profile', require('./components/Profile/ProfileComponent.vue').default);
Vue.component('edit-profile', require('./components/Profile/EditProfileComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import swalPlugin from './plugins/VueSweetAlert';
Vue.use(swalPlugin);

const app = new Vue({
    store,
    el: '#wrapper',
});
