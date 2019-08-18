<template>
      <div class="TaskreadComponent_conteer">
            <h2 class="text-center mb-4">Read Tasks</h2>

                  <div class="card mb-5" v-for="(read_task,index) in read_tasks" :key="index" >
                        <div v-if="read_task[0]" class="card-header text-center">{{read_task[0].timestamp  | formatDate}}</div>
                        <div class="card-body">
                              <table class="table table-sm">
                                    <thead>
                                    <tr>
                                          <th class="text-left" colspan="5">
                                                <a href="#" class="btn btn-primary" @click.prevent="saveRead(index)">Save</a>
                                          </th>
                                          <th class="text-right" colspan="4">
                                                <a href="#" class="btn btn-primary" @click.prevent="saveSelect(index)">Save select</a>
                                          </th>
                                    </tr>
                                    <tr>
                                          <th>ID</th>
                                          <th>IP</th>
                                          <th>PORT</th>
                                          <th>DONAIN\LOGIN</th>
                                          <th>PASSWORD</th>
                                          <th>COST</th>
                                          <th >
                                                <div class="form-group form-check">
                                                      <input type="checkbox" class="form-check-input" @change="checkAll($event,index)" />
                                                </div>
                                          </th>
                                          <th>STATUS</th>
                                          <th>
                                                <input :id="'all_'+index" class="form-control"  type="text" />
                                          </th>
                                    </tr>
                                    </thead>
                                    <tbody :id="'tbody_'+index">
                                        <tr v-for="(task,ind) in read_task">
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
                                              <td>
                                                    <input
                                                            class="form-control"
                                                            :ref="'weight'+index+ind"
                                                            :value="task.weight"
                                                            type="text"
                                                    />
                                              </td>
                                              <td>
                                                    <div class="form-group form-check">
                                                          <input
                                                                  type="checkbox"
                                                                  :data-id="task.id"
                                                                  class="form-check-input"
                                                          />
                                                    </div>
                                              </td>
                                              <td class="text-center">{{status[task.status]}}</td>
                                              <td>
                                                    <a href="#" @click.prevent="saveLink(task.id,index,ind)" class="btn btn-link">save</a>
                                              </td>

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
        name: "TaskreadComponent",
        data() {
            return {

             };
        },
        computed: {
            read_tasks() {
                return store.state.task.read_tasks;
            } ,
            status() {
                return store.state.task.status;
            }
        },
        created() {
            store.dispatch('getTasks').then(() => {
                this.loading = false;
            });
        },
        methods:{
            //установить все чекбоксы
            checkAll(event,index) {
                let this_el = event.target,
                    bol = $(this_el).prop("checked");
                $('#tbody_'+index).find('[type="checkbox"]').prop("checked", bol);
            },
            //сохранить выбранные
            saveSelect(index){
                let res=[];
                $('#tbody_'+index).find('[type="checkbox"]').each(function (el) {
                    if($(this).prop("checked")){
                        res.push($(this).data('id'))
                    }
                });
                store.commit("saveReadWeightSelected", {
                    index:index,
                    val: $('#all_'+index).val(),
                    indexs: res
                });
            },
            saveLink(id,index,ind){
                let inp = this.$refs["weight" + index+ind],
                    val = $(inp).val();
                store.commit('saveWeightRead',{id:id,index:index,val:val})
            },
            // сохранить все изменения
            saveRead(index){
                store.dispatch('saveRead', index)
            }
        }
    }
</script>

<style scoped>

</style>