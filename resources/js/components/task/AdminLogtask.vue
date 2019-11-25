<template>
    <div>
        <h2 class="text-center">ID:{{task_id}}</h2>
        <div class="tableContent">
            <div class="table-responsive">
                <table class="table-bordered table">
                    <thead>
                    <th>USER</th>
                    <th>Status</th>
                    <th>Done(info)</th>
                    <th>Field(info)</th>
                    <th>Created</th>
                    </thead>
                    <tbody>
                    <tr v-for="(orderlog,index) in orderlogs" :key="index">
                        <td>{{orderlog.user}}</td>
                        <td>{{orderlog.status}}</td>
                        <td>
                            <div v-if="orderlog.doneData.serials.length>0">
                                <div class=" mb-2" v-for="(serial,ind) in orderlog.doneData.serials" :key="ind" >
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
                            <code v-if="orderlog.doneData.commentall!==''">{{orderlog.doneData.commentall}}</code>
                        </td>
                        <td>
                            {{orderlog.failed.failedstatus}}
                            <code v-if="orderlog.failed.comment!==''">{{orderlog.failed.comment}}</code>
                        </td>
                        <td>{{orderlog.date}}</td>
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
            usersDashbords() {
                return store.state.dashboard.usersDashbords;
            }
        },
        methods: {
            setData() {
                axios
                    .post("/orderLog", {task_id: this.task_id})
                    .then(response => {

                        this.orderlogs = response.data.orderlogs;
                        setTimeout(()=>{
                            $('.serial-popover').popover({});
                        },500)
                    })
                    .catch(error => console.log(error));
            }
        },
        watch: {
            task_id() {
            }
        }
    };
</script>

