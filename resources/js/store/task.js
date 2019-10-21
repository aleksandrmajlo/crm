import Vue from "vue";
export default {
    state: {
        uploadtask: [],

        tasks: [],
        read_tasks: [],

        status: [],

        saved: false,
        saved_text: "",
        saved_duplicate_error: "", //текст который выводится если при добавлении заданий дубли
        Save_and_publish_Button_Disabled: false
    },
    getters: {

    },
    actions: {
        //загрузка значений
        Publish({
            commit,
            state
        }) {
            commit('Set_Save_and_publish_Button_Disabled');
            return axios.post('ajax/publish', {
                    uploadtask: state.uploadtask
                })
                .then(response => {
                    commit('showPublicMess', response.data.saved_duplicate_error);
                    commit('Set_Save_and_publish_Button_Disabled');
                })
                .catch(error => {
                   commit('Set_Save_and_publish_Button_Disabled');
                   alert(error.message)
                })
        },
        //загрузить значения для просмотра и редактирования
        getTasks({
            commit
        }) {
            return axios.get('ajax/get_tasks')
                .then(response => {
                    if (response.data.success) {
                        commit('setTasks', {
                            status: response.data.status,
                            tasks: response.data.tasks,
                            read_tasks: response.data.read_tasks
                        });
                    }
                });
        },

        // сохранить загруженные задания
        saveRead({
            commit,
            state
        }, index) {
            let ids = [];
            state.read_tasks[index].forEach((el, index) => {
                ids.push({
                    id: el.id,
                    weight: el.weight
                })
            });

            return axios.post('ajax/save', {
                    ids: ids
                })
                .then(response => {
                    if (response.data.success) {
                        commit('savedShow', response.data.success);
                    }
                });

        },
    },
    mutations: {
        //установка значения для заданий в листе
        setTasks(state, data) {
            let read_tasks = data.read_tasks
            let tasks = data.tasks;
            state.status = data.status;
            state.tasks = Object.keys(tasks).sort((a, b) => b - a).map(key => tasks[key]);
            state.read_tasks = Object.keys(read_tasks).sort((a, b) => b - a).map(key => read_tasks[key]);
        },

        // при загрузке .txt устанавливаем значения в основной таблице
        setUploadtask(state, data) {
            state.uploadtask = data.tasks;

        },

        // при клике на ссылку сохранить для .txt
        setUploadtaskWeight(state, data) {
            Vue.set(state.uploadtask[data.index], 'weight', data.val)
            this.commit('savedShow', "");
        },
        //массоввое сохранение
        setUploadtaskWeightSelected(state, data) {
            data.indexs.forEach(element => {
                Vue.set(state.uploadtask[element], 'weight', data.weight)
            });
            this.commit('savedShow', "");
        },

        // при клике на ссылку сохранить для доступных для редактирования
        saveWeightRead(state, data) {
            state.read_tasks[data.index].forEach((el, index) => {
                if (el.id == data.id) {
                    Vue.set(state.read_tasks[data.index][index], 'weight', data.val)
                }
            });
            this.commit('savedShow', "");
        },
        // сохранение выбранных значений для редактирования
        saveReadWeightSelected(state, data) {
            state.read_tasks[data.index].forEach((el, index) => {
                if (data.indexs.indexOf(el.id) !== -1) {
                    Vue.set(state.read_tasks[data.index][index], 'weight', data.val)
                }
            });
            this.commit('savedShow', "");
        },

        // cообщение после публикации данных из загрузки
        showPublicMess(state, text) {
            state.saved_duplicate_error = text;
        },
        // кнопка публикации
        Set_Save_and_publish_Button_Disabled(state) {
            state.Save_and_publish_Button_Disabled = !state.Save_and_publish_Button_Disabled;
        },

        //показать сообщение об успехе
        savedShow(state, text) {
            state.saved = true;
            if (text !== '') {
                state.saved_text = text;
            }

            setTimeout(() => {
                state.saved = false;
                state.saved_text = "";
            }, 3000);

        }
    },
};
