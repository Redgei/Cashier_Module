<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class BillingController extends Controller
{
    /**
     * Store a newly created billing in storage.
     * POST /api/billings
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,student_id',
            'semester' => 'required|string|max:255',
            'school_year' => 'required|string|max:255',
            'tuition_fee' => 'required|numeric|min:0',
            'misc_fee' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'status' => ['sometimes', 'string', Rule::in(['pending', 'paid', 'partially_paid', 'overdue'])],
        ]);

        $billing = Billing::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Billing created successfully',
            'data' => $billing
        ], 201);
    }

    /**
     * Display a listing of the billings.
     * GET /api/billings
     */
    public function index(): JsonResponse
    {
        $billings = Billing::all();

        return response()->json([
            'success' => true,
            'data' => $billings
        ]);
    }

    /**
     * Update the specified billing in storage.
     * PUT/PATCH /api/billings/{billing_id}
     */
    public function update(Request $request, int $billing_id): JsonResponse
    {
        $billing = Billing::findOrFail($billing_id);

        $validatedData = $request->validate([
            'student_id' => 'sometimes|required|exists:students,student_id',
            'semester' => 'sometimes|required|string|max:255',
            'school_year' => 'sometimes|required|string|max:255',
            'tuition_fee' => 'sometimes|required|numeric|min:0',
            'misc_fee' => 'sometimes|nullable|numeric|min:0',
            'total_amount' => 'sometimes|required|numeric|min:0',
            'status' => ['sometimes', 'string', Rule::in(['pending', 'paid', 'partially_paid', 'overdue'])],
        ]);

        $billing->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Billing updated successfully',
            'data' => $billing
        ], 200);
    }

    /**
     * Remove the specified billing from storage.
     * DELETE /api/billings/{billing_id}
     */
    public function destroy(int $billing_id): JsonResponse
    {
        $billing = Billing::findOrFail($billing_id);
        $billing->delete();

        return response()->json([
            'success' => true,
            'message' => 'Billing deleted successfully'
        ], 200);
    }
}