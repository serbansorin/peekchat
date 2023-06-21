<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentCreateRequest;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Messages;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CommAndLikeController extends Controller
{


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


    /**
     * Store a newly created resource in storage.
     */
    public function createComment(CommentCreateRequest $request)
    {

        $comment = Comment::create([
            'post_id' => $request->input('post_id'),
            'user_id' => $request->input('user_id'),
            'text' => $request->input('comment')
        ]);

        return redirect()->route('home.index');
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
