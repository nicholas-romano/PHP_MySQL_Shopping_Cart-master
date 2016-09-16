<?php
session_start();
include 'db.php';

$prod = ($_POST['prod']);
$quantity = ($_POST['quantity']);
$customer_id = $_SESSION['customer_id'];
 
$order_Id = getOrderId($customer_id);

$queryMenu = "SELECT * FROM Menu WHERE
    item_id = '$prod'";
    
    $menuQuery = mysql_query($queryMenu)
                or die(mysql_error());
                
    $numRows = mysql_num_rows($menuQuery);
    
    $menuRow = mysql_fetch_array($menuQuery);
    
    $item_id = $menuRow['item_id'];
    $item_category_code = $menuRow['item_category_code'];
    $item_category = $menuRow['item_category'];
    $item_name = $menuRow['item_name'];
    $item_size = $menuRow['item_size'];
    $item_price = $menuRow['item_price'];
    $image_url = $menuRow['image_url'];
    $item_inventory = $menuRow['item_inventory'];


if ($item_inventory < $quantity)
{
    header('Location: ../purchase.php?prod=$prod&retry=true');
}
else
{
    
    $addOrderItem = "INSERT INTO Order_Items (order_item_id, customer_id, order_item_category_code, order_item_category,
                     image_url, order_item_name, order_status_code, order_id, order_item_quantity, order_item_inventory,
                     order_item_price, order_item_size, other_order_item_details)
                     
                     VALUES ('$item_id', '$customer_id', '$item_category_code', '$item_category', '$image_url', '$item_name', 'IP', '$order_Id', '$quantity', '$item_inventory', '$item_price', '$item_size', NULL);";
                     
    $insertOrderItem = mysql_query($addOrderItem)
                    or die(mysql_error());
                    
                    
    
    header('Location: ../purchase.php?prod=view');
}

function getOrderId($customer_id)
{
    $queryOrders = "SELECT
                    Orders.order_id, Orders.order_status_code,
                    Orders.customer_id
                    FROM Orders WHERE
                    Orders.order_status_code = 'IP' and
                    Orders.customer_id = '$customer_id'";
            
    $order = mysql_query($queryOrders)
        or die(mysql_error());
        
    $rowOrders = mysql_fetch_array($order);
    
    return $rowOrders['order_id'];
}

?>