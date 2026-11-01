<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WorkspaceController;
use App\Livewire\BillingPage;
use App\Livewire\DashboardPage;
use App\Livewire\ProjectPage;
use App\Livewire\ProjectsPage;
use App\Livewire\SettingsPage;
use App\Livewire\TeamPage;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:6,1')->name('register.perform');
});
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/workspaces/switch', [WorkspaceController::class, 'switch'])->name('workspaces.switch');
    Route::post('/workspaces', [WorkspaceController::class, 'store'])->name('workspaces.store');
    Route::get('/dashboard', DashboardPage::class)->name('dashboard');
    Route::get('/projects', ProjectsPage::class)->name('projects.index');
    Route::get('/projects/{project}', ProjectPage::class)->name('projects.show');
    Route::get('/team', TeamPage::class)->name('team.index');
    Route::get('/billing', BillingPage::class)->name('billing.index');
    Route::get('/settings', SettingsPage::class)->name('settings.index');
});
