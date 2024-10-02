<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->modelProduct = new Product();
    }

    public function getProduct()
    {
        $data = $this->modelProduct->getProduct();

        // Jika data ditemukan, kembalikan respons sukses
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $data
        ]);
    }

    public function productPost(Request $request)
    {
        // Validator
        $validator = Validator::make($request->all(), [
            'item' => 'required|min:3',
            'price' => 'required|integer',
            'stock' => 'required|integer',
        ]);

        // validator is not met
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // array from request
        $data = [
            'item' => $request->item,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
        ];

        $this->modelProduct->createProduct($data);

        // Response 
        return response()->json([
            'success' => true,
            'message' => 'successfully',
            'data' => $data
        ]);
    }

    public function updateProduct(Request $request, $id)
    {
        $data = [
            'item' => $request->item,
            'price' => $request->price,
            'stock' => $request->stock,
            'description' => $request->description,
        ];

        $update = $this->modelProduct->updateProduct($data, $id);

        // Cek apakah update berhasil
        return $update;
    }
}
