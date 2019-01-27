<?php
session_start();
$baseurl="http://localhost/web/";
$token="";
$api="http://localhost/WebScan/public/";
include 'api.php';

$page=empty($_GET['page'])?"":$_GET['page'];

switch ($page) {
	// HOME
	default:
		$data=allNews(4);
		$news="";
		foreach ($data->data->news->data as $v) {
			$news.='
			<div class="card p-3 col-12 col-md-6 col-lg-4">
				<div class="card-wrapper ">
					<div class="card-box">
						<h4 class="card-title mbr-fonts-style display-7">'.$v->judul.'</h4>
						<p class="mbr-text mbr-fonts-style align-left display-7">'.substr($v->isi,0,100).' ...</p>
						<br>
						<a href="'.$baseurl.'?page=news/show&id='.$v->id.'" class="btn btn-sm btn-primary">Baca</a>
					</div>
				</div>
			</div>
			';
		}

		$replace=array(
			"{{title}}"=>"Bajax - Barudak Jaringan Komputer",
			"{{news}}"=>$news,
		);
		$view=view("home");
		foreach ($replace as $key => $value) {
			$view=str_replace($key, $value, $view);
		}
		print($view);
		break;

	// NEWS
	case 'news':
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
						<td><a href='$baseurl?page=news/show&id=$v->id' >Baca</a></td>
					</tr>
				";
				$n++;
			}
			$view=view("news");

			$replace=array(
				"{{title}}"=>"Bajax - Hasil Pencarian $search",
				"{{news}}"=>$news,
			);
			foreach ($replace as $key => $value) {
				$view=str_replace($key, $value, $view);
			}
			print($view);
		}
		break;
		break;
	case 'news/show':
		$id=empty($_GET['id'])?"":$_GET['id'];
		$data=showNews($id);
		if(!empty($data) and $data->success==true){
			$kategori="";
			foreach ($data->data->kategori as $k) {
				$kategori.=$k->name.", ";
			}
			$replace=array(
				"{{title}}"=>"Bajax - ".$data->data->news->judul,
				"{{kategori}}"=>$kategori,
				"{{judul}}"=>$data->data->news->judul,
				"{{isi}}"=>$data->data->news->isi,
			);
			$view=view("detailnews");
			foreach ($replace as $key => $value) {
				$view=str_replace($key, $value, $view);
			}
			print($view);
		}
		break;
	case 'news/filter':
		$search=empty($_GET['search'])?"":$_GET['search'];
		$data=filterNews($search);
		if(!empty($data) and $data->success==true){
			$news="";
			$n=1;
			foreach ($data->data->news->data as $v) {
				$news.="
					<tr>
						<td>$n</td>
						<td>$v->judul</td>
						<td>".substr($v->isi,0,100)."</td>
						<td><a href='$baseurl?page=news/show&id=$v->id' >Baca</a></td>
					</tr>
				";
				$n++;
			}
			$view=view("search");

			$replace=array(
				"{{title}}"=>"Bajax - Hasil Pencarian $search",
				"{{search}}"=>$search,
				"{{hasilcari}}"=>$news,
			);
			foreach ($replace as $key => $value) {
				$view=str_replace($key, $value, $view);
			}
			print($view);
		}
		break;

	case 'web':
		$url=empty($_GET['url'])?"":$_GET['url'];
		$data=scanWeb($url);
		if(!empty($data) and $data->success==true){
			header("Location: $baseurl?page=web/result&id=".$data->data->key);
		}
		else {
			echo '
			<script>
			   alert("URL NOT Falid");
			   window.location = "'.$baseurl.'";
			</script>
			';
		}
		break;
	
	case 'web/result':
		$id=empty($_GET['id'])?"":$_GET['id'];
		$data=resultWeb($id);
		if(!empty($data) and $data->success==true){
            $resultscan="";
            $no=1;
            foreach ($data->data as $v) {
                $resultscan.="
                <tr>
                    <td>$no</td>
                    <td>$v->name</td>
                    <td>$v->ids</td>
                    <td>$v->summary</td>
                    <td>$v->publish</td>
                    <td>".str_replace("|", "<br>", $v->severity)."</td>
                </tr>
                ";
                $no++;
            }

			$view=view('webScan');
			$replace=array(
				"{{title}}"=>"Bajax - Hasil Scanning",
				"{{saveurl}}"=>$baseurl."?page=web/result&id=$id",
				"{{id}}"=>$id,
				"{{status}}"=>($data->scanning == 1)?"Dalam Proses":"Scanning Selesai",
				"{{resultscan}}" => $resultscan
			);
			foreach ($replace as $key => $value) {
				$view=str_replace($key, $value, $view);
			}
			print($view);
		}
		else {
			echo '
			<script>
			   alert("ID NOT Falid");
			   window.location = "'.$baseurl.'";
			</script>
			';
		}
		break;
}

?>