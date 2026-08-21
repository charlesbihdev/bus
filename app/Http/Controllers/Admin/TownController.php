<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTownRequest;
use App\Http\Requests\UpdateTownRequest;
use App\Models\Town;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TownController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('admin/towns/index', [
            'towns' => Town::orderBy('name')->get(['id', 'name', 'region', 'is_active']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/towns/create');
    }

    public function store(StoreTownRequest $request): RedirectResponse
    {
        Town::create($request->validated());

        return to_route('admin.towns.index');
    }

    public function edit(Town $town): Response
    {
        return Inertia::render('admin/towns/edit', ['town' => $town->only('id', 'name', 'region', 'is_active')]);
    }

    public function update(UpdateTownRequest $request, Town $town): RedirectResponse
    {
        $town->update($request->validated());

        return to_route('admin.towns.index');
    }

    public function destroy(Town $town): RedirectResponse
    {
        $town->delete();

        return to_route('admin.towns.index');
    }

    public function toggle(Town $town): RedirectResponse
    {
        $town->update(['is_active' => ! $town->is_active]);

        return back();
    }
}
