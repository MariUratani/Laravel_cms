@props(['books'])

<div class="p-6 bg-white border-b border-gray-200">
    <table class="table-auto w-full">
        <thead>
            <tr>
                <th class="px-4 py-2">Title</th>
                <th class="px-4 py-2">Author</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($books as $book)
            <tr>
                <td class="border px-4 py-2">{{ $book->title }}</td>
                <td class="border px-4 py-2">{{ $book->author }}</td>
                <td class="border px-4 py-2">
                    <form action="{{ route('book_destroy', $book->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600">Delete</button>
                    </form>
                    <a href="{{ route('book_edit', $book->id) }}" class="text-blue-600 ml-2">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>