<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = Comment::all();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), ['comment' => 'required|string|max:1000']);

        if ($validator->fails()) {

            return back()->withErrors($validator, 'comment');
        }

        $comment = new Comment();

        $comment->comment = $request->comment;

        $comment->user()->associate($request->user());

        $post->comments()->save($comment);

        return back()->with('success', 'Comment added');

    }


    public function storeReply(Request $request, Post $post){

        $validator = Validator::make($request->all(),
            ['comment' => 'required|string|max:1000']);

        if ($validator->fails()) {

            return back()->withErrors($validator, 'comment');
        }

        $reply = new Comment();

        $reply->comment = $request->comment;

        $reply->user()->associate($request->user());

        $reply->parent_id = $request->comment_id;

        $post->comments()->save($reply);

        return back()->with('success', 'Comment added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post, Comment $comment)
    {
        return view('comment.edit', compact('post', 'comment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Post $post, Comment $comment, Request $request)
    {
        \Gate::authorize('edit-comment', $comment);

        $request->validate(['comment' => 'required|string|max:1000']);

        $comment->comment = $request->comment;

        $comment->save();

        return redirect()->route('post.show', $post->slug)
            ->with('success', 'Comment updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post, $id)
    {
        $comment = Comment::findOrFail($id);

        \Gate::authorize('delete-comment', $comment);

        //$post->comments()->delete();

        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted');
    }


    public function approve($id){

        \Gate::authorize('admin-management');

        $comment = Comment::findOrFail($id);

        $comment->update(['approved'=>true]);

        session()->flash('success', 'Comment approved');

        return redirect('/dashboard#tabs-2');
    }

    public function disapprove($id){

        \Gate::authorize('admin-management');

        $comment = Comment::findOrFail($id);

        $comment->update(['approved'=>false]);

        session()->flash('success', 'Comment disapproved');

        return redirect('/dashboard#tabs-2');
    }
}
