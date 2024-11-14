<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $activity;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->actingAs($this->user);

        $this->activity = Activity::factory()->for($this->user)->create();
    }

    public function test_create_activity(): void
    {
        $data = [
            'title' => 'Test Activity',
            'description' => 'Test Description',
            'start_time' => '2022-01-01 00:00:00',
            'end_time' => '2022-01-01 00:00:00',
        ];
        
        $response = $this->post('/activity/create', $data);
        
        $id = session('data')['id'];
        $activity = null;
        if ($id) {
            $activity = Activity::find($id);
        }

        $response->assertRedirectToRoute('activity.show', $activity)
        ->assertSessionHas('message', 'Activity created successfully')
        ->assertSessionHas('data', [
            'id' => $activity->id,
            'title' => $activity->title,
            'description' => $activity->description,
            'start_time' => $activity->start_time,
            'end_time' => $activity->end_time,
            'user_id' => $activity->user_id,
            'created_at' => $activity->created_at,
            'updated_at' => $activity->updated_at,
        ]);
    }

    public function test_show_all_activity(): void
    {
        $response = $this->get('/activity');

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Activity/Index')
            ->has('activities', fn (AssertableInertia $activities) => $activities
                ->where('0.id', $this->activity->id)
                ->where('0.title', $this->activity->title)
                ->where('0.description', $this->activity->description)
                ->where('0.start_time', Carbon::parse($this->activity->start_time)->format('Y-m-d H:i:s'))
                ->where('0.end_time', Carbon::parse($this->activity->end_time)->format('Y-m-d H:i:s'))
                ->where('0.members_count', 0)
            )
        );
    }

    public function test_show_activity_by_id(): void
    {
        $response = $this->get('/activity/' . $this->activity->id);

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Activity/Show')
            ->has('activity', fn (AssertableInertia $activity) => $activity
                ->where('id', $this->activity->id)
                ->where('title', $this->activity->title)
                ->where('description', $this->activity->description)
                ->where('start_time', Carbon::parse($this->activity->start_time)->format('Y-m-d H:i:s'))
                ->where('end_time', Carbon::parse($this->activity->end_time)->format('Y-m-d H:i:s'))
                ->where('user_id', $this->user->id)
                ->where('created_at', Carbon::parse($this->activity->created_at)->format('Y-m-d H:i:s'))
                ->where('updated_at', Carbon::parse($this->activity->updated_at)->format('Y-m-d H:i:s'))
                ->where('members', [])
            )
        );
    }

    public function test_update_activity(): void
    {
        $response = $this->patch('/activity/' . $this->activity->id, [
            'title' => 'Test Activity Updated',
        ]);

        $response->assertRedirectToRoute('activity.show', $this->activity)
        ->assertSessionHas('message', 'Activity updated successfully')
        ->assertSessionHas('data', [
            'id' => $this->activity->id,
            'title' => 'Test Activity Updated',
            'description' => $this->activity->description,
            'start_time' => $this->activity->start_time,
            'end_time' => $this->activity->end_time,
            'user_id' => $this->activity->user_id,
            'created_at' => $this->activity->created_at,
            'updated_at' => $this->activity->updated_at,
        ]);
    }

    public function test_delete_activity(): void
    {
        $response = $this->delete('/activity/' . $this->activity->id);

        $response->assertRedirectToRoute('activity.index')
        ->assertSessionHas('message', "Activity {$this->activity->title} deleted successfully")
        ->assertSessionHas('id', $this->activity->id);
    }
}
