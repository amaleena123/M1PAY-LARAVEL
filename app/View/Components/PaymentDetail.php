<?php 

namespace App\View\Components;

use Illuminate\View\Component;

class PaymentDetail extends Component
{
    public $label;
    public $value;

    /**
     * Create a new component instance.
     *
     * @param string $label
     * @param string $value
     * @return void
     */
    public function __construct($label, $value)
    {
        $this->label = $label;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.payment-detail');
    }
}
