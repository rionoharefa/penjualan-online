<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;


class DashboardTransactionController extends Controller
{
     public function index()
    {
         //ini tampilan Transaction yg ada di halaman dasboard
        $sellTransactions = TransactionDetail::with(['transaction.user', 'product.product_galleries'])
                            ->whereHas('product', function($product){
                                $product->where('users_id', Auth::user()->id);
                            })->get();

        $buyTransactions = TransactionDetail::with(['transaction.user', 'product.product_galleries'])
                            ->whereHas('transaction', function($transaction){
                                $transaction->where('users_id', Auth::user()->id);
                            })->get();
        

        return view('pages.dashboard-transactions',[
            'sellTransactions' => $sellTransactions,
            'buyTransactions' => $buyTransactions
        ]);
    }

    public function details(Request $request, $id)
    {
        $transaction = TransactionDetail::with(['transaction.user', 'product.product_galleries'])
                            ->findOrFail($id);
        return view('pages.dashboard-transactions-details', [
            'transaction' => $transaction
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $item = TransactionDetail::findOrFail($id);

        $item->update($data);

        return redirect()->route('dashboard-transactions-details', $id);
      
    }
}


