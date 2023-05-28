<script setup lang="ts">
import SecondaryButton from "@/Components/SecondaryButton.vue"
import { useFetch } from "@/fetch"
import type { User } from "@/types"

const props = defineProps<{
  user: User
}>()

const emit = defineEmits<{
  (e: "updated", user: User): void
}>()

const api = useFetch()

async function sendFriendRequest(recipient: User) {
  api
    .call(route("send-friend-request", { recipient: props.user }), {
      method: "POST",
    })
    .then((user) => {
      emit("updated", user as User)
    })
}
</script>

<template>
  <div v-if="user.can_become_friend">
    <span v-if="user.has_friend_request" class="text-sm text-gray-600">
      Your friend request is pending.
    </span>
    <SecondaryButton
      v-else
      type="button"
      :disabled="api.processing.value"
      @click="sendFriendRequest(user)">
      Send friend request
    </SecondaryButton>
  </div>
</template>
