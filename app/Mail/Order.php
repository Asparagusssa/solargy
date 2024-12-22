<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Order extends Mailable
{
    use Queueable, SerializesModels;

    public $items;
    public $userInfo;

    public function __construct($items, $userInfo)
    {
        $this->items = $items;
        $this->userInfo = $userInfo;
    }

    public function build(): Order
    {

        foreach ($this->items as &$item) {
            if (isset($item['photo'])) {
                $path = public_path('storage/products/' . basename($item['photo']));
                $imageData = base64_encode(file_get_contents($path));
                $item['photo_base64'] = 'data:image/png;base64,' . $imageData;
            }
        }

        $mail = $this->view('mail.order')
        ->with([
            'items' => $this->items,
            'userInfo' => $this->userInfo,
        ]);

        return $mail;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Заявка на приобретение товара',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.order',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
