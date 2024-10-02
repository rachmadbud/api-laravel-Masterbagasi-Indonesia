<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Voucher extends Model
{
    use HasFactory;

    protected $table = 'voucher';
    protected $fillable = ['voucher_kode', 'sk_min_price', 'expired', 'disc_percent'];

    public function updateVoucher($data, $id)
    {
        // Cek apakah ID ada
        $voucherExists = DB::table('voucher')->where('id', $id)->exists();

        if (!$voucherExists) {
            // Jika tidak ada, kembalikan pesan atau tindakan yang sesuai
            return response()->json([
                'status' => false,
                'message' => 'Voucher dengan ID ini tidak ditemukan.',
                'data' => $data
            ], 404); // Berhasil, kirim status 200
        }

        // Jika ada, lakukan update
        $updateData = DB::table('voucher')
            ->where('id', $id)
            ->update($data);

        // Cek apakah update berhasil
        if ($updateData) {
            return response()->json([
                'status' => false,
                'message' => 'Voucher berhasil diperbarui.',
                'data' => $data
            ], 200); // Berhasil, kirim status 200
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui voucher.',
                'data' => $data
            ], 500); // Berhasil, kirim status
        }
    }

    public function getVoucher()
    {
        $vouchers = Voucher::orderBy('created_at', 'desc')->get();

        // Looping melalui data untuk menambahkan status berdasarkan expired date
        foreach ($vouchers as $voucher) {
            $currentDate = Carbon::now(); // Tanggal sekarang
            $expiredDate = Carbon::parse($voucher->expired); // Tanggal expired dari voucher

            // Periksa apakah voucher masih berlaku atau sudah kedaluwarsa
            if ($expiredDate->greaterThanOrEqualTo($currentDate)) {
                $voucher->status = 'Masih berlaku';
            } else {
                $voucher->status = 'Kedaluwarsa';
            }
        }

        return $vouchers;
    }

    public function createVoucher($data)
    {
        $createData = Voucher::create($data);
        return $createData;
    }
}
