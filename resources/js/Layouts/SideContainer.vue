<template>
    <div v-if="$page.url === '/'" id="SuggestionsSection"
        class="lg:w-4/12 lg:block hidden text-black mt-4 pt-10 pl-10 mr-3 rounded-lg bg-white">
        <Link :href="route('users.show', { id: $page.props.auth.user.id })"
            class="flex items-center justify-between max-w-[300px]">
        <div class="flex items-center">
            <img class="rounded-full z-10 w-[58px] h-[58px]" :src="$page.props.auth.user.file" />
            <div class="pl-4">
                <div class="text-black font-extrabold">
                    {{ $page.props.auth.user.name }}
                </div>
                <div class="text-gray-500 text-extrabold text-sm">
                    {{ $page.props.auth.user.name }}
                </div>
            </div>
        </div>
        <button class="text-blue-500 hover:text-gray-900 text-xs font-extrabold">
            Switch
        </button>
        </Link>

        <div class="max-w-[300px] flex items-center justify-between py-3">
            <div class="text-gray-500 font-extrabold">Suggestions for you</div>
            <button class="text-blue-500 hover:text-gray-900 text-xs font-extrabold">
                See All
            </button>
        </div>

        <div v-for="randUser in randomUsers" :key="randUser">
            <Link :href="route('users.show', { id: randUser.id })"
                class="flex items-center justify-between max-w-[300px] pb-2" >
            <div class="flex items-center">
                <img class="rounded-full z-10 w-[37px] h-[37px]" :src="randUser.file" />
                <div class="pl-4">
                    <div class="text-black font-extrabold">{{ randUser.name }}</div>
                    <div class="text-gray-500 text-extrabold text-sm">Suggested for you</div>
                </div>
            </div>
            <button @click="followRequest(randUser.id)" class="text-blue-500 hover:text-gray-900 text-xs font-extrabold">
                Follow
            </button>
            <button @click="friendRequest(randUser.id)" class="text-blue-500 hover:text-gray-900 text-xs font-extrabold">
                Friend Request
            </button>

            </Link>
        </div>
    </div>
</template>

<script setup>
import { toRefs } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps(["randomUsers", "page"]);

// const $page = toRefs(props.page);

const followRequest = (id) => {
    router.post(
        route("users.friends.follow", {id: id}),
        {
            preserveState: true,

            onSuccess: () => {
                console.log("Follow Request Sent");
            }
        }
    );
};

const friendRequest = (id) => {
    router.post(
        route("users.friends.request", {id: id}),
        {
            preserveState: true,

            onSuccess: () => {
                console.log("Friend Request Sent");
            }
        }
    );
};


// const { $page, route, randomUsers } = toRefs(props);
</script>
