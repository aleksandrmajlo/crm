<template>
    <div>
        <a href="#" @click.prevent="LogTask" class>
            <i class="fa fa-info-circle"></i>
        </a>
    </div>

</template>

<script>
    export default {
        name: "AdminLogtaskother",
        props:{
            task_id: {
                type: String,
            }
        },
        methods:{
            LogTask(){
                this.$root.$emit("showLogtask",{task_id:this.task_id,reload:true});
                $("#LogTask").modal("show");
            }
        }
    }
</script>
