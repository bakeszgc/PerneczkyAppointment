<?php

namespace App\View\Components;

use App\Models\Barber;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class Calendar extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Collection $calAppointments
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.calendar');
    }
}
