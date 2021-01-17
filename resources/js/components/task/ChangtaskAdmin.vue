<template>
    <div class="text-center">
        <a href="#" @click.prevent="ChangeTask" class="btn btn-info">Change</a>
    </div>
</template>
<script>
    export default {
        name: "ChangtaskAdmin",
        props:['task_id','reload'],
        methods:{
            // установить пользователя
            ChangeTask(task_id) {
                let reload=true;
                if(typeof this.reload!=="undefined"&&this.reload=='-1')reload=false;
                this.$root.$emit("showreadtask", {task_id:this.task_id,reload:reload});
                $("#SelectUser").modal("show");
            },
        }
    }
</script>
