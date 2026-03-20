<?php

namespace App\Http\Controllers;

use App\Models\Club;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    // Shows the HTML Form
    public function create()
    {
        return view('clubs.create');
    }

    // Saves the data to the database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        Club::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect('/dashboard')->with('success', 'Club created successfully!');
    }
}