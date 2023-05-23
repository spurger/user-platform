<script setup lang="ts">
import { ref } from "vue"
import InputLabel from "@/Components/InputLabel.vue"
import TextInput from "@/Components/TextInput.vue"
import PrimaryButton from "@/Components/PrimaryButton.vue"
import SecondaryButton from "@/Components/SecondaryButton.vue"
import SendFriendRequestButton from "@/Components/SendFriendRequestButton.vue"

import { User } from "@/types"

const searchInput = ref<string>("")

const users = ref<User[]>([])

const afterSearch = ref(false)
const processing = ref(false)

async function search() {
  processing.value = true
  users.value = []
  const response = await fetch(
    route("search-users", { name: searchInput.value })
  )
  users.value = await response.json()
  processing.value = false
  afterSearch.value = true
}

function updateUser(user: User) {
  const indexOf = users.value.findIndex((u) => u.id === user.id)
  if (indexOf > 0) {
    users.value.splice(indexOf, 1, user)
  }
}

function closeSearch() {
  afterSearch.value = false
  users.value = []
}
</script>

<template>
  <div>
    <div class="flex justify-between">
      <form @submit.prevent="search">
        <div>
          <InputLabel for="search_users">Search other users</InputLabel>
          <TextInput
            id="search_users"
            v-model="searchInput"
            type="text"
            class="mt-1 block w-[300px] max-w-full"
            placeholder="Enter a name..." />
          <div class="mt-1 text-xs text-gray-600">
            Searching with empty input field will show every user.
          </div>
          <div class="mt-2">
            <PrimaryButton
              type="submit"
              :class="{ 'opacity-25': processing }"
              :disabled="processing">
              Search
            </PrimaryButton>
          </div>
        </div>
      </form>
      <div v-if="afterSearch" class="shrink flex items-end">
        <SecondaryButton
          type="button"
          :class="{ 'opacity-25': processing }"
          :disabled="processing"
          @click="closeSearch"
          >Close search</SecondaryButton
        >
      </div>
    </div>
    <template v-if="afterSearch && !processing">
      <div v-if="users.length > 0" class="mt-6 max-h-[300px] overflow-y-auto">
        <div class="text-gray-600 text-sm ml-2">Results</div>
        <div class="my-2 border border-gray-200 rounded-lg">
          <div v-for="(user, index) in users" :key="user.id">
            <div
              class="p-2 px-4 text-lg font-medium hover:bg-gray-50 flex items-center justify-between">
              <div>{{ user.name }}</div>
              <SendFriendRequestButton
                :user="user"
                class="ml-6"
                @updated="updateUser" />
            </div>
            <hr v-if="index !== users.length - 1" />
          </div>
        </div>
      </div>
      <div v-else class="mt-4">No results found.</div>
    </template>
  </div>
</template>
