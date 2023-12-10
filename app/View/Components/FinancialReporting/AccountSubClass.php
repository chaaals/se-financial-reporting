<?php

namespace App\View\Components\FinancialReporting;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AccountSubClass extends Component
{
    /**
     * Create a new component instance.
     */
    public $accountSubClass;
    public function __construct(string $accountSubClass)
    {
        $this->accountSubClass = $accountSubClass;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.financial-reporting.account-sub-class');
    }
}
