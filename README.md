# PHP_MySQL_Shopping_Cart
PHP_MySQL_Shopping_Cart application

Database Structure:


Table Name: Customers

        Table Fields:  customer_id  PRIMARY (auto_increment)
                        salutation
                        customer_first_name
                        customer_middle_initial
                        customer_last_name
                        email_address UNIQUE
                        login_name
                        login_password
                        salt
                        phone
                        address
                        city
                        state
                        zip_code
                        
Table Name: Menu

        Table Fields:  item_id PRIMARY
                       item_category_code
                       item_name INDEX
                       item_size
                       item_price
                       image_url
                       item_inventory
                       

Table Name: Orders:

        Table Fields:   order_id PRIMARY (auto_increment)
                        customer_id
                        order_status_code
                        date_order_placed
                        order_details
                    

Table Name: Order_Items:

        Table Fields:   item_sale_number PRIMARY (auto_increment)
                        order_item_id
                        customer_id
                        order_item_category_code
                        order_item_category
                        image_url
                        order_item_name
                        order_status_code
                        order_id
                        order_item_quantity
                        order_item_inventory
                        order_item_price
                        order_item_size
                        other_order_item_details
                        
Table Name:  Ref_Item_Categories

        Table Fields:   item_category_code PRIMARY
                        item_category_description
                        department_name
                        

