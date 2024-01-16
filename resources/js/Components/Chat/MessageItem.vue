<script setup>
defineProps(["message"]);
</script>

<template>
  <div
    class="relative p-4 w-fit flex flex-col min-w-16 md:max-w-96 "
    :class="{
      'self-end dark:bg-gray-700 dark:hover:bg-gray-800 rounded-l-xl rounded-tr-xl':
        message.user_id === $page.props.auth.user.id,
      'dark:bg-gray-500 dark:hover:bg-gray-700  ':
        message.user_id !== $page.props.auth.user.id,
    }"
  >
    <span
      class="break-words"
      :class="{
        '': message.user_id === $page.props.auth.user.id,
      }"
      >{{ message.content }}</span
    >
    <small class="self-end dark:text-gray-300">{{ message.created_at }}</small>
  </div>
</template>
