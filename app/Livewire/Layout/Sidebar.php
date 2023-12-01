<?php

namespace App\Livewire\Layout;

use Livewire\Component;
use Illuminate\Support\Facades\Route;

class Sidebar extends Component
{

    public $styles;
    public function mount(){
        $base = "flex items-center gap-2 mt-4 p-2 md:mt-6";

        $this->styles = [
            "/" => $base,
            "/trial-balances" => $base,
            "/financial-statements" => $base,
        ];

        $route = Route::current()->getPrefix();
        $this->styles[$route ? $route : "/"] = $base . " bg-active";
    }

    public function render()
    {
        return view('livewire.layout.sidebar');
    }
}
