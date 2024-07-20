<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Book; // Bookモデルをインポート
use Illuminate\Support\Facades\Log;

class BookController extends Controller // クラス名をBookControllerに変更
{
    public function index(Request $request)
    {
        $data = [];
        $items = null;

        if (!empty($request->keyword)) {
            $title = urlencode($request->keyword);
            $url = 'https://www.googleapis.com/books/v1/volumes?q=' . $title . '&country=JP&tbm=bks';
    
            $client = new Client();
            $response = $client->request("GET", $url);
            $body = $response->getBody();
            $bodyArray = json_decode($body, true);

            if (config('app.debug')) {                               // デバッグ用のログを開発環境で出力
                Log::info('Google Books API Response:', $bodyArray); // レスポンス全体をログに出力
            }    
            $items = $bodyArray['items'];
        }
        // $books = Book::where('user_id', auth()->id())->get(); // ユーザーの保存した本を取得
        $savedBooks = Book::where('user_id', auth()->id())->get(); // ここを修正
        $data = [
            'items' => $items,
            'keyword' => $request->keyword,
            // 'books' => $books, // ここでbooksを追加
            'savedBooks' => $savedBooks, // 保存された本の情報を追加
        ];

        return view('books', $data); // 'index'から'books'に変更
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        // ここでお気に入りの書籍を保存するロジックを実装
        $book = new Book();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->thumbnail_url = $request->thumbnail_url;
        $publishedDate = $request->published_date;
        if ($publishedDate) {
            // YYYY-MM-DD形式に変換
            $date = date_create_from_format('Y-m-d', $publishedDate);
            if ($date === false) {
                // YYYY-MM形式の場合、日付を01に設定
                $date = date_create_from_format('Y-m', $publishedDate);
                if ($date !== false) {
                    $date->setDate($date->format('Y'), $date->format('m'), 1);
                }
            }
            if ($date !== false) {
                $book->published_date = $date->format('Y-m-d');
            } else {
                // 日付の解析に失敗した場合はnullを設定
                $book->published_date = null;
            }
        } else {
            $book->published_date = null;
        }
        // $book->published_date = $request->published_date;
    
       // ISBNの処理を修正
        $identifiers = $request->input('identifiers', []);
        foreach ($identifiers as $identifier) {
            if ($identifier['type'] == 'ISBN_13') {
                $book->isbn_13 = $identifier['identifier'];
            } elseif ($identifier['type'] == 'ISBN_10') {
                $book->isbn_10 = $identifier['identifier'];
            }
        }    
        // $book->isbn_13 = $request->isbn_13;
        // $book->isbn_10 = $request->isbn_10;
        $book->description = $request->description;
        $book->user_id = auth()->id();
        $book->save();

        return redirect()->route('book_index')->with('success', 'Book added to favorites successfully.');
    }

    public function show(Book $book)
    {
        //
    }

    public function edit(Book $book)
    {
        return view('books_edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $book->update($request->all());
        return redirect()->route('book_index')->with('success', 'Book updated successfully.');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('book_index')->with('success', 'Book removed from favorites successfully.');
    }
}