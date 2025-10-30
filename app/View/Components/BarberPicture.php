<?php

namespace App\View\Components;

use Closure;
use App\Models\Barber;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class BarberPicture extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Barber|string $barber
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.barber-picture');
    }
}
