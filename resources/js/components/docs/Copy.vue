<template>
    <div class="card card-body mt-3">
        <pre  ><span v-html="text"></span></pre>
    </div>
</template>
<script>
    export default {
        name: "Copy",
        props:['text'],
        methods: {
            doCopy: function () {
                this.$copyText(this.text).then(function (e) {
                    alert('Copied');
                    console.log(e)
                }, function (e) {
                    alert('Can not copy');
                    console.log(e)
                })
            }
        }

    }
</script>
<style scoped>
.card-body{
     cursor: pointer;
}
</style>
