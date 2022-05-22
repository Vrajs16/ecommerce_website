<?php
//load from heroku
$db_url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$dbhost   = $db_url["host"];
$dbuser = $db_url["user"];
$dbpass = $db_url["pass"];
$dbdatabase       = substr($db_url["path"], 1);
