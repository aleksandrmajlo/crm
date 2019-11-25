<template>
    <div class="text-center">
        <a href="#" @click.prevent="ShowComment">
            <i class="fa fa-comment">{{count}}</i>
        </a>
    </div>
</template>
<script>
    export default {
        name: "LinkShowcomment",
        props:{
            task_id: {
                type: String,
            },
            count:{
                type: String,
                default: '0'
            }
        },
        methods:{
            // установить пользователя
            ShowComment() {
                this.$root.$emit("AddSchowComment", {task_id: this.task_id, reload: true});
                $("#AddSchowComment").modal("show");

            },
        }
    }
</script>
