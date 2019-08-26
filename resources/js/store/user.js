import Vue from "vue";
import Swal from 'sweetalert2/dist/sweetalert2.js'

export default {
    state: {

        status: [],
        failed_status: [],

        tasks: [],
        user: {},
        user_orders: [],
        limit_used: 0,
        this_user_order: [], // вывод заказов на странице листинга
        history_orders: [], // вывод заказов на странице листинга
        history_page: [], // в истории при выводе
    },
    mutations: {

        setUser(state, data) {
            state.user = data.user;
            state.limit_used = data.limit_used;
            state.status = data.status;

        },

        setUsersTasks(state, data) {
            let tasks = data.tasks;
            state.tasks = Object.keys(tasks).sort((a, b) => b - a).map(key => tasks[key]);

        },

        //добавить в заказ
        addUserOrders(state, data) {
            state.limit_used = data.limit_used;
            data.ids.forEach(id => {
                state.user_orders.push(id);
            });
        },

        //установить заказы для данного пользователя на странице листинга
        thisUserOrder(state, data) {
            state.this_user_order = data.orders;
            state.failed_status = data.failed_status;
            state.history_orders = data.history
        },

        // показать скрыть  форму
        showForm(state, order) {
            state.this_user_order.forEach((element, index) => {
                if (element.id == order.id) {
                    if (typeof element.show == "undefined" || !element.show) {
                        Vue.set(state.this_user_order[index], 'show', true)
                    } else {
                        Vue.set(state.this_user_order[index], 'show', false)
                    }
                }
            });
        },
        // добавляем заказ в историю
        HistoryPage(state, data) {
            state.history_page.push(data.id);
        },
        // заказ добавить поля для серийных номеров
        InsertSerialNumbers(state, data) {
            let arr = data.text.split("\n"),
                results = [],
                order_id = data.order.id;
            arr.forEach(el => {
                results.push({
                    serialinp: el,
                    text: ""
                })
            });
            state.this_user_order.forEach((el, index) => {
                if (order_id == el.id) {
                    Vue.set(state.this_user_order[index], 'serials', results)
                }
            })
        },
        // заказ установить поля для серийных номеров
        SetInsertSerialNumbers(state, data) {
            //serialinp:serialinp,text:text,index:index,id:index
            state.this_user_order.forEach((el, index) => {
                if (data.id == el.id) {
                    let serials = el.serials;
                    serials.forEach((serial, ind) => {
                        if (ind == data.index) {
                            Vue.set(state.this_user_order[index].serials[ind], 'text', data.text)
                            Vue.set(state.this_user_order[index].serials[ind], 'serialinp', data.serialinp)
                        }
                    })
                }
            })

        }
    },
    getters: {},
    actions: {
        // получить пользователя
        getUser({
            commit,
            state
        }) {
            return axios.get('ajaxuser/user')
                .then(response => {
                    commit('setUser', response.data)
                });
        },
        // получить все задания
        getUserTasks({
            commit,
            state
        }) {
            return axios.get('ajaxuser/tasks')
                .then(response => {
                    commit('setUsersTasks', response.data)
                });
        },
        // добавить задание
        addUserOrder({
            commit,
            state
        }, id) {

            return axios.post('order/addUserOrder', {
                    id: id
                })
                .then(response => {

                    if (typeof response.data.notorder !== "undefined") {
                        Swal.fire({
                            type: "error",
                            title: "Error",
                            text: response.data.notorder,
                            timer: 3500
                        });
                    }

                    if (typeof response.data.engaged !== "undefined") {
                        Swal.fire({
                            type: "error",
                            title: "Error",
                            text: response.data.engaged,
                            timer: 3500
                        });
                    }

                    if (typeof response.data.success !== "undefined") {
                        commit('addUserOrders', {
                            ids: response.data.id,
                            limit_used: response.data.limit_used,
                        });
                        Swal.fire({
                            type: "success",
                            title: "Success",
                            text: response.data.success,
                            timer: 3500
                        });
                    }
                });
        },
        // получить задания на странице листинга
        this_user_order({
            commit,
            state
        }) {
            return axios.get('order/thisuserorders')
                .then(response => {
                    commit('thisUserOrder', response.data)
                });
        },

        // отправка данных по заказу
        setordercompletion({
            commit,
            state
        }, data) {

            // проверяем или это заказ с дополнитеьными полями
            state.this_user_order.forEach((el, index) => {
                  if(el.id==data.id){
                      if(typeof el.serials!=="undefined"){
                          data.data_sub_options= el.serials;
                      }
                  }
            })
            return axios.post('order/setOrderCompletion', data)
                .then(response => {
                    if (response.data.success) {

                        commit('HistoryPage', response.data);
                        Swal.fire({
                            type: "success",
                            title: "Success",
                            timer: 3500
                        });

                    }
                });
        },

        // вывод ошибки
        error() {
            Swal.fire({
                type: "error",
                title: "Error",
                text: "Try later",
                timer: 3500
            });

        }

    }
}
