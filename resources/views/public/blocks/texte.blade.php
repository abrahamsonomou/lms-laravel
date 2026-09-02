<section class="py-6">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @isset($bloc['titre'])
                    <h2 class="fw-bold mb-3">{{ $bloc['titre'] }}</h2>
                @endisset
                @isset($bloc['contenu'])
                    <div class="text-body fs-5">{!! nl2br(e($bloc['contenu'])) !!}</div>
                @endisset
            </div>
        </div>
    </div>
</section>
