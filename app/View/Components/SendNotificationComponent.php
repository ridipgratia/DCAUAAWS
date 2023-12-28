<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SendNotificationComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $districts;
    public function __construct($districts)
    {
        $this->districts = $districts;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.send-notification-component');
    }
}
