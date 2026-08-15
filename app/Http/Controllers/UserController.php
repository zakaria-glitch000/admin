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
    /**
     * حماية الكونترولر: السماح للأدمن، أو إيميل الأدمن، أو من يمتلك الصلاحيات المطلوبة
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            
            // واش هو Admin ولا إيميل الأدمن؟ دوز مباشرة
            if ($user->hasRole('Admin') || $user->email === 'admin@gmail.com') {
                return $next($request);
            }

            // والا، نشيكيو على حسب الـ Route اللي مديور
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

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = User::with('roles');

        // 1. فلتر البحث بالاسم أو الإيميل أو الهاتف
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%");
            });
        }

        // 2. فلتر حسب الدور (Role)
        if ($request->filled('role')) {
            $query->role($request->role);
        }

        // 3. فلتر حسب الحالة (نشط / غير نشط)
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // استخدام withQueryString للحفاظ على الفلاتر أثناء التنقل بين صفحات الباجينيشن
        $data = $query->orderBy('id', 'DESC')->paginate(10)->withQueryString();
        $users = $data; 

        $roles = Role::pluck('name', 'name')->all();

        return view('users.index', compact('data', 'users', 'roles'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $roles = Role::pluck('name', 'name')->all();

        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
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

        return redirect()->route('users.index')
                        ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $user = User::with('roles')->findOrFail($id);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $user = User::findOrFail($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('users.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update the specified resource in storage.
     */
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

        return redirect()->route('users.index')
                        ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        User::findOrFail($id)->delete();

        return redirect()->route('users.index')
                        ->with('success', 'Utilisateur supprimé avec succès.');
    }
}