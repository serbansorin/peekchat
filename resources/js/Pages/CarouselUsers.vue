<template>
    <Carousel class="max-w-[700px] mx-auto" :settings="settings" :breakpoints="breakpoints" :pagination="false" :wrap-around="true" :transition="800" snapAlign="start">

        <Slide v-for="slide in allUsers" :key="slide.id">
            <Link :href="route('users.show', { id: slide.id })"
                class="relative mx-auto text-center mt-4 px-2 cursor-pointer">
            <div
                class="absolute z-[-1] -top-[5px] left-[4px] rounded-full rotate-45 w-[64px] h-[64px] contrast-[1.3]  bg-gradient-to-t from-yellow-300 to-purple-500 via-red-500">
                <div class="rounded-full ml-[3px] mt-[3px] w-[58px] h-[58px] bg-white" />
            </div>
            <img class="rounded-full w-[56px] h-[56px] -mt-[1px]" :src="slide.file">
            <div class="text-xs mt-2 w-[60px] truncate text-ellipsis overflow-hidden">{{ slide.name }}</div>
            </Link>
        </Slide>

        <template #addons>
            <Navigation />
        </template>

    </Carousel>
</template>


<style>
.carousel__prev,
.carousel__next,
.carousel__prev:hover,
.carousel__next:hover {
    color: rgb(49, 49, 49);
    background-color: rgb(255, 255, 255);
    border-radius: 100%;
    margin: 0 20px;
}
</style>


<script setup>
import { toRefs, onMounted } from "vue";

const props = defineProps(["allUsers", "breakpoints"]);

const settings = {
    itemsToShow: 1,
    snapAlign: "center"
};

onMounted(() => {
    window.addEventListener('resize', () => {
        wWidth.value = window.innerWidth
    })
});

// const { currentSlide, wWidth, allUsers, route } = toRefs(props);

/*
{
    components: {
        Carousel: VueCarousel.Carousel,
        Pagination: VueCarousel.Pagination,
        Navigation: VueCarousel.Navigation,
        Slide: VueCarousel.Slide
    },
    setup() {
        return {
            // carousel settings
            settings: {
                itemsToShow: 1,
                snapAlign: "center"
            },
            // breakpoints are mobile first
            // any settings not specified will fallback to the carousel settings
            breakpoints: {
                // 700px and up
                700: {
                    itemsToShow: 3.5,
                    snapAlign: "center"
                },
                // 1024 and up
                1024: {
                    itemsToShow: 5,
                    snapAlign: "start"
                }
            }
        };
    }
    */

</script>
