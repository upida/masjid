<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityMemberJoinRequest;
use App\Http\Requests\ActivityMemberLeaveRequest;
use App\Models\Activity;
use App\Models\ActivityMember;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ActivityMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {
        if (!$activity instanceof Activity) {
            throw ValidationException::withMessages([
                'message' => "Activity not found",
            ])->status(404);
        }
        
        $props = [
            "activity" => $activity->with("members.user")->first()->toArray(),
        ];

        return Inertia::render("Activity/Member/Show", $props);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function join(Activity $activity, ActivityMemberJoinRequest $request)
    {
        if (!$activity instanceof Activity) {
            throw ValidationException::withMessages([
                'message' => "Activity not found",
            ])->status(404);
        }

        $activity_member = $activity->members()->join($activity, $request);
        $data = $activity_member->load('user')->toArray();

        return redirect()->route('activity.show', $activity)
        ->with('message', "{$activity_member->user->name} joined successfully")
        ->with('data', $data);
    }

    public function leave(Activity $activity, ActivityMemberLeaveRequest $request)
    {
        if (!$activity instanceof Activity) {
            throw ValidationException::withMessages([
                'message' => "Activity not found",
            ])->status(404);
        }

        $user = $activity->members()->leave($activity, $request);

        return redirect()->route('activity.show', $activity)
        ->with('message', "{$user->name} left successfully");
    }
}
