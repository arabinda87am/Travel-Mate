<?php
 $con=mysqli_connect("localhost","id1778671_arabinda","Abcd@1234","id1778671_travelmate");
// Check connection
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  }
  
  // clean user inputs to prevent sql injections
  $fname = filter_input(INPUT_POST, 'fname');
  
  $lname = filter_input(INPUT_POST, 'lname');
  
  $mobile = filter_input(INPUT_POST, 'mobile');
  
  $email = filter_input(INPUT_POST, 'email');
  
  $pass = filter_input(INPUT_POST, 'pass');
  
  $address = filter_input(INPUT_POST, 'address');
  
  $query = "Select mobile from userdetails";
  $result = mysqli_query($con, $query);
  
  $x=1;
  
  if($result->num_rows > 0)
  {
      while($row = $result->fetch_assoc())
      {
          if($row["mobile"]==$mobile)
          {
              echo '<script>alert("User Already Exist");window.location="registration.html";</script>';
              $x=0;
              die();
          }
      }
  }
// password encrypt using SHA256();
  if($x)
  {
    $pass = hash('sha384', $pass);
    // if there's no error, continue to signup
   
    $query2 = "INSERT INTO userdetails(mobile,fname,lname,email,address) VALUES('$mobile','$fname','$lname','$email','$address')";
    mysqli_query($con,$query2); 
    $query1 = "INSERT INTO user_credentials(mobile,password) VALUES('$mobile','$pass')";
    mysqli_query($con,$query1);
    echo "<script>alert('User Successfully Created');window.location='index.php';</script>";
    $con->close();
  }
?>