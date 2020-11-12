export default {
    methods: {
        validate() {
            let res = true;
            if (this.validEmail(this.email)) {
                this.error.email = false;
            } else {
                this.error.email = true;
                res = false;
            }
            if (this.messange == "") {
                this.error.messange = true;
                res = false;
            } else {
                this.error.messange = false;
            }
            return res;
        },
        validEmail: function (email) {
            var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        }
    }
};
