<?php defined("CATALOG") or die("Access denied");

/**
* поиск автокомплит
**/
function search_autocomplete(){
	global $connection;
	$search = trim(mysqli_real_escape_string($connection, $_GET['term']));
	$query = "SELECT title FROM products WHERE title LIKE '%{$search}%' LIMIT 10";
	$res = mysqli_query($connection, $query);
	$result_search = array();
	while($row = mysqli_fetch_assoc($res)){
		$result_search[] = $row['title'];
	}
	return $result_search;
}

/**
* кол-во результатов поиска
**/
function count_search(){
	global $connection;
	$search = trim(mysqli_real_escape_string($connection, $_GET['search']));
	$query = "SELECT COUNT(*) FROM products WHERE title LIKE '%{$search}%'";
	$res = mysqli_query($connection, $query);
	$count_search = mysqli_fetch_row($res);
	return $count_search[0];
}

/**
* поиск
**/
function search($start_pos, $perpage){
	global $connection;
	$search = trim(mysqli_real_escape_string($connection, $_GET['search']));
	$query = "SELECT id, title, alias, price, image FROM products WHERE title LIKE '%{$search}%' LIMIT $start_pos, $perpage";
	$res = mysqli_query($connection, $query);
	if( !mysqli_num_rows($res) ){
		return 'Ничего не найдено';
	}

	$result_search = array();
	while($row = mysqli_fetch_assoc($res)){
		$result_search[] = $row;
	}
	return $result_search;
}