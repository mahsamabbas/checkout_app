<?php

namespace App\Mail;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReceiptEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $email;
    private $project;
    private $client_name;

    /**
     * Create a new message instance.
     *
     * @param $email
     * @param $client_name
     * @param Project $project
     */
    public function __construct($email,$client_name, Project $project)
    {
        $this->email = $email;
        $this->project = $project;
        $this->client_name = $client_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $project = $this->project;
        return $this->view('emails.receipt', [
            'email' => $this->email,
            'client_name' => $this->client_name,
            'project_name' => $project->name,
            'price' => $project->reservation_cost,
        ])->subject("$project->name - Your Receipt");
    }
}
