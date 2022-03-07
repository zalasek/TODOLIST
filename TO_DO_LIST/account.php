<?php
        session_set_cookie_params(0);
        session_start();
        if(!isset($_SESSION['logged'])){
            header('Location: index.php');
            exit();
        }
        
        $user_ID = $_SESSION['user_ID'];
        require_once "database.php";
        
        $connection = new mysqli($host, $db_user, $db_password, $db_name);
        
        if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
        }
        
        $sql1 = "SELECT * FROM `tasks` WHERE user_ID='$user_ID' AND state='not_completed' ORDER BY `due_date` limit 1";
        $result_of_connection1 = $connection->query($sql1);  
        $num_of_records1 = $result_of_connection1->num_rows;

        if($num_of_records1 > 0){
            $row = $result_of_connection1->fetch_assoc();
            $task=$row['task'];
            $due_date1=$row['due_date'];
            $due_time1=$row['due_time'];  
            $cur_time1=$row['cur_time']; 

            $y=date("Y", strtotime($due_date1));
            $mon=date("m", strtotime($due_date1));
            $d=date("d", strtotime($due_date1));
            $h=date("H", strtotime($due_time1));
            $min=date("i", strtotime($due_time1));
            $s=date("s", strtotime($due_time1));

            $due_time1=substr($due_time1,0,-3);
            
            $deadline=$y.",".$mon."-1,".$d.",".$h.",".$min.",".$s;

             
        }
        else{
            
            $task="NO ACTIVE TASK";
            $due_date1="NO ACTIVE TASK";
            $due_time1="NO ACTIVE TASK";  
            $cur_time1="NO ACTIVE TASK"; 
            $deadline =true;
            
        }     
        $connection->close();

?>

<!DOCTYPE HTML>

<html lang="pl">
<head>
<link rel="stylesheet" href="style1.css" type="text/css" /> 
    <meta charset="utf-8" />
    <meta http-equiv="X_UA_Compatible" content ="IE=edge,chrome=1" />
    <title> TASK LIST </title>  

    
<script>
        var countDownDate = new Date(<?php echo $deadline; ?>).getTime();
        var myfunc = setInterval(function() {

            if(<?php echo $deadline;?> == true){
                document.getElementById("days").innerHTML = ""
                document.getElementById("hours").innerHTML = ""
                document.getElementById("mins").innerHTML = ""
                document.getElementById("secs").innerHTML = ""
                document.getElementById("d").innerHTML = "NO ACTIVE TASK"
                document.getElementById("h").innerHTML = ""
                document.getElementById("m").innerHTML = ""
                document.getElementById("s").innerHTML = ""
            }
            else{
                var now = new Date().getTime();
                var timeleft = countDownDate - now;
                var days = Math.floor(timeleft / (1000 * 60 * 60 * 24));
                var hours = Math.floor((timeleft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((timeleft % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((timeleft % (1000 * 60)) / 1000);
                    
        
                if (timeleft > 0) {
                    document.getElementById("days").innerHTML = days
                    document.getElementById("hours").innerHTML = hours
                    document.getElementById("mins").innerHTML = minutes
                    document.getElementById("secs").innerHTML = seconds
                    document.getElementById("d").innerHTML = "d "
                    document.getElementById("h").innerHTML = "h "
                    document.getElementById("m").innerHTML = "m "
                    document.getElementById("s").innerHTML = "s"
                }   
                else if (timeleft < 0) {
                    clearInterval(myfunc);
                    document.getElementById("days").innerHTML = ""
                    document.getElementById("hours").innerHTML = ""
                    document.getElementById("mins").innerHTML = ""
                    document.getElementById("secs").innerHTML = ""
                    document.getElementById("d").innerHTML = "&nbsp;&nbsp;&nbsp;&nbsp;TIME IS UP"
                    document.getElementById("h").innerHTML = ""
                    document.getElementById("m").innerHTML = ""
                    document.getElementById("s").innerHTML = ""    
                }
                }

        }, 1000);
    </script>


</head>

<body>


<div class="menu"> 
            <form class="form1" method="get" action="display_completed_tasks.php">
                <button type="submit" class="button_menu">Completed tasks </button>
            </form>
            <form class="form1" method="get" action="display_current_tasks.php">
                <button type="submit" class="button_menu">Active tasks </button>
            </form>
            <form class="form1" method="get" action="make_task.php">
                <button type="submit" class="button_menu">Add new task </button>
            </form>
            <form class="form1" method="get" action="account.php">
                <button type="submit" class="button_pressed">Your account </button>
            </form>
            <form class="form1" method="get" action="logout.php">
                <button type="submit" class="button_menu">Log out </button>
            </form>
   </div>


   

  <div id="center_summary">  
    <div id="container_summary">
    </br>
        <div id="TASK">
            <p id="closest_task"> Closest task: </p> 
            <p id="display_task"> <?php echo $task; ?> </p>
        </div>
    </br>
        <div id="COUNTDOWN">
            <p id="closest_task"> Time left: </p> 
            <div id="timer"> 
                
                <div id="days" class="countdown"></div><div class='countdown_letters' id="d"></div>
                <div id="hours" class="countdown"></div><div class='countdown_letters' id="h"></div>
                <div id="mins" class="countdown"></div><div class='countdown_letters' id="m"></div>
                <div id="secs" class="countdown"></div><div class='countdown_letters' id="s"></div>
                <div id="end" class="countdown">   </div>
            </div>
        </div>

        </br>
        <div id="DUE_DATE">
            <p id="closest_task"> Due date: </p> 
            <p id="display_task"> <?php echo $due_date1; ?> </p>
        </div>

        </br>
        <div id="DUE_TIME">
            <p id="closest_task"> Due time: </p> 
            <p id="display_task"> <?php echo $due_time1; ?> </p>
        </div>

        </br>
        <div id="CREATED">
            <p id="closest_task"> Created: </p> 
            <p id="display_task"> <?php echo date("Y-m-d", strtotime($cur_time1))."</br>" .date("G:i ", strtotime($cur_time1)); ?> </p>
        </div>
        </br>


        
            
    </div>
</div>   
</body>
</html>


