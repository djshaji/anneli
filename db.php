<?php


// $default_database = "mysql:host=localhost;dbname=iqac;charset=utf8mb4" ;
if (!isset ($config ['database'])) {
    die ("must set default database to use db.php") ;
}

$default_database = $config ['database'] ;

$db = new PDO ($default_database, $config ['database_user'], $config ['database_pass']);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

function create_db_words () {
    global $db, $OUTPUT;
    $sql = 'CREATE table words (
            uid text,
            email text,
            word text,
            trans text,
            trans_modified text,
            article text,
            lang text,
            source text,
            auto_id int not null AUTO_INCREMENT,
            primary key (auto_id)
    )  DEFAULT CHARSET=utf8mb4';

    sql_exec ($sql);
}

function create_db_articles () {
    global $db, $OUTPUT;
    $sql = 'CREATE table articles (
            uid text,
            email text,
            article text,
            title text,
            author text,
            input_lang text,
            article_lang text,
            ddate text,
            category text,
            tags text,
            approved text,
            auto_id int not null AUTO_INCREMENT,
            primary key (auto_id)
    )  DEFAULT CHARSET=utf8mb4';

    sql_exec ($sql);
}

function sql_exec ($sql, $refer = true) {
    // die ($sql);
    global $db, $_SERVER ;
    try {
        $ret = $db -> query ($sql) ;

    } catch (Exception $e) {
        if (! isset ($_GET ['quiet']))
            printf ('<script>
            Swal.fire ("Data Not Saved", \'Your data could not be added. Try again. <br><br>%s\', "error").then((e)=>{ 
            // window.history.back ()
            // alert ("sss")
            // %s
            location.href = "%s"
            })

            </script>', str_replace ("'", "", $e -> errorInfo [2]), $sql, $_SERVER['HTTP_REFERER']);
        else {
            $response = array (
                "sql" => $sql,
                "error" => str_replace ("'", "", $e -> errorInfo [2])
            ) ;
            die (json_encode ($response));
        }
    }

    $redirect = null ;
    if (isset ($_SERVER['HTTP_REFERER']))
        $redirect = $_SERVER['HTTP_REFERER'] ;
    if (isset ($_GET ['redirect']))
        $redirect = $_GET ['redirect'] ;
    if ($ret && $refer)
        printf ('<script>
        Swal.fire ("Operation Completed Successfully", "Your operation has been completed successfully", "success").then((e)=>{ 
        // window.history.back ()
        // alert ("sss")
        location.href = "%s"
        })

        </script>', $redirect);
    else
        return $ret -> fetchAll () ;
        
}

function db_insert ($table, $params, $redirect = true) {
    global $uid, $email;
    // foreach ($params as $p => $v) {
    //     if ($v == '')
    //         unset ($params [$p]) ;
    // }
    $fields = implode(',', array_keys($params));
    // $qms    = array_fill(0, count($params), '?');
    $qms    = implode('\',\'', $params);
    $sql = "INSERT INTO $table ($fields, uid, email) VALUES('$qms', \"$uid\", \"$email\")";
    // die ($sql);
    sql_exec ($sql, $redirect);
}

function db_update_or_insert ($table, $params, $colnames, $redirect = true) {
    global $uid, $email;
    $sql .= "select * from $table WHERE uid = '$uid' and " ;
    foreach ($colnames as $col => $val) {
        $count ++ ;
        $sql .= "$col = '$val' " ;
        if ($count < sizeof ($colnames))
            $sql .= 'and ' ;
    }

    // die ($sql);
    if (sql_exec ($sql, false) == []) {
        foreach ($colnames as $name => $value) {
            $params [$name] = $value ;
        }
        db_insert ($table, $params, $redirect);
    }
    else
        db_update_multi ($table, $params, $colnames, $redirect) ;
}

function db_update ($table, $params, $colname, $value, $redirect = true) {
    global $uid, $email;
    // foreach ($params as $p => $v) {
    //     if ($v == '')
    //         unset ($params [$p]) ;
    // }
    // $fields = implode(',', array_keys($params));
    // $qms    = array_fill(0, count($params), '?');
    // $qms    = implode('","', $params);
    $sql = "UPDATE $table set " ;
    $count = 0 ;
    foreach($params as $p => $v) {
        $sql .= "$p = '$v'" ;
        $count ++ ;
        if ($count < sizeof ($params))
            $sql .= ', ';
    }
    $sql .= " WHERE $colname = '$value'" ;
    // die ($sql);
    sql_exec ($sql, $redirect);
}

function db_update_multi ($table, $params, $colnames, $redirect = true) {
    global $uid, $email;
    // foreach ($params as $p => $v) {
    //     if ($v == '')
    //         unset ($params [$p]) ;
    // }
    // $fields = implode(',', array_keys($params));
    // $qms    = array_fill(0, count($params), '?');
    // $qms    = implode('","', $params);
    $sql = "UPDATE $table set " ;
    $count = 0 ;
    foreach($params as $p => $v) {
        $sql .= "$p = '$v'" ;
        $count ++ ;
        if ($count < sizeof ($params))
            $sql .= ', ';
    }
    $sql .= " WHERE " ;
    $count = 0 ;
    foreach ($colnames as $col => $val) {
        $count ++ ;
        $sql .= "$col = '$val' " ;
        if ($count < sizeof ($colnames))
            $sql .= 'and ' ;
    }
    // die ($sql);
    sql_exec ($sql, $redirect);
}

function db_exists ($table, $colname, $value) {
    $sql = "SELECT * FROM $table WHERE $colname LIKE '$value' LIMIT 1"     ;
    return sizeof (sql_exec ($sql, false))    ;

}

function db_delete ($table, $id, $redirect = true) {
    global $uid ;
    $sql = sprintf ("DELETE from $table where auto_id = '%s' and uid = '%s'", $id, $uid) ;
    // die ($sql);
    sql_exec ($sql, $redirect);
}

function create_db ($table, $columns = null) {
    $cols = ["uid", "email"] ;
    if ($cols == null)
        $columns = $_POST ;
    foreach ($columns as $p => $v) {
        array_push ($cols, str_replace ("/", "_", $p)) ;
    }
    $sql = sprintf ("CREATE table %s (%s text, auto_id int not null AUTO_INCREMENT, primary key (auto_id))",
                    $table, implode (" text, ", $cols));
    // echo ($sql);
    sql_exec ($sql);
}

function exceptions_error_handler($severity, $message, $filename, $lineno) {
    // throw new ErrorException($message, 0, $severity, $filename, $lineno);    
    $a = array  (
        "severity" => $severity,
        "message" => $message,
        "filename" => $filename,
        "lineno" => $lineno
        // "stacktrace" => implode (", ", error_get_last ())
    ) ;
    
    // create_db ("error",$a) ;
    db_insert ("error",$a, false) ;
}

if (isset ($ENABLE_ERROR_LOG_DB))
    set_error_handler('exceptions_error_handler');
  
// create_db_words ();
// create_db_articles ();
?>