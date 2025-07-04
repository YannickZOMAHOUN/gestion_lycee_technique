@extends('layouts.template')

@section('content')
<div class="container mt-4">

    <div class="mb-4">
        <h3 class="text-color-avt">Bienvenue sur le système de gestion scolaire</h3>
        <p class="text-muted">Utilisez les liens rapides ci-dessous pour naviguer facilement dans l’application.</p>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center bg-light">
                <h4 class="text-success">{{ \App\Models\Year::where('status', true)->first()?->year ?? '-' }}</h4>
                <p class="mb-0">Année active</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center bg-light">
                <h4 class="text-primary">{{ \App\Models\Student::count() }}</h4>
                <p class="mb-0">Élèves enregistrés</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center bg-light">
                <h4 class="text-info">{{ \App\Models\Sector::count() }}</h4>
                <p class="mb-0">Filières</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm p-3 text-center bg-light">
                <h4 class="text-warning">{{ \App\Models\PromotionClassroom::count() }}</h4>
                <p class="mb-0">Classes créées</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm p-4">
                <h5 class="mb-3">Liens rapides</h5>
                <ul class="list-group">
                    <li class="list-group-item"><a href="{{ route('import') }}">📥 Importer les élèves</a></li>
                    <li class="list-group-item"><a href="{{ route('student.index') }}">👥 Voir la liste des élèves</a></li>
                    <li class="list-group-item"><a href="{{ route('promotion-classrooms.create') }}">🏫 Créer les classes</a></li>
                    <li class="list-group-item"><a href="{{ route('subject.create') }}">📚 Affecter les matières</a></li>
                    <li class="list-group-item"><a href="{{ route('ratio.create') }}">🧮 Gérer les coefficients</a></li>
                </ul>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm p-4">
                <h5 class="mb-3">Informations système</h5>
                <p>Dernière mise à jour : <strong>{{ now()->format('d/m/Y à H:i') }}</strong></p>
                <p>Connecté en tant que : <strong>{{ Auth::user()->name ?? 'Admin' }}</strong></p>
            </div>
        </div>
    </div>

</div>
@endsection
