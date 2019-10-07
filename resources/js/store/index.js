import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);
import task from './task';
import user from './user';
import dashboard from './dashboard';

export default new Vuex.Store({
    // strict: true,
    modules: {
        task,
        user,
        dashboard
    },
});
