<?php
session_start();
$baseurl="http://localhost/web/";
$user=empty($_SESSION['user'])?"":$_SESSION['user'];
$token="bajax ".(empty($_SESSION['TOKEN'])?"":$_SESSION['TOKEN']);
$api="http://localhost/WebScan/public/";
include 'api.php';
function cekLogin(){
	if(empty($_SESSION['TOKEN'])){
		header("Location: {$baseurl}admin.php");
	}
}

$page=empty($_GET['page'])?"":$_GET['page'];

switch ($page) {
	default:
		$notif="";
		if($token == "bajax " and !empty($_POST)){
			$data=login($_POST);
			if($data->success == true){
				$_SESSION['user']=$data->data->user;
				$_SESSION['TOKEN']=$data->data->api_token;
			}
			else
				$notif="(!) Email atau Password Salah";
		}
		else if($token != "bajax "){
			header("Location: {$baseurl}admin.php?page=news");
		}
		$view=view("admin/login");
		$replace=array(
			"{{title}}"=>"ADMIN - Bajax",
			"{{notif}}"=>$notif,
		);
		foreach ($replace as $key => $value) {
			$view=str_replace($key, $value, $view);
		}
		print_r($token);
		print($view);
		break;

	case 'logout':
		session_unset();
		print_r($_SESSION);
		$token="";
		$user="";
		$data=logout();
		header("Location: {$baseurl}admin.php");
		break;
	case 'news':
		cekLogin();
		$data=allNews();
		if(!empty($data) and $data->success==true){
			$news="";
			$n=1;
			foreach ($data->data->news->data as $v) {
				$news.="
					<tr>
						<td>$n</td>
						<td>$v->judul</td>
						<td>".substr($v->isi,0,100)."</td>
						<td>
							<a href='{$baseurl}?page=news/show&id=$v->id' >LiHat</a> | 
							<a href='{$baseurl}admin.php?page=news/add&id=$v->id' >Ubah</a> | 
							<a href='{$baseurl}admin.php?page=news/delete&id=$v->id' >Hapus</a>
						</td>
					</tr>
				";
				$n++;
			}
			$view=view("admin/news");

			$replace=array(
				"{{title}}"=>"Bajax - News",
				"{{news}}"=>$news,
			);
			foreach ($replace as $key => $value) {
				$view=str_replace($key, $value, $view);
			}
			print($view);
		}
		break;
	case 'news/add':
		cekLogin();
		$notif="";
		if($_POST){
			$add=addNews($_POST);
			$notif="{v} Berhasil";
		}
		cekLogin();
		$view=view("admin/newsAdd");
		$data=allKategori();
		$kategori="";
		foreach ($data->data->data as $v) {
			$kategori.="<option value='$v->id'>$v->name</option>";
		}
		$replace=array(
			"{{title}}"=>"Bajax - News",
			"{{kategori}}"=>$kategori,
			"{{notif}}"=>$notif
		);
		foreach ($replace as $key => $value) {
			$view=str_replace($key, $value, $view);
		}
		print($view);
		break;
	case 'news/edit':
		# code...
		break;
	case 'news/delete':
		$id=empty($_GET['id'])?"":$_GET['id'];
		$data=deleteNews($id);
		header("Location: {$baseurl}admin.php?page=news");
		break;
}