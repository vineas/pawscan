<?php 

include 'library/Requests.php';
Requests::register_autoloader();

// NEWS
function allNews($num = 15,$page = 1){
	global $api;
	$request = Requests::get($api."news/all/$num?page=$page");
	return json_decode($request->body);
}
function showNews($id){
	global $api;
	$request = Requests::get($api."news/show/".$id);
	return json_decode($request->body);
}
function filterNews($search,$page = 1){
	global $api;
	$headers = array('Accept' => 'application/json');
	$options = array('search' => "$search");
	$request = Requests::post($api."news/filter?page=$page",$headers,$options);
	return json_decode($request->body);
}
function addNews($data){
	global $api, $token;
	if(!empty($token)){
		$headers = array('Accept' => 'application/json', 'Authorization' => $token);
		$options = $data;
		$request = Requests::post($api."news/add",$headers,$options);
		return json_decode($request->body);
	}
	else return "";
}
function updateNews($id,$data){
	global $api, $token;
	if(!empty($token)){
		$headers = array('Accept' => 'application/json', 'Authorization' => $token);
		$options = $data;
		$request = Requests::post($api."news/update/$id",$headers,$options);
		return json_decode($request->body);
	}
	else return "";
}
function deleteNews($id){
	global $api, $token;

	if(!empty($token)){
		$headers = array('Authorization' => $token);
		$request = Requests::post($api."news/delete/$id",$headers);
		return json_decode($request->body);
	}
	else return "";
}

// Kategori
function allKategori($num = 15,$page = 1){
	global $api;
	$request = Requests::get($api."kategori/all/$num?page=$page");
	return json_decode($request->body);
}
function showKategori($id){
	global $api;
	$request = Requests::get($api."kategori/show/".$id);
	return json_decode($request->body);
}
function filterKategori($search,$page = 1){
	global $api;
	$headers = array('Accept' => 'application/json');
	$options = array('search' => "$search");
	$request = Requests::post($api."kategori/filter?page=$page",$headers,$options);
	return json_decode($request->body);
}
function addKategori($search,$page = 1,$data){
	global $api, $token;
	if(!empty($token)){
		$headers = array('Accept' => 'application/json', 'Authorization' => $token);
		$options = $data;
		$request = Requests::post($api."kategori/filter?page=$page",$headers,$options);
		return json_decode($request->body);
	}
	else return "";
}
function updateKategori($id,$data){
	global $api, $token;
	if(!empty($token)){
		$headers = array('Accept' => 'application/json', 'Authorization' => $token);
		$options = $data;
		$request = Requests::post($api."kategori/update/$id",$headers,$options);
		return json_decode($request->body);
	}
	else return "";
}
function deleteKategori($id){
	global $api, $token;

	if(!empty($token)){
		$headers = array('Authorization' => $token);
		$request = Requests::post($api."news/delete/$id",$headers);
		return json_decode($request->body);
	}
	else return "";
}

// USER
function allUser($num = 15,$page = 1){
	global $api;
	$request = Requests::get($api."user/all/$num?page=$page");
	return json_decode($request->body);
}
function showUser($id){
	global $api;
	$request = Requests::get($api."user/show/".$id);
	return json_decode($request->body);
}
function filterUser($search,$page = 1){
	global $api;
	$headers = array('Accept' => 'application/json');
	$options = array('search' => "$search");
	$request = Requests::post($api."user/filter?page=$page",$headers,$options);
	return json_decode($request->body);
}
function addUser($search,$page = 1,$data){
	global $api, $token;
	if(!empty($token)){
		$headers = array('Accept' => 'application/json', 'Authorization' => $token);
		$options = $data;
		$request = Requests::post($api."user/filter?page=$page",$headers,$options);
		return json_decode($request->body);
	}
	else return "";
}
function updateUser($id,$data){
	global $api, $token;
	if(!empty($token)){
		$headers = array('Accept' => 'application/json', 'Authorization' => $token);
		$options = $data;
		$request = Requests::post($api."user/update/$id",$headers,$options);
		return json_decode($request->body);
	}
	else return "";
}
function deleteUser($id){
	global $api, $token;
	if(!empty($token)){
		$headers = array('Accept' => 'application/json', 'Authorization' => $token);
		$request = Requests::post($api."user/delete/$id",$headers);
		return json_decode($request->body);
	}
	else return "";
}

//SCAN

function scanWeb($url){
	global $api;
	$headers = array('Accept' => 'application/json');
	$options = array('url' => $url);
	$request = Requests::post($api."web/scan",$headers,$options);
	return json_decode($request->body);
}
function resultWeb($id){
	global $api;
	$request = Requests::get($api."web/result/$id");
	return json_decode($request->body);
}

// AUTH
function login($data){
	global $api;
	$headers = array('Accept' => 'application/json');
	$options = $data;
	$request = Requests::post($api."login",$headers,$options);
	return json_decode($request->body);
}
function logout(){
	global $api, $token;
	if(!empty($token)){
		$headers = array('Accept' => 'application/json', 'Authorization' => $token);
		$request = Requests::post($api."logout",$headers);
		return json_decode($request->body);
	}
	else return "";
}

// themes
function view($view){
	global $baseurl;
	$return=file_get_contents("view/layout/head.html");
	$return.=file_get_contents("view/layout/navs.html");
	$return.=file_get_contents("view/$view.html");
	$return.=file_get_contents("view/layout/foot.html");

	return str_replace("{{baseurl}}", $baseurl, $return);
}
?>