<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import { ref, onMounted, computed, shallowRef } from "vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import ChatItem from "@/Components/Chat/ChatItem.vue";
import ChatContainer from "@/Components/Chat/ChatContainer.vue";
import ChatInfo from "@/Components/Chat/ChatInfo.vue";
import Minimize from "@/Transitions/Minimize.vue";

const props = defineProps(["chat_rooms"]);

// Variable to show/hide nre room modal
const showModal = ref(false);

const selectedChatId = ref();

const chatContainerRef = ref(null);

// Form to create a chat
const chatForm = useForm({
  name: "",
});

// Variable to switch between components
const currentComponent = shallowRef(ChatContainer);

// Dynamic props to pass to dynamic component
const dynamicProps = computed(() => {
  return currentComponent.value == ChatInfo
    ? {
        props: {
          chat_room: selectedChat.value,
        },
        events: {
          toogleChatInfo: toogleComponentInfo,
        },
      }
    : {
        props: {
          chat_room: selectedChat.value,
        },
        events: {
          toogleChatInfo: toogleComponentInfo,
        },
      };
});

const selectedChat = computed({
  get: () => {
    return props.chat_rooms.find(({ id }) => id === selectedChatId.value);
  },
});

// Function to scroll the chat container to bottom
const scrollToBottom = () => {
  setTimeout(() => {
    if (chatContainerRef.value) chatContainerRef.value.scrollToBottom();
  }, 1000);
};

const createChat = () => {
  chatForm.post(route("chat.store"), {
    onSuccess: () => {
      chatForm.reset();
      showModal.value = false;
    },
  });
};

const selectChat = async (chat) => {
  // Show chat component
  currentComponent.value = ChatContainer;
  // Change chat room
  selectedChatId.value = chat.id;

  if (chatContainerRef.value) chatContainerRef.value.scrollToBottom();
};

onMounted(async () => {
  await selectChat(props.chat_rooms[0]);
});

const toogleComponentInfo = () => {
  if (currentComponent.value == ChatInfo) {
    currentComponent.value = ChatContainer;
    scrollToBottom();
  } else currentComponent.value = ChatInfo;
};
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
          <div
            v-if="chat_rooms && selectedChat"
            class="flex flex-col gap-2 md:w-80 bg-gray-600 p-4 rounded"
          >
            <ChatItem
              v-for="chat in chat_rooms"
              :selected="chat.id === selectedChat.id"
              :key="chat.id"
              :chat_room="chat"
              @selected="selectChat(chat)"
            />
          </div>
          <!-- Chat container -->
          <div v-if="selectedChat" class="flex-1 bg-gray-600 rounded overflow-hidden">
            <Minimize>
              <KeepAlive :max="5" exclude="message">
                <component
                  :is="currentComponent"
                  v-bind.prop="dynamicProps.props"
                  v-on="dynamicProps.events"
                  ref="chatContainerRef"
                />
              </KeepAlive>
            </Minimize>
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
