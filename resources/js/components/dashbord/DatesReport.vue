<template>
    <div class="date_report_conteer">
        <div class="card">
            <div class="card-header">By dates</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-5">
                        <a class="btn btn-secondary btn-lg" href="/searchdate" target="_blank">All</a>
                    </div>
                </div>
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
        },
        created() {
            store.dispatch("usersGetDashbord");
        },
        methods: {
  
        }
    };
</script>