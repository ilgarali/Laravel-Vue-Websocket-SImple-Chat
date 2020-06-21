<template>
  <div class="container">
    <div class="row">
      <div class="col-md-8">
        <div class="car">
          <div class="card-default">
            <div class="card-header">Messages</div>
            <div class="card-body p-0">
              <ul class="list-unstyled" style="height:300px;overflow-y:scroll;" v-chat-scroll>
                <li class="p-2" v-for="(message,index) in messages" :key="index">
                  <strong>{{message.user.name}}</strong>
                  {{message.message}}
                </li>
              </ul>
            </div>

          
            <input
              type="text"
            
                v-model="newMessage"
            @keyup.enter="sendMessage"
            @keydown="sendTypingEveent"
              name="message"
              placeholder="enter your nmessage"
              class="form-control"
            />
         
         
          </div>
        </div>
        <span v-if="activeUser" class="text-muted">{{activeUser.name}} is typing...</span>
      </div>

      <div class="col-md-4">
        <div class="card-default">
          <div class="card-header">Active Users</div>
          <div class="card-body">
            <ul>
              <li class="py-2" v-for="(user, index) in users"
               :key="index">{{user.name}}</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {

    props:['user'],
    data() {
        return {
            messages:[],
            users:[],
            newMessage:'',
            activeUser:false,
        }
    },

    created() {
        this.fetchMessage(),
        Echo.join('chat')
            .here(user => {
             
              this.users = user
            })
            .joining(user => {
              this.users.push(user)
              
            })
            .listen('MessageSent',(e) => {
                this.messages.push(e.message)
              
            })
            .leaving(user => {
               this.users = this.users.filter(u => u.id != user.id)
              
            })
            .listenForWhisper('typing',user => {
              this.activeUser = user;
              setTimeout(() => {
                this.activeUser = false
              }, 3000);
              
            })
    },

    methods: {
        fetchMessage(){
            axios.get('/messages').then((result) => {
                this.messages = result.data
            }).catch((err) => {
                console.log(err);
                
            });
        },
        sendMessage() {
            this.messages.push({
                message:this.newMessage,
                user:this.user
            })
            axios.post('messages',{message:this.newMessage})
            this.newMessage = ''
            
            
        },
        sendTypingEveent(){
          Echo.join('chat')
            .whisper('typing',this.user)
        }
    },

}
</script>
