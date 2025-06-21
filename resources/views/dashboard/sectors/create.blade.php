@extends('layouts.template')

@section('another_CSS')
    <link rel="stylesheet" href="{{ asset('css/datatable/dataTables.checkboxes.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2-bootstrap-5-theme.min.css') }}">
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center pb-5">
        <div class="col-12">
            <h4 class="my-3 font-medium text-color-avt">Filière(s) disponibles</h4>
            <div class="card shadow rounded p-4">
                <form action="{{ route('sector.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="year" class="form-label fs-6 text-label">Année Scolaire</label>
                        <select class="form-select bg-form" name="year" id="year" required>
                            <option selected disabled>Choisissez l'année scolaire</option>
                            @foreach ($years as $year)
                                <option value="{{ $year->id }}">{{ $year->year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="d-flex justify-content-between my-2 flex-wrap">
                        <div>
                            <a class="btn btn-success fs-14" href="">
                                <i class="fas fa-plus"></i> Nouvelle filière
                            </a>
                        </div>
                    </div>

                    <div class="mb-3 d-flex gap-2">
                        <button type="button" id="selectAll" class="btn btn-sm btn-outline-primary d-none">Tout Sélectionner</button>
                        <button type="button" id="unselectAll" class="btn btn-sm btn-outline-danger d-none">Tout Décocher</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Filière</th>
                                    <th>Disponible</th>
                                </tr>
                            </thead>
                            <tbody id="sectorTableBody">
                                <!-- Les lignes seront injectées ici -->
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 text-end">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- JS --}}
<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#year').change(function () {
            let yearId = $(this).val();
            if (!yearId) return;

            $('#sectorTableBody').html('<tr><td colspan="2" class="text-center">Chargement...</td></tr>');

            $.get('/sectors/by-year/' + yearId, function (data) {
                let rows = '';

                if (data.sectors.length === 0) {
                    rows = `<tr><td colspan="2" class="text-center text-muted">Aucune filière disponible pour cette année.</td></tr>`;
                    $('#selectAll, #unselectAll').addClass('d-none');
                } else {
                    data.sectors.forEach(sector => {
                        let isChecked = data.selected.includes(sector.id) ? 'checked' : '';
                        rows += `
                            <tr>
                                <td>${sector.name_sector}</td>
                                <td>
                                    <input type="checkbox" name="sectors[]" value="${sector.id}" class="sector-checkbox" ${isChecked}>
                                </td>
                            </tr>
                        `;
                    });
                    $('#selectAll, #unselectAll').removeClass('d-none');
                }

                $('#sectorTableBody').html(rows);
            });
        });

        $('#selectAll').click(function () {
            $('.sector-checkbox').prop('checked', true);
        });

        $('#unselectAll').click(function () {
            $('.sector-checkbox').prop('checked', false);
        });
    });
</script>
@endsection
