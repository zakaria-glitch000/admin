<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketCategoryController extends Controller
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
        $ticketCategories = TicketCategory::all();
        return view('admin.ticket-categories.index', compact('ticketCategories'));
    }

    public function create()
    {
        return view('admin.ticket-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100|unique:ticket_categories,nom',
        ]);

        TicketCategory::create($validated);

        return redirect()->route('admin.ticket-categories.index')->with('success', 'Catégorie de ticket ajoutée avec succès.');
    }

    public function show(TicketCategory $category)
    {
        return view('admin.ticket-categories.show', compact('category'));
    }

    public function edit(TicketCategory $category)
    {
        return view('admin.ticket-categories.edit', compact('category'));
    }

    public function update(Request $request, TicketCategory $category)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100|unique:ticket_categories,nom,' . $category->id,
        ]);

        $category->update($validated);

        return redirect()->route('admin.ticket-categories.index')->with('success', 'Catégorie mise à jour avec succès.');
    }

    public function destroy(TicketCategory $category)
    {
        $category->delete();
        return redirect()->route('admin.ticket-categories.index')->with('success', 'Catégorie supprimée avec succès.');
    }
}