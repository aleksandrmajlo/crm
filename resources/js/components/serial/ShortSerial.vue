<template>
        <button type="button"
                class="btn btn-secondary serial-popover"
                data-toggle="popover"
                data-placement="left"
                :data-content="serial">
           {{short}} ...
        </button>
</template>

<script>
    export default {
        name: "ShortSerial",
        props:{
            serial:String
        },
        computed:{
            short(){
                return this.serial.substring(0,5)
            }
        },
    }
</script>

<style scoped>

</style>