<script setup>
import { get_chat_room_image, get_default_chat_room_image } from "@/composables/app.js";
import { useForm } from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "../PrimaryButton.vue";
import ChatMembers from "./ChatMembers.vue";
const props = defineProps({
  chat_room: {
    required: true,
    type: Object,
  },
});

const emits = defineEmits(["toogleChatInfo"]);

const chatForm = useForm(props.chat_room);

const scrollToBottom = () => {
  //   messagesCont.value.scrollTop = messagesCont.value.scrollHeight;
};

const deleteImage = () => {
  chatForm.delete(route("chat.image.delete", props.chat_room.id));
};

const changeImage = (event) => {
  let image = event.target.files[0];
  chatForm.image = image;
  if (image) {
    chatForm.post(route("chat.image.store", props.chat_room.id));
  }
};

const updateChat = () => {
  chatForm.post(route("chat.update", props.chat_room.id));
};

defineExpose({ scrollToBottom });
</script>

<template>
  <section class="flex flex-col h-full">
    <!-- header -->
    <header
      class="mb-5 inline-flex items-center font-bold bg-gray-900 p-2 rounded-t gap-2"
    >
      <button
        @click="$emit('toogleChatInfo')"
        title="Back"
        class="hover:bg-gray-700 transition rounded-full p-1"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="w-8 h-8"
          viewBox="0 0 24 24"
          stroke-width="2"
          stroke="currentColor"
          fill="none"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1" />
        </svg>
      </button>
      <h2 class="text-2xl">Chat info</h2>
    </header>
    <!-- Main info -->
    <article class="flex flex-col items-center justify-center p-4">
      <div class="w-full flex gap-2 [&>*]:flex-1">
        <!-- chat picture -->
        <div class="group relative max-w-44 max-h-44">
          <!-- Action buttons -->
          <div
            class="z-20 hidden group-hover:flex items-end justify-center gap-1 absolute inset-0 shadow shadow-gray-400 rounded-full bg-black/40"
          >
            <label
              for="picture-input"
              class="cursor-pointer bg-black/80 hover:bg-black border-gray-300 hover:border py-1 px-2 rounded-lg"
            >
              Editar
            </label>
            <button
              v-if="chat_room.image"
              @click="deleteImage"
              class="bg-black/80 hover:bg-black border-gray-300 hover:border py-1 px-2 rounded-lg"
            >
              Eliminar
            </button>
            <!--Hidden File input -->
            <input
              @change="changeImage($event)"
              type="file"
              name="chat picture"
              id="picture-input"
              class="hidden"
              accept="image/*"
            />
          </div>
          <img
            class="z-10 rounded-full aspect-square object-cover"
            :src="
              chat_room.image
                ? get_chat_room_image(
                    `${chat_room.image?.filename}.${chat_room.image?.extension}`
                  )
                : get_default_chat_room_image()
            "
            alt="user_image"
          />
        </div>

        <!-- Chat settings -->
        <div
          class="shadow-inner overflow-hidden rounded-md bg-gray-700 p-4 flex flex-col"
        >
          <form
            @submit.prevent="updateChat"
            class="flex flex-wrap justify-between items-center"
          >
            <div>
              <InputLabel for="name">Name</InputLabel>
              <TextInput id="name" required v-model="chatForm.name" />
              <InputError class="mt-2" :message="chatForm.errors.name" />
            </div>
            <div>
              <InputLabel for="description">Description</InputLabel>
              <TextInput id="description" required v-model="chatForm.description" />
              <InputError class="mt-2" :message="chatForm.errors.description" />
            </div>

            <div class="w-full mt-4">
              <PrimaryButton type="submit">Save</PrimaryButton>
            </div>
          </form>

          <!-- Members -->
          <div class="mt-6">
            <h3 class="text-xl font-bold text-gray-300">Members</h3>
            <ChatMembers :members="chat_room.users"></ChatMembers>
          </div>
        </div>
      </div>
    </article>
  </section>
</template>
