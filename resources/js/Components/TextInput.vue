<script setup>
import { onMounted, ref, watch } from 'vue';

const props = defineProps(['modelValue', 'classValue']);

defineEmits(['update:modelValue']);

const input = ref(null);
const valueClass = ref('border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm');

// watch for classValue changes and change the class of input
watch(props, () => {
    if (props.classValue) {
        valueClass.value = props.classValue;
    }
});

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        :class="valueClass"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
    />
</template>
