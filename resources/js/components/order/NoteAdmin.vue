<template>
    <div class="modal fade" id="adminNote" tabindex="-1" role="dialog" aria-labelledby="adminNote"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-if="task_id">Note for task ID: {{task_id}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" v-if="order_id">
                    <div class="mt-5" v-if="notes.length">
                        <h5 class="text-center">Notes</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Note</th>
                                    <th>Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="not in notes">
                                    <td>{{not.comment}}</td>
                                    <td>{{not.created_at}}</td>
                                </tr>
                                </tbody>
                            </table>
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
        name: "NoteAdmin",
        data(){
            return{
                task_id:null,
                order_id: null,
                notes: []
            }
        },
        created() {
            eventBus.$on('noteadmin', data => {
                this.task_id=data.id;
                this.order_id = data.order_id;
                $('#adminNote').modal('show');
                this.notes = [];
                this.get();
            })
        },
        methods:{
            get() {
                axios.get('/order/getNoteOrderAdmin', {params: {order_id: this.order_id}})
                    .then((response) => {
                        this.notes = response.data.notes;
                    })
                    .finally(() => {

                    });
            },
        }
    }
</script>
