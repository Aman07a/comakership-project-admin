<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Message extends Component
{
    public $message;
    public $type;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($message = null, $type = null)
    {
        $this->message = $message;
        $this->type = $type;
    }

    public function typeClass()
    {
        if($this->type === 'error') {
            return 'bg-red-600 text-white';
        }

        return 'bg-green-600 text-white';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.message');
    }
}
