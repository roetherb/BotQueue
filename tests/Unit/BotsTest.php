<?php

namespace Tests\Unit;

use App\Bot;
use App\Enums\BotStatusEnum;
use App\Events\BotCreated;
use App\Jobs\FindJobForBot;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\HasUser;
use Tests\TestCase;

class BotsTest extends TestCase
{
    use HasUser;
    use WithFaker;

    /** @test */
    public function botCreatedEventIsFired()
    {
        $this->fakesEvents(BotCreated::class);

        /** @var Bot $bot */
        $bot = factory(Bot::class)->create([
            'creator_id' => $this->user->id,
        ]);

        $this->assertDispatched(BotCreated::class)
            ->inspect(function($event) use ($bot) {
                /** @var BotCreated $event */
                $this->assertEquals($bot->id, $event->bot->id);
            })
            ->channels([
                'private-user.'.$this->user->id,
            ]);
    }

    /** @test */
    public function findJobForBotIsDispatched()
    {
        $this->markTestSkipped("See bug: https://github.com/laravel/framework/issues/22951");

        $this->expectsJobs(FindJobForBot::class);
        event(BotCreated::class);
    }

    /** @test */
    public function botIsByDefaultOffline()
    {
        /** @var Bot $bot */
        $bot = factory(Bot::class)->create([
            'creator_id' => $this->user->id,
        ]);

        $this->assertEquals(BotStatusEnum::OFFLINE, $bot->status);
    }
}
