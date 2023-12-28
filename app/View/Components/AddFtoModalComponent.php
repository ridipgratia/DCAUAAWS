<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AddFtoModalComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $addTo;
    public function __construct($addTo)
    {
        $this->addTo = $addTo;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.add-fto-modal-component');
    }
}
