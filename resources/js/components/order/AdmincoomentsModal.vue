<template>
    <div class="modal fade" id="adminCooments" tabindex="-1" role="dialog" aria-labelledby="adminCoomentsLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cooments admin ID: {{order.task_id}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-dark">
                            <th class="text-center">Date</th>
                            <th class="text-center" style="min-width:80%;width: 80%;">Comment</th>
                            </thead>
                            <tbody>
                            <tr v-for="(comment,ind) in comments" :key="ind"
                                :class="[comment.viewed==1 ? 'show' : 'notSwow']">
                                <td>{{comment.created_at}}</td>
                                <td style="min-width:80%;width: 80%;">
                                    <div>
                                        <a class="btn btn-link" @click="setvViewed(comment.id)"
                                           :href="'#comment'+comment.id"
                                           data-toggle="collapse" role="button" aria-expanded="false">
                                            Show
                                        </a>
                                        <div class="collapse" :id="'comment'+comment.id">
                                            <span v-html="comment.commentadmin"></span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import {eventBus} from "../../app";
    export default {
        name: "AdmincoomentsModal",
        data() {
            return {
                order: {},
                comments: {}
            };
        },
        mounted() {
            eventBus.$on("ModalComment", ob => {
                this.order = ob.order;
                this.comments = ob.order.admincomments;
            });
        },
        methods: {
            // установить статус просмотрено
            setvViewed(comment_id) {
                axios.post('/ajaxuser/CommentViewed', {
                    comment_id: comment_id,
                })
                    .then(response => {

                    })
                    .catch(error => {
                        this.showShwal('error', 'Error.Try later')
                    });

            }
        }
    }
</script>
<style scoped>
    .notSwow {
        background-color: red;
    }
</style>
