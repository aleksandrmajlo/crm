<template>
    <div>
        <h2 class="text-center">ID:{{task_id}}</h2>
        <div class="tableContent">
            <div class="table-responsive">
                <table class="table-bordered table">
                    <thead>
                    <th>Status</th>
                    <th>IP:PORT</th>
                    <th>USER</th>
                    <th>ADMIN</th>
                    <th>INFO</th>
                    <th>Created</th>
                    </thead>
                    <tbody>

                    <tr v-for="(orderlog,index) in orderlogs" :key="index">
                        <td>{{orderlog.status}}</td>
                        <td>{{orderlog.ip}}:{{orderlog.port}}</td>
                        <td>
                            <span v-if="orderlog.user_id">
                                {{orderlog.user_id}}
                            </span>
                        </td>

                        <td>
                            <span v-if="orderlog.admin_id">
                                {{orderlog.admin_id}}
                            </span>
                        </td>
                        <td>
                            <div v-if="orderlog.text">
                                <div v-if="orderlog.status_id==2||orderlog.status_id==4||orderlog.status_id==7">
                                    <p v-if="orderlog.text.done.commentall !==undefined">
                                        <strong>Comment:</strong>{{orderlog.text.done.commentall}}
                                    </p>
                                    <div v-if="orderlog.text.done.serials.length>0">
                                        <div class=" mb-2" v-for="(serial,ind) in orderlog.text.done.serials"
                                             :key="ind">
                                            <div class="">
                                                <short-serial :serial="serial.serial"></short-serial>
                                            </div>
                                            <div class="">
                                                <b>Link:</b> {{serial.link}}
                                            </div>
                                            <div class="">
                                                <b>Text:</b>{{serial.text}}
                                            </div>
                                            <hr/>
                                        </div>
                                    </div>

                                </div>
                                <div v-else-if="orderlog.status_id==3||orderlog.status_id==5||orderlog.status_id==6">
                                    <p>
                                        <strong>Type:</strong> {{orderlog.failedStatus}}
                                    </p>
                                    <div>
                                        <span v-html="orderlog.text.comment"></span>
                                    </div>
                                </div>
                                <div v-else-if="orderlog.status_id==8">
                                    <div>
                                        <span v-html="orderlog.text.comment"></span>
                                    </div>
                                </div>

                                <div v-else-if="orderlog.status_id==11">
                                    <div>
                                        <span v-html="orderlog.text.comment"></span>
                                    </div>
                                    <p>
                                        <strong>Show:</strong> {{orderlog.text.showcommentadmin}}
                                    </p>
                                </div>
                                <div v-else-if="orderlog.status_id==14||orderlog.status_id==15">
                                    <p>
                                         {{orderlog.text.text}}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td>
                            {{orderlog.created_at}}
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
    import {mapState} from "vuex";
    import store from "../../store/";

    export default {
        name: "AdminLogtask",
        data() {
            return {
                task_id: "",
                orderlogs: []
            };
        },
        mounted() {
            this.$root.$on("showLogtask", ob => {
                this.task_id = ob.task_id;
                this.reload = ob.reload;
                this.setData();
            });
        },
        computed: {
            status() {
                return store.state.task.status;
            },
        },
        methods: {
            setData() {
                axios
                    .post("/orderLog", {task_id: this.task_id})
                    .then(response => {
                        this.orderlogs = response.data.orderlogs;
                        setTimeout(() => {
                            $('.serial-popover').popover({});
                        }, 500)
                    })
                    .catch(error => console.log(error));
            }
        },
        watch: {}
    };
</script>

