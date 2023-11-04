<?php

namespace App\Models\Blog;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class BlogUser extends Model
{
    use HasFactory;
    protected $connection = 'blog_db';
    protected $table = 'users';
    protected $fillable = [
        'post_id',
        'path',
        'alt_text',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    protected static function boot(): void
    {
        parent::boot();

        static::deleted(fn (Photo $photo) => Storage::disk('public')->delete($photo->path));
    }
}
