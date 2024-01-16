<script setup>
import MessageInput from "@/Components/Chat/MessageInput.vue";
import MessageItem from "@/Components/Chat/MessageItem.vue";
import { ref, onUpdated, onMounted } from "vue";
import ScrollButton from "@/Components/ScrollButton.vue";
import { get_images_url } from "@/composables/app.js";
const props = defineProps({
  chat_room: {
    type: Object,
    required: true,
  },
  messages: {
    type: Object,
    required: true,
  },
});

const emits = defineEmits(["messageSended"]);
// Messages container reference
const messagesCont = ref(null);

// Variables to show/hide scroll to top/bottom buttons
const scrollInTop = ref(false);
const scrollInBottom = ref(false);

const send = async (message) => {
  //check if message is not empty
  if (message.trim() === "") return;

  await axios.post(route("chat.message.store"), {
    message,
    chat_room_id: props.chat_room.id,
  });

  scrollToBottom();

  emits("messageSended");
};

const scrollToBottom = () => {
  messagesCont.value.scrollTop = messagesCont.value.scrollHeight;
};

const scrollToTop = () => {
  // Set to 1 in order to avoid de function scrollToBotton in onUpdated method
  messagesCont.value.scrollTop = 1;
};

// Run when messages container scroll is updated
const watchScroll = () => {
  scrollInTop.value = !(messagesCont.value.scrollTop > 100);

  scrollInBottom.value =
    messagesCont.value.scrollTop <
    messagesCont.value.scrollHeight - messagesCont.value.clientHeight - 100;
};

// Scroll to bottom when a message enters in container
onUpdated(() => {
  if (messagesCont.value.scrollTop == 0) scrollToBottom();
});

onMounted(() => {
  scrollToBottom();
});
</script>

<template>
  <div class="relative flex flex-col h-full">
    <!-- header -->
    <div class="mb-5 inline-flex items-center font-bold bg-gray-900 p-2 rounded-t gap-2">
      <img
        class="rounded-full max-w-10"
        :src="`${get_images_url()}/default-user.webp`"
        alt="user_image"
      />
      <h2 class="text-2xl">
        {{ chat_room.name }}
      </h2>
    </div>

    <!-- Body -->
    <div
      v-on:scroll="watchScroll()"
      ref="messagesCont"
      class="flex flex-col gap-6 mb-12 px-4 py-6 box-content mb-12 overflow-y-auto overflow-x-hidden"
    >
      <!-- Scroll to top button -->
      <ScrollButton
        :top="true"
        @click.prevent="scrollToTop()"
        v-if="!scrollInTop"
        class="absolute top-20 right-5"
        title="scroll to top"
      />

      <!-- Messages -->
      <MessageItem v-for="message in messages" :key="message.id" :message="message" />

      <!-- Scroll to bottom button-->
      <ScrollButton
        :top="false"
        @click.prevent="scrollToBottom()"
        v-if="scrollInBottom"
        class="absolute bottom-20 right-5"
        title="scroll to bottom"
      />
    </div>

    <!-- Input -->
    <div class="absolute bottom-0 w-full">
      <MessageInput @sendMessage="(message) => send(message)" />
    </div>
  </div>
</template>
