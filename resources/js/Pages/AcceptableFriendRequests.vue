<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3"
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue"
import PrimaryButton from "@/Components/PrimaryButton.vue"
import DangerButton from "@/Components/DangerButton.vue"
import type { FriendRequest } from "@/types"

const form = useForm({})

defineProps<{
  friendRequests: FriendRequest[]
}>()

function accept(friendRequest: FriendRequest) {
  form.post(route("accept-friend-request", { friendRequest }))
}

function disableRefuse(friendRequest: FriendRequest) {
  return form.processing || friendRequest.refused
}

function refuse(friendRequest: FriendRequest) {
  form.post(route("refuse-friend-request", { friendRequest }))
}
</script>

<template>
  <Head title="Acceptable friend requests" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Acceptable friend requests
      </h2>
    </template>

    <section class="py-12 px-5">
      <div class="max-w-3xl mx-auto p-5 bg-white rounded-md">
        <table v-if="friendRequests.length > 0">
          <tbody>
            <tr
              v-for="(friendRequest, index) in friendRequests"
              :key="friendRequest.id"
              class="hover:bg-gray-50">
              <td class="w-full p-2">
                <div class="font-bold">{{ friendRequest.sender.name }}</div>
                <div class="text-xs text-gray-600">
                  {{ friendRequest.created_at }}
                </div>
              </td>
              <td class="p-2 flex gap-2 items-center flex-wrap md:flex-nowrap">
                <span
                  v-if="friendRequest.refused"
                  class="text-sm text-gray-600 whitespace-nowrap mr-5">
                  You refused this friend request.
                </span>
                <PrimaryButton
                  type="button"
                  :class="{ 'opacity-25': form.processing }"
                  :disabled="form.processing"
                  @click="accept(friendRequest)">
                  Accept
                </PrimaryButton>
                <DangerButton
                  type="button"
                  :class="{ 'opacity-25': disableRefuse(friendRequest) }"
                  :disabled="disableRefuse(friendRequest)"
                  @click="refuse(friendRequest)">
                  Refuse
                </DangerButton>
              </td>
            </tr>
          </tbody>
        </table>
        <div v-else class="text-center text-gray-600 py-12">
          Nothing to display.
        </div>
      </div>
    </section>
  </AuthenticatedLayout>
</template>
