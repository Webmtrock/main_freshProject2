<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VendorProduct;
use App\Models\Product;
use App\Models\VendorProfile;
use App\Models\User;
use App\Models\Category;
use App\Models\VendorProductVariant;
use App\Models\Setting;
use App\Models\Tax;
use App\Helper\Helper;
use File;

class VendorProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $q = VendorProduct::query()
                            ->join('vendor_profiles', 'vendor_products.vendor_id', '=', 'vendor_profiles.user_id')
                            ->join('categories', 'vendor_products.category_id', '=', 'categories.id')
                            ->join('products', 'vendor_products.product_id', '=', 'products.id')
                            ->select('vendor_products.*', 'vendor_profiles.store_name as vendor_name', 'categories.name as category_name', 'products.name as product_name');

        if($request->keyword){
            $data['keyword'] = $request->keyword;

            $q->where(function ($query) use ($data) {
                $query->where('vendor_profiles.store_name', 'like', '%'.$data['keyword'].'%')
                ->orwhere('categories.name', 'like', '%'.$data['keyword'].'%')
                ->orwhere('products.name', 'like', '%'.$data['keyword'].'%');
            });
        }

        if($request->status){
            $data['status'] = $request->status;

            if($request->status == 'active'){
                $q->where('vendor_products.status', '=', 1);
            }
            else {
                $q->where('vendor_products.status', '=', 0);
            }
        }

        if($request->category){
            $data['category'] = $request->category;

            $q->where('category_id', '=', $data['category']);
        }

        if($request->items){
            $data['items'] = $request->items;
        }
        else{
            $data['items'] = 10;
        }

        $data['data'] = $q->orderBy('created_at','DESC')->paginate($data['items']);
        $data['categories'] = Category::getAllActiveCategoriesNameId();

        return view('admin.vendor-product.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['vendors'] = VendorProfile::getVendorNameAndId();
        $data['categories'] = Category::getAllActiveCategoriesNameId();
        $data['units'] = Helper::units();
        $data['tax'] = Tax::getAllActiveTaxes();
        return view('admin.vendor-product.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'vendor' => 'required',
                'category' => 'required',
                'product' => 'required',
                'status' => 'required',
                'image' => 'mimes:jpeg,png,jpg',
            ]
        );

        $imagePath = config('app.vendor_product_image');

        $newImageName = $request->imageOld;
        if(!$request->hasfile('image')) {
            if(isset($request->imageOld)) {
                if(File::exists(config('app.product_image').'/'.$request->imageOld)) {
                    $newImageName = time().'-'.$request->imageOld;
                    File::copy(config('app.product_image').'/'.$request->imageOld, config('app.vendor_product_image').'/'.$newImageName);
                }
            }
        }

        $data = VendorProduct::updateOrCreate(
            [
                'id' => $request->id,
            ],
            [
                'vendor_id' => $request->vendor,
                'category_id' => $request->category,
                'product_id' => $request->product,
                'image' => $request->hasfile('image') ? Helper::storeImage($request->file('image'),$imagePath,$request->imageOld) : (isset($newImageName) ? $newImageName : ''),
                'status' => $request->status,
            ]
        );

        $result = $data->update();

        $i = 0;
        $variants_data_new = [];
        $remove_variant_id = [];

        $productVariants = VendorProductVariant::where('vendor_product_id' ,$request->vendor_product_id)->pluck('id')->toArray();

        if(!empty($request->vendor_product_variant_id)) {
            $remove_variant_id = array_diff($productVariants,$request->vendor_product_variant_id);
        }

        foreach($request->variants as $variant) {

            if(!empty($request->vendor_product_variant_id) && (isset($request->vendor_product_variant_id[$i]))) {
                $variants_data[] = $variant 
                + (!empty($request->id) ? ['vendor_product_id' => $request->id] : ['vendor_product_id' => $data->id])
                + (['id' => $request->vendor_product_variant_id[$i]]);
            }
            else {
                $variants_data_new[] = $variant 
                + (!empty($request->id) ? ['vendor_product_id' => $request->id] : ['vendor_product_id' => $data->id]);
            }
            $i++;
        }

        if(!empty($request->id)) {
            VendorProductVariant::upsert($variants_data, ['id'],['vendor_product_id','market_price','variant_qty','variant_qty_type','min_qty','max_qty','price']);
        }
        if(count($variants_data_new)>0) {
            VendorProductVariant::upsert($variants_data_new, ['id'],['vendor_product_id','market_price','variant_qty','variant_qty_type','min_qty','max_qty','price']);
        }

        if(count($remove_variant_id)>0) {
            VendorProductVariant::whereIn('id',$remove_variant_id)->delete();
        }

        if($result)
        {
            return redirect()->route('admin.vendor-products.index');
        }
        else
        {
            return redirect()->back()->with('error', 'Something went Wrong, Please try again!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['data'] = VendorProduct::where('id',$id)->with('product.tax','variants')->first();

        return view('admin.vendor-product.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $data['vendors'] = VendorProfile::getVendorNameAndId();
        $data['categories'] = Category::getAllActiveCategoriesNameId();
        $data['units'] = Helper::units();
        $data['data'] = VendorProduct::where('id', $id)->with('product.tax','variants')->first();
        $data['tax'] = Tax::getAllActiveTaxes();
        return view('admin.vendor-product.create',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        try {
            $data= VendorProduct::where('id',$id)->first();
            $result = $data->delete();

            $variants = VendorProductVariant::where('vendor_product_id',$id)->pluck('id')->toArray();
            VendorProductVariant::whereIn('id',$variants)->delete();
            
            if($result) {
                return response()->json(["success" => true]);
            }
            else {
                return response()->json(["success" => false]);
            }
        }  catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'message'  => "Something went wrong, please try again!",
                'error_msg' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Change the specified resource status from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($id, Request $request)
    {
        try {
            $data= VendorProduct::where('id',$id)->first();

            if($data) {
                $data->status = $data->status == 1 ? 0 : 1;
                $data->save();
                return response()->json(["success" => true, "status"=> $data->status]);
            }
            else {
                return response()->json(["success" => false]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message'  => "Something went wrong, please try again!",
                'error_msg' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get Products resource from storage based on category.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getProductsByCategory($id,Request $request)
    {
        $category = Category::getTaxIdByCategoryId($id);
        $default_tax = Setting::getDataByKey('default_tax_id');
        $products = Product::getProductsByCategoryId($id);

        $output= "<option value=''>Select Product</option>";
        if(count($products)>0){
            foreach($products as $item){
                $select = (isset($request->selected_product) && ($request->selected_product == $item['id'])) ? 'selected' : '';
                $output .= '<option value="'.$item['id'].'" '.$select.'>'.$item['name'].'</option>';
            }
        }
        return response()->json(['success' => true, 'output' => $output, 'tax' => isset($category->tax) ? $category->tax->id : (isset($default_tax->value) ? $default_tax->value : '')]);
    }

    /**
     * Get Products resource from storage based on id.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getProductsById($id, Request $request)
    {   
        $vendor_data = VendorProduct::where('vendor_id', $request->vendor_id)->where('category_id', $request->cat_id)->where('product_id', $id)->first();
        if($vendor_data) {
            return response()->json(['output' => 'exists', 'id' => $vendor_data->id]);
        }
        $product = Product::getProductDetailsByID($id);
        $product->full_image_path = url(config('app.product_image')).'/'.$product->image;
        $product->default_tax = isset($product->tax) ? $product->tax->id : (isset($request->tax) ? $request->tax : '');

        if($product){
            return response()->json(['success' => true, 'output' => $product]);
        }
        return response()->json(['success' => false]);
    }
}
