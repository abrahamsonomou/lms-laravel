@extends('layouts.dashboard')

@section('title', 'Coupons')
@section('page-title', 'Coupons & promotions')

@section('page-actions')
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary"><i class="fe fe-plus me-1"></i> Nouveau coupon</a>
@endsection

@section('content')
    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0 text-nowrap">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Remise</th>
                        <th>Utilisations</th>
                        <th>Période</th>
                        <th>Portée</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($coupons as $coupon)
                        <tr>
                            <td class="align-middle"><code>{{ $coupon->code }}</code></td>
                            <td class="align-middle">
                                {{ number_format($coupon->valeur, 2, ',', ' ') }}{{ $coupon->type_remise === 'POURCENTAGE' ? ' %' : '' }}
                            </td>
                            <td class="align-middle">{{ $coupon->utilisations }}{{ $coupon->nombre_utilisations ? ' / '.$coupon->nombre_utilisations : '' }}</td>
                            <td class="align-middle">
                                {{ $coupon->date_debut?->format('d/m/Y') ?? '—' }} → {{ $coupon->date_fin?->format('d/m/Y') ?? '—' }}
                            </td>
                            <td class="align-middle">
                                {{ $coupon->formations_count > 0 ? $coupon->formations_count.' formation(s)' : 'Toutes' }}
                            </td>
                            <td class="align-middle">
                                <span class="badge bg-light-{{ $coupon->active ? 'success' : 'danger' }} text-{{ $coupon->active ? 'success' : 'danger' }}">
                                    {{ $coupon->active ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="align-middle text-end">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-outline-secondary"><i class="fe fe-edit"></i></a>
                                <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" class="d-inline" onsubmit="return confirm('Supprimer ce coupon ?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fe fe-trash-2"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-4 text-muted">Aucun coupon.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $coupons->links() }}</div>
    </div>
@endsection
