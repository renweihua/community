<?php

namespace App\Modules\Bbs\Emails;

use App\Models\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ChangeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $verify_token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $verify_token)
    {
        $this->user = $user;
        $this->verify_token = $verify_token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('请确定您的邮箱【' . cnpscy_config('site_web_title') . '】')
            ->markdown('bbs::mails.change-email', ['token' => $this->verify_token]);
    }
}
