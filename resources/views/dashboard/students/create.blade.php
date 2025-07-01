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
        <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
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
        <form id="addStudentForm" action="{{ route('student.store') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="add_year_id">Année scolaire:</label>
                    <select name="year_id" id="add_year_id" class="form-control" required>
                        <option value="">-- Choisissez une année --</option>
                        @foreach($years as $year)
                            <option value="{{ $year->id }}" {{ old('year_id') == $year->id ? 'selected' : '' }}>{{ $year->year }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="add_sector_id">Filière :</label>
                    <select name="sector_id" id="add_sector_id" class="form-control" disabled required></select>
                </div>

                <div class="col-md-3">
                    <label for="add_promotion_id">Promotion :</label>
                    <select name="promotion_id" id="add_promotion_id" class="form-control" disabled required></select>
                </div>

                <div class="col-md-3">
                    <label for="add_classroom_id">Classe :</label>
                    <select name="classroom_id" id="add_classroom_id" class="form-control" disabled required></select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="matricule">Matricule</label>
                    <input type="text" name="matricule" id="matricule" class="form-control bg-form" value="{{ old('matricule') }}">
                </div>

                <div class="col-md-6">
                    <label for="sex">Sexe</label>
                    <select name="sex" id="sex" class="form-control" required>
                        <option value="M" {{ old('sex') == 'M' ? 'selected' : '' }}>Masculin</option>
                        <option value="F" {{ old('sex') == 'F' ? 'selected' : '' }}>Féminin</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name">Nom</label>
                    <input type="text" name="name" id="name" class="form-control bg-form" value="{{ old('name') }}">
                </div>
                <div class="col-md-6">
                    <label for="surname">Prénom</label>
                    <input type="text" name="surname" id="surname" class="form-control bg-form" value="{{ old('surname') }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="birthday">Date de Naissance</label>
                    <input type="date" name="birthday" id="birthday" class="form-control bg-form" value="{{ old('birthday') }}">
                </div>
                <div class="col-md-6">
                    <label for="birthplace">Lieu de Naissance</label>
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
    // Fonction générique de chargement dynamique
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
            console.error('Erreur de chargement :', e);
        }
    }

    // -------------------- Import --------------------
    const importYear = document.getElementById('import_year_id');
    const importSector = document.getElementById('import_sector_id');
    const importPromotion = document.getElementById('import_promotion_id');
    const importClassroom = document.getElementById('import_classroom_id');

    importYear.addEventListener('change', () => {
        const yearId = importYear.value;
        fetchOptions(`/api/sectors-by-year/${yearId}`, importSector, '-- Choisissez une filière --');
        importPromotion.innerHTML = '';
        importPromotion.disabled = true;
        importClassroom.innerHTML = '';
        importClassroom.disabled = true;
    });

    importSector.addEventListener('change', () => {
        const yearId = importYear.value;
        const sectorId = importSector.value;
        fetchOptions(`/api/promotions-by-year-sector/${yearId}/${sectorId}`, importPromotion, '-- Choisissez une promotion --');
        importClassroom.innerHTML = '';
        importClassroom.disabled = true;
    });

    importPromotion.addEventListener('change', () => {
        const promotionId = importPromotion.value;
        fetchOptions(`/api/classes-by-promotion/${promotionId}`, importClassroom, '-- Choisissez une classe --');
    });

    // -------------------- Ajout --------------------
    const addYear = document.getElementById('add_year_id');
    const addSector = document.getElementById('add_sector_id');
    const addPromotion = document.getElementById('add_promotion_id');
    const addClassroom = document.getElementById('add_classroom_id');

    addYear.addEventListener('change', () => {
        const yearId = addYear.value;
        fetchOptions(`/api/sectors-by-year/${yearId}`, addSector, '-- Choisissez une filière --');
        addPromotion.innerHTML = '';
        addPromotion.disabled = true;
        addClassroom.innerHTML = '';
        addClassroom.disabled = true;
    });

    addSector.addEventListener('change', () => {
        const yearId = addYear.value;
        const sectorId = addSector.value;
        fetchOptions(`/api/promotions-by-year-sector/${yearId}/${sectorId}`, addPromotion, '-- Choisissez une promotion --');
        addClassroom.innerHTML = '';
        addClassroom.disabled = true;
    });

    addPromotion.addEventListener('change', () => {
        const promotionId = addPromotion.value;
        fetchOptions(`/api/classes-by-promotion/${promotionId}`, addClassroom, '-- Choisissez une classe --');
    });

    // Effacer uniquement le champ de classe après soumission
    document.getElementById('addStudentForm').addEventListener('submit', function () {
        setTimeout(() => {
            addClassroom.value = '';
        }, 100); // léger délai pour éviter interférence
    });
</script>
@endsection
