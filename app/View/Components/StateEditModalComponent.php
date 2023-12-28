<?php

namespace App\View\Components;

use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class StateEditModalComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $labelNames;
    public function __construct($labelNames)
    {
        $this->labelNames = $labelNames;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.state-edit-modal-component');
    }
}
