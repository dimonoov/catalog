<?php defined("CATALOG") or die("Access denied");

include 'main_controller.php';
include "models/{$view}_model.php";

$get_one_product = get_one_product($product_alias);
if(!$get_one_product){
	include VIEW . "404.php";
	exit;
}
// получаем ID категории
$id = $get_one_product['parent'];

// ID товара
$product_id = $get_one_product['id'];
// кол-во комментариев
$count_comments = count_comments($product_id);
// получаем комментарии к товару
$get_comments = get_comments($product_id);
// строим дерево
$comments_tree = map_tree($get_comments);
// получаем HTML-код комментариев
$comments = categories_to_string($comments_tree, 'comments_template.php');

include 'libs/breadcrumbs.php';

include VIEW . "{$view}.php";

?>