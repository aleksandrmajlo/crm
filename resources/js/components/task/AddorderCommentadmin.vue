<template>
    <div class="modal fade bd-example-modal-lg " id="AddSchowComment" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cooments</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form" v-if="form=='1'">
                        <form method="post"  @submit.prevent="Send"   id="orderaddCommentAdminForm">
                            <div class="form-group">
                                <wysiwyg v-model="text" />
                            </div>
                            <div class="custom-control custom-radio">
                                <input  v-model="showcommentadmin" type="radio" id="customRadio2" name="showcommentadmin" required value="1"
                                        class="custom-control-input">
                                <label class="custom-control-label" for="customRadio2">Show user comment</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input v-model="showcommentadmin" type="radio" id="customRadio1" name="showcommentadmin" required value="0"
                                       class="custom-control-input">
                                <label class="custom-control-label" for="customRadio1">Hidden user comment</label>
                            </div>

                            <button type="submit" @disabled="disabled" class="btn btn-primary">Send</button>
                        </form>
                        <div class="row" v-if="comments.length">
                            <div class="col-md-12 mt-5">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th style="width:70%">Text</th>
                                            <th>Date</th>
                                            <th>Admin</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(comment,ind) in comments" :key="ind">
                                            <td style="width:70%"><span v-html="comment.text"></span></td>
                                            <td>{{comment.date}}</td>
                                            <td >{{comment.admin}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import {eventBus} from "../../app";
    export default {
        name: "AddorderCommentadmin",
        props:['form'],
        components:{
        },
        data() {
            return {
                disabled:false,
                task_id: "",
                reload:false,
                text:'',
                showcommentadmin:1,
                comments:[]
            };
        },
        created(){
            eventBus.$on("AddSchowComment", ob => {
                this.task_id = ob.task_id;
                this.setData();
                $("#AddSchowComment").modal("show");
            })
        },
        methods:{
            //получаем комментарии
            setData(){
                axios.post('/comment/get',{task_id:this.task_id,form:this.form})
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
                axios.post('/comment/add',
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
