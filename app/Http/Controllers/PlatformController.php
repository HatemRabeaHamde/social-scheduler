<?php

namespace App\Http\Controllers;

use App\Models\Platform;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
    public function index()
    {
        $platforms = Platform::all();
        $userPlatforms = request()->user()->platforms->pluck('id')->toArray();

        return view('platforms.index', compact('platforms', 'userPlatforms'));
    }

    public function create()
    {
        return view('platforms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:platforms,name',
        ]);

        Platform::create([
            'name' => $request->name,
        ]);

        return redirect()->route('platforms.index')->with('success', 'Platform created successfully.');
    }

    public function edit(Platform $platform)
    {
        return view('platforms.edit', compact('platform'));
    }

    public function update(Request $request, Platform $platform)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:platforms,name,' . $platform->id,
        ]);

        $platform->update([
            'name' => $request->name,
        ]);

        return redirect()->route('platforms.index')->with('success', 'Platform updated successfully.');
    }

    public function destroy(Platform $platform)
    {
        $platform->users()->detach();
        $platform->delete();

        return redirect()->route('platforms.index')->with('success', 'Platform deleted successfully.');
    }

    public function toggle(Request $request, Platform $platform)
    {
        $user = $request->user();

        if ($request->has('enabled')) {
            if (! $user->platforms->contains($platform->id)) {
                $user->platforms()->attach($platform->id);
            }
        } else {
            $user->platforms()->detach($platform->id);
        }

        return redirect()->back()->with('success', 'Platform preference updated.');
    }
}
