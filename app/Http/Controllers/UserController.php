use App\Models\User;

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
    ]);

    $user = User::create($validated);
    return response()->json($user, 201);
}

public function show($id)
{
    $user = User::with('wallets.transactions')->findOrFail($id);

    $wallets = $user->wallets->map(function ($wallet) {
        return [
            'id' => $wallet->id,
            'name' => $wallet->name,
            'balance' => $wallet->balance,
        ];
    });

    $totalBalance = $wallets->sum('balance');

    return response()->json([
        'user' => $user->only(['id','name','email']),
        'wallets' => $wallets,
        'total_balance' => $totalBalance,
    ]);
}