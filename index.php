<?php include("fetch_data.php"); 

  $base_url = 'http://localhost/test_code/'
?>
<html>
<head>
<title>Employee Form</title>
 <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
  <div class="container">
    <div class="row">
      <h4 class="text-info">Employee Records Entry</h4>
    </div>
    <hr>
    <form id="emp_form" name="emp_form" method="post" action="form_action.php" enctype="multipart/form-data">
    <div class="row">
      <div class="col-md-4">
        <input type="text" class="form-control" name="emp_name" required>
        <span>Employee Name</span>
      </div>
      <div class="col-md-4">
        <input class="form-control" type="text" name="comp_name" required>
        <span>Company Name</span>
      </div>
      <div class="col-md-4">
        <input class="form-control" type="text" name="designation" required>
        <span>Designation</span>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <input type="number" min="1" class="form-control" name="salary" required>
        <span>Salary</span>
      </div>
      <div class="col-md-4">
        <input class="form-control" onchange="exp_calc()" id="doj" type="date" name="doj" required>
        <span>DOJ</span>
      </div>
      <div class="col-md-4">
        <input class="form-control" onchange="exp_calc()" id="doe" type="date" name="doe" required>
        <span>DOE</span>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <input class="form-control" type="file" name="ss" required>
        <span>Salary <small>Note: Please attach pdf/jpg/jpeg/png of max 2MB</small></span>
      </div>
      <div class="col-md-8">
        <label id="date_error" class="text-danger"></label>
        <h5 class="text-info" id="experience_info"></h5>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <button type="submit" class="btn btn-info">Add Row</button>
        <label id="form_status">
          <?php 
            if (isset($resp)) {

              echo $resp;

            }

          ?>
        </label>
      </div>
    </div>
    </form>


    <div class="row">
      <div class="col-md-12">
        <table class="table table-striped table-bordered">
          <thead>
              <tr>
                <th>Sno</th>
                <th>Employee Name</th>
                <th>Company</th>
                <th>Designation</th>
                <th>Salary</th>
                <th>Date Of Joining</th>
                <th>Date of Exit</th>
                <th>Total Experience</th>
                <th>Salary Slip</th>
              </tr>
            </thead>
            <tbody>
              <?php 
              $sno = 1;  
              $ttl_years = 0;
              $ttl_months = 0;
              $ttl_days = 0;

              //var_dump($emp_data);

              if ($emp_data) { 
                        foreach ($emp_data as $key => $empvalue) { ?>
              <tr>
                <td><?php echo $sno; ?></td>
                <td><?php echo $empvalue['emp_name']; ?></td>
                <td><?php echo $empvalue['company_name']; ?></td>
                <td><?php echo $empvalue['designation']; ?></td>
                <td><?php echo $empvalue['salary']; ?></td>
                <td><?php echo $empvalue['start_date']; ?></td>
                <td><?php echo $empvalue['end_date']; ?></td>
                <td><?php echo $empvalue['exp_in_years'].' Years, '.$empvalue['exp_in_months'].' Months, '.$empvalue['exp_in_days'].' Days '; ?></td>
                <td><a target="_blank" href="<?php echo $base_url.$empvalue['filepath'].'/'.$empvalue['filename']; ?>">Download Salary Slip</a></td>
              </tr>

              <?php $sno++;
                  $ttl_years+=$empvalue['exp_in_years'];
                  $ttl_months+=$empvalue['exp_in_months'];
                  $ttl_days+=$empvalue['exp_in_days'];

                } //end of foreach 
              } //end of if ?>

            </tbody>
            <tfoot>
              <tr>
                <td colspan="4">Total Experience</td>
                <td colspan="5">
                  <?php if ($emp_data) { 

                    



                    echo $ttl_years.' Years, '.$ttl_months.' Months, '.$ttl_days.' Days';


                  }?>

                </td>
              </tr>
            </tfoot>
        </table>
      </div>
    </div>
  </div>

  
  <script type="text/javascript">
    
    function exp_calc(){
      var doj = $('#doj').val();
      var doe = $('#doe').val();

      var doj_date = new Date(doj);
      var doe_date = new Date(doe);
      var current_date = new Date();


      if (Date.parse(doj)>=Date.parse(doe)) {
        var alert_info = "DOJ must be less than DOE";
        $('#date_error').text(alert_info);
        $('#experience_info').text(''); 
      }else if (Date.parse(doj)>Date.parse(current_date)) {
        var alert_info = "DOJ must be less than Current Date";
        $('#date_error').text(alert_info); 
      }else if (Date.parse(doe)>Date.parse(current_date)) {
        var alert_info = "DOE must be less than Current Date";
        $('#date_error').text(alert_info);
        $('#experience_info').text('');
      }else{
        $('#date_error').text('');
        var diff = Math.floor(doe_date.getTime() - doj_date.getTime());
        var sec_day = 1000 * 60 * 60 * 24; //day in seconds
        var sec_month = sec_day*31;
        var sec_years = sec_day*365;

        var ttl_years = Math.floor(diff/sec_years);
        diff -= ttl_years*sec_years;

        var ttl_months = Math.floor(diff/sec_month);
        diff -= ttl_months*sec_month;

        var ttl_days = Math.floor(diff/sec_day);


        if ((!isNaN(ttl_years))&&(!isNaN(ttl_months))&&(!isNaN(ttl_days))) {
            var experience_info = ttl_years+' Years, '+ttl_months+' Months, '+ttl_days+' Days';
            $('#experience_info').text(experience_info);
        }


         
      }//end of if else
        
    }//end of function 

  </script>


</body>
</html>