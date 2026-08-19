<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <div class="mt-6 space-y-4">
                @foreach ($tweets as $tweet)
                    <div class="bg-white p-6 rounded-lg shadow-sm flex space-x-4">
                        
                        <div class="h-12 w-12 flex-shrink-0 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xl font-bold shadow-sm">
                            {{ strtoupper(substr($tweet->user->name, 0, 1)) }}
                        </div>

                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <h3 class="font-bold text-gray-900">
                                    <a href="{{ route('profile.show', $tweet->user) }}" class="hover:underline hover:text-indigo-600">
                                        {{ $tweet->user->name }}
                                    </a>
                                </h3>
                                <span class="text-sm text-gray-500">{{ $tweet->created_at->diffForHumans() }}</span>
                            </div>
                            
                            <p class="mt-2 text-gray-800 text-lg">{{ $tweet->body }}</p>
                        </div>

                    </div>
                @endforeach
            </div>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('tweets.store') }}">
                        @csrf
                        <textarea
                            name="body"
                            placeholder="What's on your mind?"
                            class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            rows="3"
                            maxlength="280"
                            required
                        >{{ old('body') }}</textarea>
                        
                        <x-input-error :messages="$errors->get('body')" class="mt-2" />

                        <div class="mt-4 flex justify-end">
                            <x-primary-button>
                                {{ __('Tweet') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            </div>
    </div>
</x-app-layout>