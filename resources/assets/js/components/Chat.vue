<template>
    <div>
        <div class="col-md-2">
            <h5>Create Conversation</h5><hr>
            <div class="form-group">
                <select name="" id="" class="form-control" v-model="recipient_id">
                    <option value="0">Select User</option>
                    <option v-for="user in users" v-bind:key="user.id" v-bind:value="user.id">{{user.name}}</option>
                </select><br>
                <button class="btn btn-default btn-block" type="button" @click="createConversation()">Create</button>
            </div>
        </div>
        <div class="col-md-2">
            <h5>Conversation Lists</h5><hr>
            <ul class="list-group">
                <li class="list-group-item" v-for="conversation in conversations" v-bind:key="conversation.id">
                    <a v-if="user.id == conversation.sender.id" @click.prevent="getConversation(conversation.id)">{{conversation.recipient.name}}</a>
                    <a v-else @click.prevent="getConversation(conversation.id)">{{conversation.sender.name}}</a>
                </li>
            </ul>
        </div>
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5 v-if="user.id == conv.sender.id">{{conv.recipient.name}}</h5>
                    <h5 v-else>{{conv.sender.name}}</h5>
                </div>
                <div class="panel-body" style="min-height: 400px; max-height:400px; overflow: scroll;">
                    <div v-for="message in conv.message" v-bind:key="message.id">
                        <div class="self" v-if="message.sender_id == user.id">
                            {{message.message}}
                        </div>
                        <div class="other" v-else>
                            {{message.message}}
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-10">
                            <input type="text" class="form-control" v-model="message">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-detault btn-block" @click.prevent="sendMessage()">Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import pusher from 'pusher-js';
export default {
    props:['users', 'user'],
    data: function(){
        return {
            conversations: {},
            recipient_id: 0,
            conv:{},
            message: ""
        }
    },
    created () {
        this.loadConversation();
    },
    methods:{
        createConversation: function(){
            let form = new FormData;
            form.append('recipient_id',this.recipient_id)
            axios.post('/chat/conversation', form).then((data) => {
                this.loadConversation()

            })
        },
        loadConversation: function(){
            axios.get('/chat/conversation').then((data) => {
                this.conversations = data.data.data.conversation
            })
        },
        getConversation: function(id){
            axios.get('/chat/conversation/'+id).then((data) => {
                this.conv = data.data.data.conversation
            })

            let pusher = new Pusher('16c66ae7a977053ee578', {
                cluster: 'ap1',
                forceTLS: true
            })
            var channel = pusher.subscribe('conversation.'+id)
            let self = this
            channel.bind('message', function(data){
                if(data.sender.id != self.user.id){
                    self.conv.message.push(data.message)
                }else{
                    self.conv.message.push(data.message)
                }
            })
        },
        sendMessage: function(){
            let form = new FormData();
            form.append('conversation_id', this.conv.id)
            form.append('message', this.message);
            axios.post('/chat/message', form).then((data) => {
                this.message = ''

            })
        }
    }
}
</script>

<style lang="scss">
    .self{
        background-color: #0984e3;
        padding-top: 10px;
        padding-right: 10px;
        padding-bottom: 10px;
        border-radius: 10px;
        margin-bottom:5px;
        color: #fff;
        text-align: right;
    }
    .other{
        background-color: #b2bec3;
        padding-top: 10px;
        padding-left: 10px;
        padding-bottom: 10px;
        border-radius: 10px;
        margin-bottom:5px;
    }
</style>
