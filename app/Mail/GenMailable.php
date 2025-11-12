<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GenMailable extends Mailable {
  use Queueable, SerializesModels;

  public $data;
  public $subject;
  public $view;

  public function __construct($data, $subject, $view) {
    $this->data = $data;
    $this->subject = $subject;
    $this->view = $view;
  }

  public function build() {
    return $this->view('email.' . $this->view, [
      'data' => $this->data
    ]);
  }
}
