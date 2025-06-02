<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Button extends Component
{
    public function __construct(
        public ?string $value,
        public ?string $name,
        public ?string $id,
        public string $role = '',
        public bool $full = false,
        public bool $hidden = false,
        public bool $disabled = false
    ) { 
        
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.button');
    }
}
