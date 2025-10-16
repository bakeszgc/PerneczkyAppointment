<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PasswordChecklist extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $passwordInput = 'password',
        public string $passwordConfInput = 'password_confirmation',
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.password-checklist');
    }
}
