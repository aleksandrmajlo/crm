<template>
    <div>
        <span v-if="!readMoreActivated">{{longtext.slice(0, 30)}}   </span>
        <a  class="btn btn-outline-info" v-if="!readMoreActivated" @click.prevent="activateReadMore" href="#">
            Read more...
        </a>
        <span v-if="readMoreActivated" v-html="longtext"></span>

    </div>
</template>

<script>
    export default {
        name: "ReadMore",
        data(){
            return {
                readMoreActivated: false
            }
        },
        props:['longtext'],
        methods:{
            activateReadMore(){
                this.readMoreActivated = true;
            },
        }
    }
</script>

