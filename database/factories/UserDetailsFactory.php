<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserDetails>
 */
class UserDetailsFactory extends Factory
{
    public $user;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->setUserIdThatExists();
        $user = $this->user;

        return [
           // user details will have a user_id, first name, last name, email, following, followers_count, posts_count, likes_count, comments_count, profile_pic
           'user_id' => $user->id,
           'first_name' => $user->first_name,
           'last_name' => $user->last_name,
           'email' => $user->email,

        ];
    }

    public function setUserIdThatExists($user_id = null, $nextUser = null) 
    {
        $userDetails_id = UserDetails::orderBy('id', 'desc')->first()->user_id ?? 1;

        // random
        $user = $nextUser ?? User::where('id', $userDetails_id)->first();

        $nextUser = User::find($user->id + 1);

        if ($user && empty($nextUser)) {

            $this->user = $nextUser;
            return;
        } else {
           return $this->getUserIdThatExists($nextUser->id + 1, $nextUser);
        }

    }
}
