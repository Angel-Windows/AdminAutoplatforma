<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'cover',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function publishedFormat(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->published_at < now()->subDays()
                ? $this->published_at->format('F j, Y')
                : $this->published_at->diffForHumans(),
        );
    }

    public function scopePublished(Builder $query): void
    {
        $query
            ->whereNotNull('published_at')
            ->whereDate('published_at', '<', now());
    }

    protected static function boot()
    {
        parent::boot();

        static::deleted(fn (Post $post) => Storage::disk('public')->delete($post->cover));
    }
}
