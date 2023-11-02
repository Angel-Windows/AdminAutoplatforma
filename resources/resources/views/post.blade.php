<x-app-layout :title="$post->title">
    <div class="dark:bg-gray-800 flex-grow">
        <article class="container max-w-2xl px-6 py-12 mx-auto space-y-12 dark:bg-gray-800 dark:text-gray-50">
            <div class="w-full mx-auto space-y-4 text-center">
                <div class="flex justify-center gap-x-2">
                    @foreach ($post->tags as $tag)
                        <p class="text-xs font-semibold tracki uppercase">#{{ $tag->name }}</p>
                    @endforeach
                </div>
                <h1 class="text-4xl font-bold leadi md:text-5xl">{{ $post->title }}</h1>
                <p class="text-sm dark:text-gray-400">by
                    <a rel="noopener noreferrer" href="#" target="_blank" class="underline dark:text-violet-400">
                        <span itemprop="name">{{ $post->user->name }}</span>
                    </a>on
                    <time datetime="2021-02-12 15:34:18-0200">{{ $post->published_format }}</time>
                </p>
            </div>
            <div class="dark:text-gray-100 prose prose-headings:text-white prose-blockquote:text-slate-400 prose-a:text-slate-100 prose-a:font-semibold prose-figcaption:text-slate-300">
                {!! $post->content !!}
            </div>
        </article>
    </div>

    @if ($post->photos->isNotEmpty())
        <section class="py-6 dark:bg-gray-800">
            <div class="container flex flex-col justify-center p-4 mx-auto">
                <div class="grid grid-cols-1 gap-4 lg:grid-cols-4 sm:grid-cols-2">
                    @foreach ($post->photos as $photo)
                        <img class="object-cover w-full dark:bg-gray-500 aspect-square" src="{{ asset('storage/' . $photo->path) }}" alt="{{ $photo->alt_text }}" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-app-layout>