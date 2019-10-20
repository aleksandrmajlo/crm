<template>
    <div class="date_report_conteer">
        <!--
        <div class="loader_conteer" v-if="loader">
          <vue-loaders-ball-pulse color="red" scale="1"></vue-loaders-ball-pulse>
        </div>
        -->
        <div class="card">
            <div class="card-header">By dates</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-header">Date Start</div>
                        <FunctionalCalendar v-model="calendarData" :configs=" calendarConfigs "></FunctionalCalendar>
                    </div>
                    <div class="col-md-6">
                        <div class="card-header">Date End</div>
                        <FunctionalCalendar v-model="calendarDataEnd" :configs=" calendarConfigs "></FunctionalCalendar>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mt-5">
                        <form method="get" action="/searchdate">
                            <div class="form-group">
                                <input class="form-control" required type="text" name="start" :value="DataStart"/>
                            </div>
                            <div class="form-group">
                                <input class="form-control" required type="text" name="end" :value="DataEnd"/>
                            </div>
                            <div class="form-group">
                                <label>User</label>
                                <select class="form-control" name="user">
                                    <option value="-1">Select</option>
                                    <option
                                            v-for="(user,index) in usersDashbords"
                                            :key="index"
                                            :value="user.id"
                                     >{{user.name}}</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Get</button>
                        </form>
                    </div>
                </div>
                <!--
                      <div class="mt-3 mb-3">
                          <button @click="getReportDay" class="btn btn-primary">Get</button>
                      </div>
                      <div class="date_dashboard_conteer mb-5">
                          <h3>In work</h3>
                          <table class="table table-bordered" id="In_Work">
                              <thead>
                              <tr>
                                  <th>ID</th>
                                  <th>user</th>
                                  <th>task</th>
                                  <th>weight</th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr v-for="(datedashbord,index) in dateWorkdashbords" :key="index"
                                  :style="{'background-color':datedashbord.color!=='' ? datedashbord.color : '' }"
                              >
                                  <td>{{datedashbord.id}}</td>
                                  <td>{{datedashbord.user}}</td>
                                  <td>{{datedashbord.task}}</td>
                                  <td>{{datedashbord.weight}}</td>
                              </tr>
                              </tbody>
                          </table>
                      </div>
                      <div class="date_dashboard_conteer mb-5">
                          <h3>Done</h3>
                          <table class="table table-bordered" id="Done">
                              <thead>
                              <tr>
                                  <th>ID</th>
                                  <th>user</th>
                                  <th>task</th>
                                  <th>weight</th>
                                  <th>serials</th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr v-for="(datedashbord,index) in dateDonedashbords" :key="index"
                                  :style="{'background-color':datedashbord.color!=='' ? datedashbord.color : '' }">
                                  <td>{{datedashbord.id}}</td>
                                  <td>{{datedashbord.user}}</td>
                                  <td>{{datedashbord.task}}</td>
                                  <td>{{datedashbord.weight}}</td>
                                  <td>
                                      <div v-if="datedashbord.serials">
                                        <span class="d-block" v-for="(serial,ind) in datedashbord.serials" :key="ind">
                                            <a :href="serial" target="_blank">show</a>
                                        </span>
                                      </div>
                                  </td>
                              </tr>
                              </tbody>
                          </table>
                      </div>
                      <div class="date_dashboard_conteer mb-5">
                          <h3>Failed</h3>
                          <table class="table table-bordered" id="Failed">
                              <thead>
                              <tr>
                                  <th>ID</th>
                                  <th>user</th>
                                  <th>task</th>
                                  <th>weight</th>
                                  <th>failed</th>
                                  <th>comment</th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr v-for="(datedashbord,index) in dateFaileddashbords" :key="index"
                                  :style="{'background-color':datedashbord.color!=='' ? datedashbord.color : '' }">
                                  <td>{{datedashbord.id}}</td>
                                  <td>{{datedashbord.user}}</td>
                                  <td>{{datedashbord.task}}</td>
                                  <td>{{datedashbord.weight}}</td>
                                  <td>{{datedashbord.failed}}</td>
                                  <td>{{datedashbord.comment}}</td>
                              </tr>
                              </tbody>
                          </table>
                      </div>
                -->
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState} from "vuex";
    import store from "../../store/";
    import {FunctionalCalendar} from "vue-functional-calendar";

    export default {
        name: "DatesReport",
        components: {
            FunctionalCalendar
        },
        data() {
            return {
                calendarData: {},
                calendarDataEnd: {},
                calendarConfigs: {
                    dateFormat: "yyyy-mm-dd",
                    isDatePicker: true,
                    isDateRange: false
                }
            };
        },
        mounted() {
            $('[name="start"]').keydown(function (e) {
                e.preventDefault();
            });
            $('[name="end"]').keydown(function (e) {
                e.preventDefault();
            });
        },
        computed: {
            DataStart() {
                return this.calendarData.selectedDate;
            },
            DataEnd() {
                return this.calendarDataEnd.selectedDate;
            },

            usersDashbords() {
                return store.state.dashboard.usersDashbords;
            }
            /*
            loader() {
              return store.state.dashboard.dateLoader;
            },
            dateWorkdashbords() {
              return store.state.dashboard.dateWorkdashbords;
            },

            dateDonedashbords() {
              return store.state.dashboard.dateDonedashbords;
            },
            dateFaileddashbords() {
              return store.state.dashboard.dateFaileddashbords;
            }
            */
        },
        created() {
            // let date = new Date();
            // let month = date.getMonth() + 1;
            // if (month < 10) month = "0" + month;
            // let d = date.getFullYear() + "-" + month + "-" + date.getDate();
            // store.dispatch("dateGetDashbord", d);
            store.dispatch("usersGetDashbord");
        },
        methods: {
            /*
            getReportDay() {
              store.dispatch("dateGetDashbord", this.calendarData.selectedDate);
            }
            */
        }
    };
</script>

<style scoped>
</style>