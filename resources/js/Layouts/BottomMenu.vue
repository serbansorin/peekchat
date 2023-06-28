<template>
    <div class="flex fixed bottom-0 bg-white w-[100%] h-[60px]"
    v-if="$page.props.auth.user && $page.props.auth.user.id && !route().current('home.index') "
    >

        <div
            class="z-30 bottom-0 ml-4 gap-12 h-[60px] flex justify-between items-center bg-white border-t px-2 py-2 border-t-gray-300">
            <Link href="/">
            <HomeOutline fillColor="#000000" :size="33" class="cursor-pointer" />
            </Link>
            <Compass fillColor="#000000" :size="33" class="cursor-pointer" />
            <SendOutline fillColor="#000000" :size="33" class="cursor-pointer" />
            <Plus @click="showCreatePost = true" fillColor="#000000" :size="33" class="cursor-pointer" />
            <AccountOutline fillColor="#000000" :size="33" class="cursor-pointer" />
            <Link :href="route('users.show', { id: $page.props.auth.user.id })">
            <img class="rounded-full w-[30px] cursor-pointer" :src="$page.props.auth.user.file">
            </Link>
        </div>

        <div class="w-full mt-2 mr-5">
            <!-- <div class="w-[10%]"></div> -->

            <!-- <div class="justify-center"> -->

            <div class="text-right text-gray-400 mt-[2%]">© 2023 SharedByMe.</div>
            <!-- </div> -->
        </div>
    </div>
</template>

<script setup>

import HomeOutline from 'vue-material-design-icons/HomeOutline.vue';
import Compass from 'vue-material-design-icons/Compass.vue';
import SendOutline from 'vue-material-design-icons/SendOutline.vue';
import Plus from 'vue-material-design-icons/Plus.vue';
import AccountOutline from 'vue-material-design-icons/AccountOutline.vue';

import { Link } from '@inertiajs/vue3';
import { toRefs } from "vue";

// const props = defineProps();

const emit = defineEmits(['showCreatePost', 'comment'])

const showCreatePost = () => {
    emit("showCreatePost", true);
}

const allowedRoutes = ['/', '/posts', '/users'];

// const { } = toRefs();
</script>
