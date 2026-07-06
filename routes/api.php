<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OpportunityController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\ChurnController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Authentication Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);

// Lead Routes
Route::get('/leads', [LeadController::class, 'index']);
Route::get('/leads/{id}', [LeadController::class, 'show']);
Route::post('/leads', [LeadController::class, 'store']);
Route::put('/leads/{id}', [LeadController::class, 'update']);
Route::delete('/leads/{id}', [LeadController::class, 'destroy']);

// Contact Routes
Route::get('/contacts', [ContactController::class, 'index']);
Route::get('/contacts/{id}', [ContactController::class, 'show']);
Route::post('/contacts', [ContactController::class, 'store']);
Route::put('/contacts/{id}', [ContactController::class, 'update']);
Route::delete('/contacts/{id}', [ContactController::class, 'destroy']);

// Opportunity Routes
Route::get('/opportunities', [OpportunityController::class, 'index']);
Route::get('/opportunities/{id}', [OpportunityController::class, 'show']);
Route::post('/opportunities', [OpportunityController::class, 'store']);
Route::put('/opportunities/{id}', [OpportunityController::class, 'update']);
Route::delete('/opportunities/{id}', [OpportunityController::class, 'destroy']);

// Account Routes
Route::get('/accounts', [AccountController::class, 'index']);
Route::get('/accounts/{id}', [AccountController::class, 'show']);
Route::post('/accounts', [AccountController::class, 'store']);
Route::put('/accounts/{id}', [AccountController::class, 'update']);
Route::delete('/accounts/{id}', [AccountController::class, 'destroy']);

// Email Routes
Route::get('/emails', [EmailController::class, 'index']);
Route::get('/emails/{id}', [EmailController::class, 'show']);
Route::post('/emails', [EmailController::class, 'store']);
Route::put('/emails/{id}', [EmailController::class, 'update']);
Route::delete('/emails/{id}', [EmailController::class, 'destroy']);

// Chat Routes
Route::get('/chats', [ChatController::class, 'index']);
Route::get('/chats/{id}', [ChatController::class, 'show']);
Route::post('/chats', [ChatController::class, 'store']);
Route::put('/chats/{id}', [ChatController::class, 'update']);
Route::delete('/chats/{id}', [ChatController::class, 'destroy']);

// Meeting Routes
Route::get('/meetings', [\App\Http\Controllers\MeetingController::class, 'index']);
Route::get('/meetings/{meeting}', [\App\Http\Controllers\MeetingController::class, 'show']);
Route::post('/meetings', [\App\Http\Controllers\MeetingController::class, 'store']);
Route::put('/meetings/{meeting}', [\App\Http\Controllers\MeetingController::class, 'update']);
Route::delete('/meetings/{meeting}', [\App\Http\Controllers\MeetingController::class, 'destroy']);

// AI Routes
Route::get('/ai/lead-scoring', [AIController::class, 'leadScoring']);
Route::get('/ai/sentiment-analysis', [AIController::class, 'sentimentAnalysis']);
Route::get('/ai/churn-prediction', [AIController::class, 'churnPrediction']);

// Churn Routes
Route::get('/churn', [ChurnController::class, 'index']);
Route::get('/churn/{id}', [ChurnController::class, 'show']);
Route::post('/churn', [ChurnController::class, 'store']);
Route::put('/churn/{id}', [ChurnController::class, 'update']);
Route::delete('/churn/{id}', [ChurnController::class, 'destroy']);

// Webhook Routes
Route::post('/webhooks', [WebhookController::class, 'handle']);

// Inertia Routes
Route::get('/inertia/leads', [LeadController::class, 'indexInertia']);
Route::get('/inertia/contacts', [ContactController::class, 'indexInertia']);
Route::get('/inertia/opportunities', [OpportunityController::class, 'indexInertia']);
Route::get('/inertia/accounts', [AccountController::class, 'indexInertia']);
Route::get('/inertia/emails', [EmailController::class, 'indexInertia']);
Route::get('/inertia/chats', [ChatController::class, 'indexInertia']);
Route::get('/inertia/churn', [ChurnController::class, 'indexInertia']);