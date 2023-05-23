<script setup lang="ts">
import SecondaryButton from "@/Components/SecondaryButton.vue"
import type { User } from "@/types"
import { usePage } from "@inertiajs/vue3"
import { ref } from "vue"

const props = defineProps<{
  user: User
}>()

const emit = defineEmits<{
  (e: "updated", user: User): void
}>()

const csrf_token = usePage().props.auth.csrf_token

const processing = ref(false)

async function sendFriendRequest(recipient: User) {
  processing.value = true
  const response = await fetch(
    route("send-friend-request", { recipient: props.user }),
    {
      method: "POST",
      headers: {
        "X-CSRF-Token": csrf_token,
      },
    }
  )
  processing.value = false
  if (response.ok) {
    const user = await response.json()
    emit("updated", user)
  }
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
      :disabled="processing"
      @click="sendFriendRequest(user)">
      Send friend request
    </SecondaryButton>
  </div>
</template>
