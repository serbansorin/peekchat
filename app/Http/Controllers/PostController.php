<?php

namespace App\Http\Controllers;

use App\Services\FileService;
use Illuminate\Http\Request;
use App\Http\Resources\AllPostsCollection;
use App\Models\Post;
use App\Models\User;
use Inertia\Inertia;


class PostController extends Controller
{

    /**
     * Display Posts page.
     */
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')
            ->whereIn('user_id', function ($q) {
               return $q->select('friend_id')
                    ->from('user_friends')
                    ->where('user_friends.user_id', auth()->user()->id)
                    ->where('user_friends.follow', 1)->pluck('friend_id');
            })->orWhere('user_id', auth()->user()->id)
            ->get();

        return Inertia::render('Home', [
            'posts' => new AllPostsCollection($posts),
            'allUsers' => User::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $post = new Post;
        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png',
            'text' => 'required'
        ]);
        $post = (new FileService)->updateFile($post, $request, 'post');

        $post->user_id = auth()->user()->id;
        $post->text = $request->input('text');
        $post->save();


        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $post = Post::find($id);

        if (!empty($post->file)) {
            $currentFile = public_path() . $post->file;

            if (file_exists($currentFile)) {
                unlink($currentFile);
            }
        }

        $post->delete();
    }
}
