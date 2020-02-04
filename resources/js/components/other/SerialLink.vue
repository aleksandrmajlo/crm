<template>
    <div>
        <div v-for="link in links">
            <a target="_blank" :href="link">{{link}}</a>
        </div>
    </div>
</template>

<script>
    export default {
        name: "SerialLink",
        props:['link'],
        computed:{
            links(){
                let arrs=this.link.split(' ');
                let res=[];
                if(arrs){
                    arrs.forEach(el=>{
                        if(el!==""){
                            res.push(el)
                        }
                    })
                }
                return res;
            }
        }
    }
</script>

<style scoped>

</style>