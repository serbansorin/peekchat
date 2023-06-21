<template>
    <Head title="Instagram" />

    <main-layout>
        <div class="chat-area">
            <div>
                <ChatMessages v-model="newMessage"
                :chat_id="chatId"
                :current-user="user" :chatId="chatId" :users="friends" :currentChatRoom="currentChatRoom" :messages="currentChatRoom?.messages ?? []"
                    @send="sendMessage($event)" />
            </div>
            <div class="lg:absolute hidden lg:flex">
                <ContactSlide :allUsers="friends" @selectFriend="selectFriend($event)" />
            </div>
        </div>
    </main-layout>
</template>
<style scoped>
.chat-area {
    max-height: fit-content;
    left: 0;
    right: 0;
    margin-top: 70px;
    margin-bottom: 70px;
    border-radius: 10px;
    display: block;
    height: 100%;
    min-height: max-content;

}
</style>



<script setup>
import ContactSlide from '@/Components/ChatApp/ContactSlide.vue';
import { usePage, router } from '@inertiajs/vue3';
// import ChatBox from './ChatBox.vue';
import MainLayout from '@/Layouts/MainLayout.vue';
import { computed, reactive, ref, toRefs } from 'vue';
import ChatMessages from '@/Components/ChatApp/ChatMessages.vue';
import axios from 'axios';




const props = defineProps({
    messages: {
        type: Array,
        default: () => [],
    },
    currentChatRoom: {
        type: Object,
        default: () => { },
    },
    friends: {
        type: Array,
        default: () => [],
    },
    chatRooms: {
        type: Array,
        default: () => [],
    },
    chatId: {
        type: String,
        default: '',
    }
})

const { messages, currentChatRoom, chatId, chatRooms, friends } = toRefs(props);


const user = usePage().props.auth.user;

const newMessage = ref('');

// console.log(messages.value, 'messages');
console.log(chatId.value, 'chatId');


const sendMessage = ($event) => {

    console.log($event, 'event sendmessage from Chat.vue');

    messages.value.push({
        text: $event.message,
        user: $event.user
    })

    return;
    axios.post(route('messages.store'), {
      content: $event.message,
      user_id: $event.user_id,
      chat_id: $event.chat_id,
      chat_room: $event.chat_room
    }).then((response) => {
      if (response.status === 200) {
        console.log(response.data);
        messages.value = response.data;
      }
    }).catch((error) => {
      console.log(error);
    });
}

const selectFriend = (friend) => {
    console.log(friend);
    const idChat = findChatIdFromUsersList([friend.id, user.id]);

    router.get(`/messages/select`, {
        user_id: user.id,
        users_list: [ friend.id, user.id ],
        chat_room: idChat,
    }, {
        only: ['messages', 'chatId', 'currentChatRoom', 'friendsProfile'],
        preserveState: true,
        preserveScroll: true,
        preserveForm: true,
        replace: true,
        onSuccess: ($data) => {
            console.log('success');
            console.log($data.props.messages, 'MESSAGES FROM selectFriend.router.get');
        },
    });
}

 const findChatIdFromUsersList = (users_list) => {

    let chat = '';
    let chatUsersNum = 0;

    chatRooms.value.forEach(chatRoom => {
        console.log(chatRoom, 'chatRoom');
        chatRoom.users_list.forEach(chatUser => {

            users_list.forEach(user => {

                if (user == chatUser) {
                    chatUsersNum++;
                }
            });
        });

        if (chatUsersNum === chatRoom.users_list.length && chatUsersNum === users_list.length) {
            chat = chatRoom.id;
        }
        chatUsersNum = 0;
    });
    return chat;
 };

 const isNumeric = (num) => {
    return !isNaN(parseFloat(num)) && isFinite(num);
 }

</script>
