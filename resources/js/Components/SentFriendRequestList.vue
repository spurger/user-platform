<script setup lang="ts">
import { usePage } from "@inertiajs/vue3"
import SecondaryButton from "./SecondaryButton.vue"
import type { FriendRequest } from "@/types"
import { useFetch } from "@/fetch"

const list = usePage().props.auth.user.sentFriendRequests

const api = useFetch()

function cancelRequest(friendRequest: FriendRequest) {
  api
    .call(route("cancel-sent-friend-request", { friendRequest }), {
      method: "DELETE",
    })
    .then(() => {
      const index = list.findIndex((fr) => friendRequest.id === fr.id)
      if (index > -1) {
        list.splice(index, 1)
      }
    })
}
</script>

<template>
  <div class="max-h-[300px] overflow-y-auto">
    <div
      v-for="(friendRequest, index) in list"
      class="my-4 flex items-center justify-between gap-2">
      <span>{{ friendRequest.recipient.name }}</span>
      <SecondaryButton
        type="button"
        :class="{ 'opacity-25': api.processing.value }"
        :disabled="api.processing.value"
        @click="cancelRequest(friendRequest)">
        Cancel
      </SecondaryButton>
    </div>
  </div>
</template>
