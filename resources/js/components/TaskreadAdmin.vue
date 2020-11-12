<template>
    <div class="TaskreadComponent_conteer wrapLoader">
        <div class="loader_conteer" v-if="loader">
            <vue-loaders-ball-pulse color="red" scale="1"></vue-loaders-ball-pulse>
        </div>
        <h2 class="text-center mb-4">Read Tasks</h2>
        <div class="card mb-5" v-for="(read_task,index) in read_tasks" :key="index">
            <div v-if="read_task[0]" class="card-header text-center">
                <a
                    class="btn btn-outline-info"
                    data-toggle="collapse"
                    @click="showClick(index)"
                    :href="'#collapse_read_'+index"
                    role="button"
                    aria-expanded="false"
                    aria-controls="collapseExample"
                >{{read_task[0].timestamp | formatDate}}</a>
            </div>
            <div class="collapse" :id="'collapse_read_'+index">
                <div class="card-body">
                    <div class="double-scroll-read">
                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th class="text-left" colspan="5">
                                    <a href="#" class="btn btn-primary" @click.prevent="saveRead(index)">Submit Selected</a>
                                </th>
                                <th class="text-right" colspan="6">
                                    <a href="#" class="btn btn-primary" @click.prevent="saveSelect(index)">
                                        Save select
                                    </a>
                                </th>
                            </tr>
                            <tr>
                                <th>№</th>
                                <th>ID</th>
                                <th></th>
                                <th>IP PORT</th>
                                <th>DONAIN\LOGIN</th>
                                <th>PASSWORD</th>
                                <th>COST</th>
                                <th>
                                    <div class="form-group form-check">
                                        <input
                                            type="checkbox"
                                            class="form-check-input"
                                            @change="checkAll($event,index)"
                                        />
                                    </div>
                                </th>
                                <th>STATUS</th>
                                <th>
                                    <input :id="'all_'+index" class="form-control" type="text"/>
                                </th>
                                <th style="max-width: 30px;"></th>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody :id="'tbody_'+index">
                            <tr
                                v-for="(task,ind) in read_task"
                                :key="ind"
                                :style="{'background-color':task.color!=='' ? task.color : '' }">
                                <td>{{ind+1}}</td>
                                <td>{{task.id}}</td>
                                <td>
                                    <div v-if="task.flag">
                                        <img :src="task.flag"/>
                                    </div>
                                </td>
                                <td>
                                    {{task.ip}}:{{task.port}}
                                    <i
                                        v-clipboard:copy="task.ip+':'+task.port"
                                        class="fa fa-copy"
                                    ></i>
                                </td>
                                <td>
                                    <span v-if="task.domain===''" class="text-danger">Not Domain\</span>
                                    <span v-else>{{task.domain}}\</span>
                                    {{task.login}}
                                </td>
                                <td>{{task.password}}</td>
                                <td>
                                    <input
                                        class="form-control"
                                        :ref="'weight'+index+ind"
                                        :value="task.weight"
                                        type="text"
                                    />
                                </td>
                                <td>
                                    <div class="form-group form-check">
                                        <input type="checkbox" :data-id="task.id" class="form-check-input"/>
                                    </div>
                                </td>
                                <td class="text-center">{{status[task.status]}}</td>
                                <td>
                                    <a href="#"
                                       @click.prevent="saveLink(task.id,index,ind)"
                                       class="btn btn-link">save</a>
                                </td>
                                <td style="max-width: 30px;">
                                    <a href="#" @click.prevent="LogTask(task.id)" class>
                                        <i class="fa fa-info-circle"></i>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <span class="d-block"
                                          v-if="task.username">{{task.username}} {{task.useremail}}</span>
                                    <a href="#" @click.prevent="ChangeTask(task.id)" class="btn btn-info">Change</a>
                                </td>
                                <td>
                                    <a href="#" class="text-nowrap" @click.prevent="AddSchowComment(task.id)">
                                        <i class="fa fa-comment"></i>
                                        {{task.countComments}}
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mb-2" v-if="LoadBlock">
            <button :disabled="disabled" class="btn btn-info" @click.prevent="LoadMore">Load more {{limit}}
            </button>
        </div>

    </div>
</template>
<script>
    import {eventBus} from "../app";
    import {mapState} from "vuex";
    import store from "../store/";
    export default {
        name: "TaskreadAdmin",
        data() {
            return {
                first: true,
                read_tasks: [],
                status: [],
                loader: false,
                LoadBlock: true,
                disabled: false,
                offset: 0,
                limit: 200,
            };
        },
        mounted() {
        },
        computed: {
            /*
           read_tasks() {
               return store.state.task.read_tasks;
           },
           status() {
               return store.state.task.status;
           }
            */
        },
        created() {
            // store.dispatch("getTasks");
            this.getData();
        },
        methods: {
            //установить все чекбоксы
            checkAll(event, index) {
                let this_el = event.target,
                    bol = $(this_el).prop("checked");
                $("#tbody_" + index)
                    .find('[type="checkbox"]')
                    .prop("checked", bol);
            },
            //сохранить выбранные
            saveSelect(index) {
                let res = [];
                $("#tbody_" + index)
                    .find('[type="checkbox"]')
                    .each(function (el) {
                        if ($(this).prop("checked")) {
                            res.push($(this).data("id"));
                        }
                    });

                // store.commit("saveReadWeightSelected", {
                //     index: index,
                //     val: $("#all_" + index).val(),
                //     indexs: res
                // });
                let val = $("#all_" + index).val();
                this.read_tasks[index].forEach((el, ind) => {
                    if (res.indexOf(el.id) !== -1) {
                        Vue.set(this.read_tasks[index][ind], 'weight', val)
                    }
                });

            },
            saveLink(id, index, ind) {
                let inp = this.$refs["weight" + index + ind],
                    val = $(inp).val();
                // store.dispatch("SaveOneWeigth", {id: id, index: index, val: val});
                this.loader = true;
                axios.post('ajax/SaveOneWeigth', {
                    id: id,
                    val: val
                })
                    .then(response => {
                    })
                    .finally(() => {
                        this.loader = false;
                    })

            },
            // сохранить все изменения
            saveRead(index) {
                // store.dispatch("saveRead", index);

                this.loader = true;
                let ids = [];
                this.read_tasks[index].forEach((el, index) => {
                    ids.push({
                        id: el.id,
                        weight: el.weight
                    })
                });

                axios.post('ajax/save', {
                    ids: ids
                })
                    .then(response => {
                    })
                    .finally(() => {
                        this.loader = false;
                    })

            },
            // прокрутку добавить
            showClick(index) {
                let $el = $("#collapse_read_" + index),
                    first = $el.data("first");
                if (typeof first == "undefined") {
                    setTimeout(() => {
                        $el.find("table").DataTable({
                            pageLength: 100,
                            columnDefs: [
                                {
                                    targets: [1, 5, 6, 8, 9],
                                    orderable: false,
                                    searchable: false
                                }
                            ]
                        });
                    }, 500);
                    $el.data("first", true);
                }
            },
            // установить пользователя
            ChangeTask(task_id) {
                this.$root.$emit("showreadtask", {task_id: task_id, reload: false});
                $("#SelectUser").modal("show");
            },
            //посмотреть лог по ид
            LogTask(task_id) {
                this.$root.$emit("showLogtask", {task_id: task_id, reload: false});
                $("#LogTask").modal("show");
            },
            // добавить или отредактировать комментарий
            AddSchowComment(task_id) {
                eventBus.$emit("AddSchowComment", {
                    task_id: task_id,
                    reload: false
                })
            },
            getData() {
                this.loader = true;
                axios
                    .get('ajax/get_tasks', {params: {offset: this.offset}})
                    .then(response => {
                        if (response.data.success) {
                            this.read_tasks = Object.assign(this.read_tasks, response.data.read_tasks);
                            console.group('tasks')
                            console.log(Object.keys(this.read_tasks))
                            console.groupEnd();
                            this.read_tasks = Object.keys(this.read_tasks).sort((a, b) => b - a).map(key => this.read_tasks[key]);
                            // this.read_tasks = Object.entries(this.read_tasks).sort((a, b) => b - a);
                            console.group('tasks sort')
                            console.log(Object.keys(this.read_tasks))
                            console.groupEnd();
                            if (this.offset === 0) {
                                this.status = response.data.status;
                            }
                            this.offset = this.offset + this.limit;
                        } else {
                            this.LoadBlock = false;
                        }
                    })
                    .finally(() => {
                        this.loader = false;
                    })
            },
            LoadMore() {
                this.getData();
            },
        }
    };
</script>
