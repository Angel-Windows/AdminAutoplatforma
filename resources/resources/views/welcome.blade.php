<x-app-layout>
    <section class="dark:bg-gray-800 dark:text-gray-100">
        <div class="container mx-auto flex flex-col items-center px-4 py-16 text-center md:py-16 md:px-10 lg:px-32 xl:max-w-3xl">
            <h1 class="text-4xl font-bold leadi sm:text-5xl">Welcome to my
                <span class="block dark:text-violet-400 uppercase">Blog</span>
            </h1>
            <p class="px-8 mt-8 mb-12 text-lg">Cupiditate minima voluptate temporibus quia? Architecto beatae esse ab amet vero eaque explicabo!</p>
        </div>
    </section>

    <main class="dark:bg-gray-800 dark:text-gray-50 py-12">
        <div class="container grid mx-auto mb-6 px-6">
            <h3 class="text-3xl font-semibold">My latest <span class="dark:text-violet-400 capitalize underline">posts</span></h3>
        </div>
        @foreach ($posts as $post)
            <div class="container grid grid-cols-12 mx-auto dark:bg-gray-900 my-8">
                <div class="bg-no-repeat bg-cover dark:bg-gray-300 col-span-full lg:col-span-4" style="background-image: url('{{ asset('storage/' . $post->cover) }}'); background-position: center center; background-blend-mode: multiply; background-size: cover;"></div>
                <div class="flex flex-col p-6 col-span-full row-span-full lg:col-span-8 lg:p-10">
                    @if ($post->tags->isNotEmpty())
                        <div class="flex justify-start gap-x-2 my-2">
                            @foreach ($post->tags as $tag)
                                <span class="px-2 py-1 text-xs rounded-full dark:bg-violet-400 dark:text-white font-semibold">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    @endif
                    <a href="{{ route('post', $post) }}">
                        <h2 class="text-3xl font-semibold">{{ $post->title }}</h2>
                    </a>
                    <p class="flex-1 pt-2">{{  $post->excerpt }}</p>
                    <a rel="noopener noreferrer" href="{{ route('post', $post) }}" class="inline-flex items-center pt-2 pb-6 space-x-2 text-sm dark:text-violet-400">
                        <span>Read more</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                            <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </a>
                    <div class="flex items-center justify-between pt-2">
                        <div class="flex space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 dark:text-gray-400">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="self-center text-sm">by {{ $post->user->name }}</span>
                        </div>
                        <span class="text-xs">{{ $post->published_format }}</span>
                    </div>
                </div>
            </div>
        @endforeach
        {{ $posts->links() }}
    </main>
</x-app-layout>
