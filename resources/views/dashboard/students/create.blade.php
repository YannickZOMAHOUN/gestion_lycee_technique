@extends('layouts.template')

@section('content')

<div class="row col-12 pb-5">
    <div class="d-flex justify-content-between my-2 flex-wrap">
        <div class="text-color-avt fs-22 font-medium">Importation de la liste des élèves</div>
        <div>
            <a class="btn btn-success fs-14" href="{{ route('student.index') }}">
                <i class="fas fa-list"></i> Liste des Elèves
            </a>
        </div>
    </div>

    <div class="card py-5">
        <form action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row mb-3">
                <div class="col-md-3">
                    <label>Année scolaire :</label>
                    <select name="year_id" id="import_year_id" class="form-control" required>
                        <option value="">-- Choisissez une année --</option>
                        @foreach($years as $year)
                            <option value="{{ $year->id }}">{{ $year->year }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Filière :</label>
                    <select name="sector_id" id="import_sector_id" class="form-control" disabled required></select>
                </div>

                <div class="col-md-3">
                    <label>Promotion :</label>
                    <select name="promotion_id" id="import_promotion_id" class="form-control" disabled required></select>
                </div>

                <div class="col-md-3">
                    <label>Classe :</label>
                    <select name="classroom_id" id="import_classroom_id" class="form-control" disabled required></select>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="file">Fichier des élèves:</label>
                <input type="file" name="file" id="file" class="form-control" required>
            </div>

            <div class="row d-flex justify-content-center mt-2">
                <button type="reset" class="btn bg-secondary w-auto me-2 text-white">Annuler</button>
                <button type="submit" class="btn btn-success w-auto">Importer la liste des élèves</button>
            </div>
        </form>
    </div>
</div>

<div class="row col-12 pb-5">
    <div class="my-3">
        <h4 class="font-medium text-color-avt">Ajouter un élève</h4>
    </div>

    <div class="card py-5">
        <form action="{{ route('student.store') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="year_id">Année scolaire:</label>
                    <select name="year_id" id="year_id" class="form-control" required>
                        <option value="">-- Choisissez une année --</option>
                        @foreach($years as $year)
                            <option value="{{ $year->id }}">{{ $year->year }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="classroom_id">Classe:</label>
                    <select name="classroom_id" id="classroom_id" class="form-control" disabled required></select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="matricule">Matricule</label>
                    <input type="text" name="matricule" id="matricule" class="form-control bg-form" value="{{ old('matricule') }}">
                </div>

                <div class="col-md-6">
                    <label for="sex" class="form-label font-medium text-color-avt">Sexe</label>
                    <select name="sex" id="sex" class="form-control" required>
                        <option value="M" {{ old('sex') == 'M' ? 'selected' : '' }}>Masculin</option>
                        <option value="F" {{ old('sex') == 'F' ? 'selected' : '' }}>Féminin</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label font-medium text-color-avt">Nom de l'élève</label>
                    <input type="text" name="name" id="name" class="form-control bg-form" value="{{ old('name') }}">
                </div>
                <div class="col-md-6">
                    <label for="surname" class="form-label font-medium text-color-avt">Prénom</label>
                    <input type="text" name="surname" id="surname" class="form-control bg-form" value="{{ old('surname') }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="birthday" class="form-label font-medium text-color-avt">Date de Naissance</label>
                    <input type="date" name="birthday" id="birthday" class="form-control bg-form" value="{{ old('birthday') }}">
                </div>
                <div class="col-md-6">
                    <label for="birthplace" class="form-label font-medium text-color-avt">Lieu de Naissance</label>
                    <input type="text" name="birthplace" id="birthplace" class="form-control bg-form" value="{{ old('birthplace') }}">
                </div>
            </div>

            <div class="row d-flex justify-content-center mt-2">
                <button type="reset" class="btn bg-secondary w-auto me-2 text-white">Annuler</button>
                <button type="submit" class="btn btn-success w-auto">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('another_JS')
<script>
    // Fonctions communes pour remplir <select>
    async function fetchOptions(url, selectElement, placeholder = '-- Choisissez --') {
        selectElement.innerHTML = `<option value="">${placeholder}</option>`;
        selectElement.disabled = true;
        try {
            const res = await fetch(url);
            const data = await res.json();
            data.forEach(item => {
                selectElement.innerHTML += `<option value="${item.id}">${item.name}</option>`;
            });
            selectElement.disabled = false;
        } catch (e) {
            console.error('Erreur chargement options:', e);
        }
    }

    // Import form selects
    const importYear = document.getElementById('import_year_id');
    const importSector = document.getElementById('import_sector_id');
    const importPromotion = document.getElementById('import_promotion_id');
    const importClassroom = document.getElementById('import_classroom_id');

    importYear.addEventListener('change', () => {
        const yearId = importYear.value;
        if (!yearId) {
            importSector.innerHTML = '';
            importSector.disabled = true;
            importPromotion.innerHTML = '';
            importPromotion.disabled = true;
            importClassroom.innerHTML = '';
            importClassroom.disabled = true;
            return;
        }
        fetchOptions(`/api/sectors-by-year/${yearId}`, importSector, '-- Choisissez une filière --');
        importPromotion.innerHTML = '';
        importPromotion.disabled = true;
        importClassroom.innerHTML = '';
        importClassroom.disabled = true;
    });

    importSector.addEventListener('change', () => {
        const yearId = importYear.value;
        const sectorId = importSector.value;
        if (!sectorId) {
            importPromotion.innerHTML = '';
            importPromotion.disabled = true;
            importClassroom.innerHTML = '';
            importClassroom.disabled = true;
            return;
        }
        fetchOptions(`/api/promotions-by-year-sector/${yearId}/${sectorId}`, importPromotion, '-- Choisissez une promotion --');
        importClassroom.innerHTML = '';
        importClassroom.disabled = true;
    });

    importPromotion.addEventListener('change', () => {
        const promotionId = importPromotion.value;
        if (!promotionId) {
            importClassroom.innerHTML = '';
            importClassroom.disabled = true;
            return;
        }
        fetchOptions(`/api/classes-by-promotion/${promotionId}`, importClassroom, '-- Choisissez une classe --');
    });


    // Add form selects
    const addYear = document.getElementById('year_id');
    const addClassroom = document.getElementById('classroom_id');

    addYear.addEventListener('change', () => {
        const yearId = addYear.value;
        if (!yearId) {
            addClassroom.innerHTML = '';
            addClassroom.disabled = true;
            return;
        }
        // Pour simplifier ici, on récupère toutes les classes liées à l'année, ou mieux, les classes de toutes promotions pour cette année
        // Supposons que classes sont liées à l'année via promotions
        fetch(`/api/classes-by-year/${yearId}`)
            .then(res => res.json())
            .then(data => {
                addClassroom.innerHTML = '<option value="">-- Choisissez une classe --</option>';
                data.forEach(c => {
                    addClassroom.innerHTML += `<option value="${c.id}">${c.name}</option>`;
                });
                addClassroom.disabled = false;
            })
            .catch(e => {
                console.error('Erreur chargement classes:', e);
                addClassroom.innerHTML = '';
                addClassroom.disabled = true;
            });
    });
</script>
@endsection
