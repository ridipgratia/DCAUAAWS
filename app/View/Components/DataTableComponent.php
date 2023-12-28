<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DataTableComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $columns;
    public function __construct($columns)
    {
        $this->columns = $columns;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.data-table-component');
    }
}
