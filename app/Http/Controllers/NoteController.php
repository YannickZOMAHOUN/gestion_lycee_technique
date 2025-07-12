<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Year;
use App\Models\Ratio;
use App\Models\Recording;
use Illuminate\Http\Request;
use App\Models\PromotionClassroom;
use Illuminate\Support\Facades\DB;
use App\Exports\NotesExport;
use App\Models\Subject;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;



class NoteController extends Controller
{
    public function create()
    {
        $years =Year::where('status', true)->get();
        return view('dashboard.notes.create', compact('years'));
    }

     public function index()
    {
        $years =Year::where('status', true)->get();
        return view('dashboard.notes.list', compact('years'));
    }

    public function getStudents($classroomId, $yearId)
    {
        $recordings = Recording::with('student')
            ->where('classroom_id', $classroomId)
            ->where('year_id', $yearId)
            ->get();

        return response()->json($recordings->map(function ($rec) {
            return [
                'recording_id' => $rec->id,
                'name' => $rec->student->name,
                'surname' => $rec->student->surname,
            ];
        }));
    }


    public function getSubjectsWithRatios($classroomId, $yearId)
    {
        $ratios = Ratio::with('subject')
            ->where('classroom_id', $classroomId)
            ->where('year_id', $yearId)
            ->get();

        Log::info('Ratios chargés', ['ratios' => $ratios]);

        return response()->json($ratios);
    }

    public function getExistingNotes(Request $request)
    {
        $notes = Note::where('semester', $request->semester)
            ->where('ratio_id', $request->ratio_id)
            ->where('subject_id', $request->subject_id)
            ->whereIn('recording_id', $request->recording_ids)
            ->get();

        $type = $request->type;

        return $notes->mapWithKeys(function ($note) use ($type) {
            return [$note->recording_id => $type === 'devoir1' ? $note->devoir1 : ($type === 'devoir2' ? $note->devoir2 : null)];
        });
    }

    public function store(Request $request)
    {
        $request->validate([
            'year_id' => 'required|exists:years,id',
            'classroom_id' => 'required|exists:promotion_classrooms,id',
            'subject' => 'required|exists:ratios,id',
            'semester' => 'required|in:1,2',
            'type' => 'required|in:interro,devoir1,devoir2',
            'students' => 'required|array',
            'grades' => 'required|array',
            'students.*' => 'required|exists:recordings,id',
            'grades.*' => 'nullable|numeric|min:0|max:20'
        ]);

        try {
            DB::beginTransaction();
            $ratio = Ratio::with('subject')->findOrFail($request->subject);
            foreach ($request->students as $index => $recordingId) {
                $noteValue = $request->grades[$index];

                if (is_null($noteValue) || $noteValue === '') continue;

                $note = Note::firstOrNew([
                    'recording_id' => $recordingId,
                    'subject_id' => $ratio->subject_id,
                    'ratio_id' => $ratio->id,
                    'semester' => $request->semester,
                ]);

                if ($request->type === 'interro') {
                    $interros = is_array($note->interros) ? $note->interros : [];
                    if (count($interros) < 5) {
                        $interros[] = round($noteValue, 2);
                        $note->interros = $interros;
                    }
                } elseif ($request->type === 'devoir1') {
                    $note->devoir1 = round($noteValue, 2);
                } elseif ($request->type === 'devoir2') {
                    $note->devoir2 = round($noteValue, 2);
                }

                $note->save();
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Les notes ont été enregistrées avec succès.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function export(Request $request)
    {
        $request->validate([
            'year_id' => 'required|exists:years,id',
            'classroom_id' => 'required|exists:promotion_classrooms,id',
            'semester' => 'required|in:1,2',
        ]);

        try {
            // Récupération du nom de la classe
            $classroom = PromotionClassroom::findOrFail($request->classroom_id);
            $className = str_replace(' ', '_', $classroom->name); // Optionnel : remplace les espaces par des underscores

            // Génération du nom de fichier
            $filename = "Notes_Classe_{$className}_Semestre{$request->semester}.xlsx";

            return Excel::download(
                new NotesExport($request->year_id, $request->classroom_id, $request->semester),
                $filename
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'export : ' . $e->getMessage());
        }
    }

    public function export_view()
    {
        $years = Year::where('status', true)->get();
        return view('dashboard.notes.export', compact('years'));
    }

 public function byClassAndSemester($classroom_id, $year_id, $semester)
{
    $students = Recording::with(['student', 'notes' => function ($q) use ($semester) {
        $q->where('semester', $semester)->with('subject');
    }])->where('classroom_id', $classroom_id)
      ->where('year_id', $year_id)
      ->get();

    return $students->map(function ($rec) {
        return [
            'id' => $rec->id,
            'name' => $rec->student->name,
            'surname' => $rec->student->surname,
            'matricule' => $rec->student->matricule,
            'notes' => $rec->notes->map(function ($note) {
                return [
                    'id' => $note->id,
                    'subject' => $note->subject->name,
                    'interros' => $note->interros ?? [],
                    'devoir1' => $note->devoir1,
                    'devoir2' => $note->devoir2,
                ];
            }),
        ];
    });
}

public function update(Request $request, Note $note)
{
    $note->update([
        'interros' => $request->input('interros'),
        'devoir1' => $request->input('devoir1'),
        'devoir2' => $request->input('devoir2'),
    ]);

    return response()->json(['message' => 'Note mise à jour avec succès']);
}


}
