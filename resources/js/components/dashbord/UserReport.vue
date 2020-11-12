<template>
    <div class="user_dashbord_conteer">
        <div class="loader_conteer" v-if="loader">
            <vue-loaders-ball-pulse color="red" scale="1"></vue-loaders-ball-pulse>
        </div>
        <div v-if="user_weight" class="info_user">
            <span class="text-bold">Weight:</span>
            <span class="text-info">{{user_weight}}</span>
        </div>
        <div v-if="user_limit" class="info_user">
            <span class="text-bold">Limit used:</span>
            <span class="text-info">{{user_limit}}</span>
        </div>
        <hr/>
        <div v-if="orders.length">
            <h3 class="text-center">Orders</h3>
            <div class="order_item_user_conteer" v-for="(order,index) in orders" :key="index">
                <div class="info_user">
                    <span class="text-bold">ID:</span>
                    <span class="text-info">
                        <a :href="'/orderLogID?id='+order.id" target="_blank">
                        {{order.id}}
                         </a>
                    </span>
                </div>

                <div class="info_user">
                    <span class="text-bold">Status:</span>
                    <span class="text-info">{{order.status}}</span>
                </div>
                <div class="info_user">
                    <span class="text-bold">Created:</span>
                    <span class="text-info">{{order.created_at}}</span>
                </div>
                <div class="info_user">
                    <span class="text-bold">End:</span>
                    <span class="text-info">{{order.updated_at}}</span>
                </div>
                <div v-if="order.status_id==4">
                    <span class="text-bold">Failed:</span>
                    <span class="text-info">{{order.failed}}</span>
                </div>
                <div v-if="order.status_id==4">
                    <span class="text-bold">Comment:</span>
                    <span class="text-info">{{order.comment}}</span>
                </div>
                <div v-if="order.status_id==3&&order.serials">
                    <a  class="btn btn-outline-info"
                        :href="'#serials_'+order.id"
                        data-toggle="collapse"
                    >Count serials {{order.serials.length}}</a>
                    <div  class="collapse"   :id="'serials_'+order.id"      v-for="(serial,ind) in order.serials"    :key="ind"      >
                        <a class="btn btn-outline" target="_blank" :href="serial">Show serial</a>
                    </div>
                </div>
                <div class="mt-2" v-if="order.notes.length">
                      <a @click.prevent="showNotes(order)" class="btn btn-outline">Notes {{order.notes.length}}</a>
                </div>
                <hr/>
            </div>
            <div class="text-center mb-2" v-if="LoadBlock">
                <button :disabled="disabled" class="btn btn-info" @click.prevent="LoadMore">Load more {{limit}}</button>
            </div>
        </div>
    </div>
</template>

<script>
    import {eventBus} from '../../app'
    // import {mapState} from "vuex";
    // import store from "../../store/";
    export default {
        name: "UserReport",
        data() {
            return {
                user_id: null,
                user_weight: null,
                user_limit: null,
                orders: [],
                offset: 0,
                limit: 20,
                disabled: false,
                LoadBlock: true,
                loader: false
            }
        },
        created() {
            eventBus.$on('UserId', data => {
                this.user_id = data.user_id;
                if (this.user_id == -1) {
                    this.user_id = null;
                    this.user_weight = null;
                    this.user_limit = null;
                    this.orders = [];
                    this.offset = 0;
                } else {
                    this.getData();
                }
            })
        },
        methods: {
            getData() {
                this.loader = true;
                this.disabled = true;
                axios
                    .get("/dashbord/userGetDashbord", {
                        params: {
                            offset: this.offset,
                            id: this.user_id
                        }
                    })
                    .then(response => {
                        if (response.data.success) {
                            if (response.data.orders.length) {
                                this.orders = this.orders.concat(response.data.orders);
                                this.offset = this.offset + this.limit;
                            } else {
                                this.LoadBlock = false;
                            }
                            if (response.data.weight) {
                                this.user_weight = response.data.weight
                                this.user_limit = response.data.limit
                            }
                        }
                    })
                    .catch(error => {
                        this.showShwal('error', 'Error')
                    })
                    .finally(() => {
                        this.disabled = false;
                        this.loader = false;
                    })

            },
            LoadMore() {
                this.getData();
            },
            showNotes(order){
                eventBus.$emit('noteadmin',order)
            }

        }
    };
</script>
<style>
    .user_dashbord_conteer {
        position: relative;
    }
</style>
