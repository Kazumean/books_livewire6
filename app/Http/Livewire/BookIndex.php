<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Book;
use Livewire\WithFileUploads;

class BookIndex extends Component
{
    // ファイルのアップロードに必要
    use WithFileUploads;
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

    public function render()
    {
        return view('livewire.book-index');
    }
}
