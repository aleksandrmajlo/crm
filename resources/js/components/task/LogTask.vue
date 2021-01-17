<template>
    <a href="#" @click.prevent="LogTask">
        <i class="fa fa-info-circle"></i>
    </a>
</template>
<script>
    import {eventBus} from "../../app";
    export default {
        name: "LogTask",
        props:['task_id','reload'],

        methods:{
            LogTask(task_id) {
                this.$root.$emit("showLogtask", {task_id: this.task_id, reload: false});
                $("#LogTask").modal("show");
            },
        }
    }
</script>
