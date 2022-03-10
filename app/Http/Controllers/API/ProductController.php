<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit');
        $name = $request->input('name');
        $description = $request->input('description');
        $tags = $request->input('tags');
        $categories = $request->input('categories');

        $harga_dari = $request->input('harga_dari');
        $harga_sampai = $request->input('harga_sampai');

        if($id)
        {
            $product = Product::with(['category','galleries'])->find($id);

            if($product)
            {
                return ResponseFormatter::success($product,'Data Produk Berhasil diambil');
            }
            else{
                return ResponseFormatter::error(null,'Data Produk Tidak Ada',404);
            }
        }

        $product = Product::with(['category','galleries']);

        //untuk filter produk
        if($name)
        {
            $product->where('name','like','%' . $name . '%');
        }
        if($description)
        {
            $product->where('description','like','%' . $description . '%');
        }
        if($tags)
        {
            $product->where('tags','like','%' . $tags . '%');
        }
        if($harga_dari)
        {
            $product->where('harga', '>=', $harga_dari);
        }
        if($harga_sampai)
        {
            $product->where('harga', '>=', $harga_sampai);
        }

        if($categories)
        {
            $product->where('categories', $categories);
        }

        return ResponseFormatter::success(
            $product->paginate($limit),
            'Data Produk Berhasil diambil'
        );
    }
}
