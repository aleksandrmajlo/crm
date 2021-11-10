<template>
    <a class="btn btn-primary"
       @click="doCopy"
       role="button" aria-expanded="false" aria-controls="collapseExample">
        Copy
    </a>
</template>

<script>
    export default {
        name: "Link",
        props:['text'],
        methods: {
            doCopy: function () {
                this.$copyText(this.text).then(function (e) {
                    alert('Copied');
                }, function (e) {
                    alert('Can not copy');
                })
            }
        }
    }
</script>

<style scoped>

</style>
