<?php

namespace App\View\Components\Books;

use Illuminate\View\Component;

class Cover extends Component
{
    public function __construct(
        public $libro,
        public string $defaultCover = ''
    ) {
        $this->defaultCover = $defaultCover ?: asset('img/elementos/portada_default.png');
    }

    public function render()
    {
        return view('components.books.cover');
    }
}
