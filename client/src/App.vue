<template>
    <div id="app">

        <div class="container">
            <div class="logo">
                <logo class="center-block img-responsive"></logo>
            </div>
            <div class="row">
                <div class="col-xs-12" id="waves"></div>

            </div>
            <transition-group name="list">

            <div class="row responses" v-for="(resp, index) in responses" :key="index">

                <div class="col-xs-6 response-term">
                    <div> {{resp.term}} </div>
                </div>
                <div class="col-xs-6">

                    <blockquote>
                            <template v-for="line in pickASongLines(resp.songs)">
                                {{line}} <br/>
                            </template>


                    </blockquote>

                </div>
                </div>
            </transition-group>


            <div class="row">
                <div class="col-xs-12  " >
                    <div class="bot-message">
                    <img src="/lyri-micro.png" alt="">
                    </div> </div>
                <div class="col-xs-12 input-container">
                    <input type="text" v-model="userInput" @keyup.enter="doSearch">

                </div>

            </div>

        </div>
    </div>

    </div>
</template>

<script>
    import axios from "axios";
    import Logo from './Logo';
    import SiriWave from 'siriwavejs';

    export default {
        components:{ Logo },
        name: "app",
        data() {
            return {
                userInput: "",
                responses: [],// term / songs,
                lines: {},
                isSearching:false
            };
        },
        methods: {
            doSearch() {
                var userInput = this.userInput;
            this.isSearching = true;
                axios.get("/api/songs/" + this.userInput).then(res => {
                    this.addToResponses(userInput, res.data);
                    this.isSearching = false;
                    console.log(this.isSearching);
                });
                this.userInput = '';
            },
            addToResponses(term, songs) {
                console.log(term,songs);
                this.responses.push({term, songs});
            },
            pickASongLines(songs){
                if(!songs[0]) return {};
                return songs[0].Lines;
            }
        },
        mounted(){
            this.siriWave = new SiriWave({
                container : document.getElementById('waves'),
                width:600,
                height:100,
                color:'#9e00ba',
                amplitude:0,
                speed:0.05,
            });
            this.siriWave.start();
        },
        watch:{
            isSearching:function(val){
                if(val)
                    this.siriWave.setAmplitude(0.3);
                else
                    this.siriWave.setAmplitude(0.01);
            }
        }
    };
</script>

<style>
    #app{
        font-family: 'Raleway', sans-serif;
    }
    .logo > img{
        max-width: 300px;
    }
    .logo{
        margin-top: 50px;
    }

    .input-container > input{
        display: block;
        margin: 0 auto;
        width: 100%;
        max-width: 600px;
        height: 46px;
        padding: 10px 16px;
        font-size: 18px;
        line-height: 1.3333333;
        border-radius: 5px;
        border: 1px solid #9e00ba;

    }

    .list-item {
        display: inline-block;
        margin-right: 10px;
    }
    .list-enter-active, .list-leave-active {
        transition: all 1s;
    }
    .list-enter, .list-leave-to /* .list-leave-active below version 2.1.8 */ {
        opacity: 0;
        transform: translateY(30px);
    }
    .responses{
        position: relative;
    }
    .response-term{
        text-align: right;
        opacity: 0.7;
        /*position: absolute;*/
        /*top: 50%;*/
        transform: translateY(170%);
    }

    #waves{
        text-align: center;
    }

    .bot-message{
        max-width: 600px;
        margin: 0 auto;
    }



</style>
