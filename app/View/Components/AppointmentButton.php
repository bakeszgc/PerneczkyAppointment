<?php

namespace App\View\Components;

use App\Models\Appointment;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AppointmentButton extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $type,
        public ?Appointment $appointment
    ) { }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.appointment-button');
    }
}
