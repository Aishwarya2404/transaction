@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Transactions</div>
                <div class="card-body">
                    <form method="GET" action="">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="amount">Amount</label>
                                <input type="text" class="form-control" id="amount" name="amount" value="{{ request()->input('amount') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="start_date">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request()->input('start_date') }}">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="end_date">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request()->input('end_date') }}">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </form>
                    <hr>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Date</th>
                                <th scope="col">Type</th>
                                <th scope="col">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions as $transaction)
                            <tr>
                                <th scope="row">{{ $transaction->id }}</th>
                                <td>{{ $transaction->created_at->format('m/d/Y') }}</td>
                                <td>{{ $transaction->type }}</td>
                                <td>{{ $transaction->amount }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                <form method="POST" action="{{ route('transactions.credit') }}">
                    @csrf

                    <div class="form-group row">
                        <label for="credit-amount" class="col-md-4 col-form-label text-md-right">{{ __('Credit Amount') }}</label>

                        <div class="col-md-6">
                            <input id="credit-amount" type="text" class="form-control @error('credit_amount') is-invalid @enderror" name="amount" value="{{ old('credit_amount') }}" required autocomplete="credit_amount">

                            @error('credit_amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Credit') }}
                            </button>
                        </div>
                    </div>
                </form>

            <form method="POST" action="{{ route('transactions.debit') }}">
                    @csrf

                <div class="form-group row">
                    <label for="debit-amount" class="col-md-4 col-form-label text-md-right">{{ __('Debit Amount') }}</label>

                    <div class="col-md-6">
                        <input id="debit-amount" name="debit-amount" type="text" class="form-control  @error('debit_amount')">
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Debit') }}
                            </button>
                        </div>
                    </div>
             </form>
             <form method="POST" action="{{ route('transactions.close') }}">
                 @csrf
                 <div class="form-group row">
                    <div class="col-md-8 offset-md-4">
                        <button type="submit" class="btn btn-danger">
                            {{ __('Close Account') }}
                        </button>
                    </div>
                </div>
             </form>

             @if (auth()->user()->name=="superadmin")
                <form method="POST" action="{{ route('transactions.process-interest') }}">
                    @csrf

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-success">
                                {{ __('Process Interest') }}
                            </button>
                        </div>
                    </div>
                </form>
            @endif


                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endsection
