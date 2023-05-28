import { ref } from "vue"
import { usePage } from "@inertiajs/vue3"
import { get, set } from "lodash-es"

export function useFetch() {
  const processing = ref(false)

  const csrf_token = usePage().props.auth.csrf_token

  async function call(input: RequestInfo | URL, init?: RequestInit) {
    return new Promise(async (resolve, reject) => {
      processing.value = true

      if (!init) {
        init = {}
      }

      if (!get(init, "headers.X-CSRF-Token")) {
        set(init, "headers.X-CSRF-Token", csrf_token)
      }

      const response = await fetch(input, init)

      processing.value = false

      if (response.ok) {
        const data = await response.json()
        resolve(data)
      } else {
        reject(response)
      }
    })
  }

  return {
    processing,
    call,
  }
}
