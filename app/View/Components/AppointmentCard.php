<?php

namespace App\View\Components;

use Closure;
use App\Models\Barber;
use App\Models\Appointment;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class AppointmentCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Appointment $appointment,
        public bool $showDetails = false,
        public string $access = 'user',
        public ?Barber $barber
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
