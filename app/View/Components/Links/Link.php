<?php

namespace App\View\Components\Links;

use Illuminate\View\Component;

class Link extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return <<<'blade'
            <div>
                <a href="#" class="p-2 bg-blue-200 mt-16">Inline Link</a>
            </div>
            blade;
    }
}
