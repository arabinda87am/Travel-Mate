<?php require "config/dbconnect.php";?>
<?php require "config/config.php";?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Travel made easy</title>
        <link type="text/css" rel="stylesheet" href="css/mainpage.css">

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

        $autocomplete= filter_input(INPUT_GET, 'autocomplete');
        $autocomplete1= filter_input(INPUT_GET, 'autocomplete1');
        $transit= filter_input(INPUT_GET, 'transit');
        $month= filter_input(INPUT_GET, 'month');
        $nadults = filter_input(INPUT_GET, 'nadults');
        $days = filter_input(INPUT_GET, 'days');

        $autocomplete= str_replace(", India", "", $autocomplete);
        $autocomplete1= str_replace(", India", "", $autocomplete1);

        $autocomplete= str_replace(",", "", $autocomplete);
        $autocomplete1= str_replace(",", "", $autocomplete1);

        $autocomplete= str_replace(" ", "+", $autocomplete);
        $autocomplete1= str_replace(" ", "+", $autocomplete1);

        $_SESSION['destination']=$autocomplete1;
        $_SESSION['transit']=$transit;

        $ch = curl_init();
        if($transit=='driving')
        {
            $url = 'https://maps.googleapis.com/maps/api/distancematrix/json?origins='.$autocomplete.'&destinations='.$autocomplete1.'&key='.$apikey2;//Put your api key here
        }
        else
        {
            $url = 'https://maps.googleapis.com/maps/api/distancematrix/json?origins='.$autocomplete.'&destinations='.$autocomplete1.'&mode=transit&transit_mode=train&key='.$apikey2;//Put your api key here
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $json = curl_exec($ch);

        curl_close($ch);

        $json = json_decode($json, true);

        $distance = $json['rows'][0]['elements'][0]['distance']['value'];
        $destination = $json['destination_addresses'][0];
        $origin = $json['origin_addresses'][0];
        $distance = $distance/1000;
        $distance = round($distance);
        $month = explode("-", $month);
        $area = explode(",", $json['destination_addresses'][0]);

        if($transit=='train')
        {
            $query = 'select * from train_fare where distance_max>='.$distance.' and distance_min<='.$distance;
            $sql_list = mysqli_query($con, $query);
            $row = $sql_list->fetch_assoc();

            echo '<div class="result_train">';
            echo '<div class="train"><h7>Via : Train</h7></div>';
            echo '<div class="train"><a>From :'.$origin.'</a></div>';
            echo '<div class="train"><a>To :'.$destination.'</a></div>';
            echo '<div class="train"><a>Distance :'.$distance.' km</a></div>';
            echo '<div class="train"><a>Total Tansit Cost (incl. return) Will be (for sleeper class): '.$row["sf_sl"]*2 .' (approx)</a></div>';
            $total = $row["sf_sl"]*2;

            $query = 'select * from habitat_rate where month="'.$month[1].'" and area like "'.$area[0].'"';
            $sql_list = mysqli_query($con, $query);
            if($sql_list->num_rows > 0)
            {
                $row = $sql_list->fetch_assoc();
                echo '<div class="train"><a>Accomodation Cost will be (for '.$days.' days) : '.$row["rate"]*$days.' (approx)</a></div>';
                $total = $total + $row["rate"]*$days;
            }
            else
            {
                 echo '<div class="train"><a>Accomodation Cost will be (for '.$days.' days) : '.$days*301 .' (approx)</a></div>';
                 $total = $total + $days*301;
            }
            echo '<div class="train"><a>Total Cost will be : '.$total.' (approx)</a></div>';
            echo '</div>';
        }
        else
        {
            $query = 'select * from car_fare';
            $sql_list = mysqli_query($con, $query);

            $query1 = 'select * from habitat_rate where month="'.$month[1].'" and area like "'.$area[0].'"';
            $sql_list1 = mysqli_query($con, $query1);

            if($sql_list1->num_rows > 0)
            {
                $row1 = $sql_list1->fetch_assoc();
                $rate = $row1["rate"];
            }
            else
            {
                $rate = 301;
            }
            if($sql_list->num_rows > 0)
            {
                while($row = $sql_list->fetch_assoc())
                {
                    $total=0;
                    echo '<div class="result_'.$row["car_type"].'">';
                    echo '<div class="'.$row["car_type"].'"><h7>Via '.$row["car_type"].'</h7></div>';
                    echo '<div class="'.$row["car_type"].'"><a>From :'.$origin.'</a></div>';
                    echo '<div class="'.$row["car_type"].'"><a>To :'.$destination.'</a></div>';
                    echo '<div class="'.$row["car_type"].'"><a>Total Tansit Cost (incl. return) Will be (for non AC) : '.$row["non_ac_rate"]*$distance*2 .' (approx)</a></div>';
                    echo '<div class="'.$row["car_type"].'"><a>Total Tansit Cost (incl. return) Will be (for AC) : '.$row["ac_rate"]*$distance*2 .' (approx)</a></div>';
                    $total = $row["non_ac_rate"]*2*$distance;

                    echo '<div class="'.$row["car_type"].'"><a>Accomodation Cost will be (for '.$days.' days) : '.$rate*$days.' (approx)</a></div>';
                    $total = $total + $rate*$days;

                    echo '<div class='.$row["car_type"].'><a>Total Cost will be : '.$total.' (approx)</a></div>';
                    echo '</div>';
                }
            }
        }
        //Nearest Tourist Places
        $url2= 'https://maps.googleapis.com/maps/api/place/textsearch/json?query=point_of_interest+in+'.$autocomplete1.'&key='.$apikey3;//Put your api key here
        $ch2 = curl_init();

        curl_setopt($ch2, CURLOPT_URL, $url2);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch2, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, 0);

        $json2 = curl_exec($ch2);

        curl_close($ch2);

        $json2 = json_decode($json2, true);
        $results2 = $json2["results"];
        echo '<div >';
        echo '<form id="point_of_interest" action=mainrecalculate.php method=get>';
        echo '<div class=point_of_interest_heading>';
        echo '<a>Nearesr Places You may visit near Your Destination</a>';
        echo'</div>';
        $i=0;
        foreach ($results2 as $results2)
        {
            echo '<div class="recalculate-box">';
            echo '<lebel>'.$results2['name'].'</lebel><br><br>';
            echo '<a>'.$results2['formatted_address'].'</a>';
            echo '<input type=checkbox name="p'.$i.'" value="'.$results2['name'].'" >';
            echo'</div>';
            $i=$i+1;
        }
        $_SESSION['i']=$i;
        echo '<input class=btn-recalculate name="submit" type="submit" value="ReCalculate">';
        echo '</form>';
        echo '</div>';
        //Nearest Hotel or restaurant Search
        $url1= 'https://maps.googleapis.com/maps/api/place/textsearch/json?query=restaurants|hotels+in+'.$autocomplete1.'&radius=1000&key='.$apikey4;//Put your api key here

        $ch1 = curl_init();

        curl_setopt($ch1, CURLOPT_URL, $url1);
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch1, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, 0);

        $json1 = curl_exec($ch1);

        curl_close($ch1);

        $json1 = json_decode($json1, true);

        //$name = $json1['results'][0]['name'];
        $results = $json1['results'];
        echo '<div class=hotellist><ul>';
        foreach($results as $results)
        {
            echo '<li>';
            echo '<a>Name : '.$results['name'].'</a><br>';
            echo '<a>Rating : '.$results['rating'].'</a><br>';
            echo '<a>Type : '.$results['types'][0].'</a><br>';
            echo '<a>Address : '.$results['formatted_address'].'</a><br>';
            echo '<a href="#">Book Now</a>';
            echo '</li>';
        }
        echo '</ul></div>';
        $con->close();
        ?>
    </body>
</html>
