<?php

namespace App\Http\Controllers;

use App\Events\FriendRequestSent;
use App\Http\Resources\FriendRequestResource;
use App\Http\Resources\UserResource;
use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class UserController extends Controller
{
    public function search(Request $request)
    {
        $input = $request->query('input', "");

        if ($request->has('input')) {
            /** @var User $authUser */
            $authUser = auth()->user();

            $users = User::with(['acceptableFriendRequests' => function ($query) use ($authUser) {
                $query->where('sender_id', $authUser->getKey());
            }])->when($input, function ($q) use ($input) {
                $q->where('name', 'like', "%{$input}%");
            })->get();
        } else {
            $users = [];
        }

        return Inertia::render('SearchUsers', [
            'input' => $input,
            'users' => UserResource::collection($users),
        ]);
    }

    public function sentFriendRequests()
    {
        /** @var User $user */
        $user = auth()->user();

        $friendRequests = $user->sentFriendRequests()->with('recipient')->get();

        return Inertia::render('SentFriendRequests', [
            'friendRequests' => FriendRequestResource::collection($friendRequests),
        ]);
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

        return redirect()->route('search-users', [
            'input' => $request->query('input', ''),
        ]);
    }

    public function cancelSentFriendRequest(Request $request, FriendRequest $friendRequest)
    {
        if (!Gate::allows('cancel-friend-request', $friendRequest)) {
            abort(403);
        }

        $friendRequest->delete();

        return redirect()->route('sent-friend-requests');
    }
}
