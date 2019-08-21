<template>
     <div class="TasklistuserComponent_conteer">
         <h2 class="text-center mb-5 mt-5">Material available</h2>
         <h5>Limit: <span class="text-info">{{user.weight}}</span></h5>
         <h5>Limit used: <span class="text-info">{{limit_used}}</span></h5>
         <div class="card mb-5" v-for="(task_,index) in tasks " :key="index" >
             <div v-if="task_[0]" class="card-header text-center">
                 <a class="btn btn-outline-info"
                    data-toggle="collapse"
                    :href="'#collapse'+index"
                    role="button" aria-expanded="false" aria-controls="collapseExample">
                     {{task_[0].timestamp  | formatDate}}
                 </a>
             </div>
             <div class="collapse"  :id="'collapse'+index">
                 <div class="card-body">
                     <table class="table table-sm">
                         <thead>
                         <tr>
                             <th>ID</th>
                             <th>IP</th>
                             <th>PORT</th>
                             <th>DONAIN\LOGIN</th>
                             <th>PASSWORD</th>
                             <th>COST</th>
                             <th> </th>
                             <th> </th>
                         </tr>
                         </thead>
                         <tbody :id="'tbody_'+index">
                         <tr v-for="(task,ind) in task_">

                                <!--<pre>-->
                                    <!--{{task}}-->
                                 <!--</pre>-->

                             <td>{{task.id}}</td>

                             <!--******ip************************-->
                             <td v-if="task.status==2&&task.user_id!==user.id">
                                 <div class="blind">{{xxx}}</div>
                             </td>

                             <td v-else>
                                 {{task.ip}}
                                 <span v-if="task.flag">
                                  <img :src="task.flag" >
                               </span>
                             </td>
                             <!--******ip end************************-->

                             <!--******port************************-->
                             <td v-if="task.status==2&&task.user_id!==user.id">
                                 <div class="blind">{{xxx}}</div>
                             </td>
                             <td v-else>{{task.port}}</td>
                             <!--******port end************************-->


                             <!--******login************************-->
                             <td v-if="user.blind==1!(task.user_id==user.id)&&user_orders.indexOf(task.id)==-1" >
                                 <div class="blind">{{xxx}}</div>
                             </td>
                             <td v-else  >{{task.domain}}\{{task.login}}</td>
                             <!--******login end************************-->

                             <!--******password************************-->
                             <td  v-if="user.blind==1&&!(task.user_id==user.id)&&user_orders.indexOf(task.id)==-1" class="blind">
                                 <div class="blind">{{xxx}}</div>
                             </td>
                             <td v-else>{{task.password}}</td>
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
         </div>
     </div>
</template>

<script>
    import { mapState } from 'vuex';
    import store from '../store/';
    export default {
        name: "TasklistuserComponent",
        data:function() {
            return {
                xxx:"XXXXXXXXXXX"
            };
        },
        computed: {
            tasks() {
                return store.state.user.tasks;
            } ,
            status() {
                return store.state.user.status;
            },

            user(){
                return store.state.user.user;
            },
            limit_used(){
                return store.state.user.limit_used;
            },

            user_orders(){
                return store.state.user.user_orders;
            },
        },
        created() {
            store.dispatch('getUser');
            store.dispatch('getUserTasks');
        },
        methods:{
            add($id,event){
                let $btn=$(event.target);
                $btn.attr('disabled',true);
                store.dispatch('addUserOrder',$id);
            }
        }
    }
</script>

<style scoped>

</style>