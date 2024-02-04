<script setup>
import { ref } from "vue";
defineProps(["modelValue"]);
const emits = defineEmits(["update:modelValue", "sendMessage"]);

const message = ref("");

const send = () => {
  emits("sendMessage", message.value);
  message.value = "";
};
</script>

<template>
  <div class="w-full relative p-1">
    <input
      @keydown.enter="send()"
      placeholder="..."
      autofocus
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      class="w-full rounded dark:bg-gray-700 dark:text-gray-300 border-none focus:outline-none focus:ring-1 focus:ring-indigo-500"
      type="text"
    />
    <svg
      @click.prevent="send()"
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24"
      stroke-width="1.5"
      stroke="currentColor"
      class="w-8 h-8 hover:bg-gray-600 absolute right-3 text-indigo-300 hover:text-indigo-500 cursor-pointer top-2 rounded"
    >
      <path
        stroke-linecap="round"
        stroke-linejoin="round"
        d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"
      />
    </svg>
  </div>
</template>
