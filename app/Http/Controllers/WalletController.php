use App\Models\Wallet;

public function store(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'required|exists:users,id',
        'name' => 'required|string|max:255',
    ]);

    $wallet = Wallet::create($validated);
    return response()->json($wallet, 201);
}

public function show($id)
{
    $wallet = Wallet::with('transactions')->findOrFail($id);

    return response()->json([
        'id' => $wallet->id,
        'name' => $wallet->name,
        'balance' => $wallet->balance,
        'transactions' => $wallet->transactions
    ]);
}