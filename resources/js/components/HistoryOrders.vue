<template>
    <div class="card mb-5">
        <div class="card-header text-center">History</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm" id="table_history">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th></th>
                        <th>IP PORT</th>
                        <th>DONAIN\LOGIN</th>
                        <th>PASSWORD</th>
                        <th>COST</th>
                        <th>Created</th>
                        <th>Updated</th>
                        <th>Status</th>
                        <th></th>
                        <th></th>
                        <th>Not viewed comment</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="(order, ind) in history_orders">
                        <tr>
                            <td>
                                {{ order.task_id }}
                            </td>
                            <td>
                                <div v-if="order.flag">
                                    <img :src="order.flag"/>
                                </div>
                            </td>
                            <td>{{ order.ip }}:{{ order.port }}</td>
                            <td>
                                <span v-if="order.domain === ''" class="text-danger">Not Domain\</span>
                                <span v-else>{{ order.domain }}\</span>
                                {{ order.login }}
                                <i v-clipboard:copy="order.domain + '\\' + order.login" class="fa fa-copy"></i>
                            </td>
                            <td>{{ order.password }}</td>
                            <td>{{ order.weight }}</td>
                            <td>{{ order.created_at | formatDate }}</td>
                            <td>{{ order.updated_at | formatDate }}</td>
                            <td>{{ status[order.status] }}</td>
                            <td>
                                <div v-if="order.status !== 5">
                                    <a
                                        class="btn btn-outline"
                                        target="_blank"
                                        :href="'/editOrder?order=' + order.id"
                                    >EDIT</a
                                    >
                                </div>
                            </td>
                            <td>
                                <div v-if="order.admincomments.length" class="text-center">
                                    <a
                                        class="btn btn-secondary text-nowrap"
                                        data-toggle="modal"
                                        href="#"
                                        data-target="#adminCooments"
                                        @click.prevent="showComments(order.id)"
                                    >Show comments - {{ order.admincomments.length }}
                                    </a>
                                </div>
                                <div v-else class="text-center">Not comment</div>
                            </td>
                            <td>{{ Not_viewed(order.id) }}</td>
                        </tr>
                        <template v-if="order.sub_orders">
                            <tr v-for="(sub_order, index) in order.sub_orders">
                                <td>{{ sub_order.task_id }}</td>
                                <td></td>
                                <td>{{ sub_order.ip }}:{{ sub_order.port }}</td>
                                <td>
                                    <span v-if="sub_order.domain === ''" class="text-danger" >Not Domain\</span>
                                    <span v-else>{{ sub_order.domain }}\</span>
                                    {{ sub_order.login }}
                                    <i   v-clipboard:copy="sub_order.domain + '\\' + sub_order.login"    class="fa fa-copy" ></i>
                                </td>
                                <td>{{ sub_order.password }}</td>
                                <td>{{ sub_order.weight }}</td>
                                <td>{{ sub_order.created_at }}</td>
                                <td>{{ sub_order.updated_at }}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </template>
                    </template>
                    </tbody>
                </table>
            </div>


        </div>
        <admincooments-modal></admincooments-modal>
    </div>
</template>

<script>
    import {eventBus} from "../app";
    import AdmincoomentsModal from "./order/AdmincoomentsModal";
    export default {
        name: "HistoryOrders",
        components:{AdmincoomentsModal},
        data: function () {
            return {
                history_orders:[],
                status:[],
                first: false,
                idModal: 0,
                comments: [],
            };
        },
        created(){
            axios.get('order/thisHistoryOrders')
                .then(response => {
                    this.history_orders=response.data.history;
                    this.status=response.data.status;
                })
                .catch(()=>{
                    this.showShwal('error','Try later')
                })
        },
        mounted() {
            setTimeout(() => {
                $("#table_history").DataTable({
                    order: [[11, "desc"]],
                    columnDefs: [
                        {
                            targets: [1, 9],
                            orderable: false,
                            searchable: false,
                        },
                    ],
                });
            }, 1500);
        },
        methods: {
            showComments(order_id) {
                let i = this.history_orders.map((order) => order.id).indexOf(order_id);
                let order = this.history_orders[i];
                eventBus.$emit("ModalComment", {order: order});
            },
            // количество непросмотренных
            Not_viewed(order_id) {
                let count = 0;
                let i = this.history_orders.map((order) => order.id).indexOf(order_id);
                let order = this.history_orders[i];
                order.admincomments.forEach((el) => {
                    if (el.viewed === 0) count++;
                });
                return count;
            },
        },
    };
</script>

