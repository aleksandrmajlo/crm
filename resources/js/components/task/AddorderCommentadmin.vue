<template>
    <div class="form">
        <form method="post"
              @submit.prevent="Send"
              id="orderaddCommentAdminForm">
            <div class="form-group">
                <textarea name="commentadmin" v-model="text" required class="form-control"></textarea>
            </div>
            <div class="custom-control custom-radio">
                <input v-model="showcommentadmin" type="radio" id="customRadio1" name="showcommentadmin" required value="0"
                       class="custom-control-input">
                <label class="custom-control-label" for="customRadio1">Hidden user comment</label>
            </div>
            <div class="custom-control custom-radio">
                <input  v-model="showcommentadmin" type="radio" id="customRadio2" name="showcommentadmin" required value="1"
                       class="custom-control-input">
                <label class="custom-control-label" for="customRadio2">Show user comment</label>
            </div>
            <button type="submit" @disabled="disabled" class="btn btn-primary">Send</button>
        </form>
        <div class="row">
            <div class="col-md-12 mt-5">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Text</th>
                            <th>Date</th>
                            <th>Admin</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(comment,ind) in comments" :key="ind">
                            <td>{{comment.text}}</td>
                            <td>{{comment.date}}</td>
                            <td>{{comment.admin}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: "AddorderCommentadmin",
        data() {
            return {
                disabled:false,
                task_id: "",
                reload:false,
                text:'',
                showcommentadmin:0,
                comments:[]
            };
        },
        mounted() {
            this.$root.$on("AddSchowComment", ob => {
                this.task_id = ob.task_id;
                this.reload = ob.reload;
                this.setData();
            });
        },
        methods:{
            //получаем комментарии
            setData(){
                axios.post('/Get_taskcomment',{task_id:this.task_id})
                    .then(response => {
                        this.comments=response.data.results;
                    })
            },
            Send(){
                let formData={
                    task_id:this.task_id,
                    text:this.text,
                    showcommentadmin:this.showcommentadmin
                 };
                this.disabled =true;
                axios.post('/taskcomment',
                    formData
                    )
                    .then(response => {
                        if (response.data.success) {
                            this.text="";
                        }
                    })
                    .finally(()=>{
                        this.setData();
                        this.disabled =false;
                    })
            }
        }
    }
</script>
