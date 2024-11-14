<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityIndexRequest;
use App\Http\Requests\ActivityStoreRequest;
use App\Http\Requests\ActivityUpdateRequest;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ActivityIndexRequest $request)
    {
        $activities = auth('web')->user()->activities()->search($request)->get()->toArray();

        $props = [
            "activities" => $activities,
        ];

        return Inertia::render("Activity/Index", $props);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render("Activity/Create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ActivityStoreRequest $request)
    {
        $activity = Activity::create($request->all());
        
        return redirect()->route('activity.show', $activity)
        ->with('message', 'Activity created successfully')
        ->with('data', $activity->toArray());
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

        if ($activity->user_id !== auth('web')->user()->id) {
            throw ValidationException::withMessages([
                'message' => "You are not authorized to view this activity",
            ])->status(403);
        }
        
        $props = [
            "activity" => $activity->load("members.user")->toArray(),
        ];
        
        return Inertia::render("Activity/Show", $props);
    }

    public function scan(Activity $activity)
    {
        if (!$activity instanceof Activity) {
            throw ValidationException::withMessages([
                'message' => "Activity not found",
            ])->status(404);
        }

        if ($activity->user_id !== auth('web')->user()->id) {
            throw ValidationException::withMessages([
                'message' => "You are not authorized to view this activity",
            ])->status(403);
        }

        $props = [
            "activity" => $activity->load("members.user")->toArray(),
        ];

        return Inertia::render("Activity/Scan", $props);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activity $activity)
    {
        if (!$activity instanceof Activity) {
            throw ValidationException::withMessages([
                'message' => "Activity not found",
            ])->status(404);
        }

        if ($activity->user_id !== auth('web')->user()->id) {
            throw ValidationException::withMessages([
                'message' => "You are not authorized to view this activity",
            ])->status(403);
        }

        $props = [
            "activity" => $activity,
        ];
        return Inertia::render("Activity/Edit", $props);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ActivityUpdateRequest $request, Activity $activity)
    {
        if (!$activity instanceof Activity) {
            throw ValidationException::withMessages([
                'message' => "Activity not found",
            ])->status(404);
        }

        if ($activity->user_id !== auth('web')->user()->id) {
            throw ValidationException::withMessages([
                'message' => "You are not authorized to view this activity",
            ])->status(403);
        }
        
        $activity->update($request->validated());

        return redirect()->route('activity.show', $activity)
        ->with('message', 'Activity updated successfully')
        ->with('data', $activity->toArray());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        if (!$activity instanceof Activity) {
            throw ValidationException::withMessages([
                'message' => "Activity not found",
            ])->status(404);
        }

        if ($activity->user_id !== auth('web')->user()->id) {
            throw ValidationException::withMessages([
                'message' => "You are not authorized to view this activity",
            ])->status(403);
        }

        $temporary = $activity;
        $activity->delete();

        return redirect()->route('activity.index')
        ->with('message', "Activity {$temporary->title} deleted successfully")
        ->with('id', $temporary->id);
    }
}
