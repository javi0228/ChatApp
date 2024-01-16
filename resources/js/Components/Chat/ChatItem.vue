<script setup>
defineProps({
  chat_room: {
    type: Object,
    required: true,
  },
  selected: {
    type: Boolean,
    required: false,
  },
});

defineEmits(["selected"]);
</script>

<template>
  <div
    :class="{ 'bg-gray-200 text-indigo-600': selected,'hover:bg-gray-400 cursor-pointer':!selected }"
    class="flex p-2 rounded transition duration-200 ease-in-out"
    @click.prevent="$emit('selected', chat_room)"
  >
    <p>{{ chat_room.name }}</p>
  </div>
</template>
