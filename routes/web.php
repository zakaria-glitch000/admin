<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TicketAttachmentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientDocumentController; 
use App\Http\Controllers\ChatController;
use App\Http\Controllers\SiteContratController;

// الكونترولرات الخاصة بالإعدادات
use App\Http\Controllers\Admin\AdminParametreController;
use App\Http\Controllers\Admin\TicketStatusController;
use App\Http\Controllers\Admin\TicketPriorityController;
use App\Http\Controllers\Admin\TicketCategoryController;
use App\Http\Controllers\Admin\MachineCategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Auth Routes
Auth::routes();

// ==========================================
// Espace Client
// ==========================================
Route::prefix('client')->name('client.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Client\ClientTicketController::class, 'index'])->name('dashboard');
    Route::get('/tickets', [App\Http\Controllers\Client\ClientTicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [App\Http\Controllers\Client\ClientTicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [App\Http\Controllers\Client\ClientTicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [App\Http\Controllers\Client\ClientTicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/comments', [App\Http\Controllers\Client\ClientTicketController::class, 'addComment'])->name('tickets.add-comment');
});

// ==========================================
// Protected Routes (Auth Required)
// ==========================================
Route::group(['middleware' => ['auth']], function () {

    // مسار معالجة الخطأ 404 لـ /index
    Route::get('/index', function () {
        return redirect()->route('dashboard');
    });

    // مسارات الإدارة العامة
    Route::get('/admin/dashboard-main', [DashboardController::class, 'index'])->name('admin.main.dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Chat Module
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{user}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{conversation}/message', [ChatController::class, 'store'])->name('chat.store');
    Route::delete('/chat/conversation/{conversation}', [ChatController::class, 'destroy'])->name('chat.destroy');
    Route::post('/chat/user/{user}/block', [ChatController::class, 'toggleBlock'])->name('chat.block');

    // Smart Root Redirect
    Route::get('/', function () {
        $user = auth()->user();
        if (!$user->hasRole('Admin') && $user->email !== 'admin@gmail.com' && !$user->can('ticket-list') && !$user->can('machine-list')) {
            return redirect()->route('client.tickets.index');
        }
        return redirect()->route('admin.main.dashboard');
    })->name('root');

    Route::get('/dashboard', function () {
        $user = auth()->user();
        if (!$user->hasRole('Admin') && $user->email !== 'admin@gmail.com' && !$user->can('ticket-list') && !$user->can('machine-list')) {
            return redirect()->route('client.tickets.index');
        }
        return redirect()->route('admin.main.dashboard');
    })->name('dashboard');

    // CRUD Resources
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('clients', ClientController::class);
    Route::post('clients/{client}/sites', [ClientController::class, 'storeSite'])->name('clients.sites.store');
    Route::put('sites/{site}', [ClientController::class, 'updateSite'])->name('clients.sites.update');
    Route::delete('sites/{site}', [ClientController::class, 'destroySite'])->name('clients.sites.destroy');
    
    Route::resource('machines', MachineController::class);
    Route::resource('tickets', TicketController::class);
    Route::patch('tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign');
    Route::post('tickets/{ticket}/status', [TicketController::class, 'updateStatus'])->name('tickets.update-status');
    Route::post('tickets/{ticket}/comments', [TicketController::class, 'addComment'])->name('tickets.add-comment');

    // Attachments & Documents
    Route::get('attachments/{attachment}/download', [TicketAttachmentController::class, 'download'])->name('attachments.download');
    Route::delete('attachments/{attachment}', [TicketAttachmentController::class, 'destroy'])->name('attachments.destroy');
    Route::get('documents/{document}/download', [ClientDocumentController::class, 'download'])->name('documents.download');
    Route::resource('documents', ClientDocumentController::class)->except(['show', 'edit', 'update']);

    // Sites Contrats
    Route::post('/sites/{site}/contrats', [SiteContratController::class, 'store'])->name('sites.contrats.store');
    Route::delete('/contrats/{contrat}', [SiteContratController::class, 'destroy'])->name('contrats.destroy');

    // API
    Route::get('api/clients/{client}/data', [TicketController::class, 'getSitesAndMachines']);

    // Admin Settings
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('parametres', [AdminParametreController::class, 'index'])->name('parametres.index');
        Route::resource('statuses', TicketStatusController::class);
        Route::resource('priorities', TicketPriorityController::class);
        Route::resource('ticket-categories', TicketCategoryController::class);
        Route::resource('machine-categories', MachineCategoryController::class);
    });
});