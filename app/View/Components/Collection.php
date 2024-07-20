<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Collection extends Component
{
    public $books;

    public function __construct($books)
    {
        $this->books = $books;
    }

    public function render()
    {
        return view('components.collection');
    }
}
