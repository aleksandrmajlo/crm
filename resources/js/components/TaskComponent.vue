<template>
  <div class="task_conteer">
    <div class="task_block">
      <div class="header_task">
        <span class="id_task">ID</span>
        <span class="info_task">Info</span>
        <span class="weight_task">Weight</span>
        <span class="buutton_block">
          <br />
        </span>
      </div>
      <div class="task_items">
        <div class="task_item" v-for="(task,index) in tasks" :key="index">
          <div class="engaged_conteer" v-if="task.status!==1">Already taken</div>

          <div class="engaged_conteer" v-if="myorder.indexOf(task.id)!==-1">My Order</div>
          <div class="loader_conteer" v-if="task.loader">
            <vue-loaders-ball-pulse color="red" scale="1"></vue-loaders-ball-pulse>
          </div>
          <span data-title="ID" class="id_task">{{task.id}}</span>
          <span
             data-title="Info"
            @click="showInfo(task,index)"
            v-show="typeof task.text=='undefined'"
            v-tooltip="{ content: 'Click and show order' }"
            class="hidden_info"
            >XXXXXXX</span>
          <span data-title="Info"  v-show="task.text" class="show_info">{{task.text}}</span>
          <span data-title="Weight" class="weight_task">{{task.weight}}</span>
          <span class="buutton_block">
            <button
              :disabled="typeof task.text=='undefined'"
              class="btn btn-link"
              @click="goOrder(task,index,$event)"
            >go</button>
          </span>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "TaskComponent",
  data: function() {
    return {
      myorder: [],
      tasks: []
    };
  },
  mounted() {
    this.getTask();
  },
  methods: {
    // получить все свободные задания
    getTask() {
      let api = "/ajax/tasks";
      Vue.axios
        .post(api, { type: "get" })
        .then(response => {
          this.tasks = response.data.tasks;
          this.myorder = response.data.myorder;
          this.first = false;
        })
        .catch(error => {
          this.error();
        });
    },
    // добавить заказ ******************************************
    goOrder(task, index, event) {
      if (event) event.preventDefault();
      // Vue.set(this.tasks[index], 'loader', true);
      let api = "/ajax/order";
      Vue.axios
        .post(api, { task_id: this.tasks[index].id })
        .then(response => {
          if (typeof response.data.notorder !== "undefined") {
            this.$swal.fire({
              type: "error",
              title: "Error",
              text: response.data.notorder,
              timer: 3500
            });
          } else if (typeof response.data.success != "undefined") {
            let ids = response.data.ids;
            for (let index = 0; index < ids.length; index++) {
                this.myorder.push(ids[index]);
            }
            this.$swal.fire({
              type: "success",
              title: "Success",
              text: response.data.success,
              timer: 3500
            });
          }
        })
        .catch(error => {
          this.error();
        });
    },
    // показать скрытую инфу **********************************
    showInfo(task, index) {
      Vue.set(this.tasks[index], "loader", true);
      let api = "/ajax/task";
      Vue.axios
        .post(api, { id: this.tasks[index].id })
        .then(response => {
          if (typeof response.data.engaged !== "undefined") {
            this.tasks[index].status = 0;
          } else if (typeof response.data.task !== "undefined") {
            let str = response.data.task;
            Vue.set(this.tasks[index], "text", str);
          }
          Vue.set(this.tasks[index], "loader", false);
        })
        .catch(error => {
          this.error();
        });
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

