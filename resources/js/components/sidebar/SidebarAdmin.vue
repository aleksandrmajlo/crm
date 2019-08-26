<template>
    <div class="sidebar">
        <div class="card">
            <div class="card-header">Import</div>
            <div class="card-body">

                <form id="new-file-form" action="#" method="#" @submit.prevent="submitForm">
                    <div class="form-group">
                        <input class="form-control" v-model="weight" name="weight"  placeholder="COST">
                    </div>
                    <div class="form-group">
                        <input class="file-input" type="file" ref="file" name="file" @change="addFile()">
                    </div>
                    <div class="form-group">
                        <button :disabled="disabled" type="submit" class="btn btn-primary">
                            Add material .txt
                        </button>
                    </div>
                </form>
                <div class="form-group" v-if="uploadtask.length>0">
                    <button type="button"
                            :disabled="Save_and_publish_Button_Disabled"
                            @click.prevent="save"
                            class="btn btn-success">Save and publish</button>
                    <div class="progress mt-2" v-if="Save_and_publish_Button_Disabled">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
                    </div>
                </div>

                <div v-show="error" class="alert alert-warning" role="alert">
                    {{error_txt}}
                </div>

            </div>

        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';
    import store from '../../store/';
    import Swal from 'sweetalert2/dist/sweetalert2.js'
    export default {
        name: "SidebarAdmin",
        data:function(){
            return {
                file: {},
                formData: {},
                attachment: '',
                weight:"",
                error:false,
                error_txt:"",
                disabled:false
            }
        },
        computed: {

            uploadtask(){
                return store.state.task.uploadtask;
            },
            Save_and_publish_Button_Disabled(){
                return store.state.task.Save_and_publish_Button_Disabled;
            },

        },
        mounted(){

        },
        methods:{
            submitForm(){
                if(this.attachment===""){
                    this.error=true;
                    this.error_txt="Upload file";
                    setTimeout(()=>{
                        this.error=false;
                    },3000)
                    return false;
                }
                this.disabled=true;

                // очистим загруженные
                store.commit('setUploadtask',{tasks:[]});
                store.commit('showPublicMess',"");

                this.formData = new FormData();
                this.formData.append('file', this.attachment);
                this.formData.append('weight', this.weight);
                axios.post('ajax/upload', this.formData, {headers: {'Content-Type': 'multipart/form-data'}})
                    .then(response => {
                        if(response.data.success){
                            store.commit('setUploadtask',response.data);
                        }
                        this.resetForm();
                        this.disabled=false;
                    })
                    .catch(error => {
                        this.error = true;
                        this.disabled=false;

                        Swal.fire({
                            type: "error",
                            title: "Error",
                            text: error.response.data.message,
                            timer: 3500
                        });

                    });
            },
            addFile(){
                this.attachment = this.$refs.file.files[0];
            },
            resetForm() {
                this.formData = {};
                this.attachment = '';
                this.$refs.file.value="";
            },
            // сохранить и опубликовать
            save(){
                store.dispatch('uploadSave');
            }
        }
    }
</script>

<style scoped>

</style>