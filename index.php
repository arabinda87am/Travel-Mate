<!DOCTYPE html>
<html>
  <head>
    <title>Travel Friend & Smart</title>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <link type="text/css" rel="stylesheet" href="css/nav.css">
  </head>
  <body>
    <script type="text/javascript"> 
        function stopRKey(evt) { 
        var evt = (evt) ? evt : ((event) ? event : null); 
        var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
        if ((evt.keyCode === 13) && (node.type==="text"))  {return false;} 
        }
    document.onkeypress = stopRKey; 
    </script>  
    <script type="text/javascript" src="javascript/autocomplete.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAKZ6oi-h9vBRZJW1FBCk_pZYBYPH55n-M&libraries=places&callback=initAutocomplete" async defer></script>
    <div id="header">
        <a href="index.php"><img id="logo" src="images/logo.png"></a>
        <a id="heading1">Travel Smart with Travel Friend.....</a>
        <a id="heading2">Travel According your Wallet.....</a>
    </div>
    <div id="nav">
        <ul class="right">
            <li><a href="signin.html">Sign In</a></li>
            <li><a href="registration.html">Sign Up</a></li>
        </ul>
        <ul class="left">
            <li><a href="index.php">Home</a></li>
            <li><a href="contact_us.php">Contact Us</a></li>
            <li><a href="acknowledgement.html">Acknowledgement</a></li>
        </ul>
    </div>
    <div class="searchbox">
    <form class="form-search" method="get" action="main.php">
        <label>Enter Origin Place</label>
        <input  id="autocomplete" name="autocomplete" placeholder="Enter your place" type="text" required><br>
        <label>Enter Destination Place</label>
        <input  id="autocomplete1" name="autocomplete1" placeholder="Enter your desired place" type="text" required><br>
        <label>Select journey Month</label>
        <input  type="month" name="month" id="month" required>
        <lebel>Select Number of People</lebel>
        <input  id="nadults" name="nadults" type="number" min="1" required>
        <lebel>Number of Days</lebel>
        <input  id="days" name="days" type="number" min="1" required>
        <input class="radio" type="radio" name="transit" value="driving" checked><lebel>Driving</lebel>
        <input class="radio" type="radio" name="transit" value="train"><lebel>Train</lebel><br>
        <input id="submit" type="submit" name="submit" class="btn-search" value="Search">
    </form>
    </div>
  </body>
</html>