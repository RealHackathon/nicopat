<template>
    <div id="app">

        <div class="container">

            <h1>Lyree</h1>

            <div class="col-row" v-for="(resp, index) in responses" :key="index">

                <div class="col-sm-6">{{resp.term}}</div>
                <div class="col-sm-6">{{pickASongLines(resp.songs)}}</div>
            </div>

            <div>
                <input type="text" v-model="userInput" @keyup.enter="doSearch">

            </div>

        </div>

    </div>
</template>

<script>
    import axios from "axios";

    export default {
        name: "app",
        data() {
            return {
                userInput: "",
                responses: [],// term / songs,
                lines: {},
            };
        },
        methods: {
            doSearch() {
                var userInput = this.userInput;

                axios.get("/api/songs/" + this.userInput).then(res => {
                    this.addToResponses(userInput, res.data);
                });
                this.userInput = '';
            },
            addToResponses(term, songs) {
                console.log(term,songs);
                this.responses.push({term, songs});
            },
            pickASong(songs){
                if(!songs[0]) return {};
                return songs[0].Lines;
            }
        }
    };
</script>

<style>
    body {
        /* background-color: blue; */
    }
</style>
