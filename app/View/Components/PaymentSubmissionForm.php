<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PaymentSubmissionForm extends Component
{
     public $custOrderId;
     public $custOrderAmt;
     public $custOrderCurr;
     public $custFirstName;
     public $custLastName;
     public $custMobile;
     public $custEmail;
     public $description;
     public $paymentOption; 

     /**
     * Create a new component instance.
     */
    public function __construct(
	$custOrderId = null,
	$custOrderAmt = null,
	$custOrderCurr = null,
	$custFirstName = null,
	$custLastName = null,
	$custMobile = null,
	$custEmail = null,
	$description = null,
	$paymentOption = null
    )
    {
        $this->custOrderId=$custOrderId;
        $this->custOrderAmt=$custOrderAmt;
        $this->custOrderCurr=$custOrderCurr;
        $this->custFirstName=$custFirstName;
        $this->custLastName=$custLastName;
        $this->custMobile=$custMobile;
        $this->custEmail=$custEmail;
        $this->description=$description;
        $this->paymentOption=$paymentOption;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.payment-submission-form');
    }
}
