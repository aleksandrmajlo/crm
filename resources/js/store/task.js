import Vue from "vue";
export default {
    state: {
        uploadtask: [],

        tasks: [],
        read_tasks: [],

        status:[],

        saved: false,
        saved_text: ""
    },
    getters: {
        // getCustomerById: state => id => {
        // },
    },
    actions: {
        //загрузка значений
        uploadSave({
            commit,
            state
        }) {
            return axios.post('ajax/uploadsave', {
                    uploadtask: state.uploadtask
                })
                .then(response => {
                    if (response.data.success) {
                        commit('setUploadtask', []);
                        commit('savedShow', response.data.success);
                    }
                });
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

        // сохранить изменения для редактирования
        saveRead({
            commit,
            state
        }, index) {
            let ids = [];
            state.read_tasks[index].forEach((el, index) => {
                ids.push({id:el.id,weight:el.weight})
            });

            return axios.post('ajax/saveread', {
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
            let read_tasks= data.read_tasks
            let tasks= data.tasks;

            state.status = data.status;
            state.tasks = Object.keys(tasks).sort((a,b)=>b-a).map(key=>tasks[key]);
            state.read_tasks =Object.keys(read_tasks).sort((a,b)=>b-a).map(key=>read_tasks[key]) ;
        },
        // при загрузке .txt устанавливаем значени
        setUploadtask(state, uploadtask) {
            state.uploadtask = uploadtask;
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

        //показать сообщение обуспехе
        savedShow(state, text) {
            state.saved = true;
            if (text !== '') {
                state.saved_text = text;
            }
            setTimeout(() => {
                state.saved = false
                state.saved_text = "";
            }, 3000);
        }
    },
};
