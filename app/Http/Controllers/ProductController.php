<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Group;
use App\Models\SellingInvoice;
use App\Models\SellingInvoiceDetail;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function home() {
        // last purcase
            if(Auth()->user()){
                $products_last_purcase = SellingInvoice::where('customer_id', Auth()->user()->user_id)->orderBy('order_date', 'desc')->get();

                
                if ($products_last_purcase->count() > 0) {
                    foreach($products_last_purcase as $product){
                        foreach($product->sellinginvoicedetail as $p){
                            $product_last_purcase[] = $p;
                        }
                    }

                    foreach(collect($product_last_purcase) as $p){
                        if(Product::where('product_name', $p->product_name)->where('product_status',  'aktif')->first() != NULL){
                            $products[] = Product::where('product_name', $p->product_name)->get();
                        }
                    }
                } else {
                    $products = NULL;
                }
            } else {
                $products = NULL;
            }
        // akhir last purcase
        
        // banyak dicari
            // ubah jadi view
            $products_best_seller = SellingInvoiceDetail::select('product_name', DB::raw('COUNT(*) as jumlah_kemunculan'))
            ->groupBy('product_name')
            ->OrderBy('jumlah_kemunculan', 'DESC')
            ->get();

            if ($products_best_seller->count() > 0) {
                foreach($products_best_seller as $p){
                    if(Product::where('product_name', $p->product_name)->where('product_status',  'aktif')->first() != NULL){
                        // echo(collect(Product::where('product_name', $p->product_name)->first()));
                        $product_best_seller[] = Product::where('product_name', $p->product_name)->get();
                    }
                }
            } else {
                $product_best_seller = NULL;
            }
        // akhir banyak dicari


        return view("user.index", [
            "title"=> "Apotek | Home",
            "categories"=> Category::orderBy('category')->get() ?? [],
            "products_last_purcase"=> collect($products ?? [])->take(5),
            "products_best_seller" => collect($product_best_seller ?? [])->take(5),
        ]);
    }
    
    public function produk(Request $request) {
        $categories = Category::orderBy('category')->get();
        $groups = Group::orderBy('group')->get();
        $units = Unit::orderBy('unit')->get();

        $all_product = Product::all();

        // filter kategori
        if ($request->kategori) {
            if (Category::where('category', $request->kategori)->first() != NULL) {
                // ubah menjadi view
                $product = Product::whereHas('description.category', function ($query) use ($request) { 
                    $query->where('category', $request->kategori);
                });
            }else{
                $product = NULL;
            }
        }
        // akhir filter kategori

        // filter group
        if ($request->golongan) {
            if (Group::where('group', $request->golongan)->first() != NULL) {
                // ubah menjadi view
                if($request->kategori) {
                    $product = $product->whereHas('description.group', function ($query) use ($request) {
                        $query->where('group', $request->golongan);
                    });
                }else{
                    $product = Product::whereHas('description.group', function ($query) use ($request) { 
                        $query->where('group', $request->golongan);
                    });
                }
            }else{
                $product = NULL;
            }
        }
        // akhir filter group

        // filter unit
        if ($request->bentuk) {
            if (Unit::where('unit', $request->bentuk)->first() != NULL) {
                // ubah menjadi view
                if($request->golongan) {
                    $product = $product->whereHas('description.unit', function ($query) use ($request) {
                        $query->where('unit', $request->bentuk);
                    });
                }elseif($request->kategori){
                    $product = $product->whereHas('description.unit', function ($query) use ($request) {
                        $query->where('unit', $request->bentuk);
                    });
                }else{
                    $product = Product::whereHas('description.unit', function ($query) use ($request) { 
                        $query->where('unit', $request->bentuk);
                    });
                }
            }else{
                $product = NULL;
            }
        }
        // akhir filter unit

        // filter harga
        if ($request->maksimum || $request->minimum) {
            if ($request->maksimum) {
                if($request->golongan || $request->kategori || $request->bentuk) {
                    $product = $product->whereHas('detail', function($query) use ($request) {
                        $query->where('product_sell_price', '<=', $request->maksimum);
                    });
                }else{
                    $product = Product::whereHas('detail', function($query) use ($request) {
                        $query->where('product_sell_price', '<=', $request->maksimum);
                    });
                }
            }
            
            if ($request->minimum){
                if($request->golongan || $request->kategori || $request->bentuk || $request->maksimum) {
                    $product = $product->whereHas('detail', function($query) use ($request) {
                        $query->where('product_sell_price', '>=', $request->minimum);
                    });
                }else{
                    $product = Product::whereHas('detail', function($query) use ($request) {
                        $query->where('product_sell_price', '>=', $request->minimum);
                    });
                }
            }
        }
        // akhir filter harga

        // filter
        if ($request->filter) {
            if ($request->filter == "Popular") {
                if($request->golongan || $request->kategori || $request->bentuk || $request->maksimum || $request->minimum) {
                    $product = Product::join('Selling_Invoice_Details', 'Products.product_name', '=', 'Selling_Invoice_Details.product_name')
                    ->whereIn('Products.product_name', $product->pluck('product_name')->toArray())
                    ->select('Products.*', DB::raw('COUNT(Selling_Invoice_Details.product_name) as jumlah_kemunculan'))
                    ->groupBy('Products.product_id', 'Products.description_id', 'Products.product_name')
                    ->orderBy('jumlah_kemunculan', 'DESC');
                }else{
                    $product = Product::join('Selling_Invoice_Details', 'Products.product_name', '=', 'Selling_Invoice_Details.product_name')
                    ->select('Products.*', DB::raw('COUNT(Selling_Invoice_Details.product_name) as jumlah_kemunculan'))
                    ->groupBy('Products.product_id', 'Products.description_id', 'Products.product_name')
                    ->orderBy('jumlah_kemunculan', 'DESC');
                }
            }
            
            if ($request->filter == "Nama A - Z"){
                if($request->golongan || $request->kategori || $request->bentuk || $request->minimum || $request->maksimum) {
                    $product = $product->orderBy('product_name');
                }else{
                    $product = Product::orderBy('product_name');
                }
            }

            if ($request->filter == "Nama Z - A"){
                if($request->golongan || $request->kategori || $request->bentuk || $request->minimum || $request->maksimum) {
                    $product = $product->orderBy('product_name', 'DESC');
                }else{
                    $product = Product::orderBy('product_name', 'DESC');
                }
            }

            if ($request->filter == "Harga Tinggi - Rendah"){
                if($request->golongan || $request->kategori || $request->bentuk || $request->minimum || $request->maksimum) {
                    $product = $product->join('product_details', 'products.product_id', '=', 'product_details.product_id')
                    ->orderBy('product_details.product_sell_price', 'DESC')
                    ->select('products.*', 'product_details.product_sell_price')
                    ->distinct();
                }else{
                    $product = Product::join('product_details', 'products.product_id', '=', 'product_details.product_id')
                    ->orderBy('product_details.product_sell_price', 'DESC')
                    ->select('products.*', 'product_details.product_sell_price')
                    ->distinct();
                }
            }

            if ($request->filter == "Harga Rendah - Tinggi"){
                if($request->golongan || $request->kategori || $request->bentuk || $request->minimum || $request->maksimum) {
                    $product = $product->join('product_details', 'products.product_id', '=', 'product_details.product_id')
                    ->orderBy('product_details.product_sell_price', 'ASC')
                    ->select('products.*', 'product_details.product_sell_price')
                    ->distinct();
                }else{
                    $product = Product::join('product_details', 'products.product_id', '=', 'product_details.product_id')
                    ->orderBy('product_details.product_sell_price', 'ASC')
                    ->select('products.*', 'product_details.product_sell_price')
                    ->distinct();
                }
            }
        }
        // akhir filter

        // filter cari
        if ($request->cari) {
                if($request->golongan || $request->kategori || $request->bentuk || $request->minimum || $request->maksimum || $request->filter) {
                    $product = $product->where('Products.product_name', 'like' ,"%". $request->cari ."%");
                }else{
                    $product = Product::where('Products.product_name', 'like' ,"%". $request->cari ."%");
                }
        }
        // akhir filter cari
            
        if(isset($product)) {
            $product = $product->paginate(9)->withQueryString();
        }else{
            $product = Product::paginate(9)->withQueryString();
        }

        return view("user.products", [
            "products"=> $product ?? NULL,
            "all_products" => $all_product ?? [],
            "categories"=> $categories ?? [],
            "units"=> $units ?? [],
            "groups"=> $groups ?? [],
        ]);
    }


    public function liveSearch(Request $request)
{
    $query = $request->input('query');

    $products = Product::where('product_name', 'like', "%$query%")->get();

    return response()->json($products);
}

    public function deskripsiProduk(Request $request){
        $products = Product::all();
        
        foreach($products as $product){
            if(Str::slug($product->product_name) == $request->product){
                $description_product = $product->description;
                return view("user.description-product",[
                    "description_product" => $description_product ?? [],
                ]);
            }
        }

        abort(404);
    }

    public function produk_cashier(Request $request)
    {
        $searchTerm = $request->input('search');

        if ($searchTerm === "") {
            $product = Product::orderBy('product_status')->paginate(8);
        } else {
            $product = Product::orderBy('product_status')->where(function ($query) use ($searchTerm) {
                $query->where(DB::raw("product_name"), "LIKE", "%" . $searchTerm . "%");
            })->paginate(8);
        }
        $categories = Category::orderBy('category')->get();
        $groups = Group::orderBy('group')->get();
        $units = Unit::orderBy('unit')->get();


        return view("kasir.index", [
            "products"=> $product ?? NULL,
            "all_products" => $all_product ?? [],
            "categories"=> $categories ?? [],
            "units"=> $units ?? [],
            "groups"=> $groups ?? [],
        ]);
    }
}