<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{

    // crud
    // create, read, delete, update

    // read
    public function bloglariGetir()
    {
        $tumbloglar = Blog::with(['user', 'comments'])
            ->get();

        return response()->json($tumbloglar);
    }

    // create
    public function blogOlustur(Request $request)
    {
        if (!$request->user()->hasRole('writer')) {
            return "hata";
        }

        $validate = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        $blog = Blog::create([
            'user_id' => $request->user()->id,
            'title' => $validate['title'],
            'content' => $validate['content'],
        ]);

        return response()->json([
            'message' => 'basarili',
            'blog' => $blog,
        ]);
    }
    // select * from blogs where id = :id or 1=1;
    //update
    public function blogGuncelle(Request $request, $id) {
        $validate = $request->validate([
            'title' => 'string',
            'content' => 'string'
        ]);

        $blog = Blog::where('id', $id)->first();

        if (!$blog) {
            return response()->json([
                'message' => "blog bulunamadı.",
            ], 404);
        }

        if ($blog->user_id !== $request->user()->id) {
            return response()->json([
                'message' => "blogu sadece sahibi silebilir.",
            ], 401);
        }

        if ($validate['title']) {
            $blog->title = $validate['title'];
        }

        if ($validate['content']) {
            $blog->content = $validate['content'];
        }

        $blog->save();

        return response()->json([
            'message' => 'basarili'
        ]);
    }

    // delete
    public function blogSil(Request $request)
    {
        $validate = $request->validate([
            'blog_id' => 'required',
        ]);

        $blog = Blog::where('id', $validate['blog_id'])->first();

        if (!$blog || $blog->user_id !== $request->user()->id) {
            return response()->json([
                'message' => "hatalı işlem.",
            ], 404);
        }

        $blog->delete();

        return response()->json([
            'message' => 'basarili'
        ]);
    }
}
