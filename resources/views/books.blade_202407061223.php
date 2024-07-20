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
                    <h3 class="text-lg font-semibold mb-4">Your Saved Books</h3>
                    <div class="grid grid-cols-6 gap-4 mb-6">
                        @foreach($savedBooks as $book)
                            <div class="text-center">
                                @if($book->thumbnail_url)
                                    <img src="{{ $book->thumbnail_url }}" alt="{{ $book->title }}" class="w-24 h-36 object-cover mx-auto mb-2">
                                @else
                                    <div class="w-24 h-36 bg-gray-200 flex items-center justify-center mx-auto mb-2">No Image</div>
                                @endif
                                <p class="text-sm truncate">{{ $book->title }}</p>
                            </div>
                        @endforeach
                    </div>

                    @include('components.errors')
                    
                    <form action="{{ route('book_index') }}" method="get" class="mb-4">
                        <input type="text" name="keyword" placeholder="Search books..." value="{{ $keyword ?? '' }}">
                        <button type="submit">Search</button>
                    </form>

                    @if (isset($items) && count($items) > 0)
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
                                    <button type="submit">Add to Favorites</button>
                                </form>
                            </div>
                        @endforeach
                    @elseif (isset($keyword) && empty($items))
                        <p>No results found for "{{ $keyword }}"</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>