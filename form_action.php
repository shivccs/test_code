<?php
error_reporting(E_ALL);
//ini_set('display_errors', '1');
include("database.php");
$db= $conn;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  

  $unique_id = date('ymd') . time();

  $emp_id       = 'EMP'.$unique_id;
  $emp_name     = $_POST['emp_name'];
  $comp_name    = $_POST['comp_name'];
  $designation  = $_POST['designation'];
  $salary       = $_POST['salary'];
  $doj          = $_POST['doj'];
  $doe          = $_POST['doe'];
  $start_date   =     new DateTime($doj);
  $end_date   =     new DateTime($doe);
  $diffrence_data =     $start_date->diff($end_date);

  $exp_years    =     $diffrence_data->y;

  $exp_month    =     $diffrence_data->m;

  $exp_days     =     $diffrence_data->d;

  $data_added_on = date('Y-m-d');

  $path = '/var/www/html/test_code/uploads/emp/salary_slips';
        
  $ss_path='uploads/emp/salary_slips/';

  $allowed_extensions = ['jpeg','jpg','png', 'pdf']; // These will be the only file extensions allowed 

  $maxsize = 2 * 1024 * 1024;


  if(isset($_FILES["ss"])){

  $fileName = $_FILES['ss']['name'];
  $fileSize = $_FILES['ss']['size'];
  $fileTmpName  = $_FILES['ss']['tmp_name'];
  $fileType = $_FILES['ss']['type'];
  $fileExtension = strtolower(end(explode('.',$fileName)));
  $new_file_name = $unique_id.'.'.$fileExtension;
  //var_dump($fileExtension);

      if (!in_array($fileExtension,$allowed_extensions)) {
         echo "This file extension is not allowed.";
      }else if ($fileSize > $maxsize) {
         echo "File size is larger than the allowed limit.";
      }else{
        move_uploaded_file($_FILES["ss"]["tmp_name"],$ss_path.'/'.$new_file_name);

        $Inqueryemp = "INSERT INTO employees VALUES ('$emp_id', '$emp_name', '$comp_name', '$designation', '$salary', '$doj', '$doe', '$exp_years', '$exp_month', '$exp_days', '$data_added_on')";

        $Inquerydoc = "INSERT INTO documents (employee_id, filepath, filename, added_on) VALUES ('$emp_id', '$ss_path', '$new_file_name', '$data_added_on')";

        if (($conn->query($Inqueryemp) === TRUE)&&($conn->query($Inquerydoc) === TRUE)) {
            $resp = "Data successfully added";
        } else {
            $resp = "Error: " . $sql . "<br>" . $conn->error;
        }//end of if else
      }//end of if else
      
  }else{
    $resp = "Please select salary slip!";
  }


  $conn->close();

  header("Location: index.php");
  exit;
}//end of if form submission

?>