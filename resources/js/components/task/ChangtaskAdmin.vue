<template>
    <div class="text-center">
        <a href="#" @click.prevent="ChangeTask" class="btn btn-info">Change</a>
    </div>
</template>
<script>
    export default {
        name: "ChangtaskAdmin",
        props:{
            task_id: {
                type: String,
            }
        },
        methods:{
            // установить пользователя
            ChangeTask(task_id) {
                this.$root.$emit("showreadtask", {task_id:this.task_id,reload:true});
                $("#SelectUser").modal("show");
            },
        }
    }
</script>
