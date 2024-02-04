<script setup>
import MessageInput from "@/Components/Chat/MessageInput.vue";
import MessageItem from "@/Components/Chat/MessageItem.vue";
import { ref, onMounted, onUpdated, watchEffect } from "vue";
import ScrollButton from "@/Components/ScrollButton.vue";
import { get_chat_room_image, get_default_chat_room_image } from "@/composables/app.js";

const props = defineProps({
  chat_room: {
    type: Object,
    required: true,
  },
});

const messages = ref([]);
const message = ref("");
const emits = defineEmits(["toogleChatInfo"]);
// Messages container reference
const messagesCont = ref(null);
// Variables to show/hide scroll to top/bottom buttons
const scrollInTop = ref(false);
const scrollInBottom = ref(false);

const getMessages = async () => {
  messages.value = await axios.get(route("chat.messages.index", props.chat_room.id));
};

const send = async () => {
  //check if message is not empty
  if (message.value.trim() === "") return;

  await axios.post(route("chat.message.store"), {
    message: message.value,
    chat_room_id: props.chat_room.id,
  });

  await getMessages();
  scrollToBottom();
  message.value = "";
};

// Run when messages container scroll is updated
const watchScroll = () => {
  scrollInTop.value = !(messagesCont.value.scrollTop > 100);

  scrollInBottom.value =
    messagesCont.value.scrollTop <
    messagesCont.value.scrollHeight - messagesCont.value.clientHeight - 100;
};

watchEffect(async () => {
  await getMessages();
  scrollToBottom();
});

const scrollToBottom = () => {
  messagesCont.value.scrollTop = messagesCont.value.scrollHeight;
};

defineExpose({ scrollToBottom });
</script>

<template>
  <div class="relative flex flex-col h-full">
    <!-- header -->
    <button
      title="Info"
      @click="$emit('toogleChatInfo')"
      class="mb-5 inline-flex items-center font-bold bg-gray-900 p-2 rounded-t gap-2 hover:bg-gray-900/90 transition cursor-pointer"
    >
      <img
        class="z-10 rounded-full max-w-10 aspect-square object-cover"
        :src="
          chat_room.image
            ? get_chat_room_image(
                `${chat_room.image?.filename}.${chat_room.image?.extension}`
              )
            : get_default_chat_room_image()
        "
        alt="chat_image"
      />
      <h2 class="text-2xl">
        {{ chat_room.name }}
      </h2>
    </button>

    <!-- Body -->
    <div
      v-on:scroll="watchScroll()"
      ref="messagesCont"
      class="flex flex-col gap-6 mb-12 scroll-smooth px-4 py-6 box-content mb-12 overflow-y-auto overflow-x-hidden"
    >
      <!-- Scroll to top button -->
      <ScrollButton
        :top="true"
        :parentContainer="messagesCont"
        v-if="!scrollInTop && messagesCont"
        class="absolute top-20 right-5"
        title="scroll to top"
      />

      <!-- Messages -->
      <MessageItem
        v-for="message in messages.data"
        :key="message.id"
        :message="message"
      />

      <!-- Scroll to bottom button-->
      <ScrollButton
        :top="false"
        :parentContainer="messagesCont"
        v-if="scrollInBottom && messagesCont"
        class="absolute bottom-20 right-5"
        title="scroll to bottom"
      />
    </div>

    <!-- Input -->
    <div class="absolute bottom-0 w-full">
      <MessageInput v-model="message" @sendMessage="send" />
    </div>
  </div>
</template>
