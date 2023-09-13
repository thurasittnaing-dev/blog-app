<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Author;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'color', 'thumbnail', 'content', 'publish'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class);
    }
}
