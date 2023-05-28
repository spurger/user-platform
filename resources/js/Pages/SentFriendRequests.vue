<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3"
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue"
import SecondaryButton from "@/Components/SecondaryButton.vue"
import type { FriendRequest } from "@/types"

const form = useForm({})

defineProps<{
  friendRequests: FriendRequest[]
}>()

function cancelRequest(friendRequest: FriendRequest) {
  form.delete(route("cancel-sent-friend-request", { friendRequest }))
}
</script>

<template>
  <Head title="Sent friend requests" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Sent friend requests
      </h2>
    </template>

    <section class="py-12 px-5">
      <div class="max-w-xl mx-auto p-5 bg-white rounded-md">
        <table v-if="friendRequests.length > 0">
          <tbody>
            <tr
              v-for="(friendRequest, index) in friendRequests"
              :key="friendRequest.id"
              class="hover:bg-gray-50">
              <td class="w-full p-2">
                {{ friendRequest.recipient.name }}
              </td>
              <td class="p-2">
                <SecondaryButton
                  type="button"
                  :class="{ 'opacity-25': form.processing }"
                  :disabled="form.processing"
                  @click="cancelRequest(friendRequest)">
                  Cancel
                </SecondaryButton>
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
