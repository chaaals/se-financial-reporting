<?php

namespace App\View\Components\FinancialReporting;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AccountTitleItems extends Component
{
    /**
     * Create a new component instance.
     */
    public $accountTitles;
    public $data;

    public function __construct($accountTitles, $data)
    {
        $this->accountTitles = $accountTitles;
        $this->data = $data;
        // dd($this->data);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        
        return view('components.financial-reporting.account-title-items', [
            "data" => $this->data,
            "accountTitles" => $this->accountTitles
        ]);
    }
}
