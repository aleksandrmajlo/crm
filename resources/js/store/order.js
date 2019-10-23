export default {
    state: {
        order: {},
        failed: []
    },
    mutations: {
        setOrderRead(state, data) {
            state.order = data.order;
            state.failed = data.failed;
        },
        setValueSerial(state, data) {

            if (data.type == 'commentAll') {
                state.order.comment = data.value;
            } else {
                state.order.serials[data.index][data.type] = data.value;
            }

        },
        removeDoneSerial(state, index) {
            state.order.serials.splice(index, 1);
        },
        plusSerialDone(state) {
            state.order.serials.push({
                serial: "",
                link: "",
                text: "",
            });
        }
    },
    actions: {
        GetOrderData({
            commit,
            state
        }, orderId) {
            return axios.post('order/thisUserOrder', {
                    order_id: orderId
                })
                .then(response => {
                    commit('setOrderRead', response.data);
                });
        },
        // обновляем заказ
        UpdateUserOrder({
            commit,
            state
        }, data) {

            return axios.post('order/UpdateUserOrder', data)
                .then(response => {

                });
        }
    }
}
