<template>
    <div style="text-align: center">
        <!--<a @click.prevent="getData">Test 222222222222222222</a>-->
    </div>
</template>

<script>
    import Swal from 'sweetalert2/dist/sweetalert2.js'

    export default {
        name: "AddworkTask",
        mounted() {
            this.getData();
        },
        methods: {
            getData() {
                axios
                    .post("/ajaxuser/messange")
                    .then(response => {
                        if(response.data.html!=""){
                            Swal.fire({
                                position: 'top-end',
                                title: '<strong>Important information</strong>',
                                icon: 'info',
                                html:response.data.html,
                                showCloseButton: true,
                                showCancelButton: true,
                                focusConfirm: false,
                                confirmButtonText:
                                    '<i class="fa fa-thumbs-up"></i> Read!',
                            }).then((result) => {
                                if (result.value) {
                                    this.setReadStaus();
                                }
                            })
                        }
                    })
                    .catch(error => console.log(error))
                    .finally(() => {  })
            },
            setReadStaus(){
                axios
                    .post("/ajaxuser/messangeReadStaus")
                    .then(response => {
                        Swal.fire(
                            'Read status!',
                            '',
                            'info'
                        )
                    })
                    .catch(error => console.log(error))
                    .finally(() => {  })
            }
        }
    }
</script>
