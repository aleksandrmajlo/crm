<template>
  <div class="user_dashbord_conteer">
    <div v-if="userDashbords.weight" class="info_user">
      <span class="text-bold">Weight:</span>
      <span class="text-info">{{userDashbords.weight}}</span>
    </div>

    <div v-if="userDashbords.limit" class="info_user">
      <span class="text-bold">Limit used:</span>
      <span class="text-info">{{userDashbords.limit}}</span>
    </div>
    <hr />
    <div v-if="userDashbords.orders&&userDashbords.orders.length>0">
      <h3 class="text-center">Orders</h3>
      <div
        class="order_item_user_conteer"
        v-for="(order,index) in userDashbords.orders"
        :key="index"
      >
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
          <a
            class="btn btn-outline-info"
            :href="'#serials_'+order.id"
            data-toggle="collapse"
          >Count serials {{order.serials.length}}</a>
          <div
            class="collapse"
            :id="'serials_'+order.id"
            v-for="(serial,ind) in order.serials"
            :key="ind"
          >
            <a class="btn btn-outline" target="_blank" :href="serial">Show serial</a>
          </div>
        </div>

        <hr />
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import store from "../../store/";

export default {
  name: "UserReport",
  computed: {
    userDashbords() {
      return store.state.dashboard.userDashbords;
    }
  }
};
</script>