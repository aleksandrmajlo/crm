<template>
    <div class="SavedComponent">
        <div v-if="saved" class="alert alert-success text-center" role="alert">
            Saved!!!
            <div v-if="saved_text!=''">
                {{saved_text}}
            </div>
        </div>
    </div>
</template>
<script>
    import { mapState } from 'vuex';
    import store from '../../store/';
    export default {
        name: "SavedComponent",
        computed: {
            saved(){
                return store.state.task.saved;
            },
            saved_text(){
                return store.state.task.saved_text;
            }
        },
    }
</script>
