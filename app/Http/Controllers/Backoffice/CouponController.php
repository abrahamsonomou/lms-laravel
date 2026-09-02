<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Catalogue\Formation;
use App\Models\Coupon\Coupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function index(): View
    {
        $coupons = Coupon::query()->withCount('formations')->latest()->paginate(15);

        return view('backoffice.coupons.index', compact('coupons'));
    }

    public function create(): View
    {
        return view('backoffice.coupons.create', ['formations' => Formation::query()->orderBy('titre')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateCoupon($request);
        $validated['active'] = $request->boolean('active');
        $validated['utilisations'] = 0;

        $coupon = Coupon::query()->create($validated);
        $coupon->formations()->sync($request->input('formations', []));

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon créé avec succès.');
    }

    public function edit(Coupon $coupon): View
    {
        return view('backoffice.coupons.edit', [
            'coupon' => $coupon->load('formations'),
            'formations' => Formation::query()->orderBy('titre')->get(),
        ]);
    }

    public function update(Request $request, Coupon $coupon): RedirectResponse
    {
        $validated = $this->validateCoupon($request, $coupon);
        $validated['active'] = $request->boolean('active');

        $coupon->update($validated);
        $coupon->formations()->sync($request->input('formations', []));

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon mis à jour.');
    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon supprimé.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateCoupon(Request $request, ?Coupon $coupon = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('coupons', 'code')->ignore($coupon?->id)],
            'nom' => ['nullable', 'string', 'max:255'],
            'type_remise' => ['required', 'in:POURCENTAGE,MONTANT'],
            'valeur' => ['required', 'numeric', 'min:0'],
            'montant_minimum' => ['nullable', 'numeric', 'min:0'],
            'date_debut' => ['nullable', 'date'],
            'date_fin' => ['nullable', 'date', 'after_or_equal:date_debut'],
            'nombre_utilisations' => ['nullable', 'integer', 'min:1'],
            'formations' => ['array'],
            'formations.*' => ['integer', 'exists:formations,id'],
        ]);
    }
}
