<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ActivityMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'user_id',
        'status',
        'reason',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeJoin(Builder $query, Activity $activity, Request $request)
    {
        if ($activity->user_id !== auth('web')->user()->id) {
            throw ValidationException::withMessages([
                'message' => "You are not authorized to handle this activity",
            ])->status(403);
        }

        $user_id = $request->user_id ?? null;
        if (!$user_id) {
            $user = Qrcode::where('code', $request->code)->first();
            if (!$user) {
                throw ValidationException::withMessages([
                    'message' => "(1) User not found",
                ])->status(400);
            }
            $user_id = $user->user_id;
        }

        $check_user = User::find($user_id);
        if (!$check_user) {
            throw ValidationException::withMessages([
                'message' => "(2) User not found",
            ])->status(400);
        }

        $check_member = $activity->members()->where('user_id', $user_id)->first();
        if ($check_member) {
            throw ValidationException::withMessages([
                'message' => "{$check_user->name} has been joined",
            ])->status(400);
        }

        $data = [
            'user_id' => $user_id,
            'activity_id' => $activity->id,
            'status' => 'present',
        ];

        if ($request->filled('status')) {
            $data['status'] = $request->status;
            if ($request->status === 'permit') {
                if (!$request->filled('reason')) {
                    throw ValidationException::withMessages([
                        'message' => "Reason is required",
                    ])->status(400);
                }

                $data['reason'] = $request->reason;
            }
        }

        return $query->create($data);
    }

    public function scopeLeave(Builder $query, Activity $activity, Request $request)
    {
        try {
            if ($activity->user_id !== auth('web')->user()->id) {
                throw ValidationException::withMessages([
                    'message' => "You are not authorized to handle this activity",
                ])->status(403);
            }

            $query = $query->where([
                'user_id' => $request->user_id,
                'activity_id' => $activity->id,
            ]);
            
            $query->delete();

            return User::find($request->user_id);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'message' => "Failed to leave",
            ])->status(400);
        }
    }
}
