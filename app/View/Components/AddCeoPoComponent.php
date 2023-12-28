<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AddCeoPoComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $selectDatas;
    public function __construct($selectDatas)
    {
        $this->selectDatas = $selectDatas;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.add-ceo-po-component');
    }
}
