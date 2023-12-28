<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\MyMethod\StateMethod;

class searchByDistrictBlockGpComponent extends Component
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
        // $districts = StateMethod::getDistricts();
        return view('components.search-by-district-block-gp-component');
    }
}
