<?php

namespace App\View\Components\Property;

use Illuminate\View\Component;

class index extends Component
{
    public $component;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($component)
    {
        $this->component = $component;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.property.index');
    }
}
