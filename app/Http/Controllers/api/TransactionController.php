<?php

namespace App\Http\Controllers\api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');
        $status = $request->input('status');

        if($id)
        {
            $transaction = Transaction::with(['items.product'])->find($id);
            if($transaction)
            {
                return ResponseFormatter::success($transaction, 'Data transaksi berhasil di ambil');
            }else{
                return ResponseFormatter::error(null, 'Data transaksi tidak ada', 404);
            }
        }

        $transactions = Transaction::with(['items.product'])->where('users_id', Auth::user()->id);
        if($status)
        {
            $transactions->where('status', $status);
        }

        return ResponseFormatter::success(
            $transactions->paginate($limit),
            'Data list transaksi berhasil di ambil'
        );
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'exists:products,id',
            'total_price' => 'required',
            'shipping_price' => 'required',
            'status' => 'required|in:PENDING,SUCCESS,CANCELLED,FAILED,SHIPPING,SHIPPED'
        ]);

        $transaction = Transaction::create([
            'users_id' => Auth::user()->id,
            'address' => $request->address,
            'total_price' => $request->total_price,
            'shipping_price' => $request->shipping_price,
            'status' => $request->status
        ]);

        foreach($request->items as $product)
        {
            TransactionItem::create([
                'transactions_id' => $transaction->id,
                'products_id' => $product['id'],
                'users_id' => Auth::user()->id,
                'quantity' => $product['quantity']
            ]);
        }

        return ResponseFormatter::success($transaction->load('items.product'), 'Transaksi berhasil');
    }
}
