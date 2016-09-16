<?php
function displayReceipt($customer_id, $order)
{
    $items = getExistingOrder($customer_id);
    $numRecords = mysql_num_rows($items);
    
    displayHeader($order);
    $grandTotal = 0;
    for ($i = 0; $i < $numRecords; $i++)
                {
                    $grandTotal += displayExistingItems($items);
                }
                displayFooter($grandTotal);
}

function displayHeader($order)
{
    echo "<p class='receipt'><strong>***** R E C E I P T *****</strong></p>
      <p><strong>Payment received from $_SESSION[customer_first_name]
      $_SESSION[customer_middle_initial] $_SESSION[customer_last_name] on "
      .date("F j, Y")." at ".date('g:ia'). "<br />" .
      "<p>". "Order # " . $order . "<p>" . "</strong></p>";
    echo "<table border='1px' cellpadding='2px' class='center'>";
    echo "<tr>
        <td align='center'><strong>Image</strong></td>
        <td align='center'><strong>Name</strong></td>
        <td align='center'><strong>Size</strong></td>
        <td align='center'><strong>Price</strong></td>
        <td align='center'><strong>Quantity</strong></td>
        <td align='center'><strong>Total</strong></td>
        </tr>";
}

function getExistingOrder($customer_id)
{
    $query = "SELECT
        Orders.order_id,
        Orders.customer_id,
        Orders.order_status_code,
        Order_Items.*
        FROM Order_Items,Orders WHERE
        Orders.order_id=Order_Items.order_id and
        Orders.order_status_code='IP' and
        Orders.customer_id = '$customer_id';";
    $items = mysql_query($query)
        or die(mysql_error());
    return $items;
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
        echo "<td align='center'>" . $rowExisting['order_item_quantity'] . "</td><td align='center'>";
        $total = $rowExisting['order_item_price']*$rowExisting['order_item_quantity'];
        printf("$%.2f", $total);
        echo "</td>";
        echo "</td></tr>";
    return $total;   
}

function displayFooter($grandTotal)
{   
    echo "<tr><td colspan='5'><strong>Grand Total</strong></td>";
    printf("<td align='right'>\$%.2f</td></tr>", $grandTotal);
    echo "<tr><td colspan='6'>
        <p><strong>Your order has been processed.<br />
        Thank you very much for shopping with Romano's Signature Delights.<br />
        We appreciate your purchase of the above product(s).<br />
        You may print a copy of this page for your permanent record.<br />
        <a href='ebakery.php' class='noDecoration'>Please click here
        to return to the e-bakery home page.</a><br />
        Or you can choose any one of the navigation
        links provided in the menu.</strong></p>
        <p>Note to users of this site:<br />We have only
        marked the order and corresponding items as paid.<br />
        We have also reduced the inventory in the Menu table.<br />
        </p>
        </td></tr></table>";
}
?>