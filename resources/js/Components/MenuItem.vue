<script setup>
import { toRefs } from 'vue';
import { usePage } from '@inertiajs/vue3';

import HomeOutline from 'vue-material-design-icons/HomeOutline.vue';
import Magnify from 'vue-material-design-icons/Magnify.vue';
import Compass from 'vue-material-design-icons/Compass.vue';
import SendOutline from 'vue-material-design-icons/SendOutline.vue';
import MessageOutline from 'vue-material-design-icons/MessageOutline.vue';
import HeartOutline from 'vue-material-design-icons/HeartOutline.vue';
import Plus from 'vue-material-design-icons/Plus.vue';
import AccountOutline from 'vue-material-design-icons/AccountOutline.vue';
import Menu from 'vue-material-design-icons/Menu.vue';
import LogoutIcon from 'vue-material-design-icons/Logout.vue';
import ChatProcessingOutline from 'vue-material-design-icons/ChatProcessingOutline.vue';


const props = defineProps({ iconString: String })
const { iconString } = toRefs(props)

const user = usePage().props.auth.user

const getIcon = (iconName) => {
  switch (iconName) {
    case 'Home': return HomeOutline
    case 'Search': return Magnify
    case 'Explore': return Compass
    case 'Messages': return ChatProcessingOutline
    case 'Events': return Compass
    case 'Notifications':
    case 'Post': return MessageOutline
    case 'Create': return HeartOutline
    case 'Profile': return AccountOutline
    case 'Logout': return LogoutIcon
    case 'Chat': return ChatProcessingOutline
    default: return null
  }
}

const icon = getIcon(iconString.value)


</script>

<template>
    <div class="w-full xl:inline-block xl:hover:bg-gray-100 p-2 rounded-full transition duration-300 ease-in-out cursor-pointer mb-4">
        <div class="flex items-center">
            <img
                v-if="iconString === 'Profile'"
                :class="{'mr-1': iconString === 'Profile'}"
                class="rounded-full ml-[2px] w-[30px] cursor-pointer"
                :src="user.file"
            >
            <component v-else :is="icon" fillColor="#000000" :size="30" />

            <span class="xl:block hidden text-black font-extrabold text-[14px] pl-4">
                {{ iconString }}
            </span>
        </div>
    </div>
</template>
