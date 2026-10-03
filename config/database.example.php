<?php

$host = 'localhost';
$db = 'dbTicketVentas';
$user = 'postgres';
$password = 'YOUR_PASSWORD';
$port = '5432';

$conn = pg_connect("host=$host dbname=$db user=$user password=$password port=$port");
