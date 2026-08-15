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

// الكونترولرات الخاصة بالإعدادات
use App\Http\Controllers\Admin\AdminParametreController;
use App\Http\Controllers\Admin\TicketStatusController;
use App\Http\Controllers\Admin\TicketPriorityController;
use App\Http\Controllers\Admin\TicketCategoryController;
use App\Http\Controllers\Admin\MachineCategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes - Application SAV
|--------------------------------------------------------------------------
*/

// ==========================================
// Espace Client (مفتوح للمستخدمين المسجلين بـ auth)
// ==========================================
Route::prefix('client')->name('client.')->middleware(['auth'])->group(function () {
    
    // لوحة التحكم الخاصة بالزبون
    Route::get('/dashboard', [App\Http\Controllers\Client\ClientTicketController::class, 'index'])->name('dashboard');
    
    // رابط تيكيات الكلينت
    Route::get('/tickets', [App\Http\Controllers\Client\ClientTicketController::class, 'index'])->name('tickets.index');
    
    // إنشاء تيكيت جديد من طرف الكلينت
    Route::get('/tickets/create', [App\Http\Controllers\Client\ClientTicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [App\Http\Controllers\Client\ClientTicketController::class, 'store'])->name('tickets.store');
    
    // عرض تفاصيل التيكيت والردود عليه
    Route::get('/tickets/{ticket}', [App\Http\Controllers\Client\ClientTicketController::class, 'show'])->name('tickets.show');
    
    // إضافة رد أو تعليق من طرف الزبون على التيكيت ديالو
    Route::post('/tickets/{ticket}/comments', [App\Http\Controllers\Client\ClientTicketController::class, 'addComment'])->name('tickets.add-comment');
});

// 1. Auth Routes (Login, Register, Logout...)
Auth::routes();

// مسار مخصص للوحة تحكم الأدمن الحقيقية لكي لا يحدث تداخل
Route::group(['middleware' => ['auth']], function () {
    Route::get('/admin/dashboard-main', [DashboardController::class, 'index'])->name('admin.main.dashboard');
});

// 2. Protected Routes (جميع مسارات التطبيق)
Route::group(['middleware' => ['auth']], function () {

    // مسارات إدارة البروفيل الشخصي
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Module Chat / Messages bin les utilisateurs (مسارات الشات الجديدة)
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{user}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{conversation}/message', [ChatController::class, 'store'])->name('chat.store');
    Route::delete('/chat/conversation/{conversation}', [ChatController::class, 'destroy'])->name('chat.destroy');
    Route::post('/chat/user/{user}/block', [ChatController::class, 'toggleBlock'])->name('chat.block');

    // التوجيه الذكي عند الدخول للجذر أو الداشبورد العام بناءً على الصلاحيات
    Route::get('/', function () {
        $user = auth()->user();
        
        if (
            !$user->hasRole('Admin') && 
            $user->email !== 'admin@gmail.com' && 
            !$user->can('ticket-list') && 
            !$user->can('ticket-create') &&
            !$user->can('machine-list')
        ) {
            return redirect()->route('client.tickets.index');
        }
        
        return redirect()->route('admin.main.dashboard');
    })->name('root');

    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        if (
            !$user->hasRole('Admin') && 
            $user->email !== 'admin@gmail.com' && 
            !$user->can('ticket-list') && 
            !$user->can('ticket-create') &&
            !$user->can('machine-list')
        ) {
            return redirect()->route('client.tickets.index');
        }
        
        return redirect()->route('admin.main.dashboard');
    })->name('dashboard');

    // Roles & Users Management (Spatie ACL)
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);

    // Module Clients & Sites
    Route::resource('clients', ClientController::class);
    Route::post('clients/{client}/sites', [ClientController::class, 'storeSite'])->name('clients.sites.store');
    Route::put('sites/{site}', [ClientController::class, 'updateSite'])->name('clients.sites.update');
    Route::delete('sites/{site}', [ClientController::class, 'destroySite'])->name('clients.sites.destroy');

    // Module Parc Machines
    Route::resource('machines', MachineController::class);

    // Module Tickets SAV & Fil de discussion
    Route::resource('tickets', TicketController::class);
    Route::patch('tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign');
    Route::post('tickets/{ticket}/status', [TicketController::class, 'updateStatus'])->name('tickets.update-status');
    Route::post('tickets/{ticket}/comments', [TicketController::class, 'addComment'])->name('tickets.add-comment');

    // Gestion des Fichiers Joints
    Route::get('attachments/{attachment}/download', [TicketAttachmentController::class, 'download'])->name('attachments.download');
    Route::delete('attachments/{attachment}', [TicketAttachmentController::class, 'destroy'])->name('attachments.destroy');

    // Module Factures & Devis (Pièces Jointes) - المسارات مع إضافة رابط التحميل المنظم
    Route::get('documents/{document}/download', [ClientDocumentController::class, 'download'])->name('documents.download');
    Route::resource('documents', ClientDocumentController::class)->except(['show', 'edit', 'update']);

    // Endpoint AJAX (pour charger les sites et machines dynamiquement)
    Route::get('api/clients/{client}/data', [TicketController::class, 'getSitesAndMachines']);

    // Administration & Paramètres BDD
    Route::prefix('admin')->name('admin.')->group(function () {
        
        // الصفحة الرئيسية للإعدادات
        Route::get('parametres', [AdminParametreController::class, 'index'])->name('parametres.index');
        
        // 1. CRUD كامل لـ Statuts de Tickets
        Route::resource('statuses', TicketStatusController::class);

        // 2. CRUD كامل لـ Priorités & SLA
        Route::resource('priorities', TicketPriorityController::class);

        // 3. CRUD كامل لـ Catégories de Tickets
        Route::resource('ticket-categories', TicketCategoryController::class);

        // 4. CRUD كامل لـ Catégories de Machines
        Route::resource('machine-categories', MachineCategoryController::class);
    });

});