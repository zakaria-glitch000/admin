<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketPriority;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketPriorityController extends Controller
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
        $priorities = TicketPriority::all();
        return view('admin.priorities.index', compact('priorities'));
    }

    public function create()
    {
        return view('admin.priorities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'couleur' => 'required|string|max:50',
            'delai_sla_heures' => 'required|integer|min:1',
        ]);

        TicketPriority::create($validated);

        return redirect()->route('admin.priorities.index')->with('success', 'Priorité SLA ajoutée avec succès.');
    }

    public function show(TicketPriority $priority)
    {
        return view('admin.priorities.show', compact('priority'));
    }

    public function edit(TicketPriority $priority)
    {
        return view('admin.priorities.edit', compact('priority'));
    }

    public function update(Request $request, TicketPriority $priority)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'couleur' => 'required|string|max:50',
            'delai_sla_heures' => 'required|integer|min:1',
        ]);

        $priority->update($validated);

        return redirect()->route('admin.priorities.index')->with('success', 'Priorité SLA mise à jour avec succès.');
    }

    public function destroy(TicketPriority $priority)
    {
        $priority->delete();
        return redirect()->route('admin.priorities.index')->with('success', 'Priorité supprimée avec succès.');
    }
}