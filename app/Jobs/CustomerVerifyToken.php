<?php

namespace App\Jobs;

use App\Mail\CustomerVerifyToken as MailCustomerVerifyToken;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class CustomerVerifyToken implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $token;
    protected $email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $email, string $token)
    {
        $this->token = $token;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)->send(new MailCustomerVerifyToken($this->token));
    }
}
