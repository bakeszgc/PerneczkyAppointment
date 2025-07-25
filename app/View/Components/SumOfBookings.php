<?php

namespace App\View\Components;

use App\Models\Barber;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SumOfBookings extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public array $sumOfBookings,
        public ?Barber $barber
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sum-of-bookings');
    }
}
