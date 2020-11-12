<template>
    <div class="row mt-5 mb-5">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header">Admin Comments</div>
                <div class="card-body wrapLoader">
                    <div class="loader_conteer" v-if="loader">
                        <vue-loaders-ball-pulse color="red" scale="1"></vue-loaders-ball-pulse>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="adminComments">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Admin</th>
                                <th>Comment</th>
                                <th>Date</th>
                                <th>Viewed user</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(comment,index) in comments" :key="index">
                                <td>
                                    <a :href="'/orderLogID?id='+comment.task_id" target="_blank">
                                        {{comment.task_id}}
                                    </a>
                                </td>
                                <td>
                                    {{userEmail(comment.user_id)}}
                                </td>
                                <td style="width:70%;"><span v-html="comment.commentadmin"></span></td>
                                <td class="nowrap">{{comment.created_at}}</td>
                                <td>
                                    <span v-if="comment.viewed==1">Yes</span>
                                    <span v-else>Not</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mb-2" v-if="LoadBlock">
                        <button  class="btn btn-info" @click.prevent="LoadMore">Load more {{limit}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "DashbordCommentsadmin",
        data(){
            return{
                comments:[],
                users:[],
                offset: 0,
                limit: 20,
                LoadBlock: true,
                loader: true
            }
        },
        created() {
            this.getData();
        },
        methods: {
            getData() {
                this.loader = true;
                axios
                    .get("/", {params: {offset: this.offset}})
                    .then(response => {
                        if (response.data.success) {
                            if(this.offset===0){
                                this.users=response.data.users;
                            }
                            if (response.data.comments.length > 0) {
                                this.comments = this.comments.concat(response.data.comments);
                                this.offset = this.offset + this.limit;
                            } else {
                                this.LoadBlock = false;
                            }

                        }
                    })
                    .catch(error => console.log(error))
                    .finally(() => {
                        this.loader = false;
                    })
            },
            userEmail(user_id){
                 let result=this.users.find(ob=>{return ob.id==user_id});
                 if(result) return result.email;
            },
            LoadMore() {
                this.getData();
            },
        }
    }
</script>

