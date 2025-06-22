@extends('layouts.template')

@section('content')
<div class="container">
    <h4 class="my-3 font-medium text-color-avt">Les Matières</h4>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
        <div class="card shadow rounded p-4">
            <form method="POST" action="{{ route('subject.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="year_id">Année scolaire :</label>
                        <select name="year_id" id="year_id" class="form-control" required>
                            <option value="">-- Choisissez une année --</option>
                            @foreach($years as $year)
                                <option value="{{ $year->id }}">{{ $year->year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="sector_id">Filière :</label>
                        <select name="sector_id" id="sector_id" class="form-control" disabled required>
                            <option value="">-- Choisissez d'abord l’année --</option>
                        </select>
                    </div>
                </div>

                <div id="global-subjects" class="mb-3" style="display: none;">
                    <label>Matières à appliquer à toutes les promotions :</label><br>
                    @foreach($allSubjects as $subject)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input global-subject" type="checkbox" value="{{ $subject->name }}" id="subject_{{ $subject->id }}">
                            <label class="form-check-label" for="subject_{{ $subject->id }}">{{ $subject->name }}</label>
                        </div>
                    @endforeach
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Promotion</th>
                                <th>Matières (séparées par ;)</th>
                            </tr>
                        </thead>
                        <tbody id="promotionBody">
                            <tr>
                                <td colspan="2" class="text-center text-muted">Sélectionnez une année et une filière</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div id="oldSubjectsTable" class="mt-4" style="display: none;">
                    <h5>Matières déjà enregistrées pour une ancienne année</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Promotion</th>
                                <th>Matières</th>
                            </tr>
                        </thead>
                        <tbody id="oldSubjectsBody"></tbody>
                    </table>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Enregistrer</button>
            </form>
        </div>
</div>
@endsection

@section('another_JS')
<script>
    const yearSelect = document.getElementById('year_id');
    const sectorSelect = document.getElementById('sector_id');
    const promotionBody = document.getElementById('promotionBody');
    const globalSubjects = document.querySelectorAll('.global-subject');
    const globalSubjectsDiv = document.getElementById('global-subjects');
    const oldSubjectsTable = document.getElementById('oldSubjectsTable');
    const oldSubjectsBody = document.getElementById('oldSubjectsBody');

    yearSelect.addEventListener('change', () => {
        const yearId = yearSelect.value;
        sectorSelect.innerHTML = `<option>Chargement...</option>`;
        sectorSelect.disabled = true;

        fetch(`/api/sectors-by-year/${yearId}`)
            .then(res => res.json())
            .then(data => {
                let options = `<option value="">-- Choisissez une filière --</option>`;
                data.forEach(sector => {
                    options += `<option value="${sector.id}">${sector.name}</option>`;
                });
                sectorSelect.innerHTML = options;
                sectorSelect.disabled = false;
            });
    });

    sectorSelect.addEventListener('change', () => {
        const yearId = yearSelect.value;
        const sectorId = sectorSelect.value;

        fetch(`/api/promotions/${yearId}/${sectorId}`)
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    promotionBody.innerHTML = `<tr><td colspan="2" class="text-center text-muted">Aucune promotion trouvée</td></tr>`;
                    return;
                }

                let rows = '';
                data.forEach(promotion => {
                    rows += `
                        <tr>
                            <td>${promotion.promotion_sector}
                                <input type="hidden" name="promotion_ids[]" value="${promotion.id}">
                            </td>
                            <td>
                                <input type="text" name="subjects_by_promotion[]" class="form-control subject-field">
                            </td>
                        </tr>
                    `;
                });

                promotionBody.innerHTML = rows;
                globalSubjectsDiv.style.display = 'block';
                oldSubjectsTable.style.display = 'block';

                const oldYearId = parseInt(yearId) - 1;
                fetch(`/api/old-subjects/${oldYearId}/${sectorId}`)
                    .then(res => res.json())
                    .then(subjects => {
                        let oldRows = '';
                        Object.values(subjects).forEach(item => {
                            oldRows += `
                                <tr>
                                    <td>${item.promotion}</td>
                                    <td>${item.subjects.join('; ')}</td>
                                </tr>
                            `;
                        });
                        oldSubjectsBody.innerHTML = oldRows;
                    });
            });
    });

    globalSubjects.forEach(cb => {
        cb.addEventListener('change', () => {
            const selectedSubjects = Array.from(globalSubjects)
                .filter(el => el.checked)
                .map(el => el.value);

            const fields = document.querySelectorAll('.subject-field');
            fields.forEach(field => {
                field.value = selectedSubjects.join('; ');
            });
        });
    });
</script>
@endsection
