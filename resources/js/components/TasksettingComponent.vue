<template>
    <div class="TasksettingComponent_conteer">
        <div class="mb-2">
            <span v-html="saved_duplicate_error"></span>
        </div>
        <div class="card" v-if="uploadtasks.length>0">
            <!--<div class="card" >-->
            <div class="card-header">Uploaded</div>
            <div class="card-body">
                <!--<div class="table-responsive">-->
                <div class="double-scroll">
                    <table class="table table-sm">
                        <thead>
                        <tr>
                            <th class="text-right" colspan="7">
                                <a href="#" class="btn btn-primary" @click.prevent="saveSelect">Save select</a>
                            </th>
                        </tr>
                        <tr>
                            <th>IP</th>
                            <th>PORT</th>
                            <th>DONAIN\LOGIN</th>
                            <th>PASSWORD</th>
                            <th>COST</th>
                            <th>
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" @change="checkAll($event)"/>
                                </div>
                            </th>
                            <th>
                                <input class="form-control" v-model="allValue" type="text"/>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(uploadtask,index) in uploadtasks" :key="index">
                            <td>{{uploadtask.ip}}</td>
                            <td>{{uploadtask.port}}</td>
                            <td>{{uploadtask.domain}}
                                <span v-if="uploadtask.domain===''" class="text-danger">Not Domain\</span><span v-else>{{uploadtask.domain}}\</span>{{uploadtask.login}}
                            </td>
                            <td>{{uploadtask.password}}</td>
                            <td>
                                <input
                                        class="form-control"
                                        :ref="'weight'+index"
                                        :value="uploadtask.weight"
                                        type="text"
                                />
                            </td>
                            <td>
                                <div class="form-group form-check">
                                    <input
                                            type="checkbox"
                                            :ref="'checkbox'+index"
                                            class="form-check-input"
                                    />
                                </div>
                            </td>
                            <td>
                                <a href="#" @click.prevent="saveLink(index)" class="btn btn-link">save</a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import {mapState} from "vuex";
    import store from "../store/";

    export default {
        name: "TasksettingComponent",
        data: function () {
            return {
                allValue: "",
                error: false
            };
        },
        created() {
        },
        computed: {
            uploadtasks() {
                return store.state.task.uploadtask;
            },
            saved_duplicate_error() {
                return store.state.task.saved_duplicate_error;
            },
        },
        watch: {
        },
        methods: {
            //установить все чекбоксы
            checkAll(event) {
                let this_el = event.target,
                    bol = $(this_el).prop("checked");
                this.uploadtasks.forEach((element, index) => {
                    let check = this.$refs["checkbox" + index];
                    $(check).prop("checked", bol);
                });
            },
            // клик на одиночной ссылке
            saveLink(index) {
                let inp = this.$refs["weight" + index],
                    val = $(inp).val();
                store.commit("setUploadtaskWeight", {index: index, val: val});
            },
            //сохранить выбранные
            saveSelect() {
                let res = [];
                this.uploadtasks.forEach((element, index) => {
                    let check = this.$refs["checkbox" + index];
                    let bol = $(check).prop("checked");
                    if (bol) {
                        res.push(index)
                    }
                });
                store.commit("setUploadtaskWeightSelected", {weight: this.allValue, indexs: res});
            }

        }
    };
</script>

<style scoped>
</style>