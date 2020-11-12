<template>
    <div class="date_report_conteer">
        <div class="card">
            <div class="card-header">By user</div>
            <div class="card-body">
                <div class="form-group">
                    <select class="form-control" v-model="user">
                        <option value="-1">Select</option>
                        <option
                            v-for="(user,index) in usersDashbords"
                            :key="index"
                            :value="user.id"
                        >{{user.name}}
                        </option>
                    </select>
                </div>
                <div class="mb-5">
                    <button @click.prevent="getUser" class="btn btn-primary">Get</button>
                    <a @click.prevent="clear" class="btn btn-danger">Clear</a>
                </div>
                <user-report></user-report>
            </div>
        </div>
    </div>
</template>

<script>
    import {eventBus} from '../../app'
    import {mapState} from "vuex";
    import store from "../../store/";
    import UserReport from './UserReport'

    export default {
        name: "UsersReport",
        components: {
            UserReport
        },
        data() {
            return {
                user: -1
            };
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
            getUser() {
                if (this.user == -1) {
                    alert("Select user");
                    return false;
                }
                eventBus.$emit('UserId',{
                    user_id:this.user
                })
            },
            clear(){
                 this.user=-1;
                eventBus.$emit('UserId',{
                    user_id:this.user
                })
            }
        }
    };
</script>
