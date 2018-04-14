<template>
    <div id="app">

        <div class="container">
            <div class="logo">
                <logo class="center-block img-responsive"></logo>
            </div>
            <div class="row">
                <div class="col-xs-12" id="waves"></div>

            </div>
            <div class="responses-container">

                <transition-group name="list" tag="div">

                    <div class="row responses" v-for="(resp, index) in computedResponses" :key="resp.id">

                        <div class="col-xs-6 response-term">
                            <div> {{resp.term}}</div>
                        </div>
                        <div class="col-xs-6">

                            <blockquote>
                                <template v-for="line in resp.song.Lines">
                                    {{line}} <br/>
                                </template>

                                <cite> {{ resp.song.Artist }} - {{resp.song.Song}} </cite>

                            </blockquote>

                        </div>
                    </div>
                </transition-group>

            </div>

            <div class="row">
                <div class="col-xs-12  ">
                    <div class="bot-message">
                        <img src="/lyri-micro.png" class="pull-left">
                        <p>{{botMessage}}
                            <span v-for="(sugg, index) in suggestions" :key="index">
                               <span class="sugg" @click="addSuggestion(sugg)"> {{sugg.Artist}} </span>
                                <span v-if="index < suggestions.length-1"> ou </span>
                            </span>

                        </p>
                    </div>
                </div>
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
    import {sample, sampleSize, uniqBy, last, uniqueId, filter} from 'lodash';
    import MSG from './messages';

    export default {
        components: {Logo},
        name: "app",
        data() {
            return {
                userInput: "",
                responses: [],// term / song,
                lines: {},
                isSearching: false,
                botMessage: '',
                suggestions: []
            };
        },
        methods: {
            doSearch() {
                var userInput = this.userInput;
                this.suggestions = [];
                if (!userInput.trim()) return;
                this.isSearching = true;
                this.botMessage = this.getRandMsg('searching');

                axios.get("/api/songs/" + this.userInput).then(res => {
                    this.addToResponses(userInput, res.data);

                    this.isSearching = false;
                }).catch(err => {

                    this.botMessage = this.getRandMsg('nothing');
                    this.isSearching = false;

                });
                this.userInput = '';
            },
            addToResponses(term, songs) {
                songs = filter(songs, 'found');
                if (!songs.length) {
                    this.botMessage = this.getRandMsg('nothing');

                    return;

                }
                var song = songs[0];
                var msg = `J'ai trouvé du ${song.Artist} `;
                var sample = uniqBy(songs, 'Artist');
                var suggestions = sampleSize(sample.slice(1, sample.length - 1), 3);


                if (suggestions.length > 0) {
                    msg += this.getRandMsg('suggestions');

                    this.suggestions = suggestions;
                }
                else {
                    msg += this.getRandMsg('nosuggestions');

                }
                this.botMessage = msg;
                this.responses.push({term, song, id: uniqueId('lyri')});
            },
            pickASongLines(songs) {
                if (!songs[0]) return {};
                return songs[0].Lines;
            },
            getRandMsg(type) {
                return sample(MSG[type]);
            },
            addSuggestion(song) {
                var term = last(this.responses).term;
                this.responses.push({term, song, id: uniqueId('lyri')});

            }

        },
        mounted() {
            this.botMessage = this.getRandMsg('welcome');
            this.siriWave = new SiriWave({
                container: document.getElementById('waves'),
                width: 600,
                height: 100,
                color: '#9e00ba',
                amplitude: 0,
                speed: 0.05,
            });
            this.siriWave.start();
        },
        watch: {
            isSearching: function (val) {
                if (val)
                    this.siriWave.setAmplitude(0.3);
                else
                    this.siriWave.setAmplitude(0.01);
            }
        },
        computed: {
            computedResponses() {
                var endI = this.responses.length;
                var startI = Math.max(0, endI - 3);
                var result = [];
                for (var i = startI; i < endI; i++) {
                    result.push(this.responses[i]);
                }
                return result;
            }

        },
    };
</script>

<style>
    #app {
        font-family: 'Raleway', sans-serif;
    }

    .logo > img {
        max-width: 300px;
    }

    .logo {
        margin-top: 20px;
    }

    .input-container > input {
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

    /*.responses {*/
    /*transition: all 1s;*/

    /*}*/
    .responses-container {

    }

    .list-item {
        display: inline-block;
        margin-right: 10px;

    }

    /*.list-move{*/
    /*transition: transform 0.5s;*/
    /*}*/

    /*.list-enter-active, .list-leave-active {*/
    .list-enter-active {
        transition: all 0.5s;
    }

    .list-enter {
        opacity: 0;
        transform: translateY(30px);
    }

    .list-leave-to {
        transform: scaleY(0);

    }

    .responses {
        position: relative;
    }

    .response-term {
        text-align: right;
        opacity: 0.7;
        /*position: absolute;*/
        /*top: 50%;*/
        transform: translateY(170%);
    }

    #waves {
        text-align: center;
    }

    .bot-message {
        max-width: 600px;
        margin: 0 auto;
    }

    .bot-message > p {
        padding-top: 45px;
    }

    cite {
        font-size: 0.8em;
        opacity: 0.8;
        text-align: right;
    }

    .responses-container {
        max-height: 50vh;

    }

    span.sugg {
        color: #9e00ba;
        cursor: pointer;
    }


</style>
