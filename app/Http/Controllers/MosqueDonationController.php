<?php

namespace App\Http\Controllers;

use App\Models\Mosque;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MosqueDonationController extends Controller
{
    /**
     * Display donation page with DuitNow QR.
     */
    public function showDonationPage(Mosque $mosque)
    {
        // Generate DuitNow QR data
        $qrData = $this->generateDuitNowQRData($mosque);

        return Inertia::render('Mosques/Donations/Show', [
            'mosque' => $mosque,
            'qrCode' => base64_encode(QrCode::format('svg')
                ->size(300)
                ->errorCorrection('H')
                ->generate($qrData)),
        ]);
    }

    /**
     * List all donations for mosque admin.
     */
    public function index(Request $request, Mosque $mosque)
    {
        if (Gate::denies('view', $mosque)) {
            abort(403);
        }

        $donations = $mosque->donations()
            ->when($request->input('status'), function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->input('payment_method'), function ($query, $method) {
                return $query->where('payment_method', $method);
            })
            ->when($request->input('search'), function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('donor_name', 'like', "%{$search}%")
                        ->orWhere('receipt_number', 'like', "%{$search}%")
                        ->orWhere('reference_number', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Mosques/Donations/Index', [
            'mosque' => $mosque,
            'donations' => $donations,
            'filters' => $request->only(['status', 'payment_method', 'search']),
        ]);
    }

    /**
     * Store a new donation.
     */
    public function store(Request $request, Mosque $mosque)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'donor_name' => 'nullable|string|max:255',
            'donor_phone' => 'nullable|string|max:20',
            'donor_email' => 'nullable|email|max:255',
            'purpose' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:duitnow_qr,cash,bank_transfer',
            'reference_number' => 'required_if:payment_method,duitnow_qr,bank_transfer|nullable|string|max:255',
        ]);

        // Generate receipt number
        $receiptNumber = Donation::generateReceiptNumber();

        // Create donation record
        $donation = $mosque->donations()->create([
            'amount' => $validated['amount'],
            'donor_name' => $validated['donor_name'],
            'donor_phone' => $validated['donor_phone'],
            'donor_email' => $validated['donor_email'],
            'purpose' => $validated['purpose'],
            'notes' => $validated['notes'],
            'payment_method' => $validated['payment_method'],
            'reference_number' => $validated['reference_number'],
            'status' => $validated['payment_method'] === 'cash' ? 'completed' : 'pending',
            'receipt_number' => $receiptNumber,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Donation recorded successfully',
                'donation' => $donation,
            ]);
        }

        return redirect()->back()->with('success', 'Donation recorded successfully');
    }

    /**
     * Handle DuitNow payment callback.
     */
    public function handleDuitNowCallback(Request $request)
    {
        // Verify the callback signature
        if (!$this->verifyDuitNowCallback($request)) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Find the donation by reference number
        $donation = Donation::where('reference_number', $request->input('referenceNumber'))
            ->where('status', 'pending')
            ->firstOrFail();

        // Update donation status based on payment status
        $donation->update([
            'status' => $request->input('status') === 'SUCCESSFUL' ? 'completed' : 'failed',
        ]);

        return response()->json(['message' => 'Callback processed successfully']);
    }

    /**
     * Generate DuitNow QR data string.
     */
    private function generateDuitNowQRData(Mosque $mosque): string
    {
        // This is a placeholder for actual DuitNow QR data format
        // You'll need to replace this with actual DuitNow QR format
        $data = [
            'merchantId' => config('services.duitnow.merchant_id'),
            'merchantName' => $mosque->name,
            'amount' => '', // Dynamic amount to be filled by user
            'reference1' => $mosque->id,
            'reference2' => 'DONATION',
        ];

        // Format according to DuitNow QR specifications
        return json_encode($data);
    }

    /**
     * Verify DuitNow callback signature.
     */
    private function verifyDuitNowCallback(Request $request): bool
    {
        // This is a placeholder for actual DuitNow signature verification
        // You'll need to implement this according to DuitNow's specifications
        return true;
    }
}
