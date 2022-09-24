<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include("database.php");
$db= $conn;

$query = "select employees.*, documents.filepath, documents.filename from employees INNER JOIN documents ON employees.employee_id=documents.employee_id";
$emp_data_result = $db->query($query);
if($emp_data_result== true){ 

  //var_dump($emp_data);//exit;

 if ($emp_data_result->num_rows > 0) {
    $emp_data = $emp_data_result->fetch_all(MYSQLI_ASSOC);
 } else {
    $emp_data = false; 
 }
}else{
  $msg= mysqli_error($db);
}


?>