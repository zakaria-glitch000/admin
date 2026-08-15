<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketStatusController extends Controller
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
        return view('admin.statuses.index', compact('statuses'));
    }

    public function store(Request $request)
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

        return redirect()->route('admin.statuses.index')->with('success', 'Statut de ticket ajouté avec succès.');
    }

    // عرض صفحة التعديل
    public function edit(TicketStatus $status)
    {
        return view('admin.statuses.edit', compact('status'));
    }

    // عرض صفحة الإضافة (Create)
    public function create()
    {
        return view('admin.statuses.create');
    }

    // عرض تفاصيل عنصر واحد (Show)
    public function show(TicketStatus $status)
    {
        return view('admin.statuses.show', compact('status'));
    }

    // حفظ التعديلات
    public function update(Request $request, TicketStatus $status)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100',
            'couleur' => 'required|string|max:50',
            'ordre' => 'required|integer',
        ]);

        $status->update([
            'nom' => $validated['nom'],
            'couleur' => $validated['couleur'],
            'ordre' => $validated['ordre'],
            'est_final' => $request->has('est_final'),
        ]);

        return redirect()->route('admin.statuses.index')->with('success', 'Statut mis à jour avec succès.');
    }

    public function destroy(TicketStatus $status)
    {
        $status->delete();
        return redirect()->route('admin.statuses.index')->with('success', 'Statut supprimé avec succès.');
    }
}