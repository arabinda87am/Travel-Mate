<?php
 
  $conn = new mysqli("localhost", "root", "root", "travel");
 
 if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
 }
 else {
     echo 'Successfull';
}
 ?>