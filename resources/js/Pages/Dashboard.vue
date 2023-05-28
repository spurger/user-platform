<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue"
import { Head } from "@inertiajs/vue3"
import SearchUsers from "./SearchUsers.vue"
import SentFriendRequestList from "@/Components/SentFriendRequestList.vue"
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard
      </h2>
    </template>

    <div class="py-12">
      <div class="px-5 sm:px-6 lg:px-8 flex flex-wrap gap-5">
        <div class="flex-[9999] bg-white shadow-sm sm:rounded-lg">
          <SearchUsers class="p-4"></SearchUsers>
        </div>
        <div class="flex-1 min-w-[300px] bg-white shadow-sm sm:rounded-lg">
          <h3 class="text-lg font-medium text-gray-600 p-4 whitespace-nowrap">
            Sent friend requests
          </h3>
          <hr />
          <div class="p-4">
            <SentFriendRequestList />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
