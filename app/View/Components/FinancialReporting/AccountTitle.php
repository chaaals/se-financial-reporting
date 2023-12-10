<?php

namespace App\View\Components\FinancialReporting;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AccountTitle extends Component
{
    /**
     * Create a new component instance.
     */
    public $accountTitle;
    public function __construct(string $accountTitle)
    {
        $this->accountTitle = $accountTitle;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.financial-reporting.account-title');
    }
}
