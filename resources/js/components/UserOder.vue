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
                        <span class="info_task info_task_ip">IP PORT</span>
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
                            <div class="order_item_inner">
                                          <span class="order_text_all order_text_id">
                                              {{order.task_id}}
                                          </span>
                                          <span class="order_text_all order_text_flag">
                                                 <span v-if="order.flag">
                                                     <img :src="order.flag">
                                                 </span>
                                          </span>
                                          <span class="order_text_all order_text_ip">
                                               {{order.ip}}:{{order.port}}
                                               <i v-clipboard:copy="order.ip+':'+order.port"
                                                  class="fa fa-copy">
                                               </i>
                                          </span>
                                          <span class="order_text_all order_text_domain">
                                                 <span v-if="order.domain===''" class="text-danger">Not Domain\</span><span v-else>{{order.domain}}\</span>{{order.login}}
                                                 <i v-clipboard:copy="order.domain+'\\'+order.login"  class="fa fa-copy" ></i>
                                          </span>
                                          <span class="order_text_all order_text_password">
                                               {{order.password}}
                                               <i v-clipboard:copy="order.password"
                                                  class="fa fa-copy">
                                               </i>
                                          </span>
                                          <span data-title="Weight" class="order_weight">{{order.weight}}</span>
                                          <span class="order_show">
                                             <a href="#"  class="btn btn-primary" @click.prevent="showForm(order)" >report</a>
                                          </span>
                            </div>
                            <div v-if="order.sub_orders.length>0" class=" suborders">
                                <div class="order_item_inner" v-for="sub_order in order.sub_orders">

                                     <span class="order_text_all order_text_id">
                                         {{sub_order.task_id}}
                                     </span>

                                     <span class="order_text_all order_text_flag">
                                         <br>
                                     </span>

                                    <span class="order_text_all order_text_ip">
                                          {{sub_order.ip}}:{{sub_order.port}}
                                          <i v-clipboard:copy="sub_order.ip+':'+sub_order.port"
                                             class="fa fa-copy">
                                          </i>
                                     </span>

                                    <span data-title="domain\login" class="order_text_all order_text_domain">
                                                 <span v-if="sub_order.domain===''" class="text-danger">Not Domain\</span><span v-else>{{sub_order.domain}}\</span>{{sub_order.login}}
                                                 <i v-clipboard:copy="sub_order.domain+'\\'+sub_order.login"  class="fa fa-copy" ></i>
                                    </span>
                                    <span data-title="password" class="order_text_all order_text_password">
                                               {{sub_order.password}}
                                               <i v-clipboard:copy="sub_order.password"
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
                                        <li class="nav-item doneStatusConteer ">
                                            <a class="nav-link active"
                                               data-toggle="tab"
                                               :href="'#done'+order.id"
                                               role="tab"
                                               aria-controls="home" aria-selected="true">
                                                Set status done
                                            </a>
                                        </li>
                                        <li class="nav-item failedStatusConteer">
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
                                        <div class="tab-pane fade show active doneStatusConteer"
                                             :id="'done'+order.id"
                                             role="tabpanel"
                                             aria-labelledby="home-tab">

                                                <!-- если одно IP -->

                                                <div v-if="typeof order.serials=='undefined'||order.serials.length===0">

                                                    <div class="order_form_textarea mb-2 mt-3">
                                                        <input type="text"
                                                               :id="'succes_input_'+order.id"
                                                               placeholder="unique serial number"
                                                               class="form-control"/>
                                                    </div>

                                                    <div class="order_form_textarea  mt-3">
                                                        <input type="text"
                                                               :id="'link_'+order.id"
                                                               placeholder="Link"
                                                               class="form-control"/>
                                                    </div>
                                                    <div class="order_form_textarea mb-3 mt-3">
                                                       <textarea
                                                             :id="'succes_textarea_all_'+order.id"
                                                             class="form-control"
                                                             placeholder=" comment"
                                                        ></textarea>
                                                    </div>
                                                </div>
                                                <div v-else>
                                                    <div class="order_form_textarea mb-3 mt-3">
                                                       <textarea
                                                               :id="'comment_all_With_Serials_'+order.id"
                                                               class="form-control"
                                                               placeholder="All comment"
                                                       ></textarea>
                                                    </div>
                                                </div>
                                                <paste-ordertext :order="order"></paste-ordertext>
                                                <hr/>
                                                <plus-serial :order="order"></plus-serial>
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
                                            <!--</div>-->
                                            <button class="btn setStatus"
                                                    :id="'but_done_'+order.id"

                                                    @click="orderSendDone(order)">Set status done
                                            </button>

                                        </div>

                                        <div class="tab-pane fade failedStatusConteer"
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
                                            <button class="btn setStatus"
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
        <history-orders
                :history_orders="history_orders"
                :status="status"
        ></history-orders>
    </div>
</template>

<script>
    import {mapState} from 'vuex';
    import store from '../store/';
    import SerialNumber from './other/SerialNumber'
    import PasteOrdertext from './other/PasteOrdertext'
    import PlusSerial from './other/PlusSerial'
    import HistoryOrders from './HistoryOrders'
    export default {
        name: "UserorderComponent",
        components: {
            HistoryOrders,
            SerialNumber,
            PasteOrdertext,
            PlusSerial
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
        watch: {
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
            // отправка order успеха на сохранения в базе
            orderSendDone(order){
                let sub_options=order.sub_orders;
                let id = order.id;
                $('#succes_input_' + id).removeClass('is-invalid');
                $('#succes_textarea_all_' + id).removeClass('is-invalid');
                $('#comment_all_With_Serials_' + id).removeClass('is-invalid');
                let serials=order.serials;
                let coommet_all=false,
                    serials_one=false,
                    link_one=false,
                    comment_all_With_Serials=false;
                if($('#link_' + id).length){
                    link_one=$('#link_' + id).val();
                }
                // если не ведена сеть
                if(serials=== undefined ||serials.length===0){
                    if($('#succes_input_' + id).length){
                        serials_one=$('#succes_input_' + id).val();
                        if(serials_one==""){
                            $('#succes_input_' + id).addClass('is-invalid');
                            $("html, body").animate({ scrollTop: $('#succes_input_' + id).offset().top-100+"px" },
                                500, "linear");
                            alert('Serial number empty');
                            return false;
                        }
                    }
                    coommet_all=$('#succes_textarea_all_' + id).val();
                    if(coommet_all==""){
                        $('#succes_textarea_all_' + id).addClass('is-invalid');
                        $("html, body").animate({ scrollTop: $('#succes_textarea_all_' + id).offset().top-100+"px" },
                            500, "linear");
                        alert('Comment  empty');
                        return false;
                    }
                }
                else {
                    // если ведена сеть
                    let boll=false;
                    serials.forEach((serial, ind) => {
                        if(serial.serialinp!==""&&serial.text!==""){
                            boll=true;
                        }
                    });
                    if(!boll){
                        alert('Serial or Comment number empty');
                        return false;
                    }
                    comment_all_With_Serials=$('#comment_all_With_Serials_' + id).val();
                    if(comment_all_With_Serials==""){
                        $('#comment_all_With_Serials_' + id).addClass('is-invalid');
                        $("html, body").animate({ scrollTop: $('#comment_all_With_Serials_' + id).offset().top-100+"px" },
                            500, "linear");
                        alert('Comment All empty');
                        return false;
                    }

                }
                // $('#but_done_'+order.id).prop( "disabled", true );
                let data={
                    id: id,
                    task_id: order.task_id,
                    status: 3,
                    serial_number:serials_one,
                    succes_textarea_all:coommet_all,
                    comment_all_With_Serials:comment_all_With_Serials,
                    link:link_one
                };
                store.dispatch('setOrderCompletion', data);
            },
            //отправить форму со статусом не сделано
            orderFailedSend(order) {
                // $('#but_failed_'+order.id).prop( "disabled", true );
                let id = order.id,
                    data = {
                        id: id,
                        task_id: order.task_id,
                        status: 4,
                        select: $('#failed_select_' + id).val(),
                        comment: $('#failed_text_' + id).val(),
                    };
                store.dispatch('setOrderCompletion', data);
            },

        }
    }
</script>
