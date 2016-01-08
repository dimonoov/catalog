<?php defined("CATALOG") or die("Access denied");

/**
* куки, проверка авторизации
**/
function check_remember(){
	// если пользователь авторизован - выходим
	if( isset( $_SESSION['auth']['user'] ) ) return;
	// если пользователь не запоминался
	if( !isset($_COOKIE['hash']) ) return;

	global $connection;
	$hash = mysqli_real_escape_string($connection, $_COOKIE['hash']);
	$query = "SELECT name, is_admin FROM users WHERE remember = '$hash'";
	$res = mysqli_query($connection, $query);
	if(mysqli_num_rows($res) == 1){
		$row = mysqli_fetch_assoc($res);
		$_SESSION['auth']['user'] = $row['name'];
		$_SESSION['auth']['is_admin'] = $row['is_admin'];
	}else{
		setcookie('hash', '', time() - 3600);
	}
}

/**
* Распечатка массива
**/
function print_arr($array){
	echo "<pre>" . print_r($array, true) . "</pre>";
}

/**
* Редирект
**/
function redirect($http = false){
	if($http) $redirect = $http;
	else $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : PATH;
	header("Location: $redirect");
	exit;
}

/**
* Получение страниц
**/
function get_pages(){
	global $connection;
	$query = "SELECT title, alias FROM pages ORDER BY position";
	$res = mysqli_query($connection, $query);

	$pages = array();
	while($row = mysqli_fetch_assoc($res)){
		$pages[$row['alias']] = $row['title'];
	}
	return $pages;
}

/**
* Получение массива категорий
**/
function get_cat(){
	global $connection;
	$query = "SELECT * FROM categories";
	$res = mysqli_query($connection, $query);

	$arr_cat = array();
	while($row = mysqli_fetch_assoc($res)){
		$arr_cat[$row['id']] = $row;
	}
	return $arr_cat;
}

/**
* Построение дерева
**/
function map_tree($dataset) {
	$tree = array();

	foreach ($dataset as $id=>&$node) {    
		if (!$node['parent']){
			$tree[$id] = &$node;
		}else{ 
            $dataset[$node['parent']]['childs'][$id] = &$node;
		}
	}

	return $tree;
}

/**
* Дерево в строку HTML
**/
function categories_to_string($data, $template = 'category_template.php'){
	$string = null;
	foreach($data as $item){
		$string .= categories_to_template($item, $template);
	}
	return $string;
}

/**
* Шаблон вывода категорий
**/
function categories_to_template($category, $template){
	ob_start();
	include "views/{$template}";
	return ob_get_clean();
}

/**
* Постраничная навигация
**/
function pagination($page, $count_pages, $modrew = true){
	// << < 3 4 5 6 7 > >>
	$back = null; // ссылка НАЗАД
	$forward = null; // ссылка ВПЕРЕД
	$startpage = null; // ссылка В НАЧАЛО
	$endpage = null; // ссылка В КОНЕЦ
	$page2left = null; // вторая страница слева
	$page1left = null; // первая страница слева
	$page2right = null; // вторая страница справа
	$page1right = null; // первая страница справа

	$uri = "?";
	if(!$modrew){
		// если есть параметры в запросе
		if( $_SERVER['QUERY_STRING'] ){
			foreach ($_GET as $key => $value) {
				if( $key != 'page' ) $uri .= "{$key}=$value&amp;";
			}
		}
	}else{
		$url = $_SERVER['REQUEST_URI'];
		$url = explode("?", $url);
		if(isset($url[1]) && $url[1] != ''){
			$params = explode("&", $url[1]);
			foreach($params as $param){
				if(!preg_match("#page=#", $param)) $uri .= "{$param}&amp;";
			}
		}
	}

	if( $page > 1 ){
		$back = "<li><a class='nav-link' href='{$uri}page=" .($page-1). "'>Назад</a></li>";
	}
	if( $page < $count_pages ){
		$forward = "<li><a class='nav-link' href='{$uri}page=" .($page+1). "'>Вперед</a></li>";
	}
	if( $page > 3 ){
		$startpage = "<li><a class='nav-link' href='{$uri}page=1'>В начало</a></li>";
	}
	if( $page < ($count_pages - 2) ){
		$endpage = "<li><a class='nav-link' href='{$uri}page={$count_pages}'>В конец</a></li>";
	}
	if( $page - 2 > 0 ){
		$page2left = "<li><a class='nav-link' href='{$uri}page=" .($page-2). "'>" .($page-2). "</a></li>";
	}
	if( $page - 1 > 0 ){
		$page1left = "<li><a class='nav-link' href='{$uri}page=" .($page-1). "'>" .($page-1). "</a></li>";
	}
	if( $page + 1 <= $count_pages ){
		$page1right = "<li><a class='nav-link' href='{$uri}page=" .($page+1). "'>" .($page+1). "</a></li>";
	}
	if( $page + 2 <= $count_pages ){
		$page2right = "<li><a class='nav-link' href='{$uri}page=" .($page+2). "'>" .($page+2). "</a></li>";
	}

	return $startpage.$back.$page2left.$page1left.'<li class="active-page">'.$page.'</li>'.$page1right.$page2right.$forward.$endpage;
}

/**
* Хлебные крошки
**/
function breadcrumbs($array, $id){
	if(!$id) return false;

	$count = count($array);
	$breadcrumbs_array = array();
	for($i = 0; $i < $count; $i++){
		if(isset($array[$id])){
			$breadcrumbs_array[$array[$id]['alias']] = $array[$id]['title'];
			$id = $array[$id]['parent'];
		}else break;
	}
	return array_reverse($breadcrumbs_array, true);
}

?>