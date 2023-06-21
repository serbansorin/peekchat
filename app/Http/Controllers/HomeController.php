<?php

namespace App\Http\Controllers;

use App\Http\Resources\AllPostsCollection;
use App\Models\Post;
use App\Models\User;
use Inertia\Inertia;

class HomeController extends Controller
{

    public function dashboard()
    {
        $user_id = auth()->user()?->id;
        if ($user_id === null) {
            return redirect()->route('home.index');
        }

        return $this->show($user_id);
    }

}
