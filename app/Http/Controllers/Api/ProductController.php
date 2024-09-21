<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function GetAllProducts(){
        $products = Product::get();
        if($products->count() > 0){
            return response()->json([
                'code' => 200,
                'message' => 'Data found',
                'data' => ProductResource::collection($products)
            ]);
        }else{
            return response()->json([
                'code' => 404,
                'message' => 'Data not found',
            ]);
        }
    }

    public function GetProductByID(Product $product) {
        return response()->json([
            'code' => 200,
            'message' => 'Data found',
            'data' => new ProductResource($product)
        ]);
    }    

    public function CreateProduct(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|integer',
            'description' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'code' => 422,
                'errors' => $validator->errors()
            ], 422);
        }
    
        $product = Product::create($validator->validated());
    
        return response()->json([
            'message' => 'Product created successfully',
            'code' => 200,
            'data' => new ProductResource($product)
        ]);
    } 

    public function UpdateProduct(Request $request, Product $product) {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
            'name' => 'required|string|max:255',
            'price' => 'required|integer',
            'description' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'code' => 422,
                'errors' => $validator->errors()
            ], 422);
        }

        $product = Product::find($request->product_id);

        if(!$product){
            return response()->json([
                'message' => 'Product not found',
                'code' => 404,
            ]);
        }

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
        ]);
    
        return response()->json([
            'message' => 'Product updated successfully',
            'code' => 200,
            'data' => new ProductResource($product)
        ]);
    }    

    public function DeleteProduct(Request $request){
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'code' => 422,
                'errors' => $validator->errors()
            ], 422);
        }

        $product = Product::find($request->product_id);

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully',
            'code' => 200,
            'data' => new ProductResource($product)
        ]);
    }
}
