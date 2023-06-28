<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentCreateRequest;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CommAndLikeController extends Controller
{

    public function eventsIndex()
    {
      return Inertia::render('Events');
    }

    /**
     * Store a newly created resource in storage communication.
     */
    public function createLike(Request $request)
    {
        $request->validate(['post_id' => 'required']);
        $like = new Like;

        $like->user_id = auth()->user()->id;
        $like->post_id = $request->input('post_id');
        $like->save();

        return redirect()->route('home.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteLike($id)
    {
        $like = Like::findOrFail($id);
        if (count(collect($like)) > 0) {
            $like->delete();
        }

        return redirect()->route('home.index');
    }

    public function getPostComments($id)
    {
        $post = Post::findOrFail($id);
        $comments = $post->comments()->get();

        return $comments;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function createComment(CommentCreateRequest $request)
    {

        $comment = Comment::create([
            'post_id' => $request->input('post_id'),
            'user_id' => $request->input('user_id'),
            'text' => $request->input('text')
        ]);

        // return dump($comment->with('user')->first()->toArray());

        return response()->json(['comment' => $comment->with('user')->first()->toArray()]);

        // return redirect()->route('home.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteComment($id)
    {
        $comment = Comment::find($id);
        $comment->delete();

        return redirect()->route('home.index');
    }
}
