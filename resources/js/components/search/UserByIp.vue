<template>
    <div class="UserByIpSearchConteer mb-5">

        <div class="search mb-5">
            <h2 class="text-center">Search by IP</h2>
            <div class="form-group">
                <input v-model="q" class="form-control" placeholder="IP" type="text"/>
            </div>
            <div class="text-center">
                <a @click.prevent="search" class="btn btn-primary" href="#">Search</a>
            </div>
        </div>

        <div class="card" v-show="searchs.length>0">

            <div class="card-body">
                    <h5 class="card-title text-center">Results search: <span class="text-info">{{q}}</span> </h5>
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

                        <tr v-for="(task,ind) in searchs">
                            <td>{{task.id}}</td>
                            <!--******flag************************-->
                            <td v-if="task.status==2&&task.user_id!==user.id">
                                <div class="blind">{{xxx}}</div>
                            </td>
                            <td v-else>
                                    <span v-if="task.flag">
                                      <img :src="task.flag" >
                                   </span>
                            </td>
                            <!--******flag end************************-->
                            <!--******ip port************************-->
                            <td v-if="task.status==2&&task.user_id!==user.id">
                                <div class="blind">{{xxx}}</div>
                            </td>
                            <td v-else>
                                {{task.ip}}:{{task.port}}
                                <i v-clipboard:copy="task.ip+':'+task.port"
                                   class="fa fa-copy">
                                </i>
                            </td>
                            <!--******port end************************-->
                            <!--******login************************-->
                            <td v-if="user.blind==1&&!(task.user_id==user.id)&&user_orders.indexOf(task.id)==-1" >
                                <div class="blind">{{xxx}}</div>
                            </td>
                            <td v-else  >
                                     <span v-if="task.domain===''" class="text-danger">
                                          Not Domaim \
                                     </span>
                                <span v-else>
                                           {{task.domain}}\
                                     </span>
                                {{task.login}}
                                <i v-clipboard:copy="task.domain+'\\'+task.login"
                                   class="fa fa-copy">
                                </i>
                            </td>
                            <!--******login end************************-->
                            <!--******password************************-->
                            <td  v-if="user.blind==1&&!(task.user_id==user.id)&&user_orders.indexOf(task.id)==-1" class="blind">
                                <div class="blind">{{xxx}}</div>
                            </td>
                            <td v-else>
                                {{task.password}}
                                <i v-clipboard:copy="task.password"
                                   class="fa fa-copy">
                                </i>
                            </td>
                            <!--******password************************-->
                            <!--******weight************************-->
                            <td v-if="task.status==2&&task.user_id!==user.id">
                                <div class="blind">{{xxx}}</div>
                            </td>
                            <td v-else>{{task.weight}}</td>
                            <!--******weight end ************************-->
                            <td class="text-center" >
                                <div v-if="task.status==1&&user_orders.indexOf(task.id)==-1">
                                    <button class="btn btn-link" @click.prevent="add(task.id,$event)">Add</button>
                                </div>
                                <div v-else-if="user_orders.indexOf(task.id)!==-1||task.user_id==user.id">
                                    <span>My order</span>
                                </div>
                            </td>
                            <td class="text-center">
                              <span v-if="user_orders.indexOf(task.id)!==-1||task.user_id==user.id">
                                    Work  {{user.name}}
                              </span>
                            </td>
                        </tr>

                        </tbody>
                    </table>
            </div>
        </div>
        <hr/>
    </div>
</template>

<script>
    import {mapState} from 'vuex';
    import store from '../../store/';

    export default {
        name: "UserByIp",
        data() {
            return {
                q: '',
                xxx:"XXXXXXXXXXX"
            };
        },
        computed: {
            searchs() {
                return store.state.search.searchs;
            },
            user_orders(){
                return store.state.user.user_orders;
            },
            user(){
                return store.state.user.user;
            },
        },
        methods: {
            search() {
                store.dispatch('UserByIp', this.q);
            },
            add($id,event){
                let $btn=$(event.target);
                $btn.attr('disabled',true);
                store.dispatch('addUserOrder',$id);
            },
        }
    }
</script>

<style scoped>

</style>