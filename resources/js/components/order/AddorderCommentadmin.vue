<template>
    <a href="#" @click.prevent="showModal" class="btn btn-outline">
        <span v-if="commentadmin.length>0">
            Read comment
        </span>
        <span v-else="">
             Add comment
        </span>
    </a>
</template>

<script>
    export default {
        name: "AddorderCommentadmin",
        props: {
            order_id: String,
            commentadmin: String,
            showcommentadmin: String
        },
        methods: {
            showModal(event) {
                let self = this;
                let $form = $('#orderAddCommentAdmin');
                $form.find('[name="order_id"]').val(this.order_id);
                $('[name="showcommentadmin"]').prop('checked', false);
                $('[name="showcommentadmin"]').each(function (el) {
                    let v = $(this).attr('value');
                    if ($.trim(self.showcommentadmin) == v) {
                        $(this).prop('checked', true);
                    }
                });
                $form.find('[name="commentadmin"]').val(this.commentadmin);
                $form.modal('show');
            }
        }
    }
</script>
