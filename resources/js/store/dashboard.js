import Vue from "vue";
export default {
    state: {
        dateLoader: false,
        loaderUser: false,
        dateWorkdashbords: [],
        dateDonedashbords: [],
        dateFaileddashbords: [],
        usersDashbords: [],

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
        loaderDate(state) {
            state.dateLoader = true;
        },
        loaderUser(state) {
            state.loaderUser = true;
        }
    }
}
