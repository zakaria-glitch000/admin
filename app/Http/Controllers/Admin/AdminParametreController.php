<?php

namespace App\Http\Controllers\Admin;

// الاستدعاء الضروري لحل المشكل
use App\Http\Controllers\Controller;

use App\Models\TicketStatus;
use App\Models\TicketPriority;
use App\Models\TicketCategory;
use App\Models\MachineCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminParametreController extends Controller
{
    /**
     * حماية الكونترولر بالكامل: منع أي مستخدم ليس لديه صلاحيات الإدارة
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if (!$user->hasRole('Admin') && $user->email !== 'admin@gmail.com') {
                abort(403, 'Accès non autorisé.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $statuses = TicketStatus::orderBy('ordre')->get();
        $priorities = TicketPriority::all();
        $ticketCategories = TicketCategory::all();
        $machineCategories = MachineCategory::all();

        return view('admin.parametres', compact('statuses', 'priorities', 'ticketCategories', 'machineCategories'));
    }

    // Statuses
    public function storeStatus(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'couleur' => 'required|string|max:50',
            'ordre' => 'required|integer',
        ]);

        TicketStatus::create([
            'nom' => $validated['nom'],
            'couleur' => $validated['couleur'],
            'ordre' => $validated['ordre'],
            'est_final' => $request->has('est_final'),
        ]);

        return back()->with('success', 'Statut ajouté.');
    }

    public function destroyStatus(TicketStatus $status)
    {
        $status->delete();
        return back()->with('success', 'Statut supprimé.');
    }

    // Priorities
    public function storePriority(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'couleur' => 'required|string|max:50',
            'delai_sla_heures' => 'required|integer|min:1',
        ]);

        TicketPriority::create($validated);
        return back()->with('success', 'Priorité SLA ajoutée.');
    }

    public function destroyPriority(TicketPriority $priority)
    {
        $priority->delete();
        return back()->with('success', 'Priorité supprimée.');
    }

    // Ticket Categories
    public function storeTicketCategory(Request $request)
    {
        $validated = $request->validate(['nom' => 'required|string|max:100']);
        TicketCategory::create($validated);
        return back()->with('success', 'Catégorie de ticket ajoutée.');
    }

    public function destroyTicketCategory(TicketCategory $category)
    {
        $category->delete();
        return back()->with('success', 'Catégorie supprimée.');
    }

    // Machine Categories
    public function storeMachineCategory(Request $request)
    {
        $validated = $request->validate(['nom' => 'required|string|max:100']);
        MachineCategory::create([
            'nom' => $validated['nom'],
            'slug' => Str::slug($validated['nom']),
        ]);
        return back()->with('success', 'Catégorie machine ajoutée.');
    }

    public function destroyMachineCategory(MachineCategory $category)
    {
        $category->delete();
        return back()->with('success', 'Catégorie machine supprimée.');
    }
}