<script setup>
import { ref, onMounted, toRefs, reactive } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

import LikesSection from '@/Components/LikesSection.vue';
import PostDetails from '@/Components/PostDetails.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';

import DotsHorizontal from 'vue-material-design-icons/NoteRemoveOutline.vue';

const wWidth = ref(window.innerWidth)
const currentSlide = ref(0)
const currentPost = ref(null);
const openOverlay = ref(false)

const props = defineProps({ posts: Object, allUsers: Object });

const { posts, allUsers } = toRefs(props)

// remeber router
router.remember(
    'allUsers',
);

console.log(_.isNull(allUsers.value));

// const allPosts = ref(posts.value.data);

const allPosts = ref(props.posts.data);
console.log(allPosts.value);

const user = usePage().props?.auth?.user;


const deletePost = (object) => {
    router.delete('/posts/' + object.id, {
        // onFinish: () => updatedPost(object),
    });
};

// const deletePostConfirm = (object) => {
//     openModalDeletePost(false);
//     deletePost(object);
// };

const confirmModal = ref({
    show: false,
    text: 'delete post?',
    title: 'Delete Post',
});

const alertIfUserIsNotPostOwner = (post) => {
    if (user.id !== post.user.id) {
        alert('You are not the owner of this post');
        return false;
    }
    return true;
};

const openModalDeletePost = (show, post = null) => {
    if (!alertIfUserIsNotPostOwner(post)) {
        return;
    }

    confirmModal.value.show = show;
    confirmModal.value.title = 'Delete Post';
    confirmModal.value.text = 'You are about to delete this post. Are you sure?';
    if (post !== null) {
        currentPost.value = post;
    }
};

// emit cancel
const cancel = () => {
    openModalDeletePost(false);
}

// emit confirm
const confirm = () => {
    openModalDeletePost(false);
    deletePost(currentPost.value);
}

</script>

<template>
    <Head title="Instagram" />

    <main-layout>
        <div class="mx-auto lg:pl-0 md:pl-[80px] pl-0">

           <CarouselUsers :currentSlide="currentSlide"
           :allUsers="allUsers"
           />

            <div id="Posts" class="px-4 max-w-[600px] mx-auto mt-10" v-for="post in posts.data" :key="post.id">
                <div class="flex items-center justify-between py-2">
                    <div class="flex items-center">
                        <Link :href="route('users.show', { id: post.user.id })" class="flex items-center">
                        <img class="rounded-full w-[38px] h-[38px]" :src="post.user.file">
                        <div class="ml-4 font-extrabold text-[15px]">{{ post.user.name }}</div>
                        </Link>
                        <div class="flex items-center text-[15px] text-gray-500">
                            <span class="-mt-5 ml-2 mr-[5px] text-[35px]">.</span>
                            <div>{{ post.created_at }}</div>
                        </div>
                    </div>

                    <!-- TODO: add menu to change post -->
                <DotsHorizontal @click="openModalDeletePost(true, post)" class="cursor-pointer" :size="27" />
                <ConfirmModal :show="confirmModal.show" :title="confirmModal.title" :text="confirmModal.text" @cancel="cancel" @close="cancel" @confirm="confirm" />
                </div>

                <div class="bg-black rounded-lg w-full min-h-[400px] flex items-center">
                    <img class="mx-auto w-full max-h-[600px] object-cover" :src="post.file" />
                </div>

                <LikesSection :post="post" @comment="addComment($event); openOverlay = true" />

                <div class="text-black font-extrabold py-1">{{ post.likes.length }} likes</div>
                <div>
                    <span class="text-black font-extrabold">{{ post.user.name }}</span>
                    {{ post.text }}
                </div>
                <button class="text-gray-500 font-extrabold py-1" @click="openOverlay = true; currentPost=post">
                    View all {{ post.comments.length }} comments
                </button>


                <PostDetails :post="currentPost" :posts="posts" @closeOverlay="openOverlay = false"
                    :openOverlay="openOverlay">
                </PostDetails>


            </div>

            <div class="pb-20"></div>
        </div>
    </main-layout>
</template>


