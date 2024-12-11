<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class CustomersLayout extends Component
{
    public function render(): View
    {
        return view('customers.app');
    }
}
