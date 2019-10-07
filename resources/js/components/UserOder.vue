<template>
    <div class="UserorderComponent_container">
        <h2 class="text-center mb-5 mt-5">My order</h2>
        <h5>Limit: <span class="text-info">{{user.weight}}</span></h5>
        <h5>Limit used: <span class="text-info">{{limit_used}}</span></h5>
        <div class="card mb-5">
            <div class="card-header text-center">Order in work</div>
            <div class="card-body">
                <div class="order_conteer">
                    <div class="header_task">
                        <span class="info_task info_task_id">ID</span>
                        <span class="info_task info_task_flag">
                            <br/>
                        </span>
                        <span class="info_task info_task_port">IP PORT</span>
                        <span class="info_task info_task_domain">DONAIN\LOGIN</span>
                        <span class="info_task info_task_password">PASSWORD</span>
                        <span class="weight_task ">Weight</span>
                        <span class="button_block ">
                                       <br/>
                        </span>
                    </div>
                    <div class="order_items">
                        <div class="order_item" v-for="(order,index) in orders" :key="index">
                            <div class="loader_conteer" v-if="order.loader">
                                <vue-loaders-ball-pulse color="red" scale="1"></vue-loaders-ball-pulse>
                            </div>
                            <div class="done_conteer" v-if="history_page.indexOf(order.id)!==-1">
                                Order completed
                            </div>
                            <div class="order_item_inner">
                                          <span class="order_text_all order_text_id">
                                              {{order.id}}
                                          </span>
                                          <span class="order_text_all order_text_ip">
                                               {{order.ip}}
                                               <i v-clipboard:copy="order.ip"
                                                  v-clipboard:success="onCopy"
                                                  class="fa fa-copy">
                                               </i>
                                          </span>
                                          <span class="order_text_all order_text_port">
                                               {{order.port}}
                                               <i v-clipboard:copy="order.port"
                                                  v-clipboard:success="onCopy"
                                                  class="fa fa-copy">
                                               </i>
                                          </span>
                                          <span class="order_text_all order_text_domain">
                                               {{order.domain}}\{{order.login}}
                                               <i v-clipboard:copy="order.domain+'\\'+order.login"
                                                  v-clipboard:success="onCopy"
                                                  class="fa fa-copy">
                                               </i>
                                          </span>
                                          <span class="order_text_all order_text_password">
                                               {{order.password}}
                                               <i v-clipboard:copy="order.password"
                                                  v-clipboard:success="onCopy"
                                                  class="fa fa-copy">
                                               </i>
                                          </span>
                                          <span data-title="Weight" class="order_weight">{{order.weight}}</span>
                                         <span class="order_show">
                                             <i
                                                     :class="[order.show ? 'fa-angle-up' : 'fa-angle-down']"
                                                     class="fa"
                                                     @click="showForm(order)"
                                                     aria-hidden="true"
                                             ></i>
                                          </span>
                            </div>
                            <div v-if="order.sub_orders.length>0" class=" suborders">
                                <div class="order_item_inner" v-for="sub_order in order.sub_orders">
                                     <span class="order_text_all order_text_id">
                                         <br>
                                     </span>
                                    <span class="order_text_all order_text_ip">
                                         <br>
                                     </span>
                                    <span data-title="port" class="order_text_all order_text_port">
                                               {{sub_order.port}}
                                               <i v-clipboard:copy="sub_order.port"
                                                  v-clipboard:success="onCopy"
                                                  class="fa fa-copy">
                                               </i>
                                    </span>
                                    <span data-title="domain\login" class="order_text_all order_text_domain">
                                               {{sub_order.domain}}\{{sub_order.login}}
                                               <i v-clipboard:copy="sub_order.domain+'\\'+sub_order.login"
                                                  v-clipboard:success="onCopy"
                                                  class="fa fa-copy">
                                               </i>
                                    </span>
                                    <span data-title="password" class="order_text_all order_text_password">
                                               {{sub_order.password}}
                                               <i v-clipboard:copy="sub_order.password"
                                                  v-clipboard:success="onCopy"
                                                  class="fa fa-copy">
                                               </i>
                                    </span>
                                    <span data-title="Weight" class="order_weight"><br></span>
                                    <span class="order_show"><br></span>
                                </div>
                            </div>
                            <transition name="slide">
                                <div class="order_form" v-show="order.show">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active"
                                               data-toggle="tab"
                                               :href="'#done'+order.id"
                                               role="tab"
                                               aria-controls="home" aria-selected="true">
                                                Set status done
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link"
                                               id="profile-tab"
                                               data-toggle="tab"
                                               :href="'#failed'+order.id"
                                               role="tab"
                                               aria-controls="profile" aria-selected="false">
                                                Set status failed
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active"
                                             :id="'done'+order.id"
                                             role="tabpanel"
                                             aria-labelledby="home-tab">
                                            <div v-if="order.sub_orders.length>0">
                                                <div class="order_form_textarea mb-3 mt-2">
                                                  <textarea
                                                          :id="'succes_textarea_all_'+order.id"
                                                          class="form-control"
                                                          placeholder="All comment"
                                                  ></textarea>
                                                </div>
                                                <div class=" mb-3">
                                                      <h4>Insert serial numbers(copy in .txt and paste this)</h4>
                                                      <textarea  @paste="onPaste($event,order)"
                                                                 @input="onPaste($event,order)"
                                                                 @keyup="onPaste($event,order)"
                                                                 class="form-control"></textarea>
                                                </div>
                                                <hr/>
                                                <div v-if="order.serials&&order.serials.length>0">
                                                    <serial-number
                                                            v-for="(serial,index) in order.serials"
                                                            :serial="serial"
                                                            :index="index"
                                                            :id="order.id"
                                                            :key="index">
                                                    </serial-number>
                                                </div>
                                            </div>
                                            <div v-else>
                                                <div class="order_form_textarea mb-2 mt-2">
                                                    <input type="text"
                                                           :id="'succes_input_'+order.id"
                                                           placeholder="unique serial number"
                                                           class="form-control"/>
                                                </div>
                                                <div class="order_form_textarea mb-2">
                                                  <textarea
                                                          :id="'succes_textarea_'+order.id"
                                                          class="form-control"
                                                          placeholder="comment"
                                                  ></textarea>
                                                </div>
                                            </div>
                                            <button class="btn btn-primary" :id="'but_done_'+order.id" @click="orderSendDone(order)">Set status done
                                            </button>
                                        </div>
                                        <div class="tab-pane fade"
                                             :id="'failed'+order.id"
                                             role="tabpanel" aria-labelledby="profile-tab">
                                            <div class="form-group mt-2">
                                                <select :id="'failed_select_'+order.id" class="form-control">
                                                    <option v-for="(failed,ind) in failed_status" :value="ind">
                                                        {{failed}}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group mb-2">
                                                   <textarea
                                                           :id="'failed_text_'+order.id"
                                                           class="form-control"
                                                           placeholder="comment"
                                                   ></textarea>
                                            </div>
                                            <button class="btn btn-warning"
                                                    :id="'but_failed_'+order.id" @click="orderFailedSend(order)">Set status
                                                failed
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </transition>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-5">
            <div class="card-header text-center">History</div>
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
                        <th>Created</th>
                        <th>Status </th>
                    </tr>
                    </thead>
                    <template v-for="(order,ind) in history_orders">

                        <tr>
                            <td>{{order.id}}</td>
                            <td >
                                {{order.ip}}
                                <span v-if="order.flag">
                                  <img :src="order.flag" >
                               </span>
                            </td>
                            <td>{{order.port}}</td>
                            <td>{{order.domain}}\{{order.login}}</td>
                            <td>{{order.password}}</td>
                            <td>{{order.weight}}</td>
                            <td>{{order.created_at | formatDate}}</td>
                            <td>{{order.updated_at | formatDate}}</td>
                            <td>{{status[order.status]}}</td>
                        </tr>
                        <template v-if="order.sub_orders">
                            <tr v-for="(sub_order,index) in order.sub_orders">
                                <td></td>
                                <td></td>
                                <td>{{sub_order.port}}</td>
                                <td>{{sub_order.domain}}\{{order.login}}</td>
                                <td>{{sub_order.password}}</td>
                                <td>{{sub_order.weight}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </template>

                    </template>

                </table>
            </div>

        </div>


    </div>
</template>

<script>
    import {mapState} from 'vuex';
    import store from '../store/';
    import SerialNumber from './other/SerialNumber'

    export default {
        name: "UserorderComponent",
        components: {
            SerialNumber
        },
        computed: {

            orders() {
                return store.state.user.this_user_order;
            },

            history_orders() {
                return store.state.user.history_orders;
            },

            status() {
                return store.state.user.status;
            },

            user() {
                return store.state.user.user;
            },

            limit_used() {
                return store.state.user.limit_used;
            },

            failed_status() {
                return store.state.user.failed_status;
            },

            history_page() {
                return store.state.user.history_page;
            },
        },
        created() {
            store.dispatch('getUser');
            store.dispatch('this_user_order');
        },
        methods: {

            // показать форму
            showForm(order) {
                store.commit('showForm', order);
            },
            //ввод текста
            getText(order, event) {

            },
            // отправка order успеха на сохранения в базе
            orderSendDone(order){

                let sub_options=order.sub_orders;
                let id = order.id;
                if(sub_options.length>0){
                    let coommet_all=$('#succes_textarea_all_' + id).val();
                    if(coommet_all==""){
                        $('#succes_textarea_all_' + id).addClass('is-invalid')
                        return false;
                    }
                    $('#but_done_'+order.id).prop( "disabled", true );
                    $('#succes_textarea_all_' + id).removeClass('is-invalid')
                    let data={
                        id: id,
                        task_id: order.task_id,
                        status: 3,
                        succes_textarea_all:$('#succes_textarea_all_' + id).val(),
                    };
                    store.dispatch('setordercompletion', data);
                }else{
                    let data={
                        id: id,
                        task_id: order.task_id,
                        status: 3,
                        serial_number: $('#succes_input_' + id).val(),
                        comment: $('#succes_textarea_' + id).val(),
                    };
                    store.dispatch('setordercompletion', data);
                }

            },
            //отправить форму со статусом не сделано
            orderFailedSend(order) {
                $('#but_failed_'+order.id).prop( "disabled", true );
                let id = order.id,
                    data = {
                        id: id,
                        task_id: order.task_id,
                        status: 4,
                        select: $('#failed_select_' + id).val(),
                        comment: $('#failed_text_' + id).val(),
                    };
                store.dispatch('setordercompletion', data);

            },
            orderSend(order, status) {
            },
            //вставка серийных номеров
            onPaste(e,order){
                 let el=event.target;
                 setTimeout(() => {
                     let text=el.value;
                     console.log(text)
                     store.commit('InsertSerialNumbers',{
                         text:text,
                         order:order
                     })
                 }, 0);
            },
            onCopy(e) {
                alert("You just copied: " + e.text);
            }
        }
    }

</script>

<style scoped>

</style>