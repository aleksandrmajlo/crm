<template>
    <div class="pt-2 pb-2">
        <a style="font-size: 150%" href="#" @click.prevent="plusSerial()">
            <i class="fa fa-plus-square"></i>
        </a>
    </div>
</template>

<script>
    import {mapState} from 'vuex';
    import store from '../../store/';

    export default {
        name: "PlusSerial",
        props: ['order'],
        methods: {
            plusSerial(){
                let text='@@@@'
                store.commit('InsertSerialNumbers', {
                    trigger:'plus',
                    text: text,
                    order: this.order
                });
            }
        }
    }
</script>

<style scoped>

</style>