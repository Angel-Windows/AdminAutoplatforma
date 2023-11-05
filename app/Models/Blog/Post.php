<?php

namespace App\Models\Blog;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Blog\BlogUser as User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class Post extends Model
{
    use HasFactory;

    protected $connection = 'blog_db';

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'cover',
        'cover_url',
        'published_at',
        'reading_time',
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
            get: fn() => $this->published_at < now()->subDays()
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

        static::deleted(fn(Post $post) => Storage::disk('public')->delete($post->cover));
    }

    protected static function booted()
    {
        static::saved(function ($model) {
            Log::debug('An informational message.');
            $filePath = $model->cover;
            $files = [];
            $targetUrl = env('blog_url') . 'save_file';
//            $targetUrl = env('blog_url') . '/save_file';
            if (Storage::disk('public')->exists($filePath ?? "ase")) {
                $imagePath = Storage::disk('public')->get($filePath);
                $originalFileName = basename($filePath);
                $files[] = [
                    'name' => 'images[]',
                    'contents' => $imagePath,
                    'filename' => $originalFileName,
                ];
            }
            $pattern = '/href="([^"]+)"/';

            preg_match_all($pattern, $model->content, $matches);
            $oldBaseUrl = url('/') . 'storage/';
            $newBaseUrl = env('blog_url');

            $newHtml = str_replace($oldBaseUrl, $newBaseUrl, $model->content);

            $srcValues = $matches[1];
            foreach ($srcValues as $pattern) {
                $storageString = '/storage/';
                $attachments = strpos($pattern, $storageString);
                $data_new = substr($pattern, $attachments + strlen($storageString));
                if (Storage::disk('public')->exists($data_new) ?? "asr") {
                    $fileContent = Storage::disk('public')->get($data_new);
                    $files[] = [
                        'name' => 'images[]',
                        'contents' => $fileContent,
                        'filename' => basename($data_new),
                    ];
                }
            }
            DB::connection('blog_db')->table('posts')
                ->where('id', $model->id)
                ->update(['content' => $newHtml]);
            $response = Http::attach($files)->post($targetUrl);
        });
    }

}
