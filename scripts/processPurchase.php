<?php

$customer_id = $_SESSION['customer_id'];
$prod = stripslashes($_GET['prod']);

$items = getExistingOrder($customer_id);
$numRecords = mysql_num_rows($items);

if ($numRecords == 0 && $prod == 'view')
{
    echo "<p><strong>Your shopping cart is empty.</strong></p>
    <p><a href='ebakery.php'><strong>Please click here to continue shopping.</strong></a></p>";
}
else
{
        displayHeader();
        $grandTotal = 0;
        if ($numRecords > 0)
        {
            for ($i = 0; $i < $numRecords; $i++)
            {
                $grandTotal += displayExistingItems($items);
            }
            displayNewItem($prod);
        }
        else
        {
            displayNewItem($prod);
            
            $queryOrders = "SELECT * FROM Orders WHERE
            customer_id = '$customer_id' AND order_status_code = 'IP'";
            
            $numOrders = mysql_query($queryOrders)
                        or die(mysql_error());
                        
            $numOrderRows = mysql_num_rows($numOrders);
            
            if ($numOrderRows == 0)
            {
            createOrder($customer_id);
            }
        }
        
        if ($prod != "view") //Display entry row for new item
        {
            if ($retry)
            {
                echo "<tr><td colspan='7' align='center'>
                    <strong>Please re-enter a product
                    quantity not exceeding the inventory
                    level.</strong></td></tr>";
            }
        }
        displayFooter($grandTotal);

}

function displayHeader()
{
    echo "<form id='orderForm' method='POST' onsubmit='return validateOrderForm();'
        action='scripts/addItem.php' >";
    echo "<table border='1px'>";
    echo "<tr>
        <td align='center'><strong>Dessert Image</strong></td>
        <td align='center'><strong>Dessert Name</strong></td>
        <td align='center'><strong>Dessert Size</strong></td>
        <td align='center'><strong>Price</strong></td>
        <td align='center'><strong>Inventory</strong></td>
        <td align='center'><strong>Quantity</strong></td>
        <td align='center'><strong>Total</strong></td>
        <td align='center'><strong>Action</strong></td>
        </tr>";
}

function getExistingOrder($customer_id)
{
    $query = "SELECT
        Orders.order_id,
        Orders.customer_id,
        Orders.order_status_code,
        Order_Items.*
        FROM Order_Items, Orders WHERE
        Orders.order_id=Order_Items.order_id and
        Orders.order_status_code='IP' and
        Orders.customer_id = '$customer_id'";
    $items = mysql_query($query)
        or die(mysql_error());
    return $items;
}

function displayNewItem($prod)
{
    $queryMenu = "SELECT * FROM Menu WHERE
    item_id = '$prod'";
    
    $menuQuery = mysql_query($queryMenu)
                or die(mysql_error());
                
    $numRows = mysql_num_rows($menuQuery);
    
    $row = mysql_fetch_array($menuQuery);
    
    for ($i = 0 ; $i < $numRows ; $i++)
    {
        echo "<tr>";
        echo "<td>" . "<img height='200' width='300'
        src =\"".$row['image_url']."\" />" . "</td>";
        echo "<td>" . $row['item_name'] . "</td>";
        echo "<td>" . $row['item_size'] . "</td>";
        echo "<td>" . $row['item_price'] . "</td>";
        echo "<td>" . $row['item_inventory'] . "</td>";
        echo "<td><input type='hidden' id='prod'
                name='prod' value=\"$prod\">";
        echo "<input type='text' id='quantity'
          name='quantity' size='3'>";
        echo "</td>";
        echo "<td align='center'>";
        echo "TBA";
        echo "</td>";
        echo "<td align='center'><p><input type='submit' value='Add to cart' /></p>";
        echo "<p><a href='menu.php'>
        <input type='button' value='Continue shopping' /></a></p>
        </td>";
        echo "</tr>";
    }
    
}

function displayExistingItems($items)
{
    $rowExisting = mysql_fetch_array($items);
        echo "<tr>";
        echo "<td>" . "<img height='200' width='300'
        src =\"".$rowExisting['image_url']."\" />" . "</td>";
        echo "<td align='center'>" . $rowExisting['order_item_name'] . "</td>";
        echo "<td align='center'>" . $rowExisting['order_item_size'] . "</td>";
        echo "<td align='center'>" . $rowExisting['order_item_price'] . "</td>";
        echo "<td align='center'>" . $rowExisting['order_item_inventory'] . "</td>";
        echo "<td align='center'>" . $rowExisting['order_item_quantity'] . "</td><td align='center'>";
        $total = $rowExisting['order_item_price']*$rowExisting['order_item_quantity'];
        printf("$%.2f", $total);
        echo "</td>";
        echo "<td align='center'>";
        echo "<p><a href='scripts/deleteItem.php?order_item=".
        $rowExisting['order_item_id']."&order_id=".$rowExisting['order_id']."'>
        <input type='button' value='Delete from cart' /></a></p>
        <p><a href='menu.php'>
        <input type='button' value='Continue shopping' />
        </a></p></td></tr>";
    return $total;   
}

function createOrder($customer_id)
{
    $createOrder = "INSERT INTO Orders ( customer_id, order_status_code, date_order_placed, order_details )
                    VALUES ('$customer_id', 'IP', CURDATE( ), NULL );";
                    
    mysql_query($createOrder)
        or die(mysql_error());
    
}

function displayFooter($grandTotal)
{   
    echo "<tr><td colspan='6'><strong>Grand Total</strong></td>";
    printf("<td align='right'>\$%.2f</td>", $grandTotal);
    echo "<td align='center'><a href = 'payment.php'>
        <input type='button' value='Proceed to checkout' /></a>
        </td></table></form>";
}
?>