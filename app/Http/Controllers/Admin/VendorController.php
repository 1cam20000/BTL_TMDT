<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class VendorController extends Controller
{
    public function index()
    {
        return view('admin.vendors.index');
    }

    public function getVendorData()
    {
        $vendors = Vendor::select(['id', 'name', 'email', 'phone', 'status']);

        return DataTables::of($vendors)
            ->addColumn('action', function ($vendor) {
                return '<span class="border border-danger dt-trash rounded-3 d-inline-block" onclick="deleteVendor('.$vendor->id.')"><i class="bi bi-trash-fill text-danger"></i></span>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return view('admin.vendors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email',
            'password' => 'required|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive,banned',
        ]);

        Vendor::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.vendors.index')->with('success', 'Vendor registered successfully!');
    }

    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();

        return response()->json([
            'success' => true,
            'message' => __('cms.vendors.success_delete'),
        ]);
    }
}
