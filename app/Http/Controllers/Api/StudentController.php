<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StudentController\StoreStudentRequest;
use App\Http\Requests\Api\StudentController\UpdateStudentRequest;
use App\Http\Resources\Student\StudentResource;
use App\Models\Level;
use App\Models\Period;
use App\Models\Registration;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::all();
        $message = 'Menampilkan daftar data murid';

        if ($request->period === 'active' && is_numeric($request->level)) {
            $period = Period::where('active', true)->firstOrFail();
            $level = Level::findOrFail($request->level);
            $studentIds = Registration::where('period_id', $period->id)->where('level_id', $level->id)->pluck('student_id');
            $students = Student::whereIn('id', $studentIds)->get();
            $message = "Menampilkan daftar murid periode {$period->name} level {$level->name}";
        }

        if (is_numeric($request->period) && is_numeric($request->level)) {
            $period = Period::findOrFail($request->period);
            $level = Level::findOrFail($request->level);
            $studentIds = Registration::where('period_id', $period->id)->where('level_id', $level->id)->pluck('student_id');
            $students = Student::whereIn('id', $studentIds)->get();
            $message = "Menampilkan daftar murid periode {$period->name} level {$level->name}";
        }

        return response()->json([
            'message' => $message,
            'data' => StudentResource::collection($students)
        ]);
    }

    public function store(StoreStudentRequest $request)
    {
        $student = Student::create($request->except(['active']));
        $registration = Registration::create([
            'period_id' => $request->period_id,
            'level_id' => $request->level_id,
            'student_id' => $student->id
        ]);
        return response()->json([
            'message' => 'Berhasil membuat murid baru',
            'data' => new StudentResource($student->fresh(['registrations']))
        ], 201);
    }

    public function show(Student $student)
    {
        return response()->json([
            'message' => 'Menampilkan detail murid',
            'data' => new StudentResource($student->load(['registrations']))
        ]);
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        $student->update($request->all());
        return response()->json([
            'message' => 'Berhasil mengupdate murid',
            'data' => new StudentResource($student->fresh())
        ]);
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return response()->json([
            'message' => 'Berhasil menghapus murid'
        ]);
    }
}
