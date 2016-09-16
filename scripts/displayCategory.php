<?php
session_start();
$cat = stripslashes($_GET['cat']);
$query = "SELECT * FROM Menu WHERE
    item_category_code = '$cat'
    ORDER BY item_name ASC";
$category = mysql_query($query)
    or die(mysql_error());
$numRecords = mysql_num_rows($category);
echo "<p><a class=\"noDecoration\" 
    href='menu.php'><strong>Click here to return to
    our Menu listing</strong></a></p>";
echo "<table border='4px'>";
echo "<tr><td align='center'><strong>Product Image</strong></td>
    <td align='center'><strong>Product Name</strong></td>
    <td align='center'><strong>Product Size</strong></td>
    <td align='center'><strong>Price</strong></td>
    <td align='center'><strong>Quantity in Stock</strong></td>
    <td align='center'><strong>Purchase?</strong></td></tr>";
for ($i = 0; $i < $numRecords; $i++)
{
    echo "<tr>";
    $row = mysql_fetch_array($category);
    echo "<td align='center'>";
    echo  "<img src='".$row["image_url"]."'
         alt='Product Image' />";
    echo "</td><td>";
    echo $row['item_name'];
    echo "</td><td>";
    echo $row['item_size'];
    echo "</td><td align='center'>";
    printf("$%.2f",$row['item_price']);
    echo "</td><td align='center'>";
    echo $row['item_inventory'];
    echo "</td>" . "<td align='center'>";
    echo "<a href=\"purchase.php?prod=".$row['item_id']."\">"
    ."<img src='images/buyitem.png' alt='Buy this item' />" . "</a>"
    ."</td></tr>";
}
echo "</table>";
?>
