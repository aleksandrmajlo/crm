import Vue from "vue";
export default {
    state: {
        dateLoader: false,
        loaderUser: false,

        dateWorkdashbords: [],
        dateDonedashbords: [],
        dateFaileddashbords: [],
        usersDashbords: [],
        userDashbords: {

        }
    },
    actions: {

        dateGetDashbord({
            commit,
            state
        }, date) {
            commit('loaderDate');
            return axios.post('dashbord/dateGetDashbord', {
                    date: date
                })

                .then(response => {
                    if (response.data.success) {
                        commit('savedDate', response.data.results);
                    }
                });
        },
        // получить всех пользователей
        usersGetDashbord({
            commit,
            state
        }) {
            commit('loaderUser');
            return axios.post('dashbord/usersGetDashbord', {})

                .then(response => {
                    if (response.data.success) {
                        commit('SetusersDashbords', response.data);
                    }
                });
        },
        // получить инфу по пользователю
        userGetDashbord({
            commit,
            state
        }, id) {
            commit('loaderUser');
            return axios.post('dashbord/userGetDashbord', {
                    id: id
                })

                .then(response => {
                    if (response.data.success) {
                        commit('SetuserDashbords', response.data.user);
                    }
                });
        },

    },
    mutations: {
        savedDate(state, data) {
            state.dateWorkdashbords = data.work;
            state.dateDonedashbords = data.done;
            state.dateFaileddashbords = data.failed;
            state.dateLoader = false;
        },
        // установить всех пользователей
        SetusersDashbords(state, data) {
            state.usersDashbords = data.users;
            state.loaderUser = false;
        },
        // по конкретному пользователю
        SetuserDashbords(state, user) {
            Vue.set(state.userDashbords, 'weight', user.weight)
            Vue.set(state.userDashbords, 'limit', user.limit)
            Vue.set(state.userDashbords, 'orders', user.orders);
            state.loaderUser = false;
        },

        loaderDate(state) {
            state.dateLoader = true;
        },
        loaderUser(state) {
            state.loaderUser = true;
        }
    }
}
