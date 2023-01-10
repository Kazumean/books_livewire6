<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Book;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class BookIndex extends Component
{
    // ファイルのアップロードに必要
    use WithFileUploads;
    // ページネーションに必要
    use WithPagination;
    // モーダルウィンドウ
    public $liveModal = false;
    // タイトル
    public $title;
    // 画像
    public $image;
    // 価格
    public $price;
    // 詳細
    public $description;
    // ID
    public $Id;
    // 変更前の画像
    public $oldImage;
    // 編集作業中かどうかを判別する
    public $editWork = false;


    // 書籍登録のモーダルウィンドウを表示する
    public function showBookModal()
    {
        $this->reset();
        $this->liveModal = true;
    }


    // 書籍の登録をする
    public function bookPost()
    {
        $this->validate([
            'title' => 'required',
            'image' => 'image|max:2048',
            'price' => 'integer|required',
            'description' => 'required',
        ]);

        $image = $this->image->store('public/books');

        Book::create([
            'title' => $this->title,
            'image' => $image,
            'price' => $this->price,
            'description' => $this->description,
        ]);

        $this->reset();
    }


    // モーダルウィンドウ上に編集したい書籍の情報を表示する
    public function showEditBookModal($id)
    {
        $book = Book::findOrFail($id);
        $this->Id = $book->id;
        $this->title = $book->title;
        $this->oldImage = $book->image;
        $this->price = $book->price;
        $this->description = $book->description;
        $this->editWork = true;
        $this->liveModal = true;
    }


    // 書籍の情報を更新する
    public function updateBook($Id)
    {
        $this->validate([
            'title' => 'required',
            'price' => 'integer|required',
            'description' => 'required',
        ]);

        if($this->image) {
            $image = $this->newImage->store('public/books');
            Book::where('id', $Id)->update([
                'title' => $this->title,
                'image' => $image,
                'price' => $this->price,
                'descriptiom' => $this->description,
            ]);
        } else {
            Book::where('id', $Id)->update([
                'title' => $this->title,
                'price' => $this->price,
                'description' => $this->description,
            ]);
        }

        session()->flash('message', '更新しました！');
    }

    // 書籍を削除する
    public function deleteBook($id) {
        $book = Book::findOrFail($id);
        Storage::delete($book->image);
        $book->delete();
        $this->reset();
    }

    public function render()
    {
        return view('livewire.book-index', [
            'books' => Book::select('id', 'title', 'price', 'image', 'description')
            ->orderBy('id', 'DESC')->paginate(3),
        ]);
    }
}
