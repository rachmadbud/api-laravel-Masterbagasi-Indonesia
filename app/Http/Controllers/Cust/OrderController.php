<?php

namespace App\Http\Controllers\Cust;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Voucher;
use Carbon\Carbon as CarbonCarbon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->modelOrder = new Order();
    }

    public function order(Request $request)
    {
        // Validator
        $validator = Validator::make($request->all(), [
            'voucher_kode' => 'required',
            'id_product' => 'required|integer',
        ]);

        // validator is not met
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $id_user = Auth::id();
        // cek id product exist
        $productsExists = DB::table('products')->where('id', $request->id_product)->exists();
        if (!$productsExists) {
            // Jika tidak ada, kembalikan pesan atau tindakan yang sesuai
            return response()->json([
                'status' => false,
                'message' => 'Product dengan id ini tidak ditemukan.',
            ], 404);
        }
        $product = DB::table('products')->where('id', $request->id_product)->first();

        // cek voucher_kode exist
        $voucherExists = DB::table('voucher')->where('voucher_kode', $request->voucher_kode)->exists();
        if (!$voucherExists) {
            // Jika tidak ada, kembalikan pesan atau tindakan yang sesuai
            return response()->json([
                'status' => false,
                'message' => 'Voucher dengan kode voucher ini tidak ditemukan.',
            ], 404);
        }

        // cek tgl expired voucher
        $voucher = DB::table('voucher')->where('voucher_kode', $request->voucher_kode)->first();
        if ($voucher) {
            $currentDate = Carbon::now(); // Tanggal sekarang
            $expiredDate = Carbon::parse($voucher->expired); // Tanggal expired dari voucher

            // Periksa apakah voucher masih berlaku atau sudah kedaluwarsa
            if ($expiredDate->greaterThanOrEqualTo($currentDate)) {
                $voucher->status = 'berlaku';
            } else {
                $voucher->status = 'kadaluarsa';
            }

            // tampung data
            $data = [
                'id' => $voucher->id,
                'voucher_kode' => $voucher->voucher_kode,
                'expired' => $voucher->expired,
                'sk_min_price' => $voucher->sk_min_price,
                'disc_percent' => $voucher->disc_percent,
                'status' => $voucher->status
            ];
        }
        //  $data['sk_min_price'];


        // cek SK_Min_Price
        $priceProduct = DB::table('products')->where('id', $request->id_product)->first(); //ambil harga berdasarkan id product
        $totalPrice = $priceProduct->price * $request->qty; // total = price product * Qty

        if ($totalPrice < $data['sk_min_price']) {
            return response()->json([
                'status' => false,
                'message' => 'price tidak mencukupi sk voucher',
                'data' => $totalPrice
            ], 404);
        }
        // Periksa apakah voucher sudah kedaluwarsa
        if ($voucher->status == 'kadaluarsa') {
            return response()->json([
                'status' => false,
                'message' => 'Voucher sudah kadaluarsa',
                'data' => $voucher->expired
            ], 400); // Status 400 karena voucher kadaluarsa
        }

        // Hitung nilai diskon
        $diskon = ($totalPrice * $data['disc_percent']) / 100;

        // Hitung harga setelah diskon
        $hargaSetelahDiskon = $totalPrice - $diskon;

        $dataArray = [
            'id_user' => $id_user,
            'id_product' => $request->id_product,
            'id_voucher' => $data['id'],
            'total' => $totalPrice,
            'qty' => $request->qty,
            'total_setelah_discount' => $hargaSetelahDiskon,
            'created_at' => Carbon::now()
        ];

        $saveOrder = $this->modelOrder->createOrder($dataArray);

        // Jika tidak ada, kembalikan pesan atau tindakan yang sesuai
        return response()->json([
            'status' => true,
            'message' => 'Success.',
            'data' => $dataArray
        ], 200);
    }
}
