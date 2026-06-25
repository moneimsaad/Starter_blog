<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store (StoreCommentRequest $request)
    {
        $data = $request->validated();
        Comment::create($data);
        return redirect()->back()->with('commentCreateStatus', 'Your comment is added successfully');
    }
}
