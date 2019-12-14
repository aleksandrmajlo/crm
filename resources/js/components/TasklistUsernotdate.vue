<template>
    <div class="TasklistuserComponent_conteer">

        <h2 class="text-center mb-5 mt-5">Material available</h2>
        <h5>
            Limit:
            <span class="text-info">{{user.weight}}</span>
        </h5>
        <h5 class="mb-3">
            Limit used:
            <span class="text-info">{{limit_used}}</span>
        </h5>
        <div class="table-responsive">
            <table class="table table-sm">

                <thead>
                <tr>
                    <th>ID</th>
                    <th></th>
                    <th>IP PORT</th>
                    <th>DONAIN\LOGIN</th>
                    <th>PASSWORD</th>
                    <th>COST</th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>


                <tbody v-for="(task_,index) in tasks " :key="index">

                <tr v-for="(task,ind) in task_" v-if="task.user_id== null">
                    <td>
                        {{task.id}}
                    </td>
                    <!--******flag************************-->
                    <td v-if="task.status==2&&task.user_id!==user.id">
                        <div class="blind">{{xxx}}</div>
                    </td>
                    <td v-else>
                    <span v-if="task.flag">
                      <img :src="task.flag"/>
                    </span>
                    </td>
                    <!--******flag end************************-->
                    <!--******ip port************************-->
                    <td v-if="task.status==2&&task.user_id!==user.id">
                        <div class="blind">{{xxx}}</div>
                    </td>
                    <td v-else>
                        {{task.ip}}:{{task.port}}
                        <i
                                v-clipboard:copy="task.ip+':'+task.port"
                                class="fa fa-copy"
                        ></i>
                    </td>
                    <!--******port end************************-->
                    <!--******login************************-->
                    <td
                            v-if="user.blind==1&&!(task.user_id==user.id)&&user_orders.indexOf(task.id)==-1"
                    >
                        <div class="blind">{{xxx}}</div>
                    </td>
                    <td v-else>
                        <span v-if="task.domain===''" class="text-danger">Not Domain\</span>
                        <span v-else>{{task.domain}}\</span>
                        {{task.login}}
                        <i
                                v-clipboard:copy="task.domain+'\\'+task.login"
                                class="fa fa-copy"
                        ></i>
                    </td>
                    <!--******login end************************-->
                    <!--******password************************-->
                    <td
                            v-if="user.blind==1&&!(task.user_id==user.id)&&user_orders.indexOf(task.id)==-1"
                            class="blind"
                    >
                        <div class="blind">{{xxx}}</div>
                    </td>
                    <td v-else>
                        {{task.password}}
                        <i v-clipboard:copy="task.password" class="fa fa-copy"></i>
                    </td>
                    <!--******password************************-->
                    <!--******weight************************-->
                    <td v-if="task.status==2&&task.user_id!==user.id">
                        <div class="blind">{{xxx}}</div>
                    </td>
                    <td v-else>{{task.weight}}</td>
                    <!--******weight end ************************-->
                    <td class="text-center">
                        <div v-if="task.status==1&&user_orders.indexOf(task.id)==-1">
                            <button class="btn btn-link" @click.prevent="add(task.id,$event)">Add</button>
                        </div>
                        <div v-else-if="user_orders.indexOf(task.id)!==-1||task.user_id==user.id">
                            <span>My order</span>
                        </div>
                    </td>
                    <td class="text-center">
                    <span
                            v-if="user_orders.indexOf(task.id)!==-1||task.user_id==user.id"
                    >Work {{user.name}}</span>
                    </td>
                </tr>

                </tbody>


            </table>
        </div>
    </div>
</template>

<script>
    import {mapState} from "vuex";
    import store from "../store/";
    import UserbyIp from "./search/UserByIp.vue";

    export default {
        name: "TasklistUsernotdate",
        data: function () {
            return {
                xxx: "XXXXXXXXXXX"
            };
        },
        components: {
            UserbyIp
        },
        computed: {
            tasks() {
                // store.state.user.tasks.forEach((el)=>{
                //     el.forEach((el1)=>{
                //         console.log(el1.user_id)
                //
                //     })
                // })
                return store.state.user.tasks;
            },
            status() {
                return store.state.user.status;
            },

            user() {
                return store.state.user.user;
            },
            limit_used() {
                return store.state.user.limit_used;
            },

            user_orders() {
                return store.state.user.user_orders;
            }
        },
        created() {
            store.dispatch("getUser");
            store.dispatch("getUserTasks");
        },
        methods: {
            add($id, event) {
                let $btn = $(event.target);
                $btn.attr("disabled", true);
                store.dispatch("addUserOrder", $id);
            },
            // прокрутку добавить
            showClick(index) {
                let $el = $("#collapse" + index + "scroll"),
                    first = $el.data("first");
                if (typeof first == "undefined") {
                    setTimeout(() => {
                        $el.find("table").DataTable({
                            columnDefs: [
                                {
                                    targets: [1, 6, 7],
                                    orderable: false,
                                    searchable: false
                                }
                            ]
                        });
                    }, 500);
                    $el.data("first", true);
                }
            }
        }
    }
</script>

<style scoped>

</style>