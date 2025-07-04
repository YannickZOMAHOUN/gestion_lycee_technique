@extends('layouts.template')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-lg mb-5 border-0" style="border-radius: 15px; overflow: hidden;">
        <div class="card-header bg-gradient-primary text-white py-3" style="border-radius: 15px 15px 0 0 !important;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-light">
                    <i class="fas fa-file-import me-2"></i>Exporter les notes
                </h5>
                <i class="fas fa-star-half-alt fs-4"></i>
            </div>
        </div>

        <div class="card-body px-4 py-4 bg-light">
            <form method="GET" action="{{ route('notes.export') }}" class="needs-validation" novalidate>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-primary">Année scolaire :</label>
                        <select name="year_id" id="export_year_id" class="form-select shadow-sm rounded-pill" required>
                            <option value="">-- Choisissez une année --</option>
                            @foreach($years as $year)
                                <option value="{{ $year->id }}">{{ $year->year }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">Veuillez sélectionner une année scolaire</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-primary">Filière :</label>
                        <select name="sector_id" id="export_sector_id" class="form-select shadow-sm rounded-pill" disabled required>
                            <option value="">-- Sélectionnez d'abord l'année --</option>
                        </select>
                        <div class="invalid-feedback">Veuillez sélectionner une filière</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-primary">Promotion :</label>
                        <select name="promotion_id" id="export_promotion_id" class="form-select shadow-sm rounded-pill" disabled required>
                            <option value="">-- Sélectionnez d'abord la filière --</option>
                        </select>
                        <div class="invalid-feedback">Veuillez sélectionner une promotion</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold text-primary">Classe :</label>
                        <select name="classroom_id" id="export_classroom_id" class="form-select shadow-sm rounded-pill" disabled required>
                            <option value="">-- Sélectionnez d'abord la promotion --</option>
                        </select>
                        <div class="invalid-feedback">Veuillez sélectionner une classe</div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <label for="semester" class="form-label fw-bold text-primary">Semestre</label>
                        <div class="input-group">
                            <span class="input-group-text bg-primary text-white"><i class="fas fa-calendar-alt"></i></span>
                            <select class="form-select rounded-end" name="semester" id="semester" required>
                                <option value="" disabled selected>Choisissez le semestre</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                            </select>
                            <div class="invalid-feedback">Veuillez sélectionner un semestre</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center mt-4 gap-3">
                    <button type="reset" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-undo me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">
                        <i class="fas fa-download me-2"></i>Exporter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('another_JS')
<script>
    function fetchOptions(url, selectElement, placeholder = '-- Choisissez --') {
        selectElement.innerHTML = `<option value="">${placeholder}</option>`;
        selectElement.disabled = true;
        fetch(url).then(res => res.json()).then(data => {
            data.forEach(item => {
                selectElement.innerHTML += `<option value="${item.id}">${item.name}</option>`;
            });
            selectElement.disabled = false;
        });
    }

    function setupFormListeners(yearId, sectorId, promotionId, classroomId) {
        const yearSelect = document.getElementById(yearId);
        const sectorSelect = document.getElementById(sectorId);
        const promotionSelect = document.getElementById(promotionId);
        const classroomSelect = document.getElementById(classroomId);

        yearSelect.addEventListener('change', () => {
            const yearId = yearSelect.value;
            if (yearId) fetchOptions(`/api/sectors-by-year/${yearId}`, sectorSelect);
            promotionSelect.innerHTML = '<option value="">-- Sélectionnez d\'abord la filière --</option>';
            promotionSelect.disabled = true;
            classroomSelect.innerHTML = '<option value="">-- Sélectionnez d\'abord la promotion --</option>';
            classroomSelect.disabled = true;
        });

        sectorSelect.addEventListener('change', () => {
            const yearId = yearSelect.value;
            const sectorId = sectorSelect.value;
            if (yearId && sectorId) fetchOptions(`/api/promotions-by-year-sector/${yearId}/${sectorId}`, promotionSelect);
            classroomSelect.innerHTML = '<option value="">-- Sélectionnez d\'abord la promotion --</option>';
            classroomSelect.disabled = true;
        });

        promotionSelect.addEventListener('change', () => {
            const promotionId = promotionSelect.value;
            if (promotionId) fetchOptions(`/api/classes-by-promotion/${promotionId}`, classroomSelect);
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        setupFormListeners('export_year_id', 'export_sector_id', 'export_promotion_id', 'export_classroom_id');
    });
</script>
@endsection
