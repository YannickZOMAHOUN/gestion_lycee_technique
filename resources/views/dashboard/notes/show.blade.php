@extends('layouts.template')

@section('another_CSS')
<link rel="stylesheet" href="{{ asset('css/datatable/dataTables.checkboxes.css') }}">
<link rel="stylesheet" href="{{ asset('css/select2-bootstrap-5-theme.min.css') }}">
@endsection

@section('content')
<div class="row col-12 pb-5">
    <div class="my-3">
        <h4 class="font-medium text-color-avt">Notes et Moyenne</h4>
    </div>
    <div class="card py-5">
        <form id="note-form">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-3 mb-3">
                        <label for="year" class="font-medium form-label fs-16 text-label">Année Scolaire</label>
                    </div>
                    <div class="col-12 col-md-3 mb-3">
                        <label for="classroom" class="font-medium form-label fs-16 text-label">Classe</label>
                    </div>
                    <div class="col-12 col-md-3 mb-3">
                        <label for="student" class="font-medium form-label fs-16 text-label">Élève</label>
                    </div>
                    <div class="col-12 col-md-3 mb-3">
                        <label for="semester" class="font-medium form-label fs-16 text-label">Semestre</label>
                        <select class="form-select bg-form" name="semester" id="semester">
                            <option selected disabled class="text-secondary">Choisissez le semestre</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>
        <div id="student-info" class="mt-4"></div>
        <!-- Tableau des notes et moyennes coefficientées -->
        <table class="table mt-3" id="notes-table">
            <thead>
                <tr>
                    <th>Matière</th>
                    <th>Coefficient</th>
                    <th>Note</th>
                    <th>Moyenne Coefficientée</th>
                    <th>Appréciation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6" class="text-center">Sélectionnez un élève et un semestre pour afficher les notes</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-end">Total :</th>
                    <th id="total-moyenne-coefficiee" class="text-end"></th>
                    <th></th>
                </tr>
                <tr>
                    <th colspan="4" class="text-end">Moyenne Semestrielle :</th>
                    <th id="moyenne-generale" class="text-end"></th>
                    <th></th>
                </tr>
                <tr id="moyenne-annuelle-row" style="display: none;">
                    <th colspan="4" class="text-end">Moyenne Annuelle :</th>
                    <th id="moyenne-annuelle" class="text-end"></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>

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
                        Êtes-vous sûr de vouloir supprimer cette note ?
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
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script type="text/javascript">

    </script>
</div>
@endsection
