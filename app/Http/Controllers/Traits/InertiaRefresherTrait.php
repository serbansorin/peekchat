<?php

namespace App\Http\Controllers\Traits;

use App\Http\Resources\AllPostsCollection;
use App\Models\Post;
use App\Models\User;
use Inertia\Inertia;


trait InertiaRefresherTrait
{
    // Inertia will hydrate the data needed and render them without refreshing the whole page.
    /**
     * @return \Inertia\Response
     */
    public function renderHomeIndex()
    {
        return Inertia::render('Home', [
            'posts' => Post::orderBy('created_at', 'desc')->get()
        ]);
    }

    public function renderData(array $data, $component = 'Home')
    {
        return Inertia::render($component, $data);
    }

    public function renderUserIndex($id)
    {
        $user = User::find($id);
        if ($user === null) {
            return redirect()->route('home.index');
        }

        $posts = Post::where('user_id', $id)->orderBy('created_at', 'desc')->get();

        return Inertia::render('User', [
            'user' => $user,
            'postsByUser' => new AllPostsCollection($posts)
        ]);
    }

}
