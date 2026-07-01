<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendQueuedMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    protected string $view;
    protected array $data;
    protected string $to;
    protected string $subject;

    public function __construct(string $to, string $subject, string $view, array $data = [])
    {
        $this->to      = $to;
        $this->subject = $subject;
        $this->view    = $view;
        $this->data    = $data;
    }

    public function handle(): void
    {
        try {
            Mail::send($this->view, $this->data, function ($message) {
                $message->to($this->to)->subject($this->subject);
            });
        } catch (\Throwable $e) {
            Log::error('Mail send failed', [
                'to'      => $this->to,
                'subject' => $this->subject,
                'error'   => $e->getMessage(),
            ]);
            // Re-throw so the job is retried up to $tries times
            throw $e;
        }
    }

    public function getTo(): string
    {
        return $this->to;
    }
}
