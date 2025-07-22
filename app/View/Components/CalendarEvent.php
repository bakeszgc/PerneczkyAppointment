<?php

namespace App\View\Components;

use App\Models\Barber;
use Closure;
use App\Models\Appointment;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class CalendarEvent extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Appointment $appointment,
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
        return view('components.calendar-event');
    }
}
