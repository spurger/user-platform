export interface User {
  id: number
  name: string
  email: string
  can_become_friend: boolean
  has_friend_request: boolean
}

export type PageProps<
  T extends Record<string, unknown> = Record<string, unknown>
> = T & {
  auth: {
    user: User
    csrf_token: string
  }
}
