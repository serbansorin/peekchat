<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserFriends;
use Illuminate\Http\Request;

class UserFriendsController extends Controller
{


    public function render($data = [])
    {
        return inertia('UserFriends', $data);
    }

    /**
     * Follow a user.
     */
    public function follow($id)
    {
        $this->setOrFailUser();
        if (!$this->user->followUser($id)) { // If not found, redirect to home

            // return $this->toHomeIndex();
        }
        return redirect()->back();
    }

    /**
     * Unfollow a user.
     */
    public function unfollow($id)
    {
        $this->setOrFailUser();
        if (!$this->user->unfollowUser($id)) { // If not found, redirect to home

                // return $this->toHomeIndex();
            }
        return redirect()->back();
    }



    /**
     * Create a friend request relationship.
     */
    public function createFriendRequest($id)
    {
        $this->setOrFailUser();
        // make a friend request
        if (!$this->user->createFriendRequest($id)) {

            // If not found, redirect to home
            // return $this->toHomeIndex();
        }
        return redirect()->back();
    }

    /**
     * Accept a friend request relationship.
     */
    public function acceptFriendRequest($id)
    {
        $this->setOrFailUser();
        if (!$this->user->friends()->acceptFriendRequest($id)) {
            // If not found, redirect to home
            // return $this->toHomeIndex();
        }
        return redirect()->back();
    }

    /**
     * Decline a friend request relationship.
     */
    public function denyFriendRequest($id)
    {
        $this->setOrFailUser();
        if (!$this->user->friends()->denyFriendRequest($id)) {
            // If not found, redirect to home
            // return $this->toHomeIndex();
        }
        return redirect()->back();
    }

    /**
     * Block a friend request relationship.
     */
    public function blockFriend($id)
    {
        return $this->denyFriendRequest($id);
    }

    /**
     * Unfriend a friend.
     */
    public function unfriend($id)
    {
        return $this->denyFriendRequest($id);
    }


    /**
     * Display all users followers.
     */
    public function getAllFollowers(UserFriends $userFriends)
    {
        return $this->render(UserFriends::auth()->followers()->get()->toArray());
    }

    /**
     * Dispwlay all users following.
     */
    public function getAllFollowing(UserFriends $userFriends)
    {
        return $this->render(UserFriends::auth()->following()->get()->toArray());
    }

    /**
     * Display all users friends.
     */
    public function getAllFriends(UserFriends $userFriends)
    {
        return $this->render(UserFriends::auth()->friends()->get()->toArray());
    }

    /**
     * Display all users friend requests that the user has received.
     */
    public function getAllFriendRequestsReceived(UserFriends $userFriends)
    {
        return $this->render(UserFriends::auth()->requestFromFriends()->get()->toArray());
    }

    /**
     * Display all friend requests that the user has sent.
     */
    public function getAllFriendRequestsSent(UserFriends $userFriends)
    {
        return $this->render(UserFriends::auth()->requestToFriends()->get()->toArray());
    }

}
