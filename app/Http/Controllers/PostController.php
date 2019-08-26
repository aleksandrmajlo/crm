<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostController extends Controller
{
    public function index(){
        $posts = Post::paginate(10);
        return view('post.posts',[
            'posts'=>$posts,
            'title'=>trans('posts.title'),
            'meta_title'=>trans('posts.meta_title'),
            'meta_description'=>trans('posts.meta_description')
        ]);
    }

    public function post($slug){
        $post = Post::where('slug',$slug)->first();

        $IMAGE_HIDDEN=env("IMAGE_HIDDEN", false);
        $image='/uploads/'.$post->image;
        if($IMAGE_HIDDEN){
            $image=false;
        }

        return view('post.post',[
            'post'=>$post,
            'title'=>$post->title,
            'image'=>$image,
            'meta_title'=>$post->meta_title,
            'meta_description'=>$post->meta_description,
        ]);
    }

}
