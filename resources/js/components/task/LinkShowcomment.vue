<template>
    <div class="text-center">
        <a href="#" @click.prevent="ShowComment">
            <i class="fa fa-comment">{{count}}</i>
        </a>
    </div>
</template>
<script>
    import {eventBus} from "../../app";
    export default {
        name: "LinkShowcomment",
        props: {
            task_id: {
                type: String,
            },
            count: {
                type: String,
                default: '0'
            }
        },
        methods: {
            // установить пользователя
            ShowComment() {
                eventBus.$emit("AddSchowComment", {
                    task_id: this.task_id,
                })

            },
        },
    }
</script>
