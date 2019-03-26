<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddProductRequest;
use App\Models\Product;
use App\Models\LoaiNhaDat;
use App\Models\Quan;
use App\Models\Users;
use App\Models\KiemDuyet;
use App\Models\NhaDat;
use DB;

class ProductController extends Controller
{
    // user
    public function getListUser(){
        $data['user_list'] = Users::all();
        return view('admin.user.list_user',$data);      
    }

    public function getDetail(){
        return view('admin.user.detail_user');
    }

    public function getFixUser($id){
        $data['user_f'] = Users::find($id);
        return view('admin.user.fix_user',$data);
    }

    public function postFixUser(Request $request,$id){
        $user = new Users;

        $ar['hoten'] = $request->TenND;
        $ar['email'] = $request->Email;
        $ar['username'] = $request->TenDN;
        $ar['sdt'] = $request->SDT;
        $ar['diachi'] = $request->DiaChi;
        $ar['gioitinh'] = $request->GioiTinh;

        if($request->hasFile('hinh')){
            $img = $request ->file('hinh')->getClientOriginalName();
            $destination = base_path().'/public/images/users';
            $request->file('hinh')->move($destination,$img);
            $ar['HinhAnhUser'] = $img;
        }

        $user::Where('id',$id)->update($ar);
        return redirect("admin/user")->withInput()->with('correct','Sửa thành công!!');
    }

    public function getDeleteUser($id){
        Users::destroy($id);
        return back();
    }

    public function getListCheck(){
        $data['i_list'] = DB::table('kiem_duyet')->join('quanhuyen','kiem_duyet.IDQuanHuyen','=','quanhuyen.IDQuanHuyen')->get();
        // $data['i_list'] = KiemDuyet::all();
        // return dd($data);
        return view('admin.user.check_prod',$data);
    }

    public function getAboutCheck($id){
        $kd = KiemDuyet::find($id);
        return dd($kd);
    }

    public function getDuyet($id){

        $kd = KiemDuyet::find($id);
        $prod = new NhaDat;

      
        $product['NgayNhap'] = $kd->NgayNhap;
        $product['DienTich'] = $kd->DienTich;
        $product['ChieuDai'] = $kd->ChieuDai;
        $product['ChieuRong'] = $kd->ChieuRong;
        $product['LoGioi'] = $kd->ChieuRong;
        $product['MoTa'] = $kd->MoTa;
        $product['Gia'] = $kd->Gia;
        $product['DiaChi'] = $kd->DiaChi;
        $product['TrangThai'] = $kd->TrangThai ;
        $product['IDLoaiNhaDat'] = $kd->IDLoaiNhaDat;
        $product['IDQuanHuyen'] = $kd->IDQuanHuyen ;
        $product['id'] = $kd->id;
        $product['HinhAnhND'] = $kd->HinhAnhND;

        // $product['NgayNhap'] = "22/2/2017" ;
        // $product ->DienTich = $kd->DienTich;
        // $product ->MoTa = $kd->MoTa;
        // $product ->Gia = $kd->Gia;
        // $product ->DiaChi = $kd->DiaChi;
        // $product ->TrangThai = $kd->TrangThai ;
        // $product ->IDLoaiNhaDat = $kd->IDLoaiNhaDat;
        // $product ->IDQuanHuyen = $kd->IDQuanHuyen ;
        // $product ->IDUser = $kd->IDUser;
        // $product ->HinhAnhND = $kd->HinhAnhND;


        DB::table('nhadat')->insert($product);
             KiemDuyet::destroy($id);
        

        return redirect('admin/check')->withInput()->with('correct','Duyệt thành công!!');
        // return dd($product);
    }
    //enduser
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    //
    public function getListProd(){
    	$data['prod_list'] = DB::table('nhadat')->join('quanhuyen','nhadat.IDQuanHuyen','=','quanhuyen.IDQuanHuyen')->get();

    	// $data['prod_list'] = Product::all();
    	return view('admin.product.list_product',$data);
    }

    public function getListFilter($id = null){

        
        if ($id == 1)
        {
            $data['prod_list'] = DB::table('nhadat')->join('quanhuyen','nhadat.IDQuanHuyen','=','quanhuyen.IDQuanHuyen')->whereNotNull('id')->get();    
        }
        else
        {
             $data['prod_list'] = DB::table('nhadat')->join('quanhuyen','nhadat.IDQuanHuyen','=','quanhuyen.IDQuanHuyen')->get();   
        }
        

        // $data['prod_list'] = Product::all();
        return view('admin.product.new_view',$data);
        // return dd($data);
    }

    public function getAddProd(){
    	$data['l_list'] = LoaiNhaDat::all();
    	$data['q_list'] = Quan::all();
    	$data['user'] = Users::all();

    	return view('admin.product.add_prod',$data);
    }

    public function postAddProd(AddProductRequest $request){

    	//dd($request->file('hinh'));	
    	$photo = '';
    	if ($request->hasFile('hinh')) {
    		$photo = $request->file('hinh')->getClientOriginalName();
			$destination = base_path() . '/public/images/product/';
			$request->file('hinh')->move($destination, $photo);
		}
    	

    	$product = new Product;	
    	$product ->NgayNhap = $request ->date;
        $product ->TenND = $request->TenND;
    	$product ->DienTich = $request ->DienTich;
        $product ->ChieuDai = $request ->ChieuDai;
        $product ->ChieuRong = $request ->ChieuRong;
        $product ->LoGioi = $request ->LoGioi;
    	$product ->MoTa = $request ->MoTa;
    	$product ->Gia = $request ->Gia;
    	$product ->DiaChi = $request ->DiaChi;
    	$product ->IDLoaiNhaDat = $request ->Loai;
    	$product ->IDQuanHuyen = $request ->Quan;
    	$product ->id = $request->user1;
    	$product ->HinhAnhND = $photo;
    	
    	
    	$product->save();
    	return redirect('admin/product')->withInput()->with('correct','Thêm thành công!!');
    	//$request ->hinh->storeAs('images',$filename);
    }

    public function getFixProd($id){
    	$data['prod_edit'] = Product::find($id);
    	$data['l_list'] = LoaiNhaDat::all();
    	$data['q_list'] = Quan::all();
    	$data['user'] = Users::all();
    	return view('admin.product.fix_prod',$data);
    }

    public function postFixProd(Request $request,$id){

    	$product = new Product;
    	$arr['NgayNhap'] = $request->date;
    	$arr['DienTich'] = $request->DienTich;
        $arr['ChieuDai'] = $request->ChieuDai;
        $arr['ChieuRong'] = $request->ChieuRong;
        $arr['LoGioi'] = $request->LoGioi;
        $arr['TenND'] = $request->TenND;
    	$arr['MoTa'] = $request ->MoTa;
    	$arr['Gia']= $request ->Gia;
    	$arr['DiaChi'] = $request ->DiaChi;
    	$arr['IDLoaiNhaDat'] = $request ->Loai;
    	$arr['IDQuanHuyen'] = $request ->Quan;
    	$arr['id'] = $request->user1;
    	if($request->hasFile('hinh')){
    		$img = $request ->file('hinh')->getClientOriginalName();
    		$destination = base_path().'/public/images/product';
    		$request->file('hinh')->move($destination,$img);
    		$arr['HinhAnhND'] = $img;
    	}
    	$product::where('IDNhaDat',$id)->update($arr);

    	return redirect('admin/product')->withInput()->with('correct','Sửa thành công!!');
        // return dd($arr);
    }

    public function getDelProd($id){
    	Product::destroy($id);
    	return back()->withInput()->with('correct','Xóa thành công!!');
    }
}
