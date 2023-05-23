<script setup lang="ts">
import { ref } from "vue"
import InputLabel from "@/Components/InputLabel.vue"
import TextInput from "@/Components/TextInput.vue"
import PrimaryButton from "@/Components/PrimaryButton.vue"
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
</script>

<template>
  <div>
    <form @submit.prevent="search">
      <div>
        <InputLabel for="search_users">Search other users</InputLabel>
        <TextInput
          id="search_users"
          v-model="searchInput"
          type="text"
          class="mt-1 block w-[300px]"
          placeholder="Enter a name..." />
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
    <template v-if="afterSearch && !processing">
      <div v-if="users.length > 0" class="mt-6">
        <div class="text-gray-600 text-sm ml-2">Results</div>
        <div class="my-2 border border-gray-200 rounded-lg">
          <div v-for="(user, index) in users" :key="user.id">
            <div class="p-2 pl-4 text-lg font-medium">
              {{ user.name }}
            </div>
            <hr v-if="index !== users.length - 1" />
          </div>
        </div>
      </div>
      <div v-else class="mt-4">No results found.</div>
    </template>
  </div>
</template>
