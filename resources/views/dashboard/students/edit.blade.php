@extends('layouts.template')

@section('content')
<div class="row col-12 pb-5">
    <div class="d-flex justify-content-between my-2 flex-wrap">
        <div class="text-color-avt fs-22 font-medium">Modifier les informations de l'élève</div>
        <div>
            <a class="btn btn-success fs-14" href="{{ route('student.index') }}">
                <i class="fas fa-list"></i> Liste des Elèves
            </a>
        </div>
    </div>

    <div class="card py-5">
        <form action="{{ route('student.update', $student->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="row">
                <div class="col-12 col-md-6 mb-3">
                    <label for="classroom">Classe:</label>
                    <select class="form-select bg-form" name="classroom_id" id="classroom_id" aria-label="Default select example">
                        @foreach ($classrooms as $classroom)
                            <option value="{{ $classroom->id }}" {{ $classroom->id == old('classroom_id', $student->recordings->first()->classroom_id) ? 'selected' : '' }}>
                                {{ $classroom->classroom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-6 mb-3">
                    <label for="year_id">Année scolaire:</label>
                    <select name="year_id" id="year_id" class="form-control" required>
                        <option value="">-- Choisissez une année --</option>
                        @foreach($years as $year)
                            <option value="{{ $year->id }}" {{ $year->id == old('year_id', $student->recordings->first()->year_id) ? 'selected' : '' }}>
                                {{ $year->year }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-6 mb-3">
                    <label for="matricule">Matricule</label>
                    <input type="text" name="matricule" id="matricule" class="form-control bg-form" value="{{ old('matricule', $student->matricule) }}">
                </div>
                <div class="col-md-6">
                    <label for="sex" class="form-label font-medium text-color-avt">Sexe</label>
                    <select name="sex" id="sex" class="form-control" required>
                        <option value="M" {{ old('sex', $student->sex) == 'M' ? 'selected' : '' }}>Masculin</option>
                        <option value="F" {{ old('sex', $student->sex) == 'F' ? 'selected' : '' }}>Féminin</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label for="name" class="form-label font-medium text-color-avt">Nom de l'élève</label>
                    <input type="text" name="name" id="name" class="form-control bg-form" value="{{ old('name', $student->name) }}">
                </div>
                <div class="col-md-6">
                    <label for="surname" class="form-label font-medium text-color-avt">Prénom</label>
                    <input type="text" name="surname" id="surname" class="form-control bg-form" value="{{ old('surname', $student->surname) }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label for="birthday" class="form-label font-medium text-color-avt">Date de Naissance</label>
                    <input type="date" name="birthday" id="birthday" class="form-control bg-form" value="{{ old('birthday', $student->birthday) }}">
                </div>
                <div class="col-md-6">
                    <label for="birthplace" class="form-label font-medium text-color-avt">Lieu de Naissance</label>
                    <input type="text" name="birthplace" id="birthplace" class="form-control bg-form" value="{{ old('birthplace', $student->birthplace) }}">
                </div>
            </div>

            <div class="row d-flex justify-content-center mt-2">
                <button type="reset" class="btn bg-secondary w-auto me-2 text-white">Annuler</button>
                <button type="submit" class="btn btn-success w-auto">Modifier</button>
            </div>
        </form>
    </div>
</div>
@endsection
