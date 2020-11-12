<template>
    <div class="modal fade" id="adminNote" tabindex="-1" role="dialog" aria-labelledby="adminNote"
         aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Fieled</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit="send">
                        <div class="form-group mt-2">
                            <select v-model="select" class="form-control" required>
                                <option v-for="(failed,ind) in failed_status" :value="ind">
                                    {{failed}}
                                </option>
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <textarea class="form-control" v-model="comment" required></textarea>
                        </div>
                        <button :disabled="disabled" type="submit" class="btn btn-danger">Send</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import {eventBus} from "../../app";
    import {mapState} from 'vuex';
    import store from '../../store/';

    export default {
        name: "FieledFormmodal",
        data() {
            return {
                ids: [],
                select: null,
                comment: null,
                disabled: false,
            }
        },
        computed: {
            failed_status() {
                return store.state.user.failed_status;
            },
        },
        created() {
            eventBus.$on('FieledForm', data => {
                this.ids = data.failedArr;
                $('#adminNote').modal('show');

            })
        },
        methods: {
            send(e) {
                this.disabled = true;
                axios.post('order/FieldArray', {
                    ids: this.ids,
                    select: this.select,
                    comment: this.comment
                }).then(response => {
                    if (response.data.success) {
                        this.showShwal('success', 'Success');
                        $('#adminNote').modal('hide');
                        store.dispatch('this_user_order');
                        this.select=null;
                        this.comment=null;
                    }
                }).catch(error => {
                    this.showShwal('error', 'Try later');
                }).finally(() => {
                    this.disabled = false;
                });
                e.preventDefault();
            }
        }
    }
</script>

