<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;


class TransactionController extends Controller
{

    public function index(Request $request)
    {
    $transactions = Transaction::query();

    if ($request->filled('amount')) {
        $transactions->where('amount', $request->amount);
    }

    if ($request->filled('start_date')) {
        $transactions->whereDate('created_at', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $transactions->whereDate('created_at', '<=', $request->end_date);
    }

    $transactions = $transactions->where('user_id',auth()->user()->id)->get();

    return view('home', ['transactions'=>$transactions]);
    }

    public function getTransactions(Request $request)
    {
        $transactions = Transaction::query();
        
        // Filter by date range
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfDay();
            $endDate = Carbon::parse($request->end_date)->endOfDay();
            $transactions->whereBetween('created_at', [$startDate, $endDate]);
        }
        
        // Search by amount
        if ($request->has('amount')) {
            $transactions->where('amount', 'like', '%' . $request->amount . '%');
        }
        
        return DataTables::of($transactions)
            ->make(true);
    }

    public function credit(Request $request)
    {
        $validatedData = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        $user = auth()->user();
        $user->balance += $validatedData['amount'];
        $user->save();
        $user->transactions()->create([
            'amount' => $validatedData['amount'],
            'type' => 'credit',
            'date' => now(),
        ]);

        return redirect()->route('home')->with('success', __('Transaction successfully credited.'));
    }

    public function debit(Request $request)
    {
        $validatedData = $request->validate([
            'debit-amount' => ['required', 'numeric', 'min:1'],
        ]);

        $user = auth()->user();
        $user->balance -= $validatedData['amount'];
        $user->save();

        $user->transactions()->create([
            'amount' => -$validatedData['debit-amount'],
            'type' => 'debit',
            'date' => now(),
        ]);
      

        return redirect()->route('home')->with('success', __('Transaction successfully debited.'));
    }

    public function close(Request $request)
    {
        $user = auth()->user();
        
        $user->transactions()->create([
            'amount' => $user->balance,
            'type' => 'debit',
            'date' => now(),
        ]);
        $user->balance = 0;
        $user->save();

        Auth::logout();
        return redirect()->route('login')->with('success', __('Account successfully closed.'));
    }

    public function processInterest()
{
    $users = User::all();

    foreach ($users as $user) {
        $interest = round($user->transactions()->sum('amount') * 0.08, 2);
        if ($interest > 0) {
            $user->transactions()->create([
                'amount' => $interest,
                'type' => 'interest',
                'date' => now(),
            ]);
            $user->balance += $interest;
            $user->save();
        }
    }

    return redirect()->route('home')->with('success', __('Interest successfully credited.'));
}



}
