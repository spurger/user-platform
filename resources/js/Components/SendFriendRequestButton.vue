<script setup lang="ts">
import SecondaryButton from "@/Components/SecondaryButton.vue"
import type { User } from "@/types"

defineProps<{
  user: User
  processing: boolean
}>()

defineEmits<{
  (e: "clicked"): void
}>()
</script>

<template>
  <div v-if="user.can_become_friend">
    <span v-if="user.has_friend_request" class="text-sm text-gray-600">
      Your friend request is pending.
    </span>
    <SecondaryButton
      v-else
      type="button"
      :class="{ 'opacity-25': processing }"
      :disabled="processing"
      @click="$emit('clicked')">
      Send friend request
    </SecondaryButton>
  </div>
</template>
