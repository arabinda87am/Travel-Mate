<?php
  require "config/dbconnect.php";
  // clean user inputs to prevent sql injection
  $mobile = filter_input(INPUT_POST, 'mobile');
  $pass = filter_input(INPUT_POST, 'pass');

  $query = "Select * from user_credentials";
  $result = mysqli_query($con, $query);

  if($result->num_rows > 0)
  {
      while($row = $result->fetch_assoc())
      {
          if($row["mobile"]==$mobile)
          {
              $pass= hash('sha384', $pass);
              if($row["password"]==$pass){
                echo '<script>alert("Successfully Loged in");window.location="index.php";</script>';
              }
              else {
                echo '<script>alert("Password Incorrect");window.location="signin.html";</script>';
            }
          }
      }
  }
  $con->close();
?>
