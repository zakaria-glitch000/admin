<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientSite;
use App\Models\SiteContrat;

class SiteContratController extends Controller
{
    /**
     * Store a newly created contract in storage.
     */
    public function store(Request $request, ClientSite $site)
    {
        $request->validate([
            'numero_contrat' => 'required|string|max:255',
            'date_debut'     => 'required|date',
            'date_fin'       => 'required|date|after_or_equal:date_debut',
        ]);

        SiteContrat::create([
            'site_id'        => $site->id,
            'numero_contrat' => $request->numero_contrat,
            'date_debut'     => $request->date_debut,
            'date_fin'       => $request->date_fin,
        ]);

        return redirect()->back()->with('success', 'تم إضافة العقد بنجاح.');
    }

    /**
     * Remove the specified contract from storage.
     */
    public function destroy(SiteContrat $contrat)
    {
        $contrat->delete();

        return redirect()->back()->with('success', 'تم حذف العقد بنجاح.');
    }
}