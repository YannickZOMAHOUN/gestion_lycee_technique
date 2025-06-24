@extends('layouts.template')

@section("another_CSS")
<link rel="stylesheet" href="{{ asset('css/datatable/bootstrap.css') }}">
<link rel="stylesheet" href="{{ asset('css/datatable/dataTables.bootstrap4.min.css') }}">
@endsection

@section("content")

<div class="d-flex justify-content-between my-2 flex-wrap">
    <div class="text-color-avt fs-22 font-medium">Liste des élèves</div>
    <div>
        <a class="btn btn-success fs-14" href="{{ route('student.create') }}">
            <i class="fas fa-plus"></i> Nouvel Elève
        </a>
    </div>
</div>

<!-- Formulaire de sélection de la classe et de l'année scolaire -->
<form method="GET" action="{{ route('student.index') }}" class="mb-4">
    <div class="row">
        <div class="col-12 col-md-6 mb-3">
            <label for="classroom" class="font-medium form-label fs-16 text-label">
                Classe
            </label>
            <select class="form-select bg-form" name="classroom" id="classroom"
                aria-label="Sélectionnez une classe">
                <option selected disabled class="text-secondary">Choisissez la classe</option>
                @foreach ($classrooms as $classroom)
                    <option value="{{ $classroom->id }}">{{ $classroom->classroom }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-12 col-md-6 mb-3">
            <label for="year" class="font-medium form-label fs-16 text-label">
                Année Scolaire
            </label>
            <select class="form-select bg-form" name="year" id="year" aria-label="Sélectionnez l'année scolaire">
                <option selected disabled class="text-secondary">Choisissez l'année scolaire</option>
                @foreach ($years as $year)
                    <option value="{{ $year->id }}">{{ $year->year }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <input type="hidden" id="school_id" value="{{ $school_id }}"> <!-- Champ caché pour school_id -->
</form>

<div class="card p-3">
    <div class="table-responsive">
        <table class="table mt-3" id="students">
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
            <tbody>
                <tr>
                    <td colspan="7" class="text-center">Sélectionnez l'année scolaire et la classe pour afficher la liste des élèves</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal de suppression générique -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    Êtes-vous sûr de vouloir supprimer cet élève ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<!-- Bootstrap JS (v5) -->
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function () {
        // Charger dynamiquement les élèves lorsque la classe et l'année sont sélectionnées
        $('#classroom, #year').change(function () {
            let yearId = $('#year').val();
            let classroomId = $('#classroom').val();
            let schoolId = $('#school_id').val();  // Récupérer school_id

            if (yearId && classroomId && schoolId) {
                $.ajax({
                    url: '{{ route("get.student.lists") }}',
                    type: 'GET',
                    data: {
                        year_id: yearId,
                        classroom_id: classroomId,
                        school_id: schoolId  // Inclure school_id dans la requête
                    },
                    success: function (data) {
                        let studentsHtml = '';
                        if (data.students.length) {
                            data.students.forEach(function (student) {
                                console.log(student.id);
                                let formattedBirthday = formatDate(student.birthday);

                                let showUrl = '{{ route("note.show", [":id"]) }}';
                                showUrl = showUrl.replace(':id', student.id) + `?year=${yearId}&classroom=${classroomId}`;

                                let editUrl = '{{ route("student.edit", ":id") }}';
                                editUrl = editUrl.replace(':id', student.id);

                                studentsHtml += `
                                    <tr>
                                        <td>${student.matricule}</td>
                                        <td>${student.name}</td>
                                        <td>${student.surname}</td>
                                        <td>${student.sex}</td>
                                        <td>${formattedBirthday}</td>
                                        <td>${student.birthplace}</td>
                                       <td class="text-center" style="cursor: pointer">
                                            <a class="text-decoration-none text-secondary" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Moyenne de l'élève" href="${showUrl}"> <i class="fas fa-eye"></i> </a>
                                            &nbsp;
                                            <a class="text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Modifier les informations" href="${editUrl}"> <i class="fas fa-pen"></i> </a>
                                            &nbsp;
                                            <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="top" title="Supprimer l'élève" class="delete-button" data-id="${student.id}">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                `;
                            });
                            $('#students tbody').html(studentsHtml);
                        } else {
                            $('#students tbody').html('<tr><td colspan="7" class="text-center">Aucun élève trouvé.</td></tr>');
                        }

                        // Initialiser les tooltips
                        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                          return new bootstrap.Tooltip(tooltipTriggerEl);
                        });
                    },
                    error: function () {
                        alert('Une erreur est survenue lors du chargement des élèves.');
                    }
                });
            }
        });

        // Fonction de formatage de la date
        function formatDate(dateString) {
            let date = new Date(dateString);
            let day = String(date.getDate()).padStart(2, '0');
            let month = String(date.getMonth() + 1).padStart(2, '0');
            let year = date.getFullYear();
            return `${day}/${month}/${year}`;
        }

        // Gestion du clic sur le bouton de suppression
        $(document).on('click', '.delete-button', function () {
            let studentId = $(this).data('id');
            let deleteUrl = '{{ route("student.destroy", ":id") }}'.replace(':id', studentId);
            $('#deleteForm').attr('action', deleteUrl);
            $('#deleteModal').modal('show');
        });
    });
</script>
@endsection
