<?php

namespace App\Http\Controllers\api;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

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
        
        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');

        if($id){
            $product = Product::with('galleries','category')->find($id);
            if($product){
                return ResponseFormatter::success($product, 'Data Produk berhasil di ambil');
            }else{
                return ResponseFormatter::error(null, 'Data produk tidak ada', Response::HTTP_NOT_FOUND);
            }
        }

        $product = Product::with('galleries','category');

        if($name){
            $product->where('name', 'like', '%' . $name . '%');
        }
        
        if($description){
            $product->where('description', 'like', '%' . $description . '%');
        }
        
        if($tags){
            $product->where('tags', 'like', '%' . $tags . '%');
        }

        if($price_from){
            $product->where('price', '>=', $price_from);
        }

        if($price_to){
            $product->where('price', '<=', $price_to);
        }

        if($categories){
            $product->where('categories_id', '=', $categories);
        }

        return ResponseFormatter::success($product->paginate($limit), 'Data produk berhasil di ambil');
    }
}
