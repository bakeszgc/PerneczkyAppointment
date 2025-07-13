<?php

namespace App\View\Components;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BookingCalendar extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Carbon $firstDaytOfMonth,
        public array $availableSlotsByDate
    ) { }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.booking-calendar');
    }
}
