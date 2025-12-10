<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class Order extends Mailable
{
    use Queueable, SerializesModels;

    public $items;
    public $userInfo;
    public $keoInfo;
    public $uploadedFiles;

    public function __construct($items, $userInfo, $keoInfo, $uploadedFiles = [])
    {
        $this->items = $items;
        $this->userInfo = $userInfo;
        $this->keoInfo     = $keoInfo;
        $this->uploadedFiles = is_array($uploadedFiles) ? $uploadedFiles : [];
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
            // 'attachment' => $this->storedAttachments,
        ]);

        // foreach ($this->storedAttachments as $file) {
        //     $disk = $file['disk'] ?? 'public';
        //     $path = $file['path'] ?? null;
        //     $name = $file['original_name'] ?? null;

        //     if ($path) {
        //         $mail->attachFromStorageDisk($disk, $path, $name);
        //     }
        // }
        foreach ($this->uploadedFiles as $file) {
            $mail->attach(
            $file->getRealPath(),
                [
                    'as'   => $file->getClientOriginalName(),
                    'mime' => $file->getMimeType(),
                ]
            );
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
    public function attachments(): array { return array_map(function ($file) { return Attachment::fromStorageDisk( $file['disk'] ?? 'public', $file['path'] )->as( $file['original_name'] ?? basename($file['path']) ); }, $this->storedAttachments ?? []); }
}
