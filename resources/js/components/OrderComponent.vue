<template>
  <div class="order_conteer">
    <div class="header_task">
      <span class="info_task">Info</span>
      <span class="weight_task">Weight</span>
      <span class="buutton_block">
        <br />
      </span>
    </div>
    <div class="order_items">
      <div class="order_item" v-for="(order,index) in orders">
        <div class="loader_conteer" v-if="order.loader">
          <vue-loaders-ball-pulse color="red" scale="1"></vue-loaders-ball-pulse>
        </div>
        <div class="done_conteer" v-if="order.done">Order completed</div>

        <div class="order_item_inner">
          <span data-title="Task" class="order_text">
            {{order.ip+':'+order.port+'@'+order.domain+'\\'+order.login+';'+order.password}}
            <i
              v-clipboard:copy="order.ip+':'+order.port+'@'+order.domain+'\\'+order.login+';'+order.password"
              v-clipboard:success="onCopy"
              class="fa fa-copy"
            ></i>
          </span>
          <span data-title="Weight" class="order_weight">{{order.weight}}</span>
          <span class="order_show">
            <i
              :class="[order.show ? 'fa-angle-up' : 'fa-angle-down']"
              class="fa"
              @click="showForm(order,index)"
              aria-hidden="true"
            ></i>
          </span>
        </div>
        <transition name="slide">
          <div class="order_form" v-show="order.show">
            <div class="order_form_textarea">
              <textarea
                @input="getText(order,index,$event)"
                class="form-control"
                placeholder="comment"
              ></textarea>
            </div>
            <div class="order_form_button">
              <button class="btn btn-primary" @click="orderSend(order,index,3)">Set status done</button>
              <button class="btn btn-warning" @click="orderSend(order,index,4)">Set status failed</button>
            </div>
          </div>
        </transition>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "OrderComponent",
  data: function() {
    return {
      orders: [],
      history: []
    };
  },
  mounted() {
    this.getOrders();
  },
  methods: {
    // получить заказы
    getOrders() {
      let api = "/ajax/orders";
      Vue.axios
        .post(api, {})
        .then(response => {
          this.orders = response.data.orders;
        })
        .catch(error => {
          this.error();
        });
    },
    // показать форму
    showForm(order, index) {
      if (
        typeof this.orders[index].show == "undefined" ||
        !this.orders[index].show
      ) {
        Vue.set(this.orders[index], "show", true);
      } else {
        Vue.set(this.orders[index], "show", false);
      }
    },
    //ввод текста
    getText(order, index, e) {
      let v = e.target.value;
      Vue.set(this.orders[index], "comments", v);
    },
    //order send
    orderSend(order, index, status) {
      Vue.set(this.orders[index], "loader", true);
      let api = "/ajax/orderSend";
      Vue.axios
        .post(api, {
          status: status,
          order: this.orders[index].id,
          comments: this.orders[index].comments
        })
        .then(response => {
          this.$swal.fire({
            type: "info",
            title: "Info",
            text: "Information saved",
            timer: 3500
          });
          Vue.set(this.orders[index], "loader", false);
          Vue.set(this.orders[index], "show", false);
          Vue.set(this.orders[index], "done", true);
        })
        .catch(error => {
          this.error();
        });
    },
    //copy
    onCopy(e) {
      alert("You just copied: " + e.text);
    },
    // ошибка
    error() {
      this.$swal.fire({
        type: "error",
        title: "Error",
        text: "Try later",
        timer: 3500
      });
    }
  }
};
</script>

<style scoped>
</style>