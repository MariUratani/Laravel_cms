<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @include('components.errors')
                    
                    <form action="{{ route('book_index') }}" method="get" class="mb-6">
                        <input type="text" name="keyword" placeholder="Search books..." value="{{ $keyword ?? '' }}" class="border rounded px-2 py-1">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded">Search</button>
                    </form>

                    <h3 class="text-lg font-semibold mb-4">Your Saved Books</h3>
                    <div class="space-y-4">
                        @foreach($savedBooks as $book)
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    @if($book->thumbnail_url)
                                        <img src="{{ $book->thumbnail_url }}" alt="{{ $book->title }}" class="w-24 h-36 object-cover">
                                    @else
                                        <div class="w-24 h-36 bg-gray-200 flex items-center justify-center">No Image</div>
                                    @endif
                                </div>
                                <div class="flex-grow">
                                    <h4 class="font-semibold">{{ $book->title }}</h4>
                                    <p class="text-gray-600">{{ $book->author }}</p>
                                    <div class="mt-2">
                                        <a href="{{ route('book_edit', $book->id) }}" class="text-blue-500 hover:underline mr-2">Edit</a>
                                        <form action="{{ route('book_destroy', $book->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:underline">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if (isset($items) && count($items) > 0)
                        <h3 class="text-lg font-semibold mt-8 mb-4">Search Results</h3>
                        @foreach ($items as $item)
                            <div class="mb-4 p-4 border rounded">
                                <h3>{{ $item['volumeInfo']['title'] ?? 'No title' }}</h3>
                                @if (isset($item['volumeInfo']['imageLinks']['thumbnail']))
                                    <img src="{{ $item['volumeInfo']['imageLinks']['thumbnail'] }}" alt="Book cover">
                                @endif
                                <p>Author: {{ $item['volumeInfo']['authors'][0] ?? 'Unknown' }}</p>
                                <p>Published: {{ $item['volumeInfo']['publishedDate'] ?? 'Unknown' }}</p>
                                @if (isset($item['volumeInfo']['industryIdentifiers']))
                                    @foreach ($item['volumeInfo']['industryIdentifiers'] as $identifier)
                                        <p>{{ $identifier['type'] }}: {{ $identifier['identifier'] }}</p>
                                    @endforeach
                                @endif
                                <p>{{ Str::limit($item['volumeInfo']['description'] ?? 'No description', 200) }}</p>
                                
                                <form action="{{ route('book_store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="title" value="{{ $item['volumeInfo']['title'] ?? '' }}">
                                    <input type="hidden" name="author" value="{{ $item['volumeInfo']['authors'][0] ?? '' }}">
                                    <input type="hidden" name="thumbnail_url" value="{{ $item['volumeInfo']['imageLinks']['thumbnail'] ?? '' }}">
                                    <input type="hidden" name="published_date" value="{{ $item['volumeInfo']['publishedDate'] ?? '' }}">
                                    <input type="hidden" name="isbn_13" value="{{ $item['volumeInfo']['industryIdentifiers'][0]['identifier'] ?? '' }}">
                                    <input type="hidden" name="isbn_10" value="{{ $item['volumeInfo']['industryIdentifiers'][1]['identifier'] ?? '' }}">
                                    <input type="hidden" name="description" value="{{ $item['volumeInfo']['description'] ?? '' }}">
                                    <button type="submit" class="bg-green-500 text-white px-4 py-1 rounded mt-2">Add to Favorites</button>
                                </form>
                            </div>
                        @endforeach
                    @elseif (isset($keyword) && empty($items))
                        <p class="mt-4">No results found for "{{ $keyword }}"</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>