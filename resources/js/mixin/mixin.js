// import Swal from 'sweetalert2/dist/sweetalert2.js'
export default {
    data() {
        return {

        }
    },
    computed: {

    },
    methods: {
        showShwal(icon, text) {
            this.$swal.fire({
                type: icon,
                text: text,
                showConfirmButton: false,
                timer: 2500
            });
        },

    }
}
