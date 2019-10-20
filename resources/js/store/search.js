import Vue from "vue";
import store from '../store/';

export default {
    state: {
        searchs:[]// результат поиска
    },
    mutations: {
        setSearchData(state,data){
            let tasks = data.tasks;
            state.searchs = tasks;
        }
    },
    getters: {},
    actions: {
        UserByIp({
                     commit,
                     state
                 }, q) {

            return axios.post('search/ip',{ip:q})
                .then(response => {
                    commit('setSearchData', response.data)
                });

        }
    }
}