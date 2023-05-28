<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FriendRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sender' => $this->whenLoaded('sender', function () {
                return new UserResource($this->sender);
            }),
            'recipient' => $this->whenLoaded('recipient', function () {
                return new UserResource($this->recipient);
            }),
            'seen_by_recipient' => $this->when(
                $this->recipient_id === auth()->user()->getAuthIdentifier(),
                $this->seen_by_recipient
            ),
            'created_at' => $this->created_at,
        ];
    }
}
