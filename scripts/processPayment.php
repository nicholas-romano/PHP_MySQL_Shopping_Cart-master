<?php
$customer_id = ($_SESSION['customer_id']);
include 'receipt.php';
$order = getOrderId($customer_id);
displayReceipt($customer_id, $order);
$order_Id = orderPaid($customer_id);
orderItemsPaid($order_Id);
reduceInventory($order_Id);

function getOrderId($customer_id) {
    
    $queryOrders = "SELECT * FROM Orders WHERE
    customer_id = '$customer_id' AND order_status_code = 'IP'";
    
    $makeQuery = mysql_query($queryOrders)
                or die(mysql_error());
                
    $orderRow = mysql_fetch_array($makeQuery);
    
    $order = $orderRow['order_id'];
    
    return $order;
    
}

function orderPaid($customer_id)
{
    $queryOrders = "SELECT * FROM Orders WHERE
    customer_id = '$customer_id' AND order_status_code = 'IP'";
    
    $makeQuery = mysql_query($queryOrders)
                or die(mysql_error());
                
    $orderRow = mysql_fetch_array($makeQuery);            
    
    
    $markPaid = "REPLACE INTO Orders (order_id, customer_id, order_status_code, date_order_placed, order_details)
                VALUES ('".$orderRow['order_id']."', '".$orderRow['customer_id']."', 'PD', CURDATE(), NULL);";
                
    $queryReplace = mysql_query($markPaid)
                    or die(mysql_error());
    
    return $orderRow['order_id'] ;

}

function orderItemsPaid($order_Id)
{
    $queryOrderItems = "SELECT * FROM Order_Items WHERE
    order_id = '$order_Id'";
        
    $orderItems = mysql_query($queryOrderItems)
        or die(mysql_error());
    
    $numRecords = mysql_num_rows($orderItems);
    
    for ($i = 0; $i < $numRecords; $i++)
    {
        $row = mysql_fetch_array($orderItems);
        
        $item_sale_number = $row['item_sale_number'];
        $prod = $row['order_item_id'];
        $customer_id = $row['customer_id'];
        $order_item_category_code = $row['order_item_category_code'];
        $order_item_category = $row['order_item_category'];
        $image_url = $row['image_url'];
        $order_item_name = $row['order_item_name'];
        $order_id = $row['order_id'];
        $quantity = $row['order_item_quantity'];
        $order_item_inventory = $row['order_item_inventory'];
        $order_item_price = $row['order_item_price'];
        $order_item_size = $row['order_item_size'];
        $other_order_item_details = $row['other_order_item_details'];
        
        $query2 = "REPLACE INTO Order_Items (item_sale_number, order_item_id, customer_id, order_item_category_code, order_item_category, image_url, order_item_name, order_status_code, order_id,
            order_item_quantity, order_item_inventory, order_item_price, order_item_size, other_order_item_details)
            
        VALUES ('$item_sale_number', '$prod', '$customer_id', '$order_item_category_code', '$order_item_category', '$image_url', '$order_item_name', 'PD', '$order_Id', '$quantity', '$order_item_inventory', '$order_item_price', '$order_item_size', NULL);";
        mysql_query($query2)
            or die(mysql_error());
            
    }
}
 
function reduceInventory($order_Id)
{
    $queryOrderItems = "SELECT * FROM Order_Items WHERE
    order_id = '$order_Id'";
        
    $orderItems = mysql_query($queryOrderItems)
        or die(mysql_error());
    
    $numRecords = mysql_num_rows($orderItems);
    
    for ($i = 0; $i < $numRecords; $i++)
    {
        
        $row = mysql_fetch_array($orderItems);
        
        $item_sale_number = $row['item_sale_number'];
        $customer_id = $row['customer_id'];
        $order_status_code = $row['order_status_code'];
        $quantity = $row['order_item_quantity'];
    
        $item_id = $row['order_item_id'];
        $item_category_code = $row['order_item_category_code'];
        $item_category = $row['order_item_category'];
        $item_name = $row['order_item_name'];
        $item_size = $row['order_item_size'];
        $item_price = $row['order_item_price'];
        $image_url = $row['image_url'];
        
        $inventory_result = $row['order_item_inventory'] - $row['order_item_quantity'];
        
        
        $query3 = "REPLACE INTO Order_Items (item_sale_number, order_item_id, customer_id, order_item_category_code, order_item_category, image_url, order_item_name, order_status_code, order_id,
            order_item_quantity, order_item_inventory, order_item_price, order_item_size, other_order_item_details)
            
        VALUES ('$item_sale_number', '$item_id', '$customer_id', '$item_category_code', '$item_category', '$image_url', '$item_name', 'PD', '$order_Id', '$quantity', '$inventory_result', '$item_price', '$item_size', NULL);";
        mysql_query($query3)
            or die(mysql_error());
            
        
        $query4 = "REPLACE INTO Menu (item_id, item_category_code, item_category, item_name, item_size, item_price, image_url, item_inventory)
        VALUES
        ('$item_id', '$item_category_code', '$item_category', '$item_name', '$item_size', '$item_price', '$image_url', '$inventory_result');";
    
        mysql_query($query4)
        or die(mysql_error());
    }
              
}       

?>