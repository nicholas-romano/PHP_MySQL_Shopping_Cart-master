      
                <?php
            
                $query = "SELECT * FROM Ref_Item_Categories
                    ORDER BY department_name DESC";
                    
                $category = mysql_query($query)
                    or die(mysql_error());
                    
                $numRecords = mysql_num_rows($category);
                
                $catCount = 0;
                $currentDepartment = "";
                
                echo "<table><tr><td><ul>";
                
                for ($i = 0; $i < $numRecords; $i++)
                {
                    $row = mysql_fetch_array($category);
                    if ($currentDepartment != $row["department_name"])
                    {
                        if ($currentDepartment != "") echo "</ol></li>";
                        if ($catCount > $numRecords/2)
                        {
                            echo "</ul></td><td valign='top'><ul>";
                            $catCount = 0;
                        }
                        $currentDepartment = $row["department_name"];
                        echo "<li>$currentDepartment<ol>";
                    }
                    echo "<li><a href=\"category.php?cat="
                        .$row['item_category_code'] . "\">"
                        .$row['item_category_description']
                        ."</a></li>";
                    $catCount++;
                }
                echo "</ol></li></ul></td></tr></table>";
                ?>
        
