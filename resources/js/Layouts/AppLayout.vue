<script setup>
import Banner from "@/Components/Banner.vue";
import { Head } from "@inertiajs/vue3";
import Menu from "@/Components/Menu.vue";

defineProps({
  title: String,
});
</script>

<template>
  <div>
    <Head :title="title" />

    <Menu />

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
      <!-- Page Heading -->
      <header v-if="$slots.header" class="bg-white dark:bg-gray-800 shadow">
        <Banner />
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          <slot name="header" />
        </div>
      </header>

      <!-- Page Content -->
      <main>
        <div class="py-12">
          <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
              class="bg-white dark:bg-gray-800 p-4 overflow-hidden shadow-xl sm:rounded-lg dark:text-white"
            >
              <slot name="content" />
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>
