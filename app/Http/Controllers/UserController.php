<?php

namespace App\Http\Controllers;

use App\Events\FriendRequestSent;
use App\Http\Resources\UserResource;
use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class UserController extends Controller
{
    public function usersAndFriendRequests(Request $request)
    {
        $input = $request->query('input', "");

        if ($request->has('input')) {
            /** @var User $authUser */
            $authUser = auth()->user();

            $users = User::with(['acceptableFriendRequests' => function ($query) use ($authUser) {
                $query->where('sender_id', $authUser->getKey());
            }])->with(['sentFriendRequests' => function ($query) use ($authUser) {
                $query->where('recipient_id', $authUser->getKey());
            }])->when($input, function ($q) use ($input) {
                $q->where('name', 'like', "%{$input}%");
            })->get();
        } else {
            $users = [];
        }

        return Inertia::render('UsersAndFriendRequests', [
            'input' => $input,
            'users' => UserResource::collection($users),
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
        if (!$exists) {
            $exists = FriendRequest::where([
                'sender_id' => $recipient->getKey(),
                'recipient_id' => $user->getKey(),
            ])->exists();
        }

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

        return back();
    }

    public function cancelSentFriendRequest(Request $request, FriendRequest $friendRequest)
    {
        if (!Gate::allows('cancel-friend-request', $friendRequest)) {
            abort(403);
        }

        if ($friendRequest->refused) {
            throw new \Exception ('Cannot cancel this request.');
        }

        $friendRequest->delete();

        return back();
    }

    public function acceptFriendRequest(Request $request, FriendRequest $friendRequest)
    {
        if (!Gate::allows('respond-to-friend-request', $friendRequest)) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            // Save friendRequest as a friend model/pivot

            $friendRequest->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return back();
    }

    public function refuseFriendRequest(Request $request, FriendRequest $friendRequest)
    {
        if (!Gate::allows('respond-to-friend-request', $friendRequest)) {
            abort(403);
        }

        $friendRequest->refused = true;
        $friendRequest->save();

        return back();
    }
}
