<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityMember;
use App\Models\Qrcode;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class ActivityMemberTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $activity;
    private $activityMember;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->actingAs($this->user);

        $this->activity = Activity::factory()->for($this->user)->create();

        $this->activityMember = ActivityMember::factory()->for($this->activity)->for($this->user)->create();
    }

    public function test_show_activity_member(): void
    {
        $response = $this->get("/activity/{$this->activity->id}/member");

        $response->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Activity/Member/Show')
            ->has('activity', fn (AssertableInertia $activity) => $activity
                ->where('id', $this->activity->id)
                ->where('title', $this->activity->title)
                ->where('description', $this->activity->description)
                ->where('start_time', Carbon::parse($this->activity->start_time)->format('Y-m-d H:i:s'))
                ->where('end_time', Carbon::parse($this->activity->end_time)->format('Y-m-d H:i:s'))
                ->where('user_id', $this->user->id)
                ->where('created_at', Carbon::parse($this->activity->created_at)->format('Y-m-d H:i:s'))
                ->where('updated_at', Carbon::parse($this->activity->updated_at)->format('Y-m-d H:i:s'))
                ->has('members', fn (AssertableInertia $members) => $members
                    ->where('0.id', $this->activityMember->id)
                    ->where('0.user_id', $this->activityMember->user_id)
                    ->where('0.status', $this->activityMember->status)
                    ->where('0.reason', $this->activityMember->reason)
                    ->has('0.user', fn (AssertableInertia $user) => $user
                        ->where('id', $this->user->id)
                        ->where('name', $this->user->name)
                        ->where('email', $this->user->email)
                        ->where('email_verified_at', Carbon::parse($this->user->email_verified_at)->toISOString())
                        ->where('created_at', Carbon::parse($this->user->created_at)->toISOString())
                        ->where('updated_at', Carbon::parse($this->user->updated_at)->toISOString())
                    )
                )
            )
        );
    }

    public function test_duplicate_join_activity_member(): void
    {
        $response = $this->postJson("/activity/{$this->activity->id}/member/join", [
            'user_id' => $this->user->id,
        ]);

        $response->assertStatus(400)->assertJson([
            'message' => "{$this->user->name} has been joined",
        ]);
    }

    public function test_join_activity_member(): void
    {
        $this->activity = Activity::factory()->for($this->user)->create();

        $response = $this->post("/activity/{$this->activity->id}/member/join", [
            'user_id' => $this->user->id,
        ]);

        $id = session('data')['id'];
        $members = null;
        if ($id) {
            $members = ActivityMember::find($id);
        }

        $response->assertRedirectToRoute('activity.show', $this->activity)
        ->assertSessionHas('message', "{$this->user->name} joined successfully")
        ->assertSessionHas('data', [
            'id' => $members->id,
            'activity_id' => $this->activity->id,
            'user_id' => $this->user->id,
            'status' => 'present',
            'created_at' => Carbon::parse($this->user->created_at)->toISOString(),
            'updated_at' => Carbon::parse($this->user->updated_at)->toISOString(),
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'email_verified_at' => Carbon::parse($this->user->email_verified_at)->toISOString(),
                'created_at' => Carbon::parse($this->user->created_at)->toISOString(),
                'updated_at' => Carbon::parse($this->user->updated_at)->toISOString(),
            ],
        ]);
    }

    public function test_join_activity_member_with_qrcode(): void
    {
        $this->activity = Activity::factory()->for($this->user)->create();
        $code = Qrcode::factory()->for($this->user)->create();

        $response = $this->post("/activity/{$this->activity->id}/member/join", [
            'code' => $code->code,
        ]);

        $id = session('data')['id'];
        $members = null;
        if ($id) {
            $members = ActivityMember::find($id);
        }

        $response->assertRedirectToRoute('activity.show', $this->activity)
        ->assertSessionHas('message', "{$this->user->name} joined successfully")
        ->assertSessionHas('data', [
            'id' => $members->id,
            'activity_id' => $this->activity->id,
            'user_id' => $this->user->id,
            'status' => 'present',
            'created_at' => Carbon::parse($this->user->created_at)->toISOString(),
            'updated_at' => Carbon::parse($this->user->updated_at)->toISOString(),
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'email_verified_at' => Carbon::parse($this->user->email_verified_at)->toISOString(),
                'created_at' => Carbon::parse($this->user->created_at)->toISOString(),
                'updated_at' => Carbon::parse($this->user->updated_at)->toISOString(),
            ],
        ]);
    }

    public function test_permit_join_activity_member(): void
    {
        $this->activity = Activity::factory()->for($this->user)->create();

        $response = $this->post("/activity/{$this->activity->id}/member/join", [
            'user_id' => $this->user->id,
            'status' => 'permit',
            'reason' => 'Sick',
        ]);

        $id = session('data')['id'];
        $members = null;
        if ($id) {
            $members = ActivityMember::find($id);
        }

        $response->assertRedirectToRoute('activity.show', $this->activity)
        ->assertSessionHas('message', "{$this->user->name} joined successfully")
        ->assertSessionHas('data', [
            'id' => $members->id,
            'activity_id' => $this->activity->id,
            'user_id' => $this->user->id,
            'status' => 'permit',
            'reason' => 'Sick',
            'created_at' => Carbon::parse($this->user->created_at)->toISOString(),
            'updated_at' => Carbon::parse($this->user->updated_at)->toISOString(),
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'email_verified_at' => Carbon::parse($this->user->email_verified_at)->toISOString(),
                'created_at' => Carbon::parse($this->user->created_at)->toISOString(),
                'updated_at' => Carbon::parse($this->user->updated_at)->toISOString(),
            ],
        ]);
    }

    public function test_permit_join_activity_member_without_reason(): void
    {
        $this->activity = Activity::factory()->for($this->user)->create();

        $response = $this->postJson("/activity/{$this->activity->id}/member/join", [
            'user_id' => $this->user->id,
            'status' => 'permit',
        ]);

        $response->assertStatus(422)->assertJson([
            'message' => "The reason field is required when status is permit.",
            'errors' => [
                'reason' => [
                    'The reason field is required when status is permit.',
                ],
            ],
        ]);
    }

    public function test_leave_activity_member(): void
    {
        $response = $this->post("/activity/{$this->activity->id}/member/leave", [
            'user_id' => $this->user->id,
        ]);

        $response->assertRedirectToRoute('activity.show', $this->activity)
        ->assertSessionHas('message', "{$this->user->name} left successfully");

        $this->assertDatabaseMissing('activity_members', [
            'user_id' => $this->user->id,
            'activity_id' => $this->activity->id,
        ]);
    }


    public function test_leave_activity_member_not_registered(): void
    {
        $other_user = User::factory()->create();
        $response = $this->postJson("/activity/{$this->activity->id}/member/leave", [
            'user_id' => $other_user->id,
        ]);

        $response->assertStatus(422)->assertJson([
            'message' => "The selected user id is invalid.",
            'errors' => [
                'user_id' => [
                    'The selected user id is invalid.',
                ],
            ],
        ]);
    }
}
