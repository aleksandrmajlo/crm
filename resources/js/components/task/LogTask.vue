<template>
  <a href="#" @click.prevent="LogTask">
    <i class="fa fa-info-circle"></i>
  </a>
</template>
<script>
import { eventBus } from "../../app";
export default {
  name: "LogTask",
  //   props: ["task_id", "reload"],
  props: {
    task_id: {},
    reload: {},
    delete: {
      default: false,
    },
  },

  methods: {
    LogTask(task_id) {
      this.$root.$emit("showLogtask", {
        task_id: this.task_id,
        reload: false,
        delete: this.delete,
      });
      $("#LogTask").modal("show");
    },
  },
};
</script>
