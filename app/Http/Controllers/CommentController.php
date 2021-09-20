<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('admin')->only(['approve', 'disapprove']);
    }

    /**
     * Store a newly created comment on a post.
     *
     * @param Request $request
     * @param Post $post
     * @return RedirectResponse
     */
    public function store(Request $request, Post $post)
    {

        $request->validate(['comment' => 'required|string|max:1000']);

        $comment = new Comment();

        $comment->comment = $request->comment;

        $comment->user()->associate($request->user());

        $post->comments()->save($comment);

        return back()->with('success', 'Comment added');

    }


    /**
     * Store comment as a reply on comment. ID of the parent comment is
     * stored into parent_id column
     *
     * @param Request $request
     * @param Post $post
     * @return RedirectResponse
     */
    public function storeReply(Request $request, Post $post){

        $request->validate(['comment' => 'required|string|max:1000']);

        $reply = new Comment();

        $reply->comment = $request->comment;

        $reply->user()->associate($request->user());

        $reply->parent_id = $request->comment_id;

        $post->comments()->save($reply); //todo check for errors

        return back()->with('success', 'Comment added');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param Post $post
     * @param Comment $comment
     * @return Factory|View
     */
    public function edit(Post $post, Comment $comment)
    {
        \Gate::authorize('edit-comment', $comment);

        return view('comment.edit', compact('post', 'comment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Post $post
     * @param Comment $comment
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Post $post, Comment $comment, Request $request)
    {
        \Gate::authorize('edit-comment', $comment);

        $request->validate(['comment' => 'required|string|max:1000']);

        $comment->comment = $request->comment;

        $comment->save(); //todo check for errors

        return redirect()->route('post.show', $post->slug)
            ->with('success', 'Comment updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Post $post
     * @param $id
     * @return RedirectResponse
     */
    public function destroy(Post $post, $id)
    {
        $comment = Comment::findOrFail($id);

        \Gate::authorize('delete-comment', $comment);

        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted');
    }


    /**
     * Approve comment
     *
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function approve($id){

        \Gate::authorize('admin-management');

        $comment = Comment::findOrFail($id);

        $comment->update(['approved'=>true]);// todo error checking

        session()->flash('success', 'Comment approved');

        return redirect('/dashboard#tabs-2');
    }

    /**
     * Disapprove comment
     *
     * @param $id
     * @return RedirectResponse|Redirector
     */
    public function disapprove($id){

        \Gate::authorize('admin-management');

        $comment = Comment::findOrFail($id);

        $comment->update(['approved'=>false]); //todo error checking

        session()->flash('success', 'Comment disapproved');

        return redirect('/dashboard#tabs-2');
    }
}
