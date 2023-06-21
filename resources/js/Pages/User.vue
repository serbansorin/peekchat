<script setup>
import { reactive, toRefs } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3';

import MainLayout from '@/Layouts/MainLayout.vue';
import PostDetails from '@/Components/PostDetails.vue'
import ContentOverlay from '@/Components/ContentOverlay.vue'

import Cog from 'vue-material-design-icons/Cog.vue';
import Grid from 'vue-material-design-icons/Grid.vue';
import PlayBoxOutline from 'vue-material-design-icons/PlayBoxOutline.vue';
import BookmarkOutline from 'vue-material-design-icons/BookmarkOutline.vue';
import AccountBoxOutline from 'vue-material-design-icons/AccountBoxOutline.vue';

let data = reactive({ post: null })
const form = reactive({ file: null })

const props = defineProps({ postsByUser: Object, user: Object, userFriends: Array })
const { postsByUser, user, userFriends } = toRefs(props)


const getUploadedImage = (e) => {
    form.file = e.target.files[0]
    router.post(`/users`, form, {
        preserveState: false
    })
}
</script>

<template>
    <Head title="Instagram" />

    <MainLayout>
        <div class="bg-white rounded-t-md md:mt-20 md:pt-6"></div>
        <div class="max-w-[880px] pb-3 lg:ml-0 md:ml-[80px] md:pl-20 px-4 w-[100vw] bg-white">
            <div class="flex items-center md:justify-between">

                <label for="fileUser">
                    <img
                        class="rounded-full object-fit md:w-[200px] w-[100px] cursor-pointer"
                        :src="user.file"
                    />
                </label>
                <input
                    v-if="user.id === $page.props.auth.user.id"
                    id="fileUser"
                    class="hidden"
                    type="file"
                    @input="getUploadedImage($event)"
                >

                <div class="ml-6 w-full">
                    <div class="flex items-center md:mb-8 mb-5">
                        <div class="md:mr-6 mr-3 rounded-lg text-[22px]">{{ user.name }}</div>
                        <Link :href="route('profile.edit')" class="md:block hidden md:mr-6 p-1 px-4 rounded-lg text-[16px] font-extrabold bg-gray-100 hover:bg-gray-200">
                            Edit Profile
                        </Link>
                        <!-- <Cog :size="28" class="cursor-pointer"/> -->
                    </div>

                </div>

            </div>
        </div>

        <div class="bg-white rounded-b-md max-w-[880px] lg:ml-0 md:ml-[80px] border-t-2 md:pl-20 px-4 w-[100vw]">
            <div class="w-full flex items-center justify-around mt-2">
                <div class="text-center p-3">
                    <div class="font-extrabold">{{ postsByUser.data.length }}</div>
                    <div class="text-gray-400 font-semibold -mt-1.5">Posts</div>
                </div>
                <div class="text-center p-3">
                    <div class="font-extrabold">{{ userFriends.followers }}</div>
                    <div class="text-gray-400 font-semibold -mt-1.5">Followers</div>
                </div>
                <div class="text-center p-3">
                    <div class="font-extrabold">{{ userFriends.following }}</div>
                    <div class="text-gray-400 font-semibold -mt-1.5">Following</div>
                </div>
                <div class="text-center p-3">
                    <div class="font-extrabold">{{ userFriends.friends.length }}</div>
                    <div class="text-gray-400 font-semibold -mt-1.5">Friends</div>
                </div>
            </div>

            <div class="w-full flex items-center justify-between border-t border-t-gray-300">
                <div class="p-3 w-1/4 flex justify-center border-t border-t-gray-900">
                    <Grid :size="28" fillColor="#0095F6" class="cursor-pointer"/>
                </div>
                <div class="p-3 w-1/4 flex justify-center">
                    <PlayBoxOutline :size="28" fillColor="#8E8E8E" class="cursor-pointer"/>
                </div>
                <div class="p-3 w-1/4 flex justify-center">
                    <BookmarkOutline :size="28" fillColor="#8E8E8E" class="cursor-pointer"/>
                </div>
                <div class="p-3 w-1/4 flex justify-center">
                    <AccountBoxOutline :size="28" fillColor="#8E8E8E" class="cursor-pointer"/>
                </div>
            </div>
        </div>

        <div id="ContentSection" class="bg-white rounded-t-md md:pr-1.5 lg:pl-0 md:pl-[90px]">
            <div class="md:block mt-10 hidden border-t border-t-gray-300">
                <div class="flex items-center justify-between max-w-[600px] mx-auto font-extrabold text-gray-400 text-[15px]">
                    <div class="p-[17px] w-1/4 flex justify-center items-center border-t border-t-gray-900">
                        <Grid :size="15" fillColor="#000000" class="cursor-pointer"/>
                        <div class="ml-2 -mb-[1px] text-gray-900">POSTS</div>
                    </div>
                    <div class="p-[17px] w-1/4 flex justify-center items-center">
                        <PlayBoxOutline :size="15" fillColor="#8E8E8E" class="cursor-pointer"/>
                        <div class="ml-2 -mb-[1px] text-gray-900">REELS</div>
                    </div>
                    <div class="p-[17px] w-1/4 flex justify-center items-center">
                        <BookmarkOutline :size="15" fillColor="#8E8E8E" class="cursor-pointer"/>
                        <span class="ml-2 -mb-[1px]">SAVED</span>
                    </div>
                    <div class="p-[17px] w-1/4 flex justify-center items-center">
                        <AccountBoxOutline :size="15" fillColor="#8E8E8E" class="cursor-pointer"/>
                        <span class="ml-2 -mb-[1px]">TAGGED</span>
                    </div>
                </div>
            </div>

            <div class="grid md:gap-4 gap-1 grid-cols-3 relative">
                <div v-for="postByUser in postsByUser.data" :key="postByUser">
                    <ContentOverlay
                        :postByUser="postByUser"
                        @selectedPost="data.post = $event"
                    />
                </div>
            </div>

            <div class="pb-20"></div>
        </div>
    </MainLayout>

    <PostDetails
        v-if="data.post"
        :post="data.post"
        @addComment="addComment($event)"
        @updateLike="updateLike($event)"
        @deleteSelected="
            deleteFunc($event);
        "
        @closeOverlay="data.post = null"
    />
</template>



