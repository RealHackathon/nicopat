<template>
    <div id="waves"></div>
</template>

<script>
    import SiriWave from 'siriwavejs';
    export default {
        mounted(){
            var siriWave = new SiriWave({
                container : document.getElementById('waves'),
                width:600,
                height:200,
                color:'#9e00ba'
            });
            siriWave.start();
        }
    }
</script>