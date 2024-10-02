<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Illuminate\Support\Facades\Validator;

class VoucherController extends Controller
{
    public function __construct()
    {
        $this->modelVoucher = new Voucher();
    }

    public function getVoucher()
    {
        $vouchers = $this->modelVoucher->getVoucher();
        // Response 
        // return response()->json([
        //     'success' => true,
        //     'message' => 'success',
        //     'data' => $data
        // ]);
        return response()->json($vouchers, 200);
    }

    public function voucherPost(Request $request)
    {
        // Validator
        $validator = Validator::make($request->all(), [
            'sk_min_price' => 'required|integer', //min belanja
            'expired' => 'required',
            'disc_percent' => 'required'
        ]);

        // validator is not met
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $voucher_kode = str()->random(8);
        // array from request
        $data = [
            'voucher_kode' => $voucher_kode,
            'sk_min_price' => $request->sk_min_price,
            'expired' => $request->expired,
            'disc_percent' => $request->disc_percent,
            'created_at' =>  Carbon::now()
        ];

        $this->modelVoucher->createVoucher($data);

        // Response 
        return response()->json([
            'success' => true,
            'message' => 'success',
            'data' => $data
        ]);
    }

    public function updateVoucher(Request $request, $id)
    {
        $data = [
            'voucher_kode' => $request->voucher_kode,
            'sk_min_price' => $request->sk_min_price,
            'expired' => $request->expired,
            'disc_percent' => $request->disc_percent,
        ];

        $update = $this->modelVoucher->updateVoucher($data, $id);

        // Cek apakah update berhasil
        return $update;
    }
}
