<?php

namespace App\View\Components;

use Illuminate\View\Component;

class BadgeSks extends Component
{
    // Tambahkan "= 0" di sini
    public function __construct(public int $sks = 0)
    {
    }

    public function render()
    {
        return view('components.badge-sks');
    }
}