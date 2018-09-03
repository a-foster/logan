<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../objects/shopping.php';

$shopping = new Shopping();

// get all added items
$items = $shopping->getItems(array('item_added' => '1'));

// loop through and group items by category
$items_by_category = new stdClass();
foreach ($items as $item) {
    $items_by_category->{$item['item_category']} = $items_by_category->{$item['item_category']} ?? new stdClass();
    $items_by_category->{$item['item_category']}->{$item['item_name']} = $item['item_added'];
}

// build up the html list by category
$html_string = '';
foreach ($items_by_category as $category => $category_items) {
    $html_string .= "<html><body><h3><strong>$category</strong></h3>";

    foreach ($category_items as $item_name => $item_frequency) {
        $html_string .= "<p><input type='checkbox'>$item_name</p>";
    }
    $html_string .= "<br>";
    $html_string .= "</body></html>";
}

echo json_encode($html_string);
?>
