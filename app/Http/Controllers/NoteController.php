<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Year;
use App\Models\Ratio;
use App\Models\Recording;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\NotesExport;
use Maatwebsite\Excel\Facades\Excel;


class NoteController extends Controller
{
    public function create()
    {
        $years =Year::where('status', true)->get();
        return view('dashboard.notes.create', compact('years'));
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

    $year = $request->year_id;
    $classroom = $request->classroom_id;
    $semester = $request->semester;

    $filename = "Notes_Classe_{$classroom}_Semestre{$semester}.xlsx";

    return Excel::download(new NotesExport($year, $classroom, $semester), $filename);
}

  public function export_view()
    {
        $years =Year::where('status', true)->get();
        return view('dashboard.notes.export', compact('years'));
    }
}
