<?php

namespace App\Jobs;

use App\Mail\PollInvitation;
use App\Models\Poll;
use App\Models\PollToken;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPollInvitations implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Poll $poll) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $users = User::where('active', true)->get();

        foreach ($users as $user) {
            // Create unique token for this user and poll
            $token = PollToken::create([
                'poll_id' => $this->poll->id,
                'user_id' => $user->id,
                'token' => PollToken::generateToken(),
                'expires_at' => now()->addDays(7),
            ]);

            // Send email with token
            Mail::to($user->email)->send(new PollInvitation($this->poll, $user, $token));

            // Sleep to avoid overwhelming mail server
            sleep(1);
        }
    }
}
