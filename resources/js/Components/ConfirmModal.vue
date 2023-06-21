<script setup>
import { toRefs, watch } from "vue";
import Modal from "@/Components/Modal.vue";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: "2xl",
    },
    closeable: {
        type: Boolean,
        default: true,
    },
    text: {
        type: String,
        default: "delete this post",
    }
});

const emit = defineEmits(["cancel", "confirm", "close"]);
const { show, maxWidth, closeable } = toRefs(props);

const close = () => {
    emit("close");
}


</script>

<template>
    <Modal :show="show"
        :maxWidth="maxWidth"
        :closable="closeable"
        title="Confirm"
        @cancel="emit('cancel')"
        @confirm="emit('confirm')"
        @close="close"
        >
        <!-- <div class="flex"> -->

            <div class="text-center">
                <p class="text-lg"> {{ text }}?</p>
                <p class="text-sm text-gray-500">Please confirm.</p>
            </div>
            <div class="flex justify-center mt-4">
                <button
                    class="w-full p-3 text-lg text-red-600 border-b border-b-gray-300 cursor-pointer"
                    @click="emit('cancel')"
                > Cancel </button>
                <button
                    class="w-full p-3 text-lg text-red-600 border-b border-b-gray-300 cursor-pointer"
                    @click="emit('confirm')"
                > Ok </button>
            </div>
        <!-- </div> -->
    </Modal>
</template>
