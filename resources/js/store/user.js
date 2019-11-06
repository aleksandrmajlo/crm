import Vue from "vue";
import store from '../store/';
import Swal from 'sweetalert2/dist/sweetalert2.js'
export default {
    state: {
        status: [],
        failed_status: [],
        tasks: [], //задания
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
        // вывод списка заданий 
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

        //установить заказы для данного пользователя на странице My order
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
                resultsText = "",
                order_id = data.order.id;
            arr.forEach(el => {
                if (el !== "") {
                    let this_ar = el.split("@@");
                    if (this_ar) {
                        let v1 = "";
                        if (typeof this_ar[1] !== "undefined") {
                            v1 = this_ar[1];
                        }
                        let v2 = "";
                        if (typeof this_ar[2] !== "undefined") {
                            v2 = this_ar[2];
                        }
                        results.push({
                            serialinp: this_ar[0],
                            link: v1,
                            text: v2,
                        });
                    }

                }
            });
            state.this_user_order.forEach((el, index) => {
                if (order_id == el.id) {
                    let serials = state.this_user_order[index].serials;
                    if (typeof serials == "undefined" || data.trigger == "textarea") {
                        Vue.set(state.this_user_order[index], 'serials', results);
                        results.forEach(res => {
                            let serialinp = res.serialinp + "@@";
                            if (res.serialinp == "") {
                                serialinp = "";
                            }
                            let link = res.link + "@@";
                            if (res.link == "") {
                                link = "";
                            }
                            let text = res.text;
                            if (res.text == "") {
                                text = "";
                            }
                            resultsText += serialinp + link + text + "\n";
                        })
                        Vue.set(state.this_user_order[index], 'Textareaserials', resultsText);
                    } else {
                        serials.forEach(res => {
                            let serialinp = res.serialinp + "@@";
                            if (res.serialinp == "") {
                                serialinp = "";
                            }
                            let link = res.link + "@@";
                            if (res.link == "") {
                                link = "";
                            }
                            let text = res.text;
                            if (res.text == "") {
                                text = "";
                            }
                            resultsText += serialinp + link + text + "\n";
                        })
                        results.forEach(res => {
                            let serialinp = res.serialinp + "@@";
                            if (res.serialinp == "") {
                                serialinp = "";
                            }
                            let link = res.link + "@@";
                            if (res.link == "") {
                                link = "";
                            }
                            let text = res.text;
                            if (res.text == "") {
                                text = "";
                            }
                            resultsText += serialinp + link + text + "\n";
                            state.this_user_order[index].serials.push(res)
                        })
                        Vue.set(state.this_user_order[index], 'Textareaserials', resultsText);
                    }
                }
            })
        },

        // очищаем поля для вставки новых данных
        ResetSerialNumbers(state, data) {
            let order_id = data.order.id;
            state.this_user_order.forEach((el, index) => {
                if (order_id == el.id) {
                    Vue.set(state.this_user_order[index], 'serials', []);
                }
            })
        },

        // заказ установить поля для серийных номеров
        SetInsertSerialNumbers(state, data) {
            let i = state.this_user_order.map(item => item.id).indexOf(data.id);
            let resultsText = "";
            state.this_user_order.forEach((el, index) => {
                if (data.id == el.id) {
                    let serials = el.serials;
                    serials.forEach((serial, ind) => {
                        if (ind == data.index) {
                            Vue.set(state.this_user_order[index].serials[ind], 'text', data.text)
                            Vue.set(state.this_user_order[index].serials[ind], 'link', data.link)
                            Vue.set(state.this_user_order[index].serials[ind], 'serialinp', data.serialinp)
                        }
                    })
                }
            })
            state.this_user_order[i].serials.forEach(serial => {
                let serialinp = serial.serialinp + "@@";
                if (serial.serialinp == "") {
                    serialinp = "";
                }
                let link = serial.link + "@@";
                if (serial.link == "") {
                    link = "";
                }
                let text = serial.text;
                if (serial.text == "") {
                    text = "";
                }
                resultsText += serialinp + link + text + "\n";

            })
            Vue.set(state.this_user_order[i], 'Textareaserials', resultsText);
        },
        // удалить ячейку
        RemoveSerialNumbers(state, data) {
            let i = state.this_user_order.map(item => item.id).indexOf(data.id);
            let serials = state.this_user_order[i].serials;
            let resultsText = "";

            serials.forEach((serial, ind) => {
                if (ind == data.index) {
                    state.this_user_order[i].serials.splice(ind, 1);
                }
            });
            state.this_user_order[i].serials.forEach(serial => {
                let serialinp = serial.serialinp + "@@";
                if (serial.serialinp == "") {
                    serialinp = "";
                }
                let link = serial.link + "@@";
                if (serial.link == "") {
                    link = "";
                }
                let text = serial.text;
                if (serial.text == "") {
                    text = "";
                }
                resultsText += serialinp + link + text + "\n";
            })
            Vue.set(state.this_user_order[i], 'Textareaserials', resultsText);

        },
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
        // получить задания на странице My order
        this_user_order({
            commit,
            state
        }) {
            return axios.get('order/thisUserOrders')
                .then(response => {
                    commit('thisUserOrder', response.data)
                });
        },

        // отправка данных по заказу
        setOrderCompletion({
            commit,
            state
        }, data) {
            // проверяем или это заказ с дополнитеьными полями
            state.this_user_order.forEach((el, index) => {
                if (el.id == data.id) {
                    if (typeof el.serials !== "undefined") {
                        data.data_sub_options = el.serials;
                    }
                }
            });
            return axios.post('order/setOrderCompletion', data)
                .then(response => {
                    if (response.data.success) {
                        Swal.fire({
                            type: "success",
                            title: "Success",
                            timer: 1500
                        });
                        store.dispatch('this_user_order');
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
