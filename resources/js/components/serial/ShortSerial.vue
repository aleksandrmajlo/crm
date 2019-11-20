<template>
        <button type="button"
                class="btn btn-secondary serial-popover"
                data-toggle="popover"
                data-placement="left"
                :data-content="serial">
               <span v-if="side_serials_def=='s'">{{short}} ...</span>
               <span v-else>... {{short}}</span>

        </button>
</template>

<script>
    export default {
        name: "ShortSerial",
        data(){
            return {
                side_serials_def:$side_serials_def,
                count_def:$count_def
            }
        },
        props:{
            serial:String
        },
        created(){
        },
        computed:{
            short(){
                if(this.side_serials_def=='s'){
                    return this.serial.substring(0,parseInt(this.count_def))
                }else{
                    return this.serial.slice(-parseInt(this.count_def));
                }

            }
        },
    }
</script>

<style scoped>

</style>