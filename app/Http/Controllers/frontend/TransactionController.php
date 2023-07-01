<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Carts;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class TransactionController extends Controller
{

    public function checkout(Request $request)
    {
        $data = [
            'user_id'               => Auth::user()->id,
            'invoice'               => Crypt::encrypt($request->invoice),
            'total'                 => $request->totals,
            'snap_token'            => Crypt::encrypt($request->snap_token),
            'transaction_status'    => $request->transaction_status,
            'order_status'          => 'P',
            'first_name'            => Crypt::encrypt($request->first_name),
            'last_name'             => Crypt::encrypt($request->last_name),
            'state'                 => Crypt::encrypt($request->state),
            'street'                => Crypt::encrypt($request->street),
            'detailstreet'          => Crypt::encrypt($request->detailstreet),
            'city'                  => Crypt::encrypt($request->city),
            'postcode'              => Crypt::encrypt($request->postcode),
            'phone'                 => Crypt::encrypt($request->phone),
            'email'                 => Crypt::encrypt($request->email),
        ];
        try {
            $order = Order::create($data);
            $cart = Carts::where('user_id', Auth::user()->id);
            foreach ($cart->get() as $c) {
                OrderDetail::create([
                    'order_id'   => $order->id,
                    'product_id' => $c->product_id,
                    'quantity'   => $c->quantity
                ]);
            }
            $cart->delete();
            $response = [
                'status'    => 200
            ];
        } catch (\Exception $e) {
            $response = [
                'status'    => 500,
                'message'   => $e->getMessage()
            ];
        }

        return response()->json($response);
    }

    public function payMidtrans(Request $request)
    {
        $this->Midtrans();

        $carts = Carts::with(['products'])->where('user_id', Auth::user()->id)->get();

        $items = [];
        foreach ($carts as $cart) {
            $items[] = [
                'id'       => $cart->products->product_name,
                'price'    => $cart->products->price,
                'quantity' => $cart->quantity,
                'name'     => $cart->products->product_name
            ];
        }

        $shipping_address = array(
            'first_name'   => $request->firstname,
            'last_name'    => $request->last_name,
            'address'      => $request->street,
            'city'         => $request->city,
            'phone'        => $request->phone,
        );

        $customer_details = array(
            'first_name'       => $request->firstname,
            'last_name'        => $request->last_name,
            'email'            => $request->email,
            'phone'            => $request->phone,
            'shipping_address' => $shipping_address
        );

        $params = array(
            'transaction_details' => array(
                'order_id' => rand(),
                'gross_amount' => $request->totals,
            ),
            'item_details'        => $items,
            'customer_details'    => $customer_details
        );

        $data = [
            'snapToken' => \Midtrans\Snap::getSnapToken($params)
        ];

        return response()->json($data);
    }

    public function order()
    {
        $order = Order::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
        return view('frontend.orders', compact('order'));
    }

    public function orderDetail($id)
    {
        $data = OrderDetail::with(['products', 'order'])->where('order_id', $id)->get();
        return response()->json($data);
    }

    public function orderUpdate($id)
    {
        Order::where('id', $id)->update(['order_status' => 'A']);
        return back()->with('success', 'Pesanan telah diterima');
    }

    public function orderCancel($id)
    {
        $data = Order::where('id', $id)->update([
            'transaction_status' => 'failure',
            'order_status' => 'C'
        ]);
        return response()->json($data);
    }

    public function pendingPay(Request $request)
    {
        $this->Midtrans();

        $order = Order::where('id', $request->order_id)->first();

        $status = \Midtrans\Transaction::status($order->invoice);

        if ($status->transaction_status == 'settlement') {
            $order->transaction_status = 'settlement';
            $order->save();
            $data = [
                'redirect'  => true
            ];
        } else {
            $data = [
                'snapToken' => $order->snap_token
            ];
        }

        return response()->json($data);
    }

    public function pendingUpdate(Request $request)
    {
        $id = $request->order_id;
        try {
            $data = Order::where('id', $id)->update([
                'transaction_status' => 'settlement',
            ]);
            $response = [
                'status'    => 200,
                'data'      => $data
            ];
        } catch (\Exception $e) {
            $response = [
                'status'    => 500,
                'message'   => $e->getMessage()
            ];
        }

        return response()->json($response);
    }

    function Midtrans()
    {
        // Merchant server key
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        // Default dari Midtrans adalah Sandbox Environment (mode pengembangan). Ubah ke true untuk Production (real transaksi).
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        // Dafault sanitization true
        \Midtrans\Config::$isSanitized = true;
        // Untuk menerima pembayaran kartu kredit setel 3DS transaction menjadi true
        \Midtrans\Config::$is3ds = true;
    }
}
