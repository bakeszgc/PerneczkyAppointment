<?php

namespace App\View\Components;

use Closure;
use App\Models\Appointment;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class TimeOffCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Appointment $appointment,
        public bool $showDetails = false
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.time-off-card');
    }
}
