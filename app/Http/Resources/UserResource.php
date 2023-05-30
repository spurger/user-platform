<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var User $user */
        $user = auth()->user();

        $isSelf = $user->id === $this->id;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->when($isSelf, $this->email),
            'sentFriendRequests' => $this->whenLoaded('sentFriendRequests', function () {
                return FriendRequestResource::collection($this->sentFriendRequests);
            }),
            'acceptableFriendRequests' => $this->whenLoaded('acceptableFriendRequests', function () {
                return FriendRequestResource::collection($this->acceptableFriendRequests);
            }),
            'friends' => $this->whenLoaded('friends', function () {
                return UserResource::collection($this->friends);
            }),
        ];
    }
}
