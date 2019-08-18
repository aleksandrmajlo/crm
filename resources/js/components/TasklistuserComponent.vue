<template>
     <div class="TasklistuserComponent_conteer">
          <h2 class="text-center mb-5 mt-5">Material available</h2>
         <h5>Limit: <span class="text-info">{{user.weight}}</span></h5>
         <div class="card mb-5" v-for="(task_,index) in tasks " :key="index" >
             <div v-if="task_[0]" class="card-header text-center">{{task_[0].timestamp  | formatDate}}</div>
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
                           <td>{{task.id}}</td>
                           <td>
                               {{task.ip}}
                               <span v-if="task.flag">
                                  <img :src="task.flag" >
                               </span>
                           </td>
                           <td  >{{task.port}}</td>

                           <td v-if="user.blind==1" class="blind">XXXXXXXXXXX</td>
                           <td v-else  >{{task.domain}}\{{task.login}}</td>


                            <td  v-if="user.blind==1" class="blind">XXXXXXXXXXX</td>
                            <td v-else>{{task.password}}</td>


                          <td >{{task.weight}}</td>
                          <td class="text-center" >
                              <div v-if="task.status==1">
                                  <a href="#" class="btn btn-link" @click.prevent="add(task.id)">Add</a>
                              </div>

                          </td>
                          <td class="text-center"></td>
                     </tr>
                     </tbody>
                 </table>
             </div>
         </div>
     </div>
</template>

<script>
    import { mapState } from 'vuex';
    import store from '../store/';
    export default {
        name: "TasklistuserComponent",
        computed: {
            tasks() {
                return store.state.user.tasks;
            } ,
            status() {
                return store.state.user.status;
            },
            user(){
                return store.state.user.user;
            }
        },
        created() {
            store.dispatch('getUser');
            store.dispatch('getUserTasks');
        },
    }
</script>

<style scoped>

</style>