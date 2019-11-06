<template>
    <button @click.prevent="send" class="btn btn-outline">Set free status</button>
</template>

<script>
    export default {
        name: "FailedFree",
        props: {
            order_id: String
        },
        methods: {
            send(event) {
                let el = event.target;
                $(el).prop('disabled',true)
                console.log(el)
                axios.post('/order/FailedFree', {
                    order_id: this.order_id,
                })
                    .then(response => {
                       $(el).parents('tr').remove();
                    })
                    .catch(error => {
                        console.log(error)
                    });
            }
        }
    }
</script>

<style scoped>

</style>