<template>
    <div class="modal fade" id="SelectUser" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <h2 class="text-center">ID:{{task_id}}</h2>
                    <div class="alert alert-warning" v-show="ShowError">
                        Empty Status Or User
                    </div>
                    <div class="form-group">
                        <label>Set Status</label>
                        <select class="form-control" v-model="status">
                            <option value="1">free</option>
                            <option value="2">work</option>
                        </select>
                    </div>

                    <div class="form-group" v-if="status==2">
                        <label>User</label>
                        <select class="form-control" v-model="user_id" name="user_id">
                            <option value="-1">Select</option>
                            <option
                                v-for="(user,index) in usersDashbords"
                                :key="index"
                                :value="user.id"
                            >{{user.name}}
                            </option>
                        </select>
                    </div>
                    <input type="hidden" name="task_id">
                    <button class="btn btn-primary" :disabled="isDisabled" @click.prevent="send" type="submit">Send</button>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from "vuex";
    import store from "../../store/";
    import {eventBus} from "../../app";

    export default {
        name: "AdminUpdate",
        data() {
            return {
                status: '',
                user_id: -1,
                task_id: 0,
                ShowError: false,
                isDisabled: false,
                update:false
            }
        },
        computed: {
            usersDashbords() {
                return store.state.dashboard.usersDashbords;
            }
        },
        created() {
            store.dispatch("usersGetDashbord");
        },
        methods: {
            send() {
                if (this.status === "") {
                    this.ShowError = true;
                } else if (this.status == 2 && this.user_id == -1) {
                    this.ShowError = true;
                } else {
                    this.ShowError = false;
                    this.isDisabled = true;
                    axios.post('/order/ChangeOrder',
                        {
                            task_id: this.task_id,
                            status: this.status,
                            user_id: this.user_id
                        }
                    )
                        .then(response => {
                            if (response.data.success) {
                                this.isDisabled = false;
                                store.dispatch('getTasks');
                                $('#SelectUser').modal('hide');
                                if (this.reload) {
                                    location.reload();
                                }else{
                                    eventBus.$emit('updateTaskStatusNew',{
                                        task_id:this.task_id
                                    });
                                }
                                // if(this.update){
                                //     eventBus.$emit('updateTaskStatusNew',{
                                //         task_id:this.task_id
                                //     });
                                // }
                            }
                        });
                }

            }
        },
        mounted() {
            this.$root.$on('showreadtask', ob => {
                this.task_id = ob.task_id;
                this.reload = ob.reload;
                if(typeof ob.update!=="undefined"){
                    this.update=true;
                }else{
                    this.update=false;
                }
            });
        }
    };
</script>
