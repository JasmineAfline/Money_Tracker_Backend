use App\Models\Transaction;
use App\Models\Wallet;

public function store(Request $request)
{
    $validated = $request->validate([
        'wallet_id' => 'required|exists:wallets,id',
        'type' => 'required|in:income,expense',
        'amount' => 'required|numeric|min:1',
    ]);

    $transaction = Transaction::create($validated);

    return response()->json($transaction, 201);
}