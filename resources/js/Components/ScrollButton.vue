<script setup>
import { onUpdated } from "vue";

const props = defineProps(["top", "parentContainer"]);

const scrollToBottom = () => {
  props.parentContainer.scrollTop = props.parentContainer.scrollHeight;
};

const scrollToTop = () => {
  // Set to 1 in order to avoid de function scrollToBotton in onUpdated method
  props.parentContainer.scrollTop = 1;
};

// Scroll to bottom when a message enters in container
onUpdated(() => {
  console.log(props.parentContainer);
  if (props.parentContainer.scrollTop == 0) scrollToBottom();
});
</script>


<template>
  <div
    @click="top ? scrollToTop() : scrollToBottom()"
    class="z-20 rounded-full dark:text-indigo-300 dark:hover:text-gray-600 dark:bg-gray-400/60 dark:hover:bg-indigo-300 cursor-pointer w-fit p-1"
  >
    <svg
      xmlns="http://www.w3.org/2000/svg"
      fill="none"
      viewBox="0 0 24 24"
      stroke-width="1.5"
      stroke="currentColor"
      class="w-6 h-6"
      :class="{ 'rotate-180': !props.top }"
    >
      <path
        stroke-linecap="round"
        stroke-linejoin="round"
        d="m4.5 18.75 7.5-7.5 7.5 7.5"
      />
      <path
        stroke-linecap="round"
        stroke-linejoin="round"
        d="m4.5 12.75 7.5-7.5 7.5 7.5"
      />
    </svg>
  </div>
</template>
