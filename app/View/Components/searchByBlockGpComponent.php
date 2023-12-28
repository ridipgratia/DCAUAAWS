<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\MyMethod\DistrictMethod;

class searchByBlockGpComponent extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $requireData;
    public function __construct($requireData)
    {
        $this->requireData = $requireData;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $district_name = DistrictMethod::getDistrictName();
        $block_names = DistrictMethod::getBlocksName();
        return view('components.search-by-block-gp-component', [
            'district_name' => $district_name,
            'block_names' => $block_names,
        ]);
    }
}
