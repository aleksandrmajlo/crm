<template>
    <div class="card mb-5">
        <div class="card-header text-center">History</div>
        <div class="card-body">
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
                    <th>Status </th>
                </tr>
                </thead>
                <tbody>
                <template v-for="(order,ind) in history_orders">

                    <tr>
                        <td>{{order.task_id}}</td>
                        <td >
                             <span v-if="order.flag">
                                  <img :src="order.flag" >
                             </span>
                        </td>
                        <td>{{order.ip}}:{{order.port}}</td>
                        <td>
                            <span v-if="order.domain===''" class="text-danger">
                                    Not Domaim \
                                </span>
                            <span v-else>
                                    {{order.domain}}\
                            </span>
                            {{order.login}}
                        </td>
                        <td>{{order.password}}</td>
                        <td>{{order.weight}}</td>
                        <td>{{order.created_at | formatDate}}</td>
                        <td>{{order.updated_at | formatDate}}</td>
                        <td>{{status[order.status]}}</td>
                    </tr>
                    <template v-if="order.sub_orders">
                        <tr v-for="(sub_order,index) in order.sub_orders">
                            <td>{{sub_order.task_id}}</td>
                            <td></td>
                            <td>{{sub_order.ip}}:{{sub_order.port}}</td>
                            <td>
                                <span v-if="sub_order.domain===''" class="text-danger">
                                    Not Domaim \
                                </span>
                                <span v-else>
                                    {{sub_order.domain}}\
                                </span>
                                {{sub_order.login}}
                            </td>
                            <td>{{sub_order.password}}</td>
                            <td>{{sub_order.weight}}</td>
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
</template>

<script>
    export default {
        name: "HistoryOrders",
        props:['history_orders','status'],
        mounted() {
            setTimeout(()=>{
                $('#table_history').DataTable({
                    "columnDefs": [
                        {
                            "targets": [ 1],
                            "orderable": false,
                            "searchable": false
                        },
                    ]
                });
            },500)

        }
    }
</script>

<style scoped>

</style>