<?php

namespace App\Http\Controllers;

// use App\Http\Resources\AllPostsCollection;
// use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
// use Inertia\Inertia;
use App\Http\Controllers\Traits\InertiaRefresherTrait;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    use InertiaRefresherTrait;

    protected User $user;



    protected function setOrFailUser()
    {
        $this->user ?? $this->user = \Auth::user();
        if (!$this->user) {
            return redirect()->route('login');
        }
    }

}
