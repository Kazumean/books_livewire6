<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Book;

class BookIndex extends Component
{
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

    public function render()
    {
        return view('livewire.book-index');
    }
}
