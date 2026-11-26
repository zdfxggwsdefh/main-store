<?php
/*
To test connection, please use the following request:
http://localhost/main-store-1/settings/database.php
*/
$local_host = "localhost";
$local_port = "5432";
$local_dbname = "main_store_";
$local_user = "postgres";
$local_password = "postgres";
$local_conn = pg_connect("
host=$local_host
port=$local_port
dbname=$local_dbname
user=$local_user
password=$local_password"
);
//supa connection
$supa_host = "aws-1-us-west-2.pooler.supabase.com";
$supa_port = "6543";
$supa_dbname = "postgres";
$supa_user = "postgres.qsegvwgqewmldfqfvcja";
$supa_password = "main-store@@";
$supa_conn = pg_connect("
host=$supa_host
port=$supa_port
dbname=$supa_dbname
user=$supa_user
password=$supa_password"
);
if (!$local_conn) {
die("Error local connection");
} else {
//echo "Success database local connection !!!";
//echo "Success database connection !!!<br>";
}
if (!$supa_conn) {
die("Error supa connection");
} else {
//echo "Success database supa connection !!!";
}?>