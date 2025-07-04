@extends('layouts.template')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-lg mb-5">
        <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-users me-2"></i>Liste des élèves</h5>
            <a href="{{ route('student.create') }}" class="btn btn-light btn-sm">
                <i class="fas fa-plus me-1"></i> Ajouter un élève
            </a>
        </div>

        <div class="card-body px-4 py-4">
            <div class="row g-3 mb-4">
                <!-- Année -->
                <div class="col-md-3">
                    <label class="form-label fw-bold">Année scolaire :</label>
                    <select id="year_id" class="form-select shadow-sm" required>
                        <option value="">-- Choisissez une année --</option>
                        @foreach($years as $year)
                            <option value="{{ $year->id }}">{{ $year->year }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Filière -->
                <div class="col-md-3">
                    <label class="form-label fw-bold">Filière :</label>
                    <select id="sector_id" class="form-select shadow-sm" disabled required>
                        <option value="">-- Sélectionnez d'abord l'année --</option>
                    </select>
                </div>
                <!-- Promotion -->
                <div class="col-md-3">
                    <label class="form-label fw-bold">Promotion :</label>
                    <select id="promotion_id" class="form-select shadow-sm" disabled required>
                        <option value="">-- Sélectionnez d'abord la filière --</option>
                    </select>
                </div>
                <!-- Classe -->
                <div class="col-md-3">
                    <label class="form-label fw-bold">Classe :</label>
                    <select id="classroom_id" class="form-select shadow-sm" disabled required>
                        <option value="">-- Sélectionnez d'abord la promotion --</option>
                    </select>
                </div>
            </div>

            <div class="card p-3 mt-4" id="studentsCard" style="display:none;">
                <div class="table-responsive">
                    <table class="table table-striped w-100" id="studentsTable">
                        <thead>
                            <tr>
                                <th>Matricule</th>
                                <th>Nom</th>
                                <th>Prénom(s)</th>
                                <th>Sexe</th>
                                <th>Date de Naissance</th>
                                <th>Lieu de Naissance</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="studentsTableBody">
                            <!-- Les élèves seront injectés ici -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('another_JS')
<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script>
$(document).ready(function () {
    const sectorSelect = $('#sector_id');
    const promotionSelect = $('#promotion_id');
    const classroomSelect = $('#classroom_id');
    const studentsCard = $('#studentsCard');
    const studentsTableBody = $('#studentsTableBody');

    function formatDate(dateStr) {
        const date = new Date(dateStr);
        if (isNaN(date)) return dateStr; // Retourne la valeur brute si invalide
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }

    // Lorsque l'année change, charger les filières
    $('#year_id').change(function () {
        const yearId = $(this).val();

        sectorSelect.prop('disabled', true).html('<option>Chargement...</option>');
        promotionSelect.prop('disabled', true).html('');
        classroomSelect.prop('disabled', true).html('');
        studentsCard.hide();
        studentsTableBody.html('');

        if (!yearId) {
            sectorSelect.prop('disabled', true).html('<option>-- Sélectionnez d\'abord l\'année --</option>');
            return;
        }

        $.getJSON(`/api/sectors-by-year/${yearId}`, function (data) {
            let options = '<option value="">-- Choisissez une filière --</option>';
            data.forEach(function (sector) {
                options += `<option value="${sector.id}">${sector.name}</option>`;
            });
            sectorSelect.prop('disabled', false).html(options);
        }).fail(function () {
            sectorSelect.prop('disabled', true).html('<option>Erreur de chargement</option>');
        });
    });

    // Lorsque la filière change, charger les promotions
    sectorSelect.change(function () {
        const yearId = $('#year_id').val();
        const sectorId = $(this).val();

        promotionSelect.prop('disabled', true).html('');
        classroomSelect.prop('disabled', true).html('');
        studentsCard.hide();
        studentsTableBody.html('');

        if (!sectorId) return;

        $.getJSON(`/api/promotions-by-year-sector/${yearId}/${sectorId}`, function (data) {
            let options = '<option value="">-- Choisissez une promotion --</option>';
            data.forEach(function (promotion) {
                options += `<option value="${promotion.id}">${promotion.name}</option>`;
            });
            promotionSelect.prop('disabled', false).html(options);
        }).fail(function () {
            promotionSelect.prop('disabled', true).html('<option>Erreur de chargement</option>');
        });
    });

    // Lorsque la promotion change, charger les classes
    promotionSelect.change(function () {
        const promotionId = $(this).val();

        classroomSelect.prop('disabled', true).html('');
        studentsCard.hide();
        studentsTableBody.html('');

        if (!promotionId) return;

        $.getJSON(`/api/classes-by-promotion/${promotionId}`, function (data) {
            console.log(data);
            let options = '<option value="">-- Choisissez une classe --</option>';
            data.forEach(function (classroom) {
                console.log(classroom);
                options += `<option value="${classroom.id}">${classroom.name}</option>`;
            });
            classroomSelect.prop('disabled', false).html(options);
        }).fail(function () {
            classroomSelect.prop('disabled', true).html('<option>Erreur de chargement</option>');
        });
    });

    // Lorsque la classe change, charger les élèves
    classroomSelect.change(function () {
        const yearId = $('#year_id').val();
        const sectorId = $('#sector_id').val();
        const promotionId = $('#promotion_id').val();
        const classroomId = $(this).val();

        studentsCard.hide();
        studentsTableBody.html('');

        if (!yearId || !sectorId || !promotionId || !classroomId) return;

        $.getJSON(`/students/${yearId}/${sectorId}/${promotionId}/${classroomId}`, function (response) {
            if (response.data.length === 0) {
                studentsTableBody.html('<tr><td colspan="7" class="text-center">Aucun élève trouvé</td></tr>');
            } else {
                let rows = '';
                response.data.forEach(function (student) {
                    rows += `
                        <tr>
                            <td>${student.matricule}</td>
                            <td>${student.name}</td>
                            <td>${student.surname}</td>
                            <td>${student.sex}</td>
                            <td>${formatDate(student.birthday)}</td>
                            <td>${student.birthplace}</td>
                            <td>
                                <a href="/student/${student.id}/edit" class="btn btn-sm btn-outline-secondary">Modifier</a>
                            </td>
                        </tr>
                    `;
                });
                studentsTableBody.html(rows);
            }
            studentsCard.show();
        }).fail(function () {
            studentsTableBody.html('<tr><td colspan="7" class="text-center text-danger">Erreur lors du chargement des élèves</td></tr>');
            studentsCard.show();
        });
    });
});
</script>
@endsection
