<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Str;
use Illuminate\Support\Facades\Validator;
use MongoDB\BSON\ObjectId;
use OpenAI\Client;
use Pusher\Pusher;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activities = Activity::all();
        return inertia('Activities/Index', [
            'activities' => $activities,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $leads = Lead::all();
        $users = User::all();
        $roles = Role::all();
        $permissions = Permission::all();
        return inertia('Activities/Create', [
            'leads' => $leads,
            'users' => $users,
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lead_id' => 'required|exists:leads,_id',
            'user_id' => 'required|exists:users,_id',
            'role_id' => 'required|exists:roles,_id',
            'permission_id' => 'required|exists:permissions,_id',
            'activity_type' => 'required|string',
            'activity_description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $activity = new Activity();
        $activity->lead_id = $request->input('lead_id');
        $activity->user_id = $request->input('user_id');
        $activity->role_id = $request->input('role_id');
        $activity->permission_id = $request->input('permission_id');
        $activity->activity_type = $request->input('activity_type');
        $activity->activity_description = $request->input('activity_description');
        $activity->save();

        // Sentiment Analysis
        $client = new Client();
        $response = $client->textAnalysis([
            'input' => $activity->activity_description,
            'model' => 'text-similarity',
        ]);

        if ($response->status === 'ok') {
            $sentiment = $response->data->result->sentiment->label;
            $activity->sentiment = $sentiment;
            $activity->save();
        }

        // Pusher Notification
        $pusher = new Pusher('YOUR_PUSHER_APP_ID', 'YOUR_PUSHER_APP_KEY', 'YOUR_PUSHER_APP_SECRET');
        $pusher->trigger('activity-channel', 'activity-created', [
            'activity' => $activity,
        ]);

        return back()->with('success', 'Activity created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function show(Activity $activity)
    {
        return inertia('Activities/Show', [
            'activity' => $activity,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function edit(Activity $activity)
    {
        $leads = Lead::all();
        $users = User::all();
        $roles = Role::all();
        $permissions = Permission::all();
        return inertia('Activities/Edit', [
            'activity' => $activity,
            'leads' => $leads,
            'users' => $users,
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Activity $activity)
    {
        $validator = Validator::make($request->all(), [
            'lead_id' => 'required|exists:leads,_id',
            'user_id' => 'required|exists:users,_id',
            'role_id' => 'required|exists:roles,_id',
            'permission_id' => 'required|exists:permissions,_id',
            'activity_type' => 'required|string',
            'activity_description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $activity->lead_id = $request->input('lead_id');
        $activity->user_id = $request->input('user_id');
        $activity->role_id = $request->input('role_id');
        $activity->permission_id = $request->input('permission_id');
        $activity->activity_type = $request->input('activity_type');
        $activity->activity_description = $request->input('activity_description');
        $activity->save();

        // Sentiment Analysis
        $client = new Client();
        $response = $client->textAnalysis([
            'input' => $activity->activity_description,
            'model' => 'text-similarity',
        ]);

        if ($response->status === 'ok') {
            $sentiment = $response->data->result->sentiment->label;
            $activity->sentiment = $sentiment;
            $activity->save();
        }

        return back()->with('success', 'Activity updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function destroy(Activity $activity)
    {
        $activity->delete();
        return back()->with('success', 'Activity deleted successfully!');
    }
}