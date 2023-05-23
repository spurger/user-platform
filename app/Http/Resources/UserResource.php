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
            'can_become_friend' => !$isSelf,
            'has_friend_request' => $this->acceptableFriendRequests()
                ->where(['sender_id' => $user->id])->exists(),
            'acceptableFriendRequests' => $this->acceptableFriendRequests,
        ];
    }
}
