<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import { ref, onMounted } from "vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import ChatItem from "@/Components/Chat/ChatItem.vue";
import ChatContainer from "@/Components/Chat/ChatContainer.vue";

const props = defineProps(["chat_rooms"]);

const showModal = ref(false);
const selectedChat = ref();
const messages = ref([]);

const chatForm = useForm({
  name: "",
});

const createChat = () => {
  chatForm.post(route("chat.store"), {
    onSuccess: () => {
      chatForm.reset();
      showModal.value = false;
    },
  });
};

const selectChat = async (chat) => {
  selectedChat.value = chat;
  await getMessages(chat);
};
const getMessages = async (chat) => {
  messages.value = await axios.get(route("chat.messages.index", chat.id));
};

onMounted(async() => {
 await selectChat(props.chat_rooms[0]);
});
</script>
<template>
  <AppLayout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        chats
      </h2>
    </template>

    <template #content>
      <div>
        <!-- Create chat room button -->
        <PrimaryButton type="button" @click.prevent="showModal = true">
          Create chat room
        </PrimaryButton>

        <!-- content -->
        <div class="flex gap-4 mt-4 md:max-h-[600px] md:min-h-[600px]">
          <!-- Chat rooms list -->
          <div v-if="chat_rooms && selectedChat" class="flex flex-col gap-2 md:w-80 bg-gray-600 p-4 rounded">
            <ChatItem
              v-for="chat in chat_rooms"
              :selected="chat.id === selectedChat.id"
              :key="chat.id"
              :chat_room="chat"
              @selected="selectChat(chat)"
            />
          </div>
          <!-- Chat container -->
          <div v-if="selectedChat && messages.data" class="grow bg-gray-600 rounded overflow-hidden">
            <ChatContainer
              @messageSended="getMessages(selectedChat)"
              :chat_room="selectedChat"
              :messages="messages.data"
            />
          </div>
        </div>
      </div>

      <!-- Create chat room modal -->
      <Modal :show="showModal" @close="showModal = false">
        <!-- Header -->
        <div class="bg-gray-700 p-6 text-white border-b border-gray-800">
          <h2 class="text-xl uppercase font-bold">Create chat room</h2>
        </div>

        <!-- Body -->
        <div class="p-6">
          <InputLabel for="name">Name</InputLabel>
          <TextInput id="name" required v-model="chatForm.name" />
          <InputError class="mt-2" :message="chatForm.errors.name" />
        </div>

        <!-- Footer -->
        <div class="flex justify-center p-6 gap-4">
          <PrimaryButton type="button" @click.prevent="createChat()"
            >Create</PrimaryButton
          >
          <PrimaryButton type="button" @click.prevent="showModal = false"
            >Cancel</PrimaryButton
          >
        </div>
      </Modal>
    </template>
  </AppLayout>
</template>
