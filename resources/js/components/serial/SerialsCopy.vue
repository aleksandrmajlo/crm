<template>
    <div class="text-center">
        <a href="#" @click.preven="copy_ser" class="btn btn-primary">Copy serial</a>
    </div>
</template>

<script>
    export default {
        name: "SerialsCopy",
        props: ['copy_serial'],
        methods: {
            copy_ser() {
                let self=this;
                const str = this.copy_serial.join('\n');
                this.$copyText(str).then(function (e) {
                    alert('Copied')
                    axios.post('/copy_serial', {
                        serials: self.copy_serial
                    })
                        .then(response => {

                        })
                        .catch(error => {
                        });


                }, function (e) {
                    alert('Can not copy')
                    console.log(e)
                });
            }
        }
    }
</script>


