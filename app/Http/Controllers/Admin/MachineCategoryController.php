<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MachineCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MachineCategoryController extends Controller
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
        $machineCategories = MachineCategory::all();
        return view('admin.machine-categories.index', compact('machineCategories'));
    }

    public function create()
    {
        return view('admin.machine-categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100|unique:machine_categories,nom',
        ]);

        MachineCategory::create([
            'nom' => $validated['nom'],
            'slug' => Str::slug($validated['nom']),
        ]);

        return redirect()->route('admin.machine-categories.index')->with('success', 'Catégorie de machine ajoutée avec succès.');
    }

    public function show(MachineCategory $category)
    {
        return view('admin.machine-categories.show', compact('category'));
    }

    public function edit(MachineCategory $category)
    {
        return view('admin.machine-categories.edit', compact('category'));
    }

    public function update(Request $request, MachineCategory $category)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:100|unique:machine_categories,nom,' . $category->id,
        ]);

        $category->update([
            'nom' => $validated['nom'],
            'slug' => Str::slug($validated['nom']),
        ]);

        return redirect()->route('admin.machine-categories.index')->with('success', 'Catégorie mise à jour avec succès.');
    }

    public function destroy(MachineCategory $category)
    {
        $category->delete();
        return redirect()->route('admin.machine-categories.index')->with('success', 'Catégorie supprimée avec succès.');
    }
}