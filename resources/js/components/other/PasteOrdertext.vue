<template>
    <div class=" mb-3">
        <h4 class="text-info">Insert serial numbers(copy in .txt and paste this)</h4>
        <h6 class="text-info"> String :
            7845ty7245h0-784g2h-7254hg7-h245-7hg@@https://best.aliexpress.com/?lan=en&@@база 100 гб</h6>
        <h6 class="text-danger"> Separator : @@ </h6>
        <div >
                    <textarea
                            style="min-height:200px;"
                            :value="order.Textareaserials"
                            :ref="'Textareaserials'+order.id"
                            class="form-control">
                    </textarea>
                   <div class="mb-2 mt-2 text-center">
                     <button class="btn btn-primary btn-lg "
                        @click.prevent="onPaste">Insert
                     </button>
                  </div>
        </div>

    </div>
</template>
<script>
    import {mapState} from 'vuex';
    import store from '../../store/';
    export default {
        name: "PasteOrdertext",
        data() {
            return {
            }
        },
        props: ['order'],
        methods: {
            onPaste(event) {
                let el = event.target;
                setTimeout(() => {
                    let text = this.$refs['Textareaserials'+this.order.id].value;
                    store.commit('InsertSerialNumbers', {
                        trigger:'textarea',
                        text: text,
                        order: this.order
                    });
                }, 0);

            },
        }
    }
</script>
