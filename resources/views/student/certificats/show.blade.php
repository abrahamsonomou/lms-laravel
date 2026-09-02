<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.partials.head')
    <style>
        body { background: #f5f4f8; }
        .certificate {
            background: #fff;
            border: 12px solid #754ffe;
            outline: 2px solid #754ffe;
            outline-offset: 8px;
            max-width: 900px;
            margin: 2rem auto;
            padding: 4rem 3rem;
            text-align: center;
            position: relative;
        }
        .certificate .seal {
            width: 96px; height: 96px; border-radius: 50%;
            background: #754ffe; color: #fff; display: flex; align-items: center; justify-content: center;
            font-size: 2rem; margin: 0 auto 1rem;
        }
        .certificate .student { font-size: 2.4rem; font-weight: 700; color: #754ffe; border-bottom: 2px dashed #ddd; display: inline-block; padding: 0 1rem .5rem; margin: .5rem 0 1.5rem; }
        .certificate .course { font-size: 1.4rem; font-weight: 600; }
        @media print {
            .no-print { display: none !important; }
            body { background: #fff; }
            .certificate { border-color: #754ffe; margin: 0; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center pt-4 no-print">
            <a href="{{ route('student.certificats.index') }}" class="btn btn-outline-secondary">
                <i class="fe fe-arrow-left me-1"></i> Mes certificats
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fe fe-printer me-1"></i> Imprimer / PDF
            </button>
        </div>

        <div class="certificate">
            <div class="seal"><i class="fe fe-award"></i></div>
            <p class="text-uppercase text-muted mb-1" style="letter-spacing: .2em;">{{ config('app.name', 'LMS') }}</p>
            <h1 class="fw-bold mb-3">Certificat de réussite</h1>
            <p class="mb-0">Ce certificat est décerné à</p>
            <div class="student">{{ $certificat->etudiant?->user?->name }}</div>
            <p class="mb-1">pour avoir complété avec succès la formation</p>
            <p class="course mb-4">« {{ $certificat->formation?->titre }} »</p>

            <div class="row justify-content-center mb-4">
                <div class="col-auto"><small class="text-muted d-block">Mention</small><strong>{{ $certificat->mention }}</strong></div>
                <div class="col-auto"><small class="text-muted d-block">Score</small><strong>{{ number_format($certificat->score, 2, ',', ' ') }} / 20</strong></div>
                <div class="col-auto"><small class="text-muted d-block">Date</small><strong>{{ $certificat->date_emission?->format('d/m/Y') }}</strong></div>
            </div>

            <div class="d-flex justify-content-between align-items-end mt-5 pt-4">
                <div class="text-start">
                    <small class="text-muted d-block">N° {{ $certificat->numero }}</small>
                    <small class="text-muted">Vérifiez sur : {{ route('certificats.verify', $certificat->hash_verification) }}</small>
                </div>
                <div class="text-end">
                    <div style="border-top: 1px solid #333; width: 180px; margin-left: auto;"></div>
                    <small class="text-muted">Signature</small>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.partials.scripts')
</body>
</html>
