<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue"
import { Head, usePage } from "@inertiajs/vue3"

const friends = usePage().props.auth.user.friends
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard
      </h2>
    </template>

    <section class="py-12 px-5">
      <div class="max-w-xl mx-auto p-4 bg-white rounded-md">
        <h3 class="font-semibold text-gray-800">Friends</h3>
        <hr class="-mx-4 mt-3 mb-6" />
        <div
          v-for="friend in friends"
          :key="friend.id"
          class="my-4 font-medium text-gray-900">
          {{ friend.name }}
        </div>
        <div v-if="friends.length === 0" class="text-sm text-gray-600">
          You don't have any friends yet.
        </div>
      </div>
    </section>
  </AuthenticatedLayout>
</template>
