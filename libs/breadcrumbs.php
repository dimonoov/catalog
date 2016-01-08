<?php defined("CATALOG") or die("Access denied");

// хлебные крошки
// return true (array not empty) || return false
$breadcrumbs_array = breadcrumbs($categories, $id);

if($breadcrumbs_array){
	$breadcrumbs = "<a href='" .PATH. "'>Главная</a> / ";
	foreach($breadcrumbs_array as $alias => $title){
		$breadcrumbs .= "<a href='" .PATH. "category/{$alias}'>{$title}</a> / ";
	}
	if( !isset($get_one_product) ){
		$breadcrumbs = rtrim($breadcrumbs, " / ");
		$breadcrumbs = preg_replace("#(.+)?<a.+>(.+)</a>$#", "$1$2", $breadcrumbs);
	}else{
		$breadcrumbs .= $get_one_product['title'];
	}
}else{
	$breadcrumbs = "<a href='" .PATH. "'>Главная</a> / Каталог";
}

$breadcrumbs2 = explode(' / ', $breadcrumbs);
$breadcrumbs_new = null;
$end = array_pop($breadcrumbs2);
foreach($breadcrumbs2 as $item){
	$breadcrumbs_new .= "<li>{$item} - </li>";
}
$breadcrumbs_new .= "<li>{$end}</li>";

?>