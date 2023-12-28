<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ViewFullNotifyComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $userLevel;
    public function __construct($userLevel)
    {
        $this->userLevel = $userLevel;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.view-full-notify-component');
    }
}
