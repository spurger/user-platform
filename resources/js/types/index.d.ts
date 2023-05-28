export interface User {
  id: number
  name: string
  email: string
  sentFriendRequests: FriendRequest[]
  acceptableFriendRequests: FriendRequest[]
}

export interface FriendRequest {
  id: number
  sender: User
  recipient: User
  refused: boolean
  created_at: Date
}

export type PageProps<
  T extends Record<string, unknown> = Record<string, unknown>
> = T & {
  auth: {
    user: User
  }
}
