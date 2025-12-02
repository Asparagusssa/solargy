<?php 

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClientOrder extends Mailable
{
    use Queueable, SerializesModels;

    public array $items;
    public array $userInfo;

    public function __construct(
        array $items, 
        array $userInfo, 
    ){
        $this->items = $items;
        $this->userInfo = $userInfo;
    }

    public function build(): self
    {
        $mail = $this
            ->subject('Ваш заказ на сайте Solargy')
            ->view('mail.client_order'); 


        return $mail;
    }
}
