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
                    
                    <div class="h-24 w-24 flex-shrink-0 rounded-full bg-indigo-600 flex items-center justify-center text-white text-4xl font-bold shadow-md">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>

                    <div class="text-center sm:text-left">
                        <h3 class="text-2xl font-extrabold text-gray-900">{{ $user->name }}</h3>
                        <p class="text-gray-500 font-medium mt-1">{{ $user->email }}</p>
                        
                        <div class="mt-4 flex items-center justify-center sm:justify-start text-sm text-gray-400">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Joined {{ $user->created_at->format('F j, Y') }}
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>