<?php

namespace App\View\Components\FinancialReporting;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AccountClass extends Component
{
    /**
     * Create a new component instance.
     */
    public $accountClass;
    public function __construct(string $accountClass)
    {
        $this->accountClass = $accountClass;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.financial-reporting.account-class');
    }
}
