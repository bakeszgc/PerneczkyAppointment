<?php

namespace App\View\Components;

use App\Models\Appointment;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AppointmentCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Appointment $appointment,
        public string $access = 'user'
    )
    {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.appointment-card');
    }
}
