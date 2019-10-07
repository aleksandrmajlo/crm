<template>
     <div class="TasklistComponent_conteer">
          <h2 class="text-center mb-5 mt-5">List Task</h2>
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
                                   <th>STATUS</th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr v-for="(task,ind) in task_"
                                  :key="ind"
                                  :style="{'background-color':task.color!=='' ? task.color : '' }"
                              >
                                   <td>{{task.id}}</td>
                                   <td>
                                        {{task.ip}}
                                        <span v-if="task.flag">
                                                          <img :src="task.flag" >
                                                    </span>
                                   </td>
                                   <td>{{task.port}}</td>
                                   <td>{{task.domain}}\{{task.login}}</td>
                                   <td>{{task.password}}</td>
                                   <td >{{task.weight}}</td>
                                   <td class="text-center">{{status[task.status]}}</td>
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
        name: "TasklistComponent",
        computed: {
            tasks() {
                return store.state.task.tasks;
            } ,
            status() {
                return store.state.task.status;
            }
        },
        created() {
        },

    }
</script>

