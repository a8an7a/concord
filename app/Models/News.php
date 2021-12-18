<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Database\Capsule\Manager as DB;

class News extends Model
{
    protected $fillable = [
        'news_title',
        'preview_text',
        'preview_image',
        'content',
        'publication_date'
    ];

    public function store($request)
    {
        $data = array(
            'news_title' => $request['news_title'],
            'preview_text' => $request['preview_text'],
            'preview_image' => $request['preview_image'],
            'content' => $request['content'],
            'publication_date' => $request['publication_date']
        );
        
        return DB::table('news')->insertGetId($data);
    }

    public function edit($request)
    {
        $news = News::find($request['id']);

        $news->news_title = $request['news_title'];
        $news->preview_text = $request['preview_text'];
        $news->preview_image = $request['preview_image'];
        $news->content = $request['content'];
        $news->publication_date = $request['publication_date'];

        $news->save();
    }
}