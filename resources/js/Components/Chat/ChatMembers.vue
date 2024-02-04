<script setup>
defineProps(["members"]);
</script>
<template>
  <ul class="flex flex-wrap gap-4 p-2 bg-gray-500 rounded-md">
    <li
      class="inline-flex items-center gap-1 py-1 px-3 bg-gray-400 rounded-lg"
      v-for="(member, index) in members"
      :key="index"
    >
      <span class="cursor-pointer" title="Eliminar usuario">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="w-4 h-4"
          viewBox="0 0 24 24"
          stroke-width="2"
          stroke="currentColor"
          fill="none"
          stroke-linecap="round"
          stroke-linejoin="round"
        >
          <path stroke="none" d="M0 0h24v24H0z" fill="none" />
          <path d="M10 10l4 4m0 -4l-4 4" />
          <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" /></svg
      ></span>
      <span>{{ member.name }}</span>
    </li>
  </ul>
</template>
