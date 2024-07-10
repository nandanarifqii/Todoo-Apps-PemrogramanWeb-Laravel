<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Groups\GroupController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\Mail\InvitationEmail as MailInvitationEmail;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskGroupController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Task Groups
Route::middleware('auth')->group(function () {
    Route::get('/groups/{group}/tasks', [TaskGroupController::class, 'index'])->name('taskgroups.index');
    Route::post('/groups/{group}/tasks', [TaskGroupController::class, 'store'])->name('taskgroups.store');
    Route::get('/groups/{group}/tasks/{task}/edit', [TaskGroupController::class, 'editTask'])->name('taskgroups.editTask');
    Route::put('/groups/{group}/tasks/{task}', [TaskGroupController::class, 'update'])->name('taskgroups.update');
    Route::delete('/groups/{group}/tasks/{task}', [TaskGroupController::class, 'destroy'])->name('taskgroups.destroy');
    Route::post('/groups/{group}/tasks/{task}/mark-as-done', [TaskGroupController::class, 'markAsDone'])->name('taskgroups.markAsDone');
});

//Ubah Routing
// Groups (Added middleware auth, kalau error dihapus aja)
Route::middleware('auth')->group(function () {
    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::delete('/groups/{group}', [GroupController::class, 'delete'])->name('groups.delete');
    Route::get('/groups/{group}/edit', [GroupController::class, 'edit'])->name('groups.edit');
    Route::put('/groups/{group}', [GroupController::class, 'update'])->name('groups.update');
    Route::get('/groups/{group}/members', [GroupController::class, 'showMembers'])->name('groups.showMembers');
    Route::get('/groups/{group}', [GroupController::class, 'show'])->name('groups.show');
    Route::delete('/groups/{group}/members/{member}', [GroupController::class, 'kickMember'])->name('groups.kickMember');
    Route::post('/groups/leave', [GroupController::class, 'leave'])->name('groups.leave');
});



//Join form
Route::get('/joinform', [GroupController::class, 'joinForm'])->name('groups.joinform');
Route::post('/join', [GroupController::class, 'join'])->name('groups.join');


// Invites (ga kepake)
Route::get('/invites/form', [InviteController::class, 'showInviteForm'])->name('invites.form');
Route::post('/invites', [InviteController::class, 'sendInvite'])->name('invites.send');
Route::get('/invites', [MailInvitationEmail::class, 'build'])->name('invites.email');

//Task Individual Feature
Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
Route::post('/tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
Route::post('/tasks/{task}/inProgress', [TaskController::class, 'inProgressTask'])->name('tasks.inProgress');
Route::resource('tasks', TaskController::class);

//Profile
Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
