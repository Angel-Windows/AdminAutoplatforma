<?php

namespace App\Filament\Forms\Components;

use App\Models\Post;
use Closure;
use Exception;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\FileUpload;
use Filament\Support\Concerns\HasAlignment;
use Filament\Support\Concerns\HasExtraAlpineAttributes;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Concerns;
use Illuminate\Support\Str;
use League\Flysystem\UnableToCheckFileExistence;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class FileUploadMy extends FileUpload
{

    public function uploadDirectory(string $directory): self
    {
        $host = $_SERVER['HTTPS_HOST'] ?? $_SERVER['HTTP_HOST'] ?? "";
        $new_directory = $directory . "/";
        $this->configure(function () use ($directory) {
            $this->directory = $directory;
        });

        return $this;
    }

    public function setSaveUploadedFileUsing($argument)
    {
        return $this->saveUploadedFileUsing(static function (BaseFileUpload $component, TemporaryUploadedFile $file) use ($argument) {
            // Ваша логика сохранения файла с аргументом, переданным в цепочке вызовов
            // Например, изменение пути в базе данных
            $result = $argument;
            return $result;
        });


        return $this;
    }

    public function afterStateUpdated(?Closure $callback): static
    {
        $postId = request('record');
        $model = Post::find($postId);
       if ($model){
           $host = $_SERVER['HTTPS_HOST'] ?? $_SERVER['HTTP_HOST'] ?? "";
           $cover = $model->cover;
           $model->url = "https://" . $host . '//storage/' . $cover;
           $model->save();
       }
//        $model = Post::where('id', 2)->first(); // Замените на логику поиска вашей модели


        return $this;
    }

    public function store()
    {
        $model = Post::where('id', 2)->first(); // Замените на логику поиска вашей модели
//        $model->cover = "222";
        $host = $_SERVER['HTTPS_HOST'] ?? $_SERVER['HTTP_HOST'] ?? "";

//        $model->cover = $host . 222;
        $model->cover = $host . '/' . $model->cover;
        $model->save();
        // Логика сохранения файла в базу данных
        // Например, сохранение пути файла в поле вашей модели

        // Затем вернуть путь, который должен быть сохранен в состоянии компонента
        return $model->cover;
    }
}
