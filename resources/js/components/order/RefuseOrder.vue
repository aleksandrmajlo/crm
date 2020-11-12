<template>
    <span></span>
</template>
<script>
    import {eventBus} from "../../app";
    import {mapState} from 'vuex';
    import store from '../../store/';

    export default {
        name: "RefuseOrder",
        data() {
            return {
                order: null
            }
        },
        created() {
            eventBus.$on('refuseOrderEvent', data => {
                this.order = data;
                this.$swal.fire({
                    icon: 'warning',
                    title: 'Are you sure?',
                    showCancelButton: true,
                    showDenyButton: true,
                    confirmButtonText: `Yes`,
                    denyButtonText: 'Not',
                }).then((result) => {
                    if (result.value) {
                        axios.post('/order/refuseOrder', {order_id: this.order.id})
                            .then((response) => {
                                store.dispatch('this_user_order')
                                    .then(() => {
                                        this.showShwal('success', 'refuse Order');
                                        this.order.null;
                                    });
                            })
                            .finally(() => {

                            });
                    }
                })
            })
        }
    }
</script>

