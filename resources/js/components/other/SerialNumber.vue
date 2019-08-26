<template>
      <div class="mt-3">
         <div class="row">
             <div class="col-md-5">
                 <input class="form-control" @input="set" ref="serialinp"  :value="serial.serialinp" />
             </div>
             <div class="col-md-5">
                 <textarea class="form-control"  @input="set" ref="text"  :value="serial.text" ></textarea>
             </div>
             <div class="col-md-2">
             </div>
         </div>
          <hr/>
      </div>
</template>

<script>
    import {mapState} from 'vuex';
    import store from '../../store/';
    export default {
        name: "SerialNumber",
        data(){
            return {
            }
        },
        computed: {
        },
        props: ['serial','index','id'],

        methods:{
            set(){
                store.commit('SetInsertSerialNumbers',{
                    serialinp:this.$refs.serialinp.value,
                    text:this.$refs.text.value,
                    index:this.index,
                    id:this.id})
            }
        },

        watch: {
        }
    }
</script>

<style scoped>

</style>