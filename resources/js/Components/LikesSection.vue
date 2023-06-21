<script setup>
import { computed, ref, toRefs } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

import Heart from 'vue-material-design-icons/Heart.vue';
import HeartOutline from 'vue-material-design-icons/HeartOutline.vue';
import CommentOutline from 'vue-material-design-icons/CommentOutline.vue';
import Comment from 'vue-material-design-icons/Comment.vue';
import SendOutline from 'vue-material-design-icons/SendOutline.vue';
import Send from 'vue-material-design-icons/Send.vue';
import BookmarkOutline from 'vue-material-design-icons/BookmarkOutline.vue';

const props = defineProps(['post'])
const { post } = toRefs(props)

const emit = defineEmits(['comment'])

const user = usePage().props.auth.user

const outlineHeart = computed(() => {
    for (const like of post.value.likes) {
        if (like.user_id === user.id && like.post_id === post.value.id) {
           return true;
        }
    }
    return false;
})

const sendMessageOutline = ref(false);

/**
 * Sends a comment and sets the sendMessageOutline value to true for 5 seconds.
 *
 * @param {Object} post - The post to comment on.
 * @param {Object} user - The user who is making the comment.
 * @return {void}
 */
const sendAndOutlineFor5s = () => {
    // go to chat page
    router.replace('/messages');

    emit('comment', { post, user })
    sendMessageOutline.value = true;
    setTimeout(() => {
        sendMessageOutline.value = false;
    }, 5000)
}

const sendCommentOutline = ref(false);

/**
 * Sends a comment and sets the sendCommentOutline value to true for 5 seconds.
 *
 * @param {Object} post - The post to comment on.
 * @param {Object} user - The user who is making the comment.
 * @return {void}
 */
const sendCommentAndOutlineFor5s = () => {
    emit('comment', { post, user })
    sendCommentOutline.value = true;
    setTimeout(() => {
        sendCommentOutline.value = false;
    }, 5000)
}

const unlike = (id) => {
    router.delete('/likes/' + id, {
            // onFinish: () => updatedPost(object),
        });
};

const like = (post, user) => {
    for (const like of post.likes) {
        if (like.user_id === user.id && like.post_id === post.id) {
            unlike(like.id);
            return;
        }
    }

    router.post('/likes', {
        post_id: post.id,
    }, {
        // onFinish: () => updatedPost(object),
    })

}

</script>

<template>
    <div class="flex z-20 items-center justify-between">
        <div class="flex items-center">
            <button @click="like(post, user)" class="-mt-[14px]">
                <HeartOutline v-if="!outlineHeart" class="pl-3 cursor-pointer" :size="30" />
                <Heart v-else class="pl-3 cursor-pointer" fillColor="#FF0000" :size="30" />
            </button>

            <button @click="sendCommentAndOutlineFor5s()" class="-mt-[14px]">
                <CommentOutline class="pl-3 pt-[10px]" :size="30" v-if="!sendCommentOutline" />
                <Comment v-else class="pl-3 cursor-pointer" fillColor="black" :size="30" />
            </button>

            <button @click="sendAndOutlineFor5s()" class="-mt-[14px]">
                <SendOutline class="pl-3 pt-[10px]" :size="30" v-if="!sendMessageOutline" />
                <Send v-else class="pl-3 cursor-pointer" fillColor="black" :size="30" />
            </button>

        </div>

        <BookmarkOutline class="pl-3 pt-[10px]" :size="30" />
    </div>
</template>
