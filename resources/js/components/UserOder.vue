<template>
    <div class="UserorderComponent_container">
        <h2 class="text-center mb-5 mt-5">My order</h2>
        <h5>Limit: <span class="text-info">{{user.weight}}</span></h5>
        <h5>Limit used: <span class="text-info">{{limit_used}}</span></h5>
        <user-export v-if="user.exportallowed"></user-export>
        <div class="card mb-5">
            <div class="card-header text-center">Order in work</div>
            <div class="card-body">
                <a href="#" class="btn btn-secondary" @click.prevent="FieledAddArray">Set failed</a>
                <div class="order_conteer">
                    <div class="header_task">
                        <span class="info_task info_task_id">ID<br/>
                        </span>
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

                                          <div class="order_text_all order_text_id">
                                               <span>{{order.task_id}}</span>
                                                <span>
                                                    <input type="checkbox" v-model="failedArr" :value="order.id">
                                                </span>
                                          </div>
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
                                             <a href="#"  class="btn btn-info mt-3" @click.prevent="addNote(order)" >note</a>
                                              <a href="#" class="btn  btn-danger mt-3" @click.prevent="refuseOrder(order)">refuse</a>
                                          </span>
                                         <div v-if="order.admincomments" class="text-center" style="border:1px solid;padding: 10px;">
                                             <span v-if="order.admincomments.length">
                                                 <a class="btn btn-secondary text-nowrap" data-toggle="modal" href="#"
                                                    data-target="#adminCooments" @click.prevent="showComments(order.id)">
                                                     Show comments - {{order.admincomments.length}}
                                                  </a>
                                             </span>
                                             <span v-else>Not comment</span>
                                             <span v-if="order.admincomments.length>0">Not viewed:{{Not_viewed(order.id)}}</span>

                                         </div>
                                         </div>
                                           <!-- suborder start    -->
                                           <div v-if="order.sub_orders.length" class=" suborders">
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
                                           <!-- suborder end    -->

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
                                                               :value="order.comment_all_With_Serials"
                                                               placeholder="All comment"></textarea>
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
                                             role="tabpanel">
                                            <div class="form-group mt-2">
                                                <select :id="'failed_select_'+order.id" :value="order.select" class="form-control">
                                                    <option v-for="(failed,ind) in failed_status" :value="ind">
                                                        {{failed}}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group mb-2">
                                                   <textarea
                                                           :id="'failed_text_'+order.id"
                                                           :value="order.comment"
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
        <admincooments-modal></admincooments-modal>
        <note-user></note-user>
        <FieledFormmodal></FieledFormmodal>
        <refuse-order></refuse-order>
    </div>
</template>

<script>
    import {eventBus} from "../app";
    import {mapState} from 'vuex';
    import store from '../store/';
    import SerialNumber from './serial/SerialNumber'
    import PlusSerial from './serial/PlusSerial'
    import PasteOrdertext from './other/PasteOrdertext'
    import UserExport from "./export/UserExport";
    import NoteUser from "./order/NoteUser";
    import FieledFormmodal from "./order/FieledFormmodal";
    import RefuseOrder from "./order/RefuseOrder";
    export default {
        name: "UserorderComponent",
        components: {
            SerialNumber,
            PasteOrdertext,
            PlusSerial,
            UserExport,
            NoteUser,
            FieledFormmodal,
            RefuseOrder
        },
        data(){
            return {
                failedArr:[]
            }
        },
        computed: {
            orders() {
                return store.state.user.this_user_order;
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

            // отказаться от order
            refuseOrder(order){
                eventBus.$emit('refuseOrderEvent',order)
            },
            // количество непросмотренных
            Not_viewed(order_id) {
                let count = 0;
                let i = this.orders.map(order => order.id).indexOf(order_id);
                let order = this.orders[i];
                order.admincomments.forEach((el) => {
                    if (el.viewed === 0) count++;
                })
                return count;
            },
            showComments(order_id) {
                let i = this.orders.map(order => order.id).indexOf(order_id);
                let order = this.orders[i];
                eventBus.$emit("ModalComment", { order: order });

            },
            // добавить пометку
            addNote(order){
                eventBus.$emit('noteuser',order)
            },
            // добавить для массив статус fieled
            FieledAddArray(){
                if(this.failedArr.length){
                    eventBus.$emit('FieledForm',{
                        failedArr:this.failedArr
                    });
                    this.failedArr=[];
                }else {
                    this.showShwal('error','Empty')
                }

            }

        }
    }
</script>
