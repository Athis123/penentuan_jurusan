<?php

namespace App\Http\Controllers\Personil;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('roles')->select('users.*');

            if ($request->filled('role')) {
                $data->whereHas('roles', function ($query) use ($request) {
                    $query->where('name', $request->role);
                });
            }

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('role', function ($item) {
                    return $item->roles->pluck('name')->first() ?? '-';
                })
                ->addColumn('aksi', function (User $item) {
                    $button = '<a href="' . route('admin.personil.user.edit', ['user' => $item->id]) . '" class="btn btn-xs btn-warning mr-1"><i class="far fa-edit"></i></a>';
                    $button .= '<button
                        class="btn btn-xs btn-danger btn-delete"
                        data-id="' . $item->id . '"  
                        data-url="' . route('admin.personil.user.destroy', $item->id) . '"
                        title="Hapus"><i class="fas fa-trash-alt"></i>
                    </button>';
                    return $button;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        $title = 'Data Pegawai';

        return view('personil.user.index', compact('title'));
    }

    public function create()
    {
        $title = 'Tambah Data Pegawai';
        $roles = Role::pluck('name', 'id');

        return view('personil.user.create', compact('title', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'no_hp' => 'nullable',
            'alamat' => 'nullable',
            'role' => 'required|exists:roles,id'
        ]);

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('uploads/foto_user', 'public');
        } else {
            $fotoPath = null;
        }


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' =>  $request->username,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'foto' => $fotoPath
        ]);

        $user->assignRole(Role::findById($request->role)->name);

        Alert::success('Berhasil', 'Data user berhasil ditambahkan');
        return redirect()->route('admin.personil.user.index');
    }

    public function edit(User $user)
    {
        $title = 'Edit Data Pegawai';
        $roles = Role::pluck('name', 'id');
        $userRoleId = $user->roles->pluck('id')->first();

        return view('personil.user.edit', compact('title', 'user', 'roles', 'userRoleId'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|unique:users,username,' . $user->id,
            'no_hp' => 'nullable',
            'alamat' => 'nullable',
            'role' => 'required|exists:roles,id',
            'password' => 'nullable|string|min:6',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }

            // Simpan foto baru
            $data['foto'] = $request->file('foto')->store('uploads/foto_user', 'public');
        }


        $user->update($data);

        $user->syncRoles([Role::findById($request->role)->name]);

        Alert::success('Berhasil', 'Data user berhasil diperbarui');
        return redirect()->route('admin.personil.user.index');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        // Hapus foto jika ada
        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }
        $user->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus']);
    }
}
