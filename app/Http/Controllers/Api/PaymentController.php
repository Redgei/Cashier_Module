<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Billing;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; // Import DB facade for transactions

class PaymentController extends Controller
{
    /**
     * Store a newly created payment in storage.
     * POST /api/payments
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'student_id' => 'required|exists:students,student_id',
            'full_name' => 'required|string|max:255',
            'billing_id' => 'nullable|exists:billings,id',
            'amount' => 'required|numeric|min:0.01', // Amount paid must be positive
            'payment_method' => ['required', 'string', Rule::in(['cash', 'card', 'bank_transfer', 'online'])],
        ]);

        // Start a database transaction
        DB::beginTransaction();

        try {
            $billing = null;
            $totalFeeAmount = $validatedData['amount']; // Default to payment amount if no billing

            if (isset($validatedData['billing_id'])) {
                $billing = Billing::findOrFail($validatedData['billing_id']);
                $totalFeeAmount = $billing->total_amount; // Use billing's total amount

                // Calculate new balance for the billing
                // Assuming 'balance' in billing table tracks remaining amount to be paid
                // If billing doesn't have a balance column, you might need to add one or calculate from payments
                // For now, let's assume we update the billing status based on payments
                // This logic needs to be refined based on how you track billing payments
                // For simplicity, let's assume we update billing status here.
                // A more robust solution would involve a 'payments_sum' on billing or a dedicated balance column.

                // Example: Update billing status (this is a simplified approach)
                // In a real system, you'd sum all payments for this billing and compare to total_amount
                $currentPaidAmount = Payment::where('billing_id', $billing->id)->sum('amount');
                $newPaidAmount = $currentPaidAmount + $validatedData['amount'];

                if ($newPaidAmount >= $billing->total_amount) {
                    $billing->status = 'paid';
                } elseif ($newPaidAmount > 0 && $newPaidAmount < $billing->total_amount) {
                    $billing->status = 'partially_paid';
                } else {
                    $billing->status = 'pending'; // Should not happen if amount > 0
                }
                $billing->save();
            }

            // Create the payment record
            $payment = Payment::create([
                'student_id' => $validatedData['student_id'],
                'full_name' => $validatedData['full_name'],
                'billing_id' => $validatedData['billing_id'] ?? null,
                'total_amount' => $totalFeeAmount, // This should be the total amount of the billing/fee
                'amount' => $validatedData['amount'],
                'balance' => $totalFeeAmount - $validatedData['amount'], // Initial balance after this payment
                'payment_method' => $validatedData['payment_method'],
                'status' => 'paid', // Assuming payment is successful upon creation
                'paid_at' => now(),
            ]);

            DB::commit(); // Commit the transaction

            return response()->json([
                'success' => true,
                'message' => 'Payment accepted successfully',
                'data' => $payment
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            return response()->json([
                'success' => false,
                'message' => 'Payment failed: ' . $e->getMessage()
            ], 500);
        }
    }
}