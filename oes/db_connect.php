<?php
$conn = mysqli_connect("localhost","root","","examination");
if(!$conn){
    echo 'Connection error:- '.mysqli_connect_error();
}

?>