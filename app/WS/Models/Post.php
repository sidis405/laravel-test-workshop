<?php

namespace WS\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['user_id' => 'integer'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
        // return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id');
    }

    // accessord - getters
    // mutators - setters
    public function setTitleAttribute($title)
    {
        $this->attributes['title'] = $title;
        $this->attributes['slug'] = Str::slug($title);
    }

    public function setCoverAttribute($cover)
    {
        $this->attributes['cover'] = $cover->storeAs(
            'covers',
            $cover->getClientOriginalName()
        );
    }
}
