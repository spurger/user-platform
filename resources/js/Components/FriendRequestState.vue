<script setup lang="ts">
import PrimaryButton from "./PrimaryButton.vue"
import SecondaryButton from "@/Components/SecondaryButton.vue"
import DangerButton from "./DangerButton.vue"
import type { User } from "@/types"
import { computed } from "vue"
import { useForm, usePage } from "@inertiajs/vue3"

const props = defineProps<{
  user: User
}>()

const authUser = usePage().props.auth.user

const isSelf = computed<boolean>(() => {
  return authUser.id === props.user.id
})

const form = useForm({})

/**
 * Notes: props.user is an other user in the system.
 * Directions are calculated from their view point.
 * For example if we send a friend request to this other user,
 * props.user will have an acceptable friend request.
 * If authenticated user gets a friend request, it will be a sent friend request.
 */

const isFriend = computed<boolean>(() => {
  return props.user.friends.length > 0
})

const hasSentFriendRequest = computed<boolean>(() => {
  return props.user.sentFriendRequests.length > 0
})

const hasAcceptableFriendRequest = computed<boolean>(() => {
  return props.user.acceptableFriendRequests.length > 0
})

const hasFriendRequest = computed<boolean>(() => {
  return hasSentFriendRequest.value || hasAcceptableFriendRequest.value
})

const acceptableRefused = computed<boolean>(() => {
  return (props.user?.acceptableFriendRequests || [])[0]?.refused
})

const sentRefused = computed<boolean>(() => {
  return (props.user?.sentFriendRequests || [])[0]?.refused
})

function sendFriendRequest() {
  if (!hasFriendRequest.value) {
    form.post(route("send-friend-request", { recipient: props.user }))
  }
}

function cancelRequest() {
  if (hasAcceptableFriendRequest.value) {
    form.delete(
      route("cancel-sent-friend-request", {
        friendRequest: props.user.acceptableFriendRequests[0],
      })
    )
  }
}

function accept() {
  if (hasSentFriendRequest.value) {
    form.post(
      route("accept-friend-request", {
        friendRequest: props.user.sentFriendRequests[0],
      })
    )
  }
}

function refuse() {
  if (hasSentFriendRequest.value) {
    form.post(
      route("refuse-friend-request", {
        friendRequest: props.user.sentFriendRequests[0],
      })
    )
  }
}

function removeFriend() {
  if (isFriend.value) {
    form.delete(route("remove-friend", { friend: props.user }))
  }
}
</script>

<template>
  <div
    v-if="!isSelf"
    class="flex flex-wrap items-center gap-x-4 gap-y-2 justify-end">
    <div class="text-sm whitespace-nowrap tracking-tight">
      <span
        v-if="isFriend"
        class="px-2 py-1 bg-emerald-600 text-white rounded-md">
        Friend
      </span>
      <span v-else-if="acceptableRefused" class="text-red-600">
        Your friend request was refused.
      </span>
      <span class="text-amber-700" v-else-if="sentRefused">
        You refused this friend request.
      </span>
      <span v-else-if="hasFriendRequest" class="text-gray-600">
        Your friend request is pending.
      </span>
    </div>
    <div class="inline-flex items-center gap-2">
      <SecondaryButton
        v-if="!isFriend && !hasFriendRequest"
        type="button"
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
        @click="sendFriendRequest">
        Send friend request
      </SecondaryButton>
      <SecondaryButton
        v-if="!isFriend && hasAcceptableFriendRequest && !acceptableRefused"
        type="button"
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
        @click="cancelRequest">
        Cancel
      </SecondaryButton>
      <PrimaryButton
        v-if="!isFriend && hasSentFriendRequest"
        type="button"
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
        @click="accept">
        Accept
      </PrimaryButton>
      <DangerButton
        v-if="!isFriend && hasSentFriendRequest"
        type="button"
        :class="{
          'opacity-25': form.processing || sentRefused,
        }"
        :disabled="form.processing || sentRefused"
        @click="refuse">
        Refuse
      </DangerButton>
      <DangerButton
        v-if="isFriend"
        type="button"
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
        @click="removeFriend">
        Remove
      </DangerButton>
    </div>
  </div>
</template>
