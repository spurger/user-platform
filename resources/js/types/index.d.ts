export interface User {
  id: number
  name: string
  email: string
  can_become_friend: boolean
  has_friend_request: boolean
}

export interface FriendRequest {
  id: number
  sender: User
  recipient: User
}

export interface AuthUser extends User {
  sentFriendRequests: FriendRequest[]
  acceptableFriendRequests: FriendRequest[]
}

export type PageProps<
  T extends Record<string, unknown> = Record<string, unknown>
> = T & {
  auth: {
    user: AuthUser
    csrf_token: string
  }
}
