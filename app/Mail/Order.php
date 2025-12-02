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
    public $keoInfo;
    public $attachments;

    public function __construct($items, $userInfo, $keoInfo, $attachments)
    {
        $this->items = $items;
        $this->userInfo = $userInfo;
        $this->keoInfo     = $keoInfo;
        $this->attachments = $attachments;
    }

    public function build(): Order
    {

        foreach ($this->items as &$item) {
            if (isset($item['photo'])) {
                $item['photo_url'] = $item['photo'];
            }
        }

        $mail = $this->view('mail.order')
        ->with([
            'items' => $this->items,
            'userInfo' => $this->userInfo,
            'keoInfo'     => $this->keoInfo,
            'attachments' => $this->attachments,
        ]);

        foreach ($this->attachments as $file) {
            $disk = $file['disk'] ?? 'public';
            $path = $file['path'] ?? null;
            $name = $file['original_name'] ?? null;

            if ($path) {
                $mail->attachFromStorageDisk($disk, $path, $name);
            }
        }

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
