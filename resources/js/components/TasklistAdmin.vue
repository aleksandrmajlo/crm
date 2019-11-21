<template>
     <div class="TasklistComponent_conteer">
          <h2 class="text-center mb-5 mt-5">List Task</h2>
          <div class="card mb-5" v-for="(task_,index) in tasks " :key="index" >
               <div v-if="task_[0]" class="card-header text-center">
                    <a class="btn btn-outline-info"
                       data-toggle="collapse"
                       :href="'#collapse'+index"
                       @click="showClick(index)"
                       role="button" aria-expanded="false" aria-controls="collapseExample">
                         {{task_[0].timestamp  | formatDate}}
                    </a>
               </div>
               <div class="collapse"  :id="'collapse'+index">
                    <div class="card-body">
                         <div class="double-scroll-read-list" :id="'collapse'+index+'scroll'" >
                              <table class="table table-sm">
                                   <thead>
                                   <tr>
                                        <th>ID</th>
                                        <th></th>
                                        <th>IP PORT</th>
                                        <th>DONAIN\LOGIN</th>
                                        <th>PASSWORD</th>
                                        <th>COST</th>
                                        <th>STATUS</th>
                                        <th style="max-width: 30px;"></th>
                                        <th></th>
                                   </tr>
                                   </thead>
                                   <tbody>
                                   <tr v-for="(task,ind) in task_"
                                       :key="ind"
                                       :style="{'background-color':task.color!=='' ? task.color : '' }"
                                   >
                                        <td>{{task.id}}</td>
                                        <td>
                                               <span v-if="task.flag">
                                                    <img :src="task.flag" >
                                               </span>
                                        </td>
                                         <td >
                                             {{task.ip}}:{{task.port}}
                                             <i v-clipboard:copy="task.ip+':'+task.port"
                                                class="fa fa-copy">
                                             </i>

                                        </td>
                                        <td>
                                             <span v-if="task.domain===''" class="text-danger">Not Domain\</span><span v-else>{{task.domain}}\</span>{{task.login}}
                                             <i v-clipboard:copy="task.domain+'\\'+task.login"  class="fa fa-copy" ></i>
                                        </td>
                                        <td>{{task.password}}</td>
                                        <td >{{task.weight}}</td>
                                        <td class="text-center">{{status[task.status]}}</td>

                                        <td style="max-width: 30px;">
                                             <a href="#" @click.prevent="LogTask(task.id)" class>
                                                  <i class="fa fa-info-circle"></i>
                                             </a>
                                        </td>

                                        <td class="text-center">
                                              <span class="d-block" v-if="task.username">
                                                    {{task.username}} {{task.useremail}}
                                              </span>
                                            <a href="#"
                                               @click.prevent="ChangeTask(task.id)"
                                               class="btn btn-info">
                                                Change
                                            </a>
                                        </td>
                                   </tr>
                                   </tbody>
                              </table>

                         </div>

                    </div>
               </div>
          </div>
     </div>
</template>

<script>
    import { mapState } from 'vuex';
    import store from '../store/';
    export default {
        name: "TasklistAdmin",
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
        methods:{
            // прокрутку добавить
            showClick(index){
                let $el=$('#collapse'+index+'scroll'),
                    first=$el.data('first')
                if(typeof first =="undefined"){
                    setTimeout(()=>{
                        $el.find('table').DataTable({
                            "columnDefs": [
                                {
                                    "targets": [ 1,6,7],
                                    "orderable": false,
                                    "searchable": false
                                },
                            ]
                        });
                    },500)
                    $el.data('first',true)
                }
            },
            // установить пользователя
            ChangeTask(task_id) {
                this.$root.$emit('showreadtask', task_id);
                $('#SelectUser').modal('show');
            },
            //посмотреть лог по ид
            LogTask(task_id) {
                this.$root.$emit("showLogtask", task_id);
                $("#LogTask").modal("show");
            }

        }

    }
</script>

