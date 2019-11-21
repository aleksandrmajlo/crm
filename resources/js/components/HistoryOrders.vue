<template>
  <div class="card mb-5">
    <div class="card-header text-center">History</div>
    <div class="card-body">
      <table class="table table-sm" id="table_history">
        <thead>
          <tr>
            <th>ID</th>
            <th></th>
            <th>IP PORT</th>
            <th>DONAIN\LOGIN</th>
            <th>PASSWORD</th>
            <th>COST</th>
            <th>Created</th>
            <th>Updated</th>
            <th>Status</th>
            <th></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <template v-for="(order,ind) in history_orders">
            <tr>
              <td>{{order.task_id}}</td>
              <td>
                <span v-if="order.flag">
                  <img :src="order.flag" />
                </span>
              </td>
              <td>{{order.ip}}:{{order.port}}</td>
              <td>
                <span v-if="order.domain===''" class="text-danger">Not Domain\</span>
                <span v-else>{{order.domain}}\</span>
                {{order.login}}
                <i
                  v-clipboard:copy="order.domain+'\\'+order.login"
                  class="fa fa-copy"
                ></i>
              </td>
              <td>{{order.password}}</td>
              <td>{{order.weight}}</td>
              <td>{{order.created_at | formatDate}}</td>
              <td>{{order.updated_at | formatDate}}</td>
              <td>{{status[order.status]}}</td>
              <td>
                <div v-if="order.status!==5">
                  <a class="btn btn-outline" target="_blank" :href="'/editOrder?order='+order.id">EDIT</a>
                </div>
              </td>
              <td>
                <div v-if="order.showcommentadmin=='1'&&order.commentadmin">
                  <a
                    tabindex="0"
                    @click="show"
                    class="btn btn-danger popover-comment"
                    role="button"
                    data-toggle="popover"
                    title="Comment Admin"
                    :data-content="order.commentadmin"
                  >Comment Admin</a>
                </div>
              </td>
            </tr>
            <template v-if="order.sub_orders">
              <tr v-for="(sub_order,index) in order.sub_orders">
                <td>{{sub_order.task_id}}</td>
                <td></td>
                <td>{{sub_order.ip}}:{{sub_order.port}}</td>
                <td>
                  <span v-if="sub_order.domain===''" class="text-danger">Not Domain\</span>
                  <span v-else>{{sub_order.domain}}\</span>
                  {{sub_order.login}}
                  <i
                    v-clipboard:copy="sub_order.domain+'\\'+sub_order.login"
                    class="fa fa-copy"
                  ></i>
                </td>
                <td>{{sub_order.password}}</td>
                <td>{{sub_order.weight}}</td>
                <td>{{sub_order.created_at }}</td>
                <td>{{sub_order.updated_at }}</td>
              </tr>
            </template>
          </template>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  name: "HistoryOrders",
  props: ["history_orders", "status"],
  data: function() {
    return {
      first: false
    };
  },
  mounted() {
    setTimeout(() => {
      $("#table_history").DataTable({
        columnDefs: [
          {
            targets: [1, 9],
            orderable: false,
            searchable: false
          }
        ]
      });
    }, 500);
  },
  methods: {
    show(event) {
      let el = event.target;
      let $el = $(event.target);
      let first = $el.data("first");
      if (typeof first == "undefined") {
        $el.popover({});
        $el.data("first", true);
        el.click();
      }
    }
  }
};
</script>

<style scoped>
</style>