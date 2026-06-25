<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\Category;
// use Illuminate\Container\Attributes\Storage;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class BlogController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth', only: ['create']),
        ];
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $categories = Category::query()->get();
        return view('theme.blogs.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBlogRequest $request)
    {
        $data = $request->validated();

        // image uploading
        // 1- get image
        $image = $request ->image;
        
        // 2- change its current name
        $newImageName = time() . '-' . $image->getClientOriginalName();

        // 3- move image to my project folder (public)
        $image->storeAs('blogs', $newImageName, 'public');

        // 4- save new name in db record
        $data['image'] = $newImageName;
        $data['user_id'] = Auth::user()->id;

        Blog::create($data);
        return back()->with('blogCreateStatus','Your Blog Created Successfully');
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Blog $blog)
    {
        return view('theme.single-blog',compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Blog $blog)
    {

        if($blog->user_id == Auth::id()){
            $categories = Category::get();
            return view('theme.blogs.edit', compact('categories','blog'));
        }
        abort(403);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        if($blog->user_id == Auth::id()){

            $data = $request->validated();
            if($request->hasFile('image'))
            {
                // 0- DELEATE OLD IMAGE
                Storage::disk('public')->delete("blogs/$blog->image");
    
                // image uploading
                // 1- get image
                $image = $request ->image;
                
                // 2- change its current name
                $newImageName = time() . '-' . $image->getClientOriginalName();
        
                // 3- move image to my project folder (public)
                $image->storeAs('blogs', $newImageName, 'public');
                // 4- save new name in db record
                $data['image'] = $newImageName;
            }    
    
            $blog->update($data);
            return back()->with('blogUpdateStatus','Your Blog Updated Successfully');
        }
        abort(403);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Blog $blog)
    {
        if($blog->user_id == Auth::id()){
            $blog->delete();
            return back()->with('blogDeleteStatus','Your Blog Deleted Successfully');
        }
        abort(403);
    }

    public function myBlogs (){
        if(Auth::check()){
            $blogs = Blog::where('user_id',Auth::user()->id)->paginate(5);
            return view('theme.blogs.my-blogs', compact('blogs'));
        }
        abort(403,'Plz Log in to view your blogs');
    } 

}
