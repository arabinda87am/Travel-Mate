<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Travel made easy</title>
        <link type="text/css" rel="stylesheet" href="css/mainrecalculatepage.css">
        
    </head>
    <body>
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
            <li><a href="contactus.html">Contact Us</a></li>
            <li><a href="acknowledgement.html">Acknowledgement</a></li>
        </ul>
        </div>
        <?php
        session_start();
        $con=mysqli_connect("localhost","id1778671_arabinda","Abcd@1234","id1778671_travelmate");
        // Check connection
        if (mysqli_connect_errno())
        {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        $origin = $_SESSION['destination'];
        $transit = $_SESSION['transit'];
        $i = $_SESSION['i'];
        $total_distance=0;
        $total_cost=0;
        if($transit=='train')
        {
            ?>
            <div class="result_train">
            <table id="table_train">
            <thead>Via Train</thead>
            <tr>
                <th>From</th>
                <th>TO</th>
                <th>Distance</th>
                <th>Cost</th>
            </tr>
            <?php
            for($j=0;$j<$i;$j++)
            {
                $a = 'p'.$j;
                $destination = filter_input(INPUT_GET, $a); 
                if($destination!="")
                {
                    $destination= str_replace(", India", "", $destination);
                    $destination= str_replace(" ", "+", $destination);
                    $ch = curl_init();
                    $url = 'https://maps.googleapis.com/maps/api/distancematrix/json?origins='.$origin.'&destinations='.$destination.'&mode=transit&transit_mode=train&key=AIzaSyCOys5USOaULjRiFWX_IrXxXb-FW83iAcw';
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        
                    $json = curl_exec($ch); 
                    curl_close($ch);
        
                    $json = json_decode($json, true);
                    $distance = $json['rows'][0]['elements'][0]['distance']['value'];
                    $distance = $distance/1000;
                    $distance = round($distance);
                    $total_distance = $total_distance+$distance;
                    $query = 'select * from train_fare where distance_max>='.$distance.' and distance_min<='.$distance;
                    $sql_list = mysqli_query($con, $query);
                    $row = $sql_list->fetch_assoc();
                    $total = $row["sf_sl"];
                    $total_cost = $total_cost + $total;
                    $destination_clean = str_replace("+", " ", $destination);
                    $origin_clean = str_replace("+", " ", $origin);
                    echo '<tr>';
                    echo '<td>'.$origin_clean.'</td>';
                    echo '<td>'.$destination_clean.'</td>';
                    echo '<td>'.$distance.'</td>';
                    echo '<td>'.$total.'</td>';
                    echo '</tr>';
                    $origin = $destination;
                }
            }
            echo '</table>';
            echo '<a>Total distance Will Be : '.$total_distance.' km</a><br>';
            echo '<a>Total Cost Will Be : '.$total_cost.' rupee</a>';
            echo '</div>';
        }
        else 
        {
            $query = 'select * from car_fare';
            $sql_list = mysqli_query($con, $query);
            if($sql_list->num_rows > 0)
            {
            ?>
                <div class="result_driving">
                <table id="table_driving">
                <thead>Via Driving</thead>
                <tr>
                    <th>From</th>
                    <th>TO</th>
                    <th>Distance</th>
                <?php
                    while($row = $sql_list->fetch_assoc())
                    {
                        echo '<th>'.$row["car_type"].' Cost</th>';
                    }
                ?>
                </tr>
                <?php
                for($j=0;$j<$i;$j++)
                {   
                    $a = 'p'.$j;
                    $destination = filter_input(INPUT_GET, $a);
                    if($destination!="")
                    {
                        $destination= str_replace(", India", "", $destination);
                        $destination= str_replace(" ", "+", $destination);
                        $ch = curl_init();
                        $url = 'https://maps.googleapis.com/maps/api/distancematrix/json?origins='.$origin.'&destinations='.$destination.'&key=AIzaSyCIu3W2zzpLHuGYYnJUrSgcE62muAh6enw';
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        
                        $json = curl_exec($ch); 
        
                        curl_close($ch);
        
                        $json = json_decode($json, true);
                        $distance = $json['rows'][0]['elements'][0]['distance']['value'];
                        $distance = $distance/1000;
                        $distance = round($distance);
                        $total_distance = $total_distance+$distance;
                        $destination_clean = str_replace("+", " ", $destination);
                        $origin_clean = str_replace("+", " ", $origin);
                        echo '<tr>';
                        echo '<td>'.$origin_clean.'</td>';
                        echo '<td>'.$destination_clean.'</td>';
                        echo '<td>'.$distance.'</td>';
                        $sql_list1 = mysqli_query($con, $query);
                        while($row = $sql_list1->fetch_assoc())
                        {
                            if($distance>3)
                            {
                                echo '<td>'.$row["non_ac_rate"]*$distance.'</td>';
                                $total_cost = $total_cost + $row["non_ac_rate"]*$distance;
                            }
                            else
                            {
                                echo '<td>'.$row["non_ac_rate"]*3 .'</td>';
                                $total_cost = $total_cost + $row["non_ac_rate"]*3;
                            }
                        }
                        echo '</tr>';
                        $origin = $destination;
                    }
                }
                echo '</table>';
                echo '<a>Total distance Will Be : '.$total_distance.' km</a><br>';
                echo '<a>Total Cost Will Be : '.$total_cost.' rupee</a>';
                echo '</div>';
            }
        }
        ?>
    </body>
</html>