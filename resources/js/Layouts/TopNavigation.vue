<template>
    <div v-if="route().current('home.index')" id="TopNavHome"
        class="fixed z-30 block w-full bg-white h-[61px] border-b border-b-gray-300">
        <!-- <Link href="/" class="px-4">
        <ChevronLeft :size="30" class="cursor-pointer" />
        </Link> -->
        <div class="flex items-center justify-between h-full">
            <Link href="/" class="w-[105px] ml-6 cursor-pointer">
            <img src="/logo-plain.svg" viewbox="0 0 80 80" width="80" height="80"/>
            </Link>

            <div class="flex items-center w-[50%]">
                <div class="flex items-center w-full bg-gray-100 rounded-lg">
                    <Magnify class="pl-4" fillColor="#8E8E8E" :size="27" />
                    <input type="text" placeholder="Search"
                        class="bg-transparent w-full placeholder-[#8E8E8E] border-0 ring-0 focus:ring-0" />
                </div>

            </div>
        </div>
    </div>

    <div
      v-else
      id="TopNavUser"
      class="fixed flex items-center justify-between z-30 w-full bg-white h-[61px] border-b border-b-gray-300"
    >
      <Link href="/" class="px-4">
        <ChevronLeft :size="30" class="cursor-pointer" />
      </Link>
      <div class="font-extrabold text-lg">{{ $page.props.auth.user.name }}</div>
      <AccountPlusOutline :size="30" class="cursor-pointer px-4" />
    </div>
</template>

<script setup>

import { toRefs } from "vue";

import Magnify from "vue-material-design-icons/Magnify.vue";
// import HomeOutline from "vue-material-design-icons/HomeOutline.vue";
// import Compass from "vue-material-design-icons/Compass.vue";
// import SendOutline from "vue-material-design-icons/SendOutline.vue";
// import Plus from "vue-material-design-icons/Plus.vue";
// import AccountOutline from "vue-material-design-icons/AccountOutline.vue";
import ChevronLeft from "vue-material-design-icons/ChevronLeft.vue";
import AccountPlusOutline from "vue-material-design-icons/AccountPlusOutline.vue";
import { Link } from "@inertiajs/vue3";

// const props = defineProps();
// const { } = toRefs(props);
</script>
