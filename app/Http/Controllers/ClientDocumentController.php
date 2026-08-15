<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClientDocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            
            if (
                !$user->hasRole('Admin') && 
                $user->email !== 'admin@gmail.com' && 
                !$user->canany(['devis-list', 'facture-list'])
            ) {
                if (!$request->is('api/*')) {
                    abort(403, 'Accès non autorisé.');
                }
            }
            
            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $query = ClientDocument::with('client');

        // البحث بسمية الملف
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nom_fichier', 'like', "%{$search}%");
        }

        // الفلتر بـ Type (facture ولا devis)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // الفلتر بـ Client
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        $documents = $query->latest()->paginate(15)->withQueryString();
        $clients = Client::all();

        return view('documents.index', compact('documents', 'clients'));
    }

    public function create()
    {
        $clients = Client::all();
        return view('documents.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id'   => 'required|exists:clients,id',
            'type'        => 'required|in:devis,facture',
            'nom_fichier' => 'required|string|max:255',
            'fichier'     => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5000',
        ]);

        if ($request->hasFile('fichier')) {
            $path = $request->file('fichier')->store('clients_docs', 'public');
            $validated['chemin'] = $path;
        }

        ClientDocument::create($validated);

        return redirect()->route('documents.index')->with('success', 'Document ajouté avec succès.');
    }

    public function show(ClientDocument $document)
    {
        $document->load('client');
        return view('documents.show', compact('document'));
    }

    public function edit(ClientDocument $document)
    {
        $clients = Client::all();
        return view('documents.edit', compact('document', 'clients'));
    }

    public function update(Request $request, ClientDocument $document)
    {
        $validated = $request->validate([
            'client_id'   => 'required|exists:clients,id',
            'type'        => 'required|in:devis,facture',
            'nom_fichier' => 'required|string|max:255',
            'fichier'     => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5000',
        ]);

        if ($request->hasFile('fichier')) {
            // مسح الملف القديم إيلا كاين
            if ($document->chemin && Storage::disk('public')->exists($document->chemin)) {
                Storage::disk('public')->delete($document->chemin);
            }
            $validated['chemin'] = $request->file('fichier')->store('clients_docs', 'public');
        }

        $document->update($validated);

        return redirect()->route('documents.index')->with('success', 'Document mis à jour avec succès.');
    }

    public function destroy(ClientDocument $document)
    {
        if ($document->chemin && Storage::disk('public')->exists($document->chemin)) {
            Storage::disk('public')->delete($document->chemin);
        }

        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Document supprimé avec succès.');
    }

    // دالة التحميل بالاسم المنظم
    public function download(ClientDocument $document)
    {
        if (!$document->chemin || !Storage::disk('public')->exists($document->chemin)) {
            abort(404, 'Fichier introuvable.');
        }

        $extension = pathinfo($document->chemin, PATHINFO_EXTENSION);
        // النتيجة مثلاً: FACTURE_3223185_07-08-2026.pdf
        $fileName = strtoupper($document->type) . '_' . $document->nom_fichier . '_' . now()->format('d-m-Y') . '.' . $extension;

        return Storage::disk('public')->download($document->chemin, $fileName);
    }
}