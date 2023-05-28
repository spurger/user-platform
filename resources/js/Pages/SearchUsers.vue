<script setup lang="ts">
import { ref } from "vue"
import { Head, useForm } from "@inertiajs/vue3"
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue"
import InputLabel from "@/Components/InputLabel.vue"
import TextInput from "@/Components/TextInput.vue"
import PrimaryButton from "@/Components/PrimaryButton.vue"
import SendFriendRequestButton from "@/Components/SendFriendRequestButton.vue"
import { User } from "@/types"

const props = defineProps<{
  input: string
  users: User[]
}>()

const form = useForm({
  input: props.input,
})

const sendFriendRequestForm = useForm({})

function search() {
  form.get(route("search-users"))
}

function sendFriendRequest(recipient: User) {
  sendFriendRequestForm.post(
    route("send-friend-request", { recipient, input: props.input })
  )
}
</script>

<template>
  <Head title="Search users" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Search users
      </h2>
    </template>

    <section class="py-12 px-5">
      <div class="max-w-3xl mx-auto p-5 bg-white rounded-md">
        <form @submit.prevent="search">
          <div>
            <InputLabel for="search_users">Search other users</InputLabel>
            <TextInput
              id="search_users"
              v-model="form.input"
              type="text"
              class="mt-1 block w-[300px] max-w-full"
              placeholder="Enter a name..." />
            <div class="mt-1 text-xs text-gray-600">
              Searching with empty input field will show every user.
            </div>
            <div class="mt-2">
              <PrimaryButton
                type="submit"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing">
                Search
              </PrimaryButton>
            </div>
          </div>
        </form>
        <div v-if="users.length > 0" class="mt-6">
          <div class="text-gray-600 text-sm ml-2">Results</div>
          <div class="my-2 border border-gray-200 rounded-lg">
            <div v-for="(user, index) in users" :key="user.id">
              <div
                class="p-2 px-4 text-lg font-medium hover:bg-gray-50 flex items-center justify-between">
                <div>{{ user.name }}</div>
                <SendFriendRequestButton
                  :user="user"
                  :processing="sendFriendRequestForm.processing"
                  class="ml-6 text-right"
                  @clicked="sendFriendRequest(user)" />
              </div>
              <hr v-if="index !== users.length - 1" />
            </div>
          </div>
        </div>
        <div v-else class="mt-4 text-gray-600 tracking-tight">
          <span v-if="input">No results found for "{{ input }}".</span>
          <span v-else>Nothing to display.</span>
        </div>
      </div>
    </section>
  </AuthenticatedLayout>
</template>
