@extends('layouts.template')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-lg">
        <div class="d-flex justify-content-between align-items-center px-4 py-3">
            <h5 class="mb-0">
                <i class="fas fa-user-edit me-2"></i>Modification des informations d'un élève
            </h5>
            <a href="{{ route('student.index') }}" class="btn btn-light btn-sm">
                <i class="fas fa-list me-1"></i> Liste des élèves
            </a>
        </div>

        <div class="card-body px-4 py-4">
            <form action="{{ route('student.update', $student->id) }}" method="POST" id="editStudentForm">
                @csrf
                @method('PUT')

              <div class="row g-3 mb-4">
    <div class="col-md-3">
        <label for="year_id" class="form-label fw-bold">Année scolaire :</label>
        <select name="year_id" id="year_id" class="form-select shadow-sm" required>
            <option value="">-- Choisissez une année --</option>
            @foreach ($years as $year)
                <option value="{{ $year->id }}"
                    {{ old('year_id', $latestRecording->year_id ?? null) == $year->id ? 'selected' : '' }}>
                    {{ $year->year }}
                </option>
            @endforeach
        </select>
        @error('year_id')<small class="text-danger">{{ $message }}</small>@enderror
    </div>

    <div class="col-md-3">
        <label for="sector_id" class="form-label fw-bold">Filière :</label>
        <select name="sector_id" id="sector_id" class="form-select shadow-sm" required>
            <option value="">-- Choisissez une filière --</option>
            @foreach ($sectors as $sector)
                <option value="{{ $sector->id }}"
                    {{ old('sector_id', optional(optional($latestRecording->classroom)->promotionSector)->sector_id) == $sector->id ? 'selected' : '' }}>
                    {{ $sector->name_sector ?? $sector->name }}
                </option>
            @endforeach
        </select>
        @error('sector_id')<small class="text-danger">{{ $message }}</small>@enderror
    </div>

    <div class="col-md-3">
        <label for="promotion_id" class="form-label fw-bold">Promotion :</label>
        <select name="promotion_id" id="promotion_id" class="form-select shadow-sm" required>
            <option value="">-- Choisissez une promotion --</option>
            @foreach ($promotions as $promotion)
                <option value="{{ $promotion->id }}"
                    {{ old('promotion_id', optional($latestRecording->classroom)->promotion_sector_id) == $promotion->id ? 'selected' : '' }}>
                    {{ $promotion->promotion_sector ?? $promotion->name }}
                </option>
            @endforeach
        </select>
        @error('promotion_id')<small class="text-danger">{{ $message }}</small>@enderror
    </div>

    <div class="col-md-3">
        <label for="classroom_id" class="form-label fw-bold">Classe :</label>
        <select name="classroom_id" id="classroom_id" class="form-select shadow-sm" required>
            <option value="">-- Choisissez une classe --</option>
            @foreach ($classrooms as $classroom)
                <option value="{{ $classroom->id }}"
                    {{ old('classroom_id', $latestRecording->classroom_id ?? null) == $classroom->id ? 'selected' : '' }}>
                    {{ $classroom->name }}
                </option>
            @endforeach
        </select>
        @error('classroom_id')<small class="text-danger">{{ $message }}</small>@enderror
    </div>
</div>


                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="matricule" class="form-label fw-bold">Matricule :</label>
                        <input type="text" name="matricule" id="matricule" class="form-control shadow-sm"
                               value="{{ old('matricule', $student->matricule) }}" required>
                        @error('matricule')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="sex" class="form-label fw-bold">Sexe :</label>
                        <select name="sex" id="sex" class="form-select shadow-sm" required>
                            <option value="M" {{ old('sex', $student->sex) == 'M' ? 'selected' : '' }}>Masculin</option>
                            <option value="F" {{ old('sex', $student->sex) == 'F' ? 'selected' : '' }}>Féminin</option>
                        </select>
                        @error('sex')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-bold">Nom :</label>
                        <input type="text" name="name" id="name" class="form-control shadow-sm"
                               value="{{ old('name', $student->name) }}" required>
                        @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="surname" class="form-label fw-bold">Prénom(s) :</label>
                        <input type="text" name="surname" id="surname" class="form-control shadow-sm"
                               value="{{ old('surname', $student->surname) }}" required>
                        @error('surname')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="birthday" class="form-label fw-bold">Date de naissance :</label>
                        <input type="date" name="birthday" id="birthday" class="form-control shadow-sm"
                               value="{{ old('birthday', $student->birthday) }}" required>
                        @error('birthday')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="col-md-6">
                        <label for="birthplace" class="form-label fw-bold">Lieu de naissance :</label>
                        <input type="text" name="birthplace" id="birthplace" class="form-control shadow-sm"
                               value="{{ old('birthplace', $student->birthplace) }}">
                        @error('birthplace')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="{{ route('student.index') }}" class="btn btn-outline-secondary me-3 px-4">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fas fa-save me-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('another_JS')
<script>
    // Fonction générique pour charger options dans un select
    async function fetchOptions(url, selectElement, placeholder = '-- Choisissez --') {
        selectElement.innerHTML = `<option value="">${placeholder}</option>`;
        selectElement.disabled = true;
        try {
            const res = await fetch(url);
            const data = await res.json();
            data.forEach(item => {
                selectElement.innerHTML += `<option value="${item.id}">${item.name || item.promotion_sector || item.name_sector}</option>`;
            });
            selectElement.disabled = false;
        } catch (e) {
            console.error('Erreur lors du chargement :', e);
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const yearSelect = document.getElementById('year_id');
        const sectorSelect = document.getElementById('sector_id');
        const promotionSelect = document.getElementById('promotion_id');
        const classroomSelect = document.getElementById('classroom_id');

        // Lors du changement d'année : charger les filières
        yearSelect.addEventListener('change', () => {
            const yearId = yearSelect.value;
            if (yearId) {
                fetchOptions(`/api/sectors-by-year/${yearId}`, sectorSelect, '-- Choisissez une filière --');
            }
            promotionSelect.innerHTML = '<option value="">-- Sélectionnez d\'abord la filière --</option>';
            promotionSelect.disabled = true;
            classroomSelect.innerHTML = '<option value="">-- Sélectionnez d\'abord la promotion --</option>';
            classroomSelect.disabled = true;
        });

        // Lors du changement de filière : charger les promotions
        sectorSelect.addEventListener('change', () => {
            const yearId = yearSelect.value;
            const sectorId = sectorSelect.value;
            if (yearId && sectorId) {
                fetchOptions(`/api/promotions-by-year-sector/${yearId}/${sectorId}`, promotionSelect, '-- Choisissez une promotion --');
            }
            classroomSelect.innerHTML = '<option value="">-- Sélectionnez d\'abord la promotion --</option>';
            classroomSelect.disabled = true;
        });

        // Lors du changement de promotion : charger les classes
        promotionSelect.addEventListener('change', () => {
            const promotionId = promotionSelect.value;
            if (promotionId) {
                fetchOptions(`/api/classes-by-promotion/${promotionId}`, classroomSelect, '-- Choisissez une classe --');
            }
        });
    });
</script>
@endsection
