<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $fillable = ['item', 'price', 'stock', 'description'];

    public function getProduct()
    {
        $data = DB::table('products')->orderBy('created_at', 'desc')->get();
        return $data;
    }

    public function createProduct($data)
    {
        $createData = Product::create($data);
        return $createData;
    }

    public function updateProduct($data, $id)
    {
        // Cek apakah ID ada
        $productExists = DB::table('products')->where('id', $id)->exists();

        if (!$productExists) {
            // Jika tidak ada, kembalikan pesan atau tindakan yang sesuai
            return response()->json([
                'status' => false,
                'message' => 'Product dengan ID ini tidak ditemukan.',
                'data' => $data
            ], 404);
        }

        // Jika ada, lakukan update
        $updateData = DB::table('products')
            ->where('id', $id)
            ->update($data);

        // Cek apakah update berhasil
        if ($updateData) {
            return response()->json([
                'status' => true,
                'message' => 'Products berhasil diperbarui.',
                'data' => $data
            ], 200); // Berhasil, kirim status 200
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui Products.',
                'data' => $data
            ], 500); // Berhasil, kirim status
        }
    }
}
