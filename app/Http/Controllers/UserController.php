<?php

namespace App\Http\Controllers;

use App\Events\FriendRequestSent;
use App\Http\Resources\UserResource;
use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->query('name');

        $users = User::when($query, function ($q) use ($query) {
            return $q->where('name', 'like', "%{$query}%");
        })->get();

        return response()->json(UserResource::collection($users), 200);
    }

    public function sendFriendRequest(Request $request, User $recipient)
    {
        /** @var User $user */
        $user = auth()->user();

        $exists = FriendRequest::where([
            'sender_id' => $user->getKey(),
            'recipient_id' => $recipient->getKey(),
        ])->exists();

        if ($exists) {
            throw new \Exception ('Friend request already exists.');
        }

        if ($user->getKey() === $recipient->getKey()) {
            throw new \Exception ('You cannot send a friend request to yourself.');
        }

        // TODO: validation
        // check if both user is friend already

        $friendRequest = FriendRequest::make();
        $friendRequest->sender()->associate($user);
        $friendRequest->recipient()->associate($recipient);
        $friendRequest->save();

        event(new FriendRequestSent($user, $recipient));

        return response()->json(new UserResource($recipient->refresh()), 200);
    }

    public function cancelSentFriendRequest(Request $request, FriendRequest $friendRequest)
    {
        if (!Gate::allows('cancel-friend-request', $friendRequest)) {
            abort(403);
        }

        $friendRequest->delete();

        return response()->json([], 200);
    }
}
