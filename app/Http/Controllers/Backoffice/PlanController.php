<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Abonnement\Plan;
use App\Models\Core\Devise;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PlanController extends Controller
{
    public function index(): View
    {
        $plans = Plan::query()->with('devise')->withCount('abonnements')->orderBy('prix')->paginate(15);

        return view('backoffice.plans.index', compact('plans'));
    }

    public function create(): View
    {
        return view('backoffice.plans.create', $this->references());
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePlan($request);
        $validated['active'] = $request->boolean('active');

        Plan::query()->create($validated);

        return redirect()->route('admin.plans.index')->with('success', 'Plan créé avec succès.');
    }

    public function edit(Plan $plan): View
    {
        return view('backoffice.plans.edit', ['plan' => $plan, ...$this->references()]);
    }

    public function update(Request $request, Plan $plan): RedirectResponse
    {
        $validated = $this->validatePlan($request, $plan);
        $validated['active'] = $request->boolean('active');

        $plan->update($validated);

        return redirect()->route('admin.plans.index')->with('success', 'Plan mis à jour.');
    }

    public function destroy(Plan $plan): RedirectResponse
    {
        $plan->delete();

        return redirect()->route('admin.plans.index')->with('success', 'Plan supprimé.');
    }

    /**
     * @return array<string, mixed>
     */
    private function references(): array
    {
        return [
            'devises' => Devise::query()->orderBy('code')->get(),
            'types' => Plan::TYPES,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validatePlan(Request $request, ?Plan $plan = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('plans', 'code')->ignore($plan?->id)],
            'nom' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'prix' => ['required', 'numeric', 'min:0'],
            'devise_id' => ['nullable', 'integer', 'exists:devises,id'],
            'duree' => ['nullable', 'integer', 'min:1'],
            'type' => ['required', Rule::in(Plan::TYPES)],
        ]);
    }
}
