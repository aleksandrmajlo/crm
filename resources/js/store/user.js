import Vue from "vue";
export default {
    state: {
        user:{},
        status:[],
        tasks: [],
    },
    mutations: {

        setUser(state, data){
            state.user=data.user;
            state.status=data.status;
        },
        setUsersTasks(state, data){
            let tasks=data.tasks;
            state.tasks = Object.keys(tasks).sort((a,b)=>b-a).map(key=>tasks[key]);
        },
    },
    getters: {
    },
    actions: {

       getUser({
                   commit,
                   state
               }){
           return axios.get('ajaxuser/user' )
               .then(response => {
                   commit('setUser',response.data)
               });
       },
        getUserTasks({
                   commit,
                   state
               }){
           return axios.get('ajaxuser/tasks' )
               .then(response => {
                   commit('setUsersTasks',response.data)
               });
       },

    }
}