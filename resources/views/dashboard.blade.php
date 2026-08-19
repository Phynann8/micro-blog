<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Global Feed') }}
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