<script setup>
import TopNavigation from './TopNavigation.vue'
import SideContainer from './SideContainer.vue'
import BottomMenu from "@/Layouts/BottomMenu.vue";
import { ref } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";

import MenuItem from "@/Components/MenuItem.vue";
import CreatePostModal from "@/Components/CreatePostModal.vue";

const showCreatePost = ref(false);

const $page = usePage();

// TODO make a route to /users/suggested and use it here
const randomUsers = $page.props.randomUsers;


</script>

<template>
    <div id="MainLayout" class="w-full h-screen">
        <TopNavigation />


        <div id="SideNav" v-if="$page.url == '/'" class="fixed h-full xl:w-[200px] w-[100px] md:block hidden tiling-card">
            <Link href="/">
            <img class="xl:hidden block w-[25px] mt-10 ml-[25px] mb-10 cursor-pointer" src="/logo/logo-plain.svg" />
            <img class="xl:block hidden w-[150px] mt-10 ml-2 mb-10 cursor-pointer" src="/logo/peek.svg"
                viewbox="0 0 160 160" />
            </Link>

            <div class="px-3">
                <Link href="/">
                <MenuItem iconString="Home" class="mb-2" />
                </Link>

                <Link :href="route('messages.index')">
                <MenuItem iconString="Chat" class="mb-2" />
                </Link>

                <Link :href="route('events.index')">
                <MenuItem iconString="Events" class="mb-2" />
                </Link>

                <MenuItem @click="showCreatePost = true" iconString="Post" class="mb-2" />

                <Link :href="route('users.show', { id: $page.props.auth.user.id })">
                <MenuItem iconString="Profile" class="mb-2" />
                </Link>

                <Link :href="route('logout')" as="button" method="post">
                <MenuItem iconString="Logout" class="mb-2" />
                </Link>
            </div>
        </div>

        <div id="MainContainer"
            class="flex lg:justify-between bg-white w-[100%-280px] xl:pl-[280px] lg:pl-[100px] overflow-auto">
            <main id="MainSlot">
                <slot />
            </main>

            <SideContainer :page="$page" :randomUsers="randomUsers" />
        </div>

        <bottom-menu @showCreatePost="showCreatePost = !showCreatePost">
        </bottom-menu>
    </div>

    <CreatePostModal v-if="showCreatePost" @close="showCreatePost = false" />
</template>

<style>
#MainContainer {
    background-color: rgb(233, 233, 233);
    height: 100vh;
}

#MainSection {
    scroll-behavior: smooth;
    scroll-padding-top: 61px;
    scroll-padding-bottom: 61px;
}

/* SideNav should not be scrollable */
#SideNav {
    overflow-y: hidden;
    overscroll-behavior: none;
    background-color: white;
    border-right: 1px solid #e5e5e5;
    min-height: 100vh;
    border-radius: 10px;
}

.tiling-card {
    background-color: #f5f5f5;
    border-radius: 10px;
    border: 1px solid #e5e5e5;
    margin-bottom: 10px;
    margin-right: 10px;
    margin-top: 10px;
    padding: 10px;

}

#Posts {
    scroll-behavior: smooth;
    scroll-padding-top: 61px;
    scroll-padding-bottom: 61px;
    overflow-y: hidden;
    overflow-x: hidden;
    background-color: white;
    min-width: 80%;
    border-radius: 10px;
    border: 1px solid #e5e5e5;
    border-block-width: 2px;
    padding-top: 20px;
    padding-left: 60px;
    padding-right: 60px;
    padding-bottom: 30px;
}

.no-scrolling {
    overflow-x: hidden;
    overflow-y: hidden;
    overscroll-behavior: none;
}

.rounded {
    border-radius: 10px;
}
</style>
