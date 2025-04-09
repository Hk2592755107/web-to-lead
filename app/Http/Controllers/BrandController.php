<?php

namespace App\Http\Controllers;
use App\Models\Brand;
use Illuminate\Support\Str;

use Illuminate\Http\Request;

class BrandController extends Controller
{


    public function index()
    {
        $brands = Brand::all();
        return view('brands.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:brands,name',
        ]);

        $brand = Brand::create([
            'name' => $request->name,
            'script_token' => Str::random(20),
        ]);

        // Generate the script tag to copy
        $script = '<script src="' . url('script.js?token=' . $brand->script_token) . '"></script>';


        return redirect()->route('brands.index')->with('success', 'Brand created successfully!')->with('script', $script);
    }


}
