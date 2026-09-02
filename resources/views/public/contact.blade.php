@extends('layouts.public')

@section('title', 'Contact')

@section('content')
    <section class="bg-light py-8 py-lg-10">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-5 fw-bold mb-3">Contactez-nous</h1>
                    <p class="lead mb-0">Une question ? Notre équipe est là pour vous aider.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-6 py-lg-8">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 col-12 mb-4 mb-lg-0">
                    <div class="card h-100">
                        <div class="card-body">
                            <h4 class="mb-4">Envoyez-nous un message</h4>
                            <form action="#" method="POST">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label for="nom" class="form-label">Nom complet <span class="text-danger">*</span></label>
                                        <input type="text" id="nom" name="nom" class="form-control" placeholder="Votre nom" required>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" id="email" name="email" class="form-control" placeholder="vous@exemple.com" required>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                        <textarea id="message" name="message" rows="5" class="form-control" placeholder="Votre message" required></textarea>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fe fe-send me-1"></i> Envoyer le message
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-12">
                    <div class="card h-100">
                        <div class="card-body">
                            <h4 class="mb-4">Nos coordonnées</h4>
                            <div class="d-flex mb-4">
                                <div class="icon-shape icon-md bg-light-primary text-primary rounded-3 me-3">
                                    <i class="fe fe-map-pin"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Adresse</h6>
                                    <p class="text-muted mb-0">123 Avenue de l'Apprentissage, 75000 Paris</p>
                                </div>
                            </div>
                            <div class="d-flex mb-4">
                                <div class="icon-shape icon-md bg-light-success text-success rounded-3 me-3">
                                    <i class="fe fe-mail"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Email</h6>
                                    <p class="text-muted mb-0">contact@{{ Str::slug(config('app.name', 'lms')) }}.com</p>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="icon-shape icon-md bg-light-info text-info rounded-3 me-3">
                                    <i class="fe fe-phone"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1">Téléphone</h6>
                                    <p class="text-muted mb-0">+33 1 23 45 67 89</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
