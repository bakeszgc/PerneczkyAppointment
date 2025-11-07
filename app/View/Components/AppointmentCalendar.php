<?php

namespace App\View\Components;

use Closure;
use App\Models\Barber;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;

class AppointmentCalendar extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Collection $calAppointments,
        public Barber $barber,
        public Collection $barbers,
        public string $access = 'barber'
    ) { }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.appointment-calendar');
    }
}
