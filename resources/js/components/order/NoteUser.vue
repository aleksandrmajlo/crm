<template>
    <div class="modal fade" id="userNote" tabindex="-1" role="dialog" aria-labelledby="userNote"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" v-if="order">Note for task ID: {{order.task_id}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" v-if="order">
                    <form @submit="submit">
                        <div v-if="errors.length">
                            <b>Error:</b>
                            <ul>
                                <li v-for="error in errors">{{error}}</li>
                            </ul>
                        </div>
                        <div class="form-group">
                            <label>
                                Note
                            </label>
                            <textarea class="form-control" required v-model="note"></textarea>

                        </div>
                        <button :disabled="disabled" type="submit" class="bnt-  btn-primary">Send</button>
                    </form>
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
        name: "NoteUser",
        data() {
            return {
                order: null,
                disabled: false,
                errors: [],
                note: null,
                notes: []
            }
        },
        created() {
            eventBus.$on('noteuser', data => {
                this.order = data;
                $('#userNote').modal('show');
                this.notes = [];
                this.get();
            })
        },
        methods: {
            get() {
                axios.get('/order/getNoteOrder', {params: {order_id: this.order.id}})
                    .then((response) => {
                        this.notes = response.data.notes;
                    })
                    .finally(() => {

                    });
            },
            submit(e) {
                this.errors = [];
                let v = true;
                if (!this.note) {
                    this.errors.push('Empty note');
                    v = false;
                }
                if (v) {
                    this.disabled = true;
                    axios.post('/order/addNote', {
                        comment: this.note,
                        order_id: this.order.id,
                        task_id: this.order.task_id,
                    })
                        .then(response => {
                            this.showShwal('success', 'Add');
                            this.get();
                            this.note = null;
                        })
                        .catch(error => {
                            this.showShwal('error', 'Error.Try later')
                        })
                        .finally(() => {
                            this.disabled = false;
                        })
                }
                e.preventDefault();
            },
        },
    }
</script>

