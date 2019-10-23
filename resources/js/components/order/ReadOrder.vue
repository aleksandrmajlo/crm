<template>
    <div class="orderReadConteer row justify-content-md-center">
        <div class="loader_conteer" v-if="loader">
            <vue-loaders-ball-pulse color="red" scale="1"></vue-loaders-ball-pulse>
        </div>
        <div class=" col-md-6 col-md-offset-3" v-if="order.status==4">
            <div class="card">
                <div class="card-body">
                    <h5 class="cart-title">Status: failed</h5>
                    <div class="form-group">
                        <select class="form-control" ref="updateFailedSelect">
                            <option
                                    v-for="(item,index) in failed"
                                    :selected="index==order.selectFailed"
                                    :value="index"
                                    :key="index"
                            >{{item}}
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea ref="updateFailedTextarea" class="form-control" :value="order.commentFailed"></textarea>
                    </div>
                    <button class="btn btn-primary" @click.prevent="save('updateFailed')">Save Change</button>
                    <br>
                    <br>
                    <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                        Change Status
                    </a>
                </div>
                <div class="card-body collapse"   id="collapseExample">
                    <div class="pt-2 pb-2">
                        <a style="font-size: 150%" href="#" @click.prevent="plusSerial()">
                            <i class="fa fa-plus-square"></i>
                        </a>
                    </div>
                    <div class="row mb-4" v-for="(serial,index) in serials">
                        <div class="col-md-1 text-center">
                            <a href="#" @click.prevent="remove(index)">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" @input="setValue(index,'serial',$event)" :value="serial.serial" placeholder="Serial number"/>
                        </div>
                        <div class="col-md-3">
                            <input class="form-control" @input="setValue(index,'link',$event)" :value="serial.link" placeholder="Link"/>
                        </div>
                        <div class="col-md-4">
                            <textarea class="form-control"  @input="setValue(index,'text',$event)" :value="serial.text" placeholder="Text"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <a class="btn-info btn" href="#" @click.prevent="save('changeFailed')"> Save New Status</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class=" col-md-6 col-md-offset-3" v-if="order.status==3">
            <div class="card">
                <div class="card-body">
                    <h5 class="cart-title">Status: Done</h5>
                    <div class="pt-2 pb-2">
                        <textarea class="form-control" @input="setValueSerial(0,'commentAll',$event)" :value="order.comment=='0'?'':order.comment"></textarea>
                    </div>
                    <div class="pt-2 pb-2">
                        <a style="font-size: 150%" href="#" @click.prevent="plusSerialDone()">
                            <i class="fa fa-plus-square"></i>
                        </a>
                    </div>
                    <div class="row mb-4" v-for="(serial,index) in order.serials">
                        <div class="col-md-1 text-center">
                            <a href="#" @click.prevent="removeDone(index)">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control" @input="setValueSerial(index,'serial',$event)" :value="serial.serial"
                                   placeholder="Serial number"/>
                        </div>
                        <div class="col-md-3">
                            <input class="form-control" @input="setValueSerial(index,'link',$event)" :value="serial.link"
                                   placeholder="Link"/>
                        </div>
                        <div class="col-md-4">
                            <textarea class="form-control"  @input="setValueSerial(index,'text',$event)" :value="serial.text"
                                      placeholder="Text"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <a class="btn-info btn" href="#" @click.prevent="save('updateDone')"> Save Change</a>
                        </div>
                    </div>
                    <br>
                    <br>
                    <a class="btn btn-primary" data-toggle="collapse" href="#collapseExample2" role="button" aria-expanded="false" aria-controls="collapseExample">
                        Change Status
                    </a>
                </div>
                <div class="card-body collapse"   id="collapseExample2">
                    <div class="form-group">
                        <select class="form-control" ref="changeDoneSelect">
                            <option
                                    v-for="(item,index) in failed"
                                    :value="index"
                                    :key="index"
                            >{{item}}
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea ref="changeDoneTextarea" class="form-control" ></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <a class="btn-info btn" href="#" @click.prevent="save('changeDone')"> Save New Status</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import {mapState} from "vuex";
    import store from "../../store/";
    import Swal from 'sweetalert2/dist/sweetalert2.js'
    export default {
        name: "ReadOrder",
        data: function () {
            return {
                loader: false,
                serials:[
                    {
                        serial:"",
                        link:"",
                        text:"",
                    }
                ]
            };
        },
        props: ["orderId"],
        computed: {
            order() {
                return store.state.order.order;
            },
            failed() {
                return store.state.order.failed;
            }
        },
        created() {
            store.dispatch("GetOrderData", this.orderId).then(() => {
            });
        },
        methods: {
            save(type) {

                if(type=='changeFailed'){
                    let b=true;
                    this.serials.forEach((el)=>{
                        if(el.text==""||el.serial==""){
                            b=false;
                        }
                    });
                    if(!b){
                        alert('Empty Serial or Comment');
                        return;
                    }
                }
                if(type=='updateDone'){
                    let b=true;
                    this.order.serials.forEach((el)=>{
                        if(el.text==""||el.serial==""){
                            b=false;
                        }
                    })
                    if(!b){
                        alert('Empty Serial or Comment');
                        return;
                    }
                }
                if(type=="changeDone"){
                    let changeDoneTextareaValue=this.$refs.changeDoneTextarea.value;
                    if(changeDoneTextareaValue==""){
                        alert('Empty  Comment');
                        return;
                    }
                }
                // this.loader = true;
                let data = {
                    type: type,
                    order_id: this.orderId
                };
                switch (type) {
                    case 'updateFailed':
                        data.comment = this.$refs.updateFailedTextarea.value;
                        data.select = this.$refs.updateFailedSelect.value;
                        break;

                    case 'changeFailed':
                        data.serials=this.serials;
                        break;

                    case 'updateDone':
                        data.serials=this.order.serials;
                        data.comment=this.order.comment;
                        break;

                    case 'changeDone':
                        data.select=this.$refs.changeDoneSelect.value;
                        data.comment=this.$refs.changeDoneTextarea.value
                        break;

                }

                store.dispatch("UpdateUserOrder", data).then(() => {
                    this.loader = false;
                    Swal.fire({
                        type: "success",
                        title: "Success",
                        timer: 3500
                    });
                    store.dispatch("GetOrderData", this.orderId);
                });
            },
            remove(index) {
                this.serials.splice(index, 1);
            },
            plusSerial(){
               this.serials.push(
                   {
                       serial:"",
                       link:"",
                       text:"",
                   }
               )
            },
            setValue(index,type,event){
                this.serials[index][type]=event.target.value;
            },
            setValueSerial(index,type,event){
                let data={
                    index:index,
                    type:type,
                    value:event.target.value

                }
                store.commit('setValueSerial',data)
            },
            removeDone(index){
                store.commit('removeDoneSerial',index)
            },
            plusSerialDone(){
                store.commit('plusSerialDone')
            }
        }
    };
</script>

