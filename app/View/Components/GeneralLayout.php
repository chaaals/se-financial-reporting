<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class GeneralLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public $pageTitle = 'Placeholder Title';

    public function __construct($pageTitle)
    {
        $this->pageTitle = $pageTitle;
    }
    
    public function render(): View
    {
        return view('layouts.general');
    }
}
