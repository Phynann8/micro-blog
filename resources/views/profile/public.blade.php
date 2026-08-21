<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $user->name }}'s Profile
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-6">

                    <div class="h-24 w-24 flex-shrink-0 rounded-full bg-indigo-600 flex items-center justify-center text-black text-4xl font-bold shadow-md">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    <div class="text-center sm:text-left">
                        
                        <h3 class="text-2xl font-extrabold text-gray-900">{{ $user->name }}</h3>
                        <p class="text-gray-500 font-medium mt-1">{{ $user->email }}</p>
                        <p class="mt-2 text-gray-700">{{ $user->bio }}</p>
                        <div class="mt-4 flex items-center justify-center sm:justify-start text-sm text-gray-400">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Joined {{ $user->created_at->format('F j, Y') }}
                            
                        </div>
                        @if(auth()->user()->isNot($user))
                            <div class="mt-4 sm:mt-0 sm:ml-auto flex items-center">
                                <form method="POST" action="{{ route('follow.store', $user) }}">
                                    @csrf
                                    
                                    @if(auth()->user()->isFollowing($user))
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                                            Unfollow
                                        </button>
                                    @else
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-200 border border-transparent rounded-md font-semibold text-xs text-black-800 uppercase tracking-widest hover:bg-blue-300 active:bg-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                                            Follow
                                        </button>
                                    @endif
                                    
                                </form>
                            </div>
                        @endif
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>