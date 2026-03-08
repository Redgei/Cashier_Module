<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Store a newly created student in storage.
     * POST /api/students
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('students', 'email')],
            'contact_number' => 'nullable|string|max:255',
            'course' => 'nullable|string|max:255',
            'year_level' => 'nullable|string|max:255',
        ]);

        $student = Student::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Student created successfully',
            'data' => $student
        ], 201);
    }

    /**
     * Display a listing of the students.
     * GET /api/students
     */
    public function index(): JsonResponse
    {
        $students = Student::all();

        return response()->json([
            'success' => true,
            'data' => $students
        ]);
    }

    /**
     * Display the specified student.
     * GET /api/students/{student_id}
     */
    public function show(int $student_id): JsonResponse
    {
        $student = Student::where('student_id', $student_id)->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $student
        ]);
    }

    /**
     * Remove the specified student from storage.
     * DELETE /api/students/{student_id}
     */
    public function destroy(int $student_id): JsonResponse
    {
        $student = Student::findOrFail($student_id);
        $student->delete();

        return response()->json([
            'success' => true,
            'message' => 'Student deleted successfully'
        ], 200);
    }

    /**
     * Update the specified student in storage.
     * PUT/PATCH /api/students/{student_id}
     */
    public function update(Request $request, int $student_id): JsonResponse
    {
        $student = Student::findOrFail($student_id);

        $validatedData = $request->validate([
            'full_name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'nullable', 'string', 'email', 'max:255', Rule::unique('students', 'email')->ignore($student->student_id, 'student_id')],
            'contact_number' => 'sometimes|nullable|string|max:255',
            'course' => 'sometimes|nullable|string|max:255',
            'year_level' => 'sometimes|nullable|string|max:255',
        ]);

        $student->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Student updated successfully',
            'data' => $student
        ], 200);
    }
}