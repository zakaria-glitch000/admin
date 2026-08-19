<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            
            if ($user->hasRole('Admin') || $user->email === 'admin@gmail.com') {
                return $next($request);
            }

            $routeName = $request->route()->getActionMethod();

            if (in_array($routeName, ['index', 'show']) && !$user->can('user-list')) {
                abort(403, 'Accès non autorisé.');
            }
            if (in_array($routeName, ['create', 'store']) && !$user->can('user-create')) {
                abort(403, 'Accès non autorisé.');
            }
            if (in_array($routeName, ['edit', 'update']) && !$user->can('user-edit')) {
                abort(403, 'Accès non autorisé.');
            }
            if (in_array($routeName, ['destroy']) && !$user->can('user-delete')) {
                abort(403, 'Accès non autorisé.');
            }

            return $next($request);
        });
    }

    public function index(Request $request): View
    {
        // تحديد التبويب الحالي (افتراضياً internes)
        $tab = $request->get('tab', 'internes');

        $query = User::with('roles');

        // التصفية حسب التبويب
        if ($tab === 'clients') {
            $query->whereHas('roles', function($q) {
                $q->where('name', 'Client');
            });
            $roles = ['Client' => 'Client'];
        } else {
            $query->whereDoesntHave('roles', function($q) {
                $q->where('name', 'Client');
            });
            $roles = Role::where('name', '!=', 'Client')->pluck('name', 'name')->all();
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->role($request->role);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $data = $query->orderBy('id', 'DESC')->paginate(10)->withQueryString();
        $users = $data; 

        return view('users.index', compact('data', 'users', 'roles', 'tab'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    public function create(): View
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'nullable|string|max:20',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $input['is_active'] = $request->has('is_active') ? true : false;

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        // إعادة التوجيه للتبويب المناسب حسب الدور المختار
        $tab = in_array('Client', (array)$request->input('roles')) ? 'clients' : 'internes';

        return redirect()->route('users.index', ['tab' => $tab])
                        ->with('success', 'Utilisateur créé avec succès.');
    }

    public function show($id): View
    {
        $user = User::with('roles')->findOrFail($id);
        return view('users.show', compact('user'));
    }

    public function edit($id): View
    {
        $user = User::findOrFail($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('users.edit', compact('user', 'roles', 'userRole'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'nom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'telephone' => 'nullable|string|max:20',
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));    
        }

        $input['is_active'] = $request->has('is_active') ? true : false;

        $user = User::findOrFail($id);
        $user->update($input);

        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $user->assignRole($request->input('roles'));

        $tab = $user->hasRole('Client') ? 'clients' : 'internes';

        return redirect()->route('users.index', ['tab' => $tab])
                        ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy($id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $tab = $user->hasRole('Client') ? 'clients' : 'internes';
        
        $user->delete();

        return redirect()->route('users.index', ['tab' => $tab])
                        ->with('success', 'Utilisateur supprimé avec succès.');
    }
}