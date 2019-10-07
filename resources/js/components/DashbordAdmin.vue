<template>
  <div class="row">
    <div class="col col-md-6">
      <dates-report></dates-report>
    </div>
    <div class="col col-md-6">
      <users-report></users-report>
    </div>
  </div>
</template>

<script>
import DatesReport from "./dashbord/DatesReport";
import UsersReport from "./dashbord/UsersReport";
export default {
  name: "DashbordAdmin",
  components: {
    DatesReport,
    UsersReport
  }
};
</script>

<style scoped>
</style>