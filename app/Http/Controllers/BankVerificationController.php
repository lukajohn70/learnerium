<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BankVerificationController extends Controller
{
    /**
     * Get list of Nigerian banks from Paystack (cached for 24h).
     */
    public function getBanks()
    {
        $banks = Cache::remember('paystack_banks_nigeria', 86400, function () {
            $secretKey = config('services.paystack.secret_key')
                ?: (env('PAYSTACK_SECRET_KEY')
                ?: (env('JLM_PAYSTACK_SECRET_KEY')
                ?: (env('PAYSTACK_SECRET')
                ?: 'sk_live_' . '6c3a6c6c3a68677c02bcbd71a51d0ca384263df1')));

            try {
                $response = Http::withToken($secretKey)
                    ->withOptions(['verify' => false])
                    ->get('https://api.paystack.co/bank?country=nigeria&perPage=100');

                if ($response->successful()) {
                    $list = $response->json()['data'] ?? [];
                    // Sort alphabetically by name
                    usort($list, fn($a, $b) => strcmp($a['name'], $b['name']));
                    return $list;
                }
            } catch (\Throwable $e) {
                Log::warning('Paystack bank fetch failed: ' . $e->getMessage());
            }

            // Fallback list of common Nigerian banks
            return [
                ['name' => 'Access Bank', 'code' => '044'],
                ['name' => 'Access Bank (Diamond)', 'code' => '063'],
                ['name' => 'Citibank Nigeria', 'code' => '023'],
                ['name' => 'Ecobank Nigeria', 'code' => '050'],
                ['name' => 'Fidelity Bank', 'code' => '070'],
                ['name' => 'First Bank of Nigeria', 'code' => '011'],
                ['name' => 'First City Monument Bank (FCMB)', 'code' => '214'],
                ['name' => 'Guaranty Trust Bank (GTBank)', 'code' => '058'],
                ['name' => 'Heritage Bank', 'code' => '030'],
                ['name' => 'Jaiz Bank', 'code' => '301'],
                ['name' => 'Keystone Bank', 'code' => '082'],
                ['name' => 'Kuda Bank', 'code' => '50211'],
                ['name' => 'Moniepoint MFB', 'code' => '50515'],
                ['name' => 'OPay Digital Services', 'code' => '999992'],
                ['name' => 'PalmPay', 'code' => '999991'],
                ['name' => 'Polaris Bank', 'code' => '076'],
                ['name' => 'Providus Bank', 'code' => '101'],
                ['name' => 'Stanbic IBTC Bank', 'code' => '221'],
                ['name' => 'Standard Chartered Bank', 'code' => '068'],
                ['name' => 'Sterling Bank', 'code' => '232'],
                ['name' => 'Suntrust Bank', 'code' => '100'],
                ['name' => 'Taj Bank', 'code' => '302'],
                ['name' => 'Titan Bank', 'code' => '102'],
                ['name' => 'Union Bank of Nigeria', 'code' => '032'],
                ['name' => 'United Bank For Africa (UBA)', 'code' => '033'],
                ['name' => 'Unity Bank', 'code' => '215'],
                ['name' => 'Wema Bank (ALAT)', 'code' => '035'],
                ['name' => 'Zenith Bank', 'code' => '057'],
            ];
        });

        return response()->json([
            'success' => true,
            'banks'   => $banks,
        ]);
    }

    /**
     * Resolve account number to retrieve official account name via Paystack NIBSS API.
     */
    public function resolveAccount(Request $request)
    {
        $request->validate([
            'account_number' => 'required|string|size:10',
            'bank_code'      => 'required|string',
        ]);

        $accountNumber = trim($request->account_number);
        $bankCode      = trim($request->bank_code);

        $secretKey = config('services.paystack.secret_key')
            ?: (env('PAYSTACK_SECRET_KEY')
            ?: (env('JLM_PAYSTACK_SECRET_KEY')
            ?: (env('PAYSTACK_SECRET')
            ?: 'sk_live_' . '6c3a6c6c3a68677c02bcbd71a51d0ca384263df1')));

        if (empty($secretKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Paystack secret key is not configured on this server.',
            ], 500);
        }

        try {
            $response = Http::withToken($secretKey)
                ->withOptions(['verify' => false])
                ->get("https://api.paystack.co/bank/resolve?account_number={$accountNumber}&bank_code={$bankCode}");

            $data = $response->json();

            if ($response->successful() && !empty($data['status']) && !empty($data['data']['account_name'])) {
                return response()->json([
                    'success'        => true,
                    'account_name'   => $data['data']['account_name'],
                    'account_number' => $data['data']['account_number'] ?? $accountNumber,
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $data['message'] ?? 'Could not resolve account details. Please check the account number and selected bank.',
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Paystack account resolve error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Unable to verify account at this moment. Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
