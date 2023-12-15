<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\LevelController;
use App\Http\Controllers\Api\MembershipController;
use App\Http\Controllers\Api\MistakeTypeController;
use App\Http\Controllers\Api\PeriodController;
use App\Http\Controllers\Api\PromotionPartController;
use App\Http\Controllers\Api\PromotionSubmissionController;
use App\Http\Controllers\Api\RegistrationController;
use App\Http\Controllers\Api\RevisionPartController;
use App\Http\Controllers\Api\RevisionSubmissionController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Auth
Route::post('/auth', [AuthController::class, 'store']);
Route::delete('/auth', [AuthController::class, 'destroy'])->middleware(['auth:sanctum']);

// User
Route::get('/users/me', [UserController::class, 'showMe'])->middleware(['auth:sanctum']);
Route::put('/users/me', [UserController::class, 'updateMe'])->middleware(['auth:sanctum']);

Route::get('/users', [UserController::class, 'index'])->middleware(['auth:sanctum']);
Route::post('/users', [UserController::class, 'store'])->middleware(['auth:sanctum', 'role:admin']);
Route::get('/users/{user}', [UserController::class, 'show'])->middleware(['auth:sanctum']);
Route::put('/users/{user}', [UserController::class, 'update'])->middleware(['auth:sanctum', 'role:admin']);
Route::delete('/users/{user}', [UserController::class, 'destroy'])->middleware(['auth:sanctum', 'role:admin']);


// Period
Route::get('/periods/active', [PeriodController::class, 'showActive'])->middleware(['auth:sanctum']);
Route::put('/periods/active', [PeriodController::class, 'updateActive'])->middleware(['auth:sanctum', 'role:admin']);
Route::delete('/periods/active', [PeriodController::class, 'destroyActive'])->middleware(['auth:sanctum', 'role:admin']);

Route::get('/periods', [PeriodController::class, 'index'])->middleware(['auth:sanctum']);
Route::post('/periods', [PeriodController::class, 'store'])->middleware(['auth:sanctum', 'role:admin']);
Route::get('/periods/{period}', [PeriodController::class, 'show'])->middleware(['auth:sanctum']);
Route::put('/periods/{period}', [PeriodController::class, 'update'])->middleware(['auth:sanctum', 'role:admin']);
Route::delete('/periods/{period}', [PeriodController::class, 'destroy'])->middleware(['auth:sanctum', 'role:admin']);

// Level
Route::get('/levels', [LevelController::class, 'index'])->middleware(['auth:sanctum']);
Route::post('/levels', [LevelController::class, 'store'])->middleware(['auth:sanctum', 'role:admin']);
Route::get('/levels/{level}', [LevelController::class, 'show'])->middleware(['auth:sanctum']);
Route::put('/levels/{level}', [LevelController::class, 'update'])->middleware(['auth:sanctum', 'role:admin']);
Route::delete('/levels/{level}', [LevelController::class, 'destroy'])->middleware(['auth:sanctum', 'role:admin']);

// Student
Route::get('/students', [StudentController::class, 'index'])->middleware(['auth:sanctum']);
Route::post('/students', [StudentController::class, 'store'])->middleware(['auth:sanctum', 'role:admin']);
Route::get('/students/{student}', [StudentController::class, 'show'])->middleware(['auth:sanctum']);
Route::put('/students/{student}', [StudentController::class, 'update'])->middleware(['auth:sanctum', 'role:admin']);
Route::delete('/students/{student}', [StudentController::class, 'destroy'])->middleware(['auth:sanctum', 'role:admin']);

// Registration
Route::post('/registrations', [RegistrationController::class, 'store'])->middleware(['auth:sanctum', 'role:admin']);
Route::delete('/registrations', [RegistrationController::class, 'destroy'])->middleware(['auth:sanctum', 'role:admin']);

// Group
Route::get('/groups', [GroupController::class, 'index'])->middleware(['auth:sanctum']);
Route::post('/groups', [GroupController::class, 'store'])->middleware(['auth:sanctum', 'role:admin']);
Route::get('/groups/{group}', [GroupController::class, 'show'])->middleware(['auth:sanctum']);
Route::put('/groups/{group}', [GroupController::class, 'update'])->middleware(['auth:sanctum', 'role:admin']);
Route::delete('/groups/{group}', [GroupController::class, 'destroy'])->middleware(['auth:sanctum', 'role:admin']);

// Membership
Route::post('/memberships', [MembershipController::class, 'store'])->middleware(['auth:sanctum', 'role:admin']);
Route::delete('/memberships', [MembershipController::class, 'destroy'])->middleware(['auth:sanctum', 'role:admin']);

// Mistake Type
Route::get('/mistake-types', [MistakeTypeController::class, 'index'])->middleware(['auth:sanctum']);
Route::post('/mistake-types', [MistakeTypeController::class, 'store'])->middleware(['auth:sanctum', 'role:admin']);
Route::get('/mistake-types/{mistakeType}', [MistakeTypeController::class, 'show'])->middleware(['auth:sanctum']);
Route::put('/mistake-types/{mistakeType}', [MistakeTypeController::class, 'update'])->middleware(['auth:sanctum', 'role:admin']);
Route::delete('/mistake-types/{mistakeType}', [MistakeTypeController::class, 'destroy'])->middleware(['auth:sanctum', 'role:admin']);

// Promotion Part
Route::get('/promotion-parts', [PromotionPartController::class, 'index'])->middleware(['auth:sanctum']);
Route::post('/promotion-parts', [PromotionPartController::class, 'store'])->middleware(['auth:sanctum', 'role:admin|teacher']);
Route::get('/promotion-parts/{promotionPart}', [PromotionPartController::class, 'show'])->middleware(['auth:sanctum']);
Route::delete('/promotion-parts/{promotionPart}', [PromotionPartController::class, 'destroy'])->middleware(['auth:sanctum', 'role:admin|teacher']);

// Promotion Submission
Route::post('/promotion-parts/{promotionPart}/tasks/current/submissions', [PromotionSubmissionController::class, 'storeCurrent'])->middleware(['auth:sanctum', 'role:admin|teacher']);
Route::post('/promotion-parts/{promotionPart}/tasks/{promotionTask}/submissions', [PromotionSubmissionController::class, 'store'])->middleware(['auth:sanctum', 'role:admin|teacher']);

// Revision Part
Route::get('/revision-parts', [RevisionPartController::class, 'index'])->middleware(['auth:sanctum']);
Route::post('/revision-parts', [RevisionPartController::class, 'store'])->middleware(['auth:sanctum', 'role:admin|teacher']);
Route::get('/revision-parts/{revisionPart}', [RevisionPartController::class, 'show'])->middleware(['auth:sanctum']);
Route::delete('/revision-parts/{revisionPart}', [RevisionPartController::class, 'destroy'])->middleware(['auth:sanctum', 'role:admin|teacher']);

// Revision Submission
Route::post('/revision-parts/{revisionPart}/tasks/current/submissions', [RevisionSubmissionController::class, 'storeCurrent'])->middleware(['auth:sanctum', 'role:admin|teacher']);
Route::post('/revision-parts/{revisionPart}/tasks/{revisionTask}/submissions', [RevisionSubmissionController::class, 'store'])->middleware(['auth:sanctum', 'role:admin|teacher']);
