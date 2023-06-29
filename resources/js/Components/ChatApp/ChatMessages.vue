<template>
    <div class="chat-container">
        <div id="messages" class="scrollable messages bg-center bg-cover bg-no-repeat h-screen" ref="chatarea">
            <!-- // show empty box if no messages -->
            <div v-if="messages.length === 0" class="flex flex-col items-center justify-center">
                <div class="text-2xl font-bold">No messages yet</div>
                <div class="text-gray-500">Start a conversation by clicking on a user and typing a message below</div>
            </div>
            <div v-else class="flex flex-col items-center justify-center">
                <div class="text-2xl font-bold bg-blue-300 rounded py-2 px-4 mb-4">Title: {{ currentChatRoom.name }}</div>
            </div>
            <div v-for="message in messages" :key="message" ref="elements">

                <div class="flex-row max-w-[70%] w-fit rounded py-[1px] mb-4"
                    :class="message.user_id === currentUser.id ? 'bg-gray-300 ml-auto' : 'bg-gray-200 mr-auto'">

                    <div class="rounded-md py-1 mx-2 text-sm"
                        :class="message.user_id === currentUser.id ? 'name right' : 'name left '">
                        <strong class="rounded-md px-2 py-1 text-sm"
                            :class="message.user_id === currentUser.id ? ' bg-blue-300 ml-2' : 'bg-green-100 mr-2'">
                            {{ message.user_id === currentUser.id ? 'Me@PeekChat' : message.user_name }}</strong>

                        <p class="text-gray-800 text-sm">{{ message.text }}</p>
                    </div>
                </div>

            </div>
        </div>
        <div class="input-area flex h-[60px] gap-4">
            <input type="text" :ref="currentMessage.text" v-model="newMessage" @keyup.enter="sendMessage"
                class="w-full p-4 rounded-lg" placeholder="Type your message here..." />
            <div class="flex justify-end">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                :disabled="!newMessage"
                    @click="sendMessage">Send</button>
            </div>
        </div>

    </div>
</template>

<style scoped>
.chat-container {
    display: flex;
    flex-direction: column;
    max-height: calc(100vh - 140px);
    overflow: hidden;
    background-color: white;
    /* border: 1px solid #656565; */
    box-shadow: 10px 1px 10px #656565;
    border-radius: 10px;
    min-width: 600px;
    margin: 10px;
    width: calc(50vw);

    @media screen and (max-width: 1025px) {
        width: calc(90vw - 10px);
        margin-left: calc(5vw);
        margin-right: 20px;
        padding-right: 10px;
        min-width: fit-content;

    }

}

.input-area {
    padding: 10px;
    border-top: 1px solid #e5e5e5;
}

.messages {
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    padding: 10px;
    max-height: fit-content;
    margin-top: 10px;
    margin-bottom: 10px;
}

.message {
    margin-bottom: 20px;

}

.name {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.right {
    flex-direction: row-reverse;
}

.left {
    flex-direction: row;
}

.scrollable {
    overflow: hidden;
    overflow-y: scroll;
    height: calc(100vh - 20px);
}
</style>

<script setup>
import { usePage, router } from '@inertiajs/vue3';
import { ref, toRefs, defineEmits, computed, onMounted, watch, reactive } from 'vue';
// import axios from 'axios';
import { io } from 'socket.io-client';
// import { isEmpty } from './../../helpers';


router.reload(['messages'], { replace: true });

// emits
const emit = defineEmits(['send']);

// Props

const props = defineProps({
    messages: {
        type: Array,
        default: () => []
    },
    currentChatRoom: Object,
    // friendsProfile: Array,
    currentUser: Object,
    chat_id: String
})

const { messages, chat_id, currentChatRoom } = toRefs(props);


// EMIT MESSAGES
const user = computed(() => usePage().props.auth.user).value;
const socket = io("http://localhost:3000");

// Declare message
const newMessage = ref('');

const currentMessage = ref({
    text: '',
    user_id: user.id,
    user_name: user.name,
    chat_id: props?.chat_id
});


const storeMessageAxios = (message, user) => {
    axios.post(route('messages.store'), {
        text: message,
        user_id: user.id,
        chat_id: chat_id.value,
    }).then((response) => {
        if (response.status === 200) {
            console.log(response.data.messages, 'MESAJUL A FOST STOCAT');
            messages.value = response.data.messages;
        }
    }).catch((error) => {
        console.log(error);
    });
}

socket.on('new-message', msg => {
    console.log(msg.chat_id, 'MESAJUL A FOST PRIMIT');
    console.log(props.chat_id, 'MESAJUL A FOST PRIMIT');

    if (msg.chat_id == props.chat_id) {

        console.log(msg, 'MESAJUL A FOST PRIMIT');
        appendMessage(msg);
    }
});

const emitMessage = () => {
    // socket.emit('new-message', currentMessage.value);

    emit('send', {
        text: newMessage.value,
        user_id: user.id,
        chat_id: chat_id.value,
        chatRoom: currentChatRoom.value
    })
}

const appendMessage = (msg) => {
    messages.value.push(msg);
}


const sendMessage = () => {

    if (_.isEmpty(chat_id.value)) {
        // send toast error alert
        alert('You must select a chat');
        return;
    }
    console.log('chat_id.value in ChatMessages', chat_id.value);

    currentMessage.value.text = newMessage.value;
    // emitMessage();
    storeMessageAxios(newMessage.value, user);

    socket.emit('send-message', currentMessage.value);

    newMessage.value = '';
    scrollToBottom();

}

// AUTO SCROLL TO BOTTOM

const elements = ref(null);

const chatarea = ref(null);
const msgbox = ref();
const chat = document.getElementById('messages');

msgbox.value = document.getElementById('messages');


const scrollToBottom = () => {
    const shouldScroll =
        chatarea.value.scrollTop + chatarea.value.clientHeight !==
        chatarea.value.scrollHeight;


    setTimeout(() => {
        if (chatarea.value) {
            chatarea.value.scrollTop = chatarea.value.scrollHeight - chatarea.value.clientHeight;
        }
    }, 100);
}

// if page refreshed scroll to bottom
onMounted(() => {
    scrollToBottom();
})
// Watchers

watch(
    // Scroll to bottom
    chat_id,
    () => {
        console.log('chat_id.value in WATCH', chat_id.value);
        setTimeout(() => {
            scrollToBottom();
        }, 300);
    }
);

// Helpers

// save message

/* OTHERS



const saveMessage = (message, user) => {
    router.post(route('messages.send'), {
        text: message,
        user: user.id,
        chat_id: chat_id.value,
        users_list: currentChatRoom.value.users_list
    }, {
        only: ['messages'],

        preserveScroll: true,
        onSuccess: () => {
            console.log('Message sent');
        }
    });
}





*/
</script>
