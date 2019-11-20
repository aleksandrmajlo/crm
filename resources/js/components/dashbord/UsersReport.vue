<template>
  <div class="date_report_conteer">
    <div class="loader_conteer" v-if="loader">
      <vue-loaders-ball-pulse color="red" scale="1"></vue-loaders-ball-pulse>
    </div>
    <div class="card">
      <div class="card-header">By user</div>
      <div class="card-body">
        <div class="form-group">
          <select class="form-control" v-model="user">
            <option value="-1">Select</option>
            <option
              v-for="(user,index) in usersDashbords"
              :key="index"
              :value="user.id"
            >{{user.name}}</option>
          </select>
        </div>
        <div class="mb-5">
          <button @click.prevent="getUser" class="btn btn-primary">Get</button>
        </div>
        <user-report></user-report>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import store from "../../store/";
import UserReport from './UserReport'
export default {
  name: "UsersReport",
  components: {
    UserReport
  },
  data() {
    return {
      user: -1
    };
  },
  computed: {
    loader() {
      return store.state.dashboard.loaderUser;
    },
    usersDashbords() {
      return store.state.dashboard.usersDashbords;
    }
  },
  created() {
    store.dispatch("usersGetDashbord");
  },
  methods: {
      getUser() {
      if (this.user == -1) {
        alert("Select user");
        return false;
      }
      store.dispatch("userGetDashbord", this.user);
    }
  }
};
</script>

<style scoped>
</style>