<?php
    session_set_cookie_params(0);
    session_start();
    require_once "database.php";

    $task_ID = $_POST['task_id'];
    $result1 = $_POST['result1'];
    $result2 = $_POST['result2'];
    $comment = $_POST['comment'];
    $user_ID = $_SESSION['user_ID'];
    $correct = true;
    
    if((isset($_POST['result1']) == true) && (isset($_POST['result2']) == true)){ 
        $correct = false;
    }

    if((isset($_POST['result1']) == false) && (isset($_POST['result2']) == false)) {
            $correct = false; 
        }
    
    if((isset($_POST['result1']) == true) && (isset($_POST['result2']) == false)){
        $result=$result1;
    }

    if((isset($_POST['result1']) == false) && (isset($_POST['result2']) == true)){
        $result=$result2;
    }
        
    

    if($correct==true){
        $connection = new mysqli($host, $db_user, $db_password, $db_name);
        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }
        $sql = "UPDATE tasks SET state='completed', completion_time=CURRENT_TIMESTAMP, result='$result' , comment='$comment' WHERE task_ID='$task_ID' AND user_ID='$user_ID' ";
        $result_of_connection = $connection->query($sql);
        header('Location: display_current_tasks.php');
        $connection->close(); 
    }

    else{
        header('Location: display_current_tasks.php');
        exit();
    }
?>


