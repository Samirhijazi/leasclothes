<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Keygen;
use App\ProductDamaged;
use App\Product;
use Auth;
use DB;
use Spatie\Permission\Models\Role;
use Datatables;
class DamagedProductController extends Controller
{
    public function index(Request $request)
    {
        $role = Role::find(Auth::user()->role_id);
        if($role->hasPermissionTo('products-index')){            
            $permissions = Role::findByName($role->name)->permissions;
            foreach ($permissions as $permission)
                $all_permission[] = $permission->name;
            if(empty($all_permission))
                $all_permission[] = 'dummy text';
            $damaged_products = ProductDamaged::all();
            return view('product.damage', compact('all_permission', 'damaged_products'));
        }
        else
            return redirect()->back()->with('not_permitted', 'Sorry! You are not allowed to access this module');
    }
    
    public function getRestoredProductQty(Request $req)
    {
        $restoredProductId = $req->restoredProductId;
        $damaged_product = ProductDamaged::find($restoredProductId);
        if($damaged_product)
        {
            return $damaged_product;
        }
    }
    
    public function restoreDamagedProduct(Request $req)
    {
        $damaged_id = $req->damaged_product_id;
        $restored_qty = $req->restored_qty;
        $damaged_product = ProductDamaged::find($damaged_id);
        if($damaged_product)
        {
            $damaged_product->damaged_qty -= $restored_qty;
            $damaged_product->save();
            
            $product_id = $damaged_product->product_id;
            $product = Product::find($product_id);
            $product->qty += $restored_qty;
            $product->save();
            return redirect()->back();
        }
    }
    
    
    public function destroy($id)
    {
        $product_damaged = ProductDamaged::findOrFail($id);
        $lims_product_data = Product::findOrFail($product_damaged->product_id);
        if($lims_product_data->image != 'zummXD2dvAtI.png') {
            $images = explode(",", $lims_product_data->image);
            foreach ($images as $key => $image) {
                unlink('public/images/product/'.$image);
            }
        }
        $product_damaged->delete();
        $lims_product_data->delete();
        return redirect('products')->with('message', 'Product deleted successfully');
    }
}