<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $type;

    public function __construct($order, $type)
    {
        $this->order = $order;
        $this->type = $type;
    }

    public function build()
    {
        return $this->subject('Order Status Update')
                    ->view('emails.order_status');
    }
}