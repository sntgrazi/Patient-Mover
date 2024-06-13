<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SenhaTemporaria extends Mailable
{
    use Queueable, SerializesModels;

    public $senha;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($senha)
    {
        $this->senha = $senha;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Sua senha de acesso')
                    ->view('maqueiro_senha')
                    ->with(['senha' => $this->senha]);
    }
}
