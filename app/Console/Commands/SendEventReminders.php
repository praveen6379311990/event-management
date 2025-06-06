<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Notifications\EventReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SendEventReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-event-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'sends notification to all event attendees that event start soon';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $events = Event::with('attendees.user')
            ->whereBetween('start_date', [now(), now()->addDay()])
            ->get();

        $eventCount = $events->count();
        $eventlable = Str::plural('Event', $eventCount);

        $this->info("Found $eventlable $eventCount");

        // $events->each(
        //     fn($event) => $event->attendees->each(
        //         fn($attendee) => $this->info("Notifying the user {$attendee->user->id}")
        //     )
        // );
        $events->each(
            fn($event) =>
            $event->attendees->each(
                fn($attendee) =>
                $attendee->user->notify(
                    new EventReminderNotification($event)
                )
            )
        );

        $this->info('Reminder Notification is send successfully');
    }
}
