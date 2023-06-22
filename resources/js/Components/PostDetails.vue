<script setup>
import { computed, watch, ref, toRefs, reactive } from 'vue'
import { usePage, router } from '@inertiajs/vue3'

import ShowPostOptionsModal from '@/Components/ShowPostOptionsModal.vue'
import LikesSection from '@/Components/LikesSection.vue'

import Close from 'vue-material-design-icons/Close.vue';
import DotsHorizontal from 'vue-material-design-icons/NoteRemoveOutline.vue';
import CommentRemoveOutline from 'vue-material-design-icons/CommentRemoveOutline.vue';
import EmoticonHappyOutline from 'vue-material-design-icons/EmoticonHappyOutline.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';

const typedText = ref('')

const user = usePage().props.auth.user;

const props = defineProps({
    post: Object,
    openOverlay: Boolean,
    posts: Object,
});

const { post, openOverlay, posts } = toRefs(props);


// const reactivePost = reactive(post.value);

// MODAL
const modal = computed(() => props.openOverlay).value;

console.log(modal, 'Modal');

const showModal = ref(openOverlay.value);
// add watcheffect for openOverlay props
watch(openOverlay, (value) => {
    showModal.value = value;
    console.log(showModal.value, 'showModal');
})

const emit = defineEmits(['closeOverlay', 'updateLike', 'confirmDeleteComment', 'confirmDeletePost']);


// COMMENTS SECTION

const showDeleteCommentModal = ref(false);

const deleteComment = (comment) => {
    showDeleteCommentModal.value = false;
    router.delete(`/comments/${comment.id}`, {
        only: () => ['comments', 'posts'],
        onFinish: () => removeCommentFromPost(comment),
    });
};

const addComment = (comment) => {
    axios.post('/comments', {
        post_id: comment.post.id,
        user_id: comment.user.id,
        text: typedText.value
    }).then((res) => {

        if (res.status === 200) {
            appendCommentToPost(res.data.comment);
            typedText.value = '';
            // post.value.comments = res.data.comments;
            console.log(res.data, 'POST');
        }
    })
}

const appendCommentToPost = (comment) => {
    post.value.comments.push({
        id: comment.id,
        text: typedText.value,
        user: comment.user
    });
}

const removeCommentFromPost = (comment) => {
    post.value.comments = post.value.comments.filter(
        (c) => c.id !== comment.id
    )
}



// TEXTAREA SECTION
const textarea = ref('');

const textareaInput = (e) => {
    // textarea.value.style.height = "auto";
    textarea.value.style.height = `${e.target.scrollHeight}px`;
}
</script>

<template>
    <div v-if="showModal">
        <div id="OverlaySection" class="fixed z-50 top-0 left-0 w-full h-screen bg-[#000000] bg-opacity-60 p-3">

            <button class="absolute right-3" @click="() => emit('closeOverlay')">
                <Close :size="27" fillColor="#FFFFFF" />
            </button>

            <div class="max-w-6xl h-[calc(100%-100px)] mx-auto mt-10 bg-white rounded-xl">
                <div class="w-full md:flex h-full overflow-auto rounded-xl">
                    <div class="flex items-center bg-black w-full">
                        <img class="rounded-xl min-w-[400px] max-h-[100%] object-cover p-4 mx-auto" :src="post?.file">
                    </div>

                    <div class="md:max-w-[500px] w-full relative">
                        <div class="flex items-center justify-between p-3 border-b">
                            <div class="flex items-center">
                                <img class="rounded-full w-[38px] h-[38px]" :src="post.user.file">
                                <div class="ml-4 font-extrabold text-[15px]">{{ post.user.name }}</div>
                                <div class="flex items-center text-[15px] text-gray-500">
                                    <span class="-mt-5 ml-2 mr-[5px] text-[35px]">.</span>
                                    <div>{{ post.created_at }}</div>
                                </div>
                            </div>
                            <button v-if="user.id === post.user.id">
                                <DotsHorizontal class="cursor-pointer" :size="27"
                                    @click="emit('deletePost', { post: post })" />
                            </button>
                        </div>

                        <div class="overflow-y-auto h-[calc(100%-170px)]">
                            <div class="flex items-center justify-between p-3">
                                <div class="flex items-center relative">
                                    <img class="absolute -top-1 rounded-full w-[38px] h-[38px]" :src="post.user.file">
                                    <div class="ml-14">
                                        <span class="font-extrabold text-[15px] mr-2">
                                            {{ post.user.name }}
                                        </span>
                                        <span class="text-[15px] text-gray-900">
                                            {{ post.text }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div v-if="post.comments.length > 0">

                                <div class="p-3" v-for="comment in post.comments" :key="comment">

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <img class="rounded-full w-[38px] h-[38px]" :src="comment.user.file">
                                            <div class="ml-4 font-extrabold text-[15px]">
                                                {{ comment.user.name }}
                                                <span class="font-light text-gray-700 text-sm">{{ post.created_at }}</span>
                                            </div>
                                        </div>

                                        <CommentRemoveOutline v-if="user.id === comment.user.id" class="cursor-pointer"
                                            @click="showDeleteCommentModal = true" :size="27"
                                        />

                                        <div id="modal-transition" v-show="showDeleteCommentModal">

                                            <ConfirmModal text="You are about to delete this comment. Are you sure?"
                                                :show="showDeleteCommentModal" @confirm="deleteComment(comment)"
                                                @cancel="showDeleteCommentModal = false"
                                                @close="showDeleteCommentModal = false"
                                            />
                                        </div>

                                    </div>

                                    <div class="text-[13px] pl-[55px]">
                                        {{ comment.text }}
                                    </div>
                                </div>

                            </div>

                            <div class="pb-16 md:hidden"></div>
                        </div>

                        <LikesSection :post="post" />

                        <!-- <LikesSection v-if="post" class="px-2 border-t mb-2" :post="post"
                                @like="$emit('updateLike', $event)" /> -->

                        <div class="absolute flex border bottom-0 w-full max-h-[200px] bg-white overflow-auto">
                            <EmoticonHappyOutline class="pl-3 pt-[10px]" :size="30" />
                            <textarea ref="textarea" :oninput="textareaInput" v-model="typedText"
                                placeholder="Add a comment..." rows="1" class="
                                                        w-full
                                                        border-0
                                                        mt-2
                                                        mb-2
                                                        text-sm
                                                        z-50
                                                        focus:ring-0
                                                        text-gray-600
                                                        text-[18px]
                                                    "></textarea>
                            <button v-if="typedText.length" class="text-blue-600 font-extrabold pr-4"
                                @click="addComment({ post, user})">
                                Post
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<style scoped>
.modal-transition {
    transition: all 0.3s ease-in-out;
}</style>
