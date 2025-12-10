<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class Order extends Mailable
{
    use Queueable, SerializesModels;

    public array $items;
    public array $userInfo;
    public ?array $keoInfo;
    public array $storedAttachments;

    public function __construct($items, $userInfo, $keoInfo, $storedAttachments = [])
    {
        $this->items = array_map(function ($item) {
            if (isset($item['photo'])) {
                $item['photo_url'] = $item['photo'];
            }
            $item['options'] = $item['options'] ?? [];
            return $item;
        }, is_array($items) ? $items : []);

        $this->userInfo = is_array($userInfo) ? $userInfo : [];
        $this->keoInfo = $keoInfo;
        $this->storedAttachments = is_array($storedAttachments) ? $storedAttachments : [];
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Заявка на приобретение товара',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.order',
            with: [
                'items' => $this->items,
                'userInfo' => $this->userInfo,
                'keoInfo' => $this->keoInfo,
            ],
        );
    }

    public function attachments(): array
    {
        $result = [];

        foreach ($this->storedAttachments as $file) {
            $disk = $file['disk'] ?? 'public';
            $path = $file['path'] ?? null;

            if (!$path) {
                continue;
            }

            $name = $file['original_name'] ?? basename($path);

            $attachment = Attachment::fromStorageDisk($disk, $path)
                ->as($name);

            if (!empty($file['mime'])) {
                $attachment->withMime($file['mime']);
            }

            $result[] = $attachment;
        }

        return $result;
    }
}
