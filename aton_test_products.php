<html>
<head>
    <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список товаров</title>
    <style>

        body {
          background: linear-gradient(#FFF 0%, #5BCAFF 100%);
        }
        
        h1 {
            text-align: center;
            font-size: 24px;
        }
        
         .container {
            display: flex;
            justify-content: center;
        }

        table {
            border: 3px solid #000;
           margin: auto;
            max-width: 600px;
            margin: 0 auto;
        }

        th, td {
            text-align: center;
            padding: 5px;
        }

        th {
            background-color: linear-gradient(#FFF 0%, #5BCAFF 100%);
        }

        td:nth-child(even) {
            background-color: #f2f2f2;
        }

        .product-name {
            color: #333;
        }

        .price {
            color: #555;
        }
        .btn-1 {
              background:#87CEFA;
              border: 1px solid #FFF;
              border-radius: 4px;
        }
        .btn-1:hover {
           border: 1px solid #000;
        }
    </style>
</head>
<body>
    <div class="container">
        <div>
        <h1>Список товаров</h1>
        <form method="GET" action="">
            <label for="filter">Фильтр по названию:</label>
            <input type="text" name="filter" id="filter">
            <button class = "btn-1" type="submit">Применить фильтр</button>
        </form>
        <form method="GET" action="">
            <label for="sort">Сортировка по цене:</label>
            <select name="sort" id="sort">
                <option value="asc">По возрастанию</option>
                <option value="desc">По убыванию</option>
            </select>
            <button class = "btn-1" type="submit">Применить сортировку</button>
        </form>
    
    <?php
    include("login.inc");
    $tab_name = "products";
    $con = mysql_connect($servername, $username, $password)
        or die("Не удалось подключиться к базе данных");

    mysql_select_db($dbname, $con)
        or die("Не удалось выбрать базу данных");

    function display_db_query($query_string, $connection, $header_bool, $table_params)
    {
        // Выполнить запрос к базе данных
        $result_id = mysql_query($query_string, $connection)
            or die("display_db_query:" . mysql_error());

        // Определить количество столбцов в результате
        $column_count = mysql_num_fields($result_id)
            or die("display_db_query: " . mysql_error());

        print("<TABLE $table_params >\n");

        if ($header_bool) {
            print ("<TR>");
            for ($column_num = 0; $column_num < $column_count; $column_num++) {
                $field_name = mysql_field_name($result_id, $column_num);
                print("<TH>$field_name</TH>");
            }
            print("</TR>\n");
        }

        while ($row = mysql_fetch_row($result_id)) {
            print ("<TR ALIGN=LEFT VALIGN=TOP>");
            for ($column_num = 0; $column_num < $column_count; $column_num++) {
                print ("<TD>$row[$column_num] </TD>");
            }
            print ("</TR>\n");
        }
        print ("</TABLE>\n");
    }
// фильтр по названию товара
    function display_db_table($tablename, $connection, $header_bool, $table_params)
    {
        $query_string = "SELECT * FROM $tablename";
        display_db_query($query_string, $connection, $header_bool, $table_params);
    }

    function display_filtered_table($tablename, $connection, $header_bool, $table_params, $filter)
    {
        $filter = mysql_real_escape_string($filter);
        $query_string = "SELECT * FROM $tablename WHERE НазваниеТовара LIKE '%$filter%'";
        display_db_query($query_string, $connection, $header_bool, $table_params);
    }

    $filter = isset($_GET['filter']) ? $_GET['filter'] : '';
    $sort = isset($_GET['sort']) ? $_GET['sort'] : '';
    if (!empty($filter)) {
        display_filtered_table($tab_name, $con, TRUE, "BORDER=5", $filter);
    } elseif ($sort === 'asc' || $sort === 'desc') {
        $query_string = "SELECT * FROM $tab_name ORDER BY Цена $sort";
        display_db_query($query_string, $con, TRUE, "BORDER=5");
    } else {
        display_db_table($tab_name, $con, TRUE, "BORDER=5");
    }

    mysql_close($con);
    

   
?>   

    </div>
    </div>
</body>
</html>
