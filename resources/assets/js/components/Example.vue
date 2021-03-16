<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Login component</div>
                    <br><br>
                    <div class="panel-body">
                        <a href="https://broker.netid.de/authorize?response_type=code&client_id=dcb4a1b4-0e9e-42a0-b282-7c160dfe8f43&redirect_uri=https://netid.design-it.de/callback&scope=openid&claims=%7B%22userinfo%22%3A%7B%22birthdate%22%3A%7B%22essential%22%3Atrue%7D%2C%22gender%22%3A%7B%22essential%22%3Atrue%7D%2C%22given_name%22%3A%7B%22essential%22%3Atrue%7D%2C%22family_name%22%3A%7B%22essential%22%3Atrue%7D%7D%7D"
                        >
                            log in with netid
                        </a>
                        <br>
                        <a href="https://broker.netid.de/authorize?response_type=code&client_id=dcb4a1b4-0e9e-42a0-b282-7c160dfe8f43&redirect_uri=https://netid.design-it.de/callback&scope=openid">
                            log in with netid without claims
                        </a>
                        <br>
                        <a href="#" @click="sendAuthorizeRequest()"> axios authorize </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                baseURL: 'https://broker.netid.de',
                authorizeEndpoint: '/authorize',
                tokenEndpoint: '/token',
                userInfoEndpoint: '/userinfo',
                clientId: 'dcb4a1b4-0e9e-42a0-b282-7c160dfe8f43',
                redirectUri: 'https://netid.design-it.de/callback',
                claimsObj: {
                    'userinfo': {
                        'gender': { 'essential': true },
                        'birthdate': { 'essential': true },
                        'given_name': { 'essential': true },
                        'family_name': { 'essential': true }
                    }
                },
                axiosConfig: {
                    headers: {'Access-Control-Allow-Origin': '*'}
                }
            };
        },
        computed: {
            authorizeURL() {
                return `${this.baseURL}/${this.authorizeEndpoint}?response_type=code&client_id=${this.clientId}&redirect_uri=${this.redirectUri}&scope=openid`
            },
            claimsString() {
                return JSON.stringify(this.claimsObj);
            },
        },
        methods: {
            sendAuthorizeRequest() {
                axios.get(`${this.authorizeURL}&claims=${this.claimsString}`, this.axiosConfig)
                    .then((response) => {
                        console.dir(response);
                    })
                    .catch(error => console.dir(error));
            },
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
