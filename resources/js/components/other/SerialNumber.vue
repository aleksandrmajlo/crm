<template>
    <div class="mt-3">
        <div class="row">
            <div class="col-md-1 text-center">
                <a href="#" @click.prevent="remove">
                    <i class="fa fa-times"></i>
                </a>
            </div>
            <div class="col-md-4">
                <input class="form-control" @input="set" ref="serialinp" :value="serial.serialinp"
                       placeholder="Serial number"/>
            </div>
            <div class="col-md-3">
                <input class="form-control" @input="set" ref="link" :value="serial.link" placeholder="Link"/>
            </div>
            <div class="col-md-4">
                <textarea class="form-control" placeholder="Text" @input="set" ref="text"
                          :value="serial.text"></textarea>
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
        data() {
            return {}
        },
        computed: {},
        props: ['serial', 'index', 'id'],
        methods: {
            set() {
                store.commit('SetInsertSerialNumbers',
                    {
                        serialinp: this.$refs.serialinp.value,
                        text: this.$refs.text.value,
                        link: this.$refs.link.value,
                        index: this.index,
                        id: this.id
                    }
                )
            },
            remove(){
                store.commit('RemoveSerialNumbers',
                    {
                        index: this.index,
                        id: this.id
                    }
                )
            }
        },

        watch: {}
    }
</script>

<style scoped>

</style>