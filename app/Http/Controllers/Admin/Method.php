<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Models\KiemDuyet;
use DB;
use App\Models\NhaDat;

class Method extends Controller
{
    //
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
}
