<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\SendMail;
use App\Models\User;

class NotifySubscribers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $advertisments;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $advertisments)
    {
        $this->advertisments = $advertisments;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // TODO: доделать получение подписанных юзеров
        $users = User::whereNotNull('email_verified_at')->get();

        foreach ($users as $user) {
            SendMail::dispatch($this->advertisments, [$user->id, $user->name])->onQueue('email');
        }
    }
}
