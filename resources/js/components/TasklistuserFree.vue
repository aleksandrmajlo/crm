<template>
    <div class="TasklistuserComponent_conteer">
        <h2 class="text-center mb-5 mt-5">Material available</h2>
        <h5>
            Limit:
            <span class="text-info">{{ user.weight }}</span>
        </h5>
        <h5 class="mb-3">
            Limit used:
            <span class="text-info">{{ limit_used }}</span>
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
                <tbody>
                <tr v-for="(task, ind) in tasks">
                    <td>
                        {{ task.id }}
                    </td>
                    <!--******flag************************-->
                    <td>
                       <span v-if="task.flag">
                         <img :src="task.flag" alt=""/>
                        </span>
                    </td>
                    <!--******flag end************************-->

                    <!--******ip port************************-->
                    <!--            <td v-if="user.blind == 1 && !task.myorder">-->
                    <!--              <div class="blind">{{ xxx }}</div>-->
                    <!--            </td>-->
                    <td>
                        {{ task.ip }}:{{ task.port }}
                        <i
                            v-clipboard:copy="task.ip + ':' + task.port"
                            class="fa fa-copy"
                        ></i>
                    </td>
                    <!--******port end************************-->

                    <!--******login************************-->
                    <td v-if="user.blind == 1 && !task.myorder">
                        <div class="blind">{{ xxx }}</div>
                    </td>
                    <td v-else>
              <span v-if="task.domain === ''" class="text-danger"
              >Not Domain\</span
              >
                        <span v-else>{{ task.domain }}\</span>
                        {{ task.login }}
                        <i
                            v-clipboard:copy="task.domain + '\\' + task.login"
                            class="fa fa-copy"
                        ></i>
                    </td>
                    <!--******login end************************-->

                    <!--******password************************-->
                    <td v-if="user.blind == 1 && !task.myorder" class="blind">
                        <div class="blind">{{ xxx }}</div>
                    </td>
                    <td v-else>
                        {{ task.password }}
                        <i v-clipboard:copy="task.password" class="fa fa-copy"></i>
                    </td>
                    <!--******password************************-->

                    <!--******weight************************-->
                    <td v-if="user.blind == 1 && !task.myorder">
                        <div class="blind">{{ xxx }}</div>
                    </td>
                    <td v-else>{{ task.weight }}</td>
                    <!--******weight end ************************-->
                    <td class="text-center">
                        <div v-if="!task.myorder">
                            <button
                                class="btn btn-link"
                                @click.prevent="add(task.id, $event)"
                            >
                                Add
                            </button>
                        </div>
                        <div v-else>
                            <span>My order</span>
                        </div>
                    </td>
                    <td class="text-center"></td>
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
        name: "TasklistuserFree",
        data: function () {
            return {
                xxx: "XXXXXXXXXXX",
                tasks: [],
            };
        },
        components: {
            UserbyIp,
        },
        computed: {
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
            },
        },
        created() {
            store.dispatch("getUser").then(() => {
                axios.get("ajaxuser/freetasks").then((response) => {
                    this.tasks = response.data.tasks;
                });
            });
        },
        methods: {
            add($id, event) {
                let $btn = $(event.target);
                $btn.attr("disabled", true);
                axios
                    .post("ajaxuser/addUserTask", {id: $id})
                    .then((response) => {
                        if (typeof response.data.notorder !== "undefined") {
                            this.showShwal("error", response.data.notorder);
                        }
                        if (typeof response.data.success !== "undefined") {
                            this.tasks.forEach((el, index) => {
                                if (el.id == $id) {
                                    this.tasks[index].myorder = true;
                                }
                            });
                            this.showShwal("success", response.data.success);
                        }
                    })
                    .catch((error) => {
                        this.showShwal("error", "Try later");
                    })
                    .then(() => {

                    });
            },
        },
    };
</script>
