
<?php 
    session_set_cookie_params(0);
    session_start();
    require_once "database.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    $correct =  true;
    $task = $_POST['task'];
    $due_date = $_POST['due_date'];
    $due_time = $_POST['due_time'];
    
    $due_time_formated = $due_time.":00";
    $_SESSION['due_time_formated'] = $due_time_formated;
    $user_ID = $_SESSION['user_ID'];

    echo $_POST['due_date'];

    $_SESSION['task'] = $task;
    $_SESSION['due_date'] = $due_date;
    $_SESSION['due_time'] = $due_time;
    

    
    $check_date = date('Y-m-d');
    $check_time = date('H:i');

    $_SESSION['check_time'] = $check_time;

  //check date
    if((strlen($due_date)>1) && ($due_date < $check_date)) 
    { 
        $correct = false;
        $_SESSION['t_error_date_past'] = "Date is in the past!";  
    }
    //check time
   if((strlen($due_time_formated)>1) && ($due_time_formated < $check_time) && ($due_date == $check_date))
    {
        $correct = false;
        $_SESSION['t_error_time_past'] = "Time is in the past!";  
    }
    //check if task is not empty
    if(strlen($task)<1)
    {
        $correct = false;
        $_SESSION['t_error_task'] = "Enter task!";  
    }
    //check if date is not empty
    if(strlen($due_date)<1)
    {
        $correct = false;
        $_SESSION['t_error_date'] = "Enter date!";
    }
    //check if time is not empty
    if(strlen($due_time)<1)
    {
        $correct = false;
        $_SESSION['t_error_time'] = "Enter time!";
    }


    
     
    try{
        $connection =  new mysqli($host, $db_user, $db_password, $db_name);
        if($connection->connect_errno!=0)
        {
            throw new Exception(mysqli_connect_errno()); 
        }
        else
        {
            if($correct==true)
            {
                if($connection->query("INSERT INTO tasks VALUES (NULL, '$user_ID', '$task', '$due_date', '$due_time_formated', NULL,'not_completed', NULL, NULL, NULL)"))
                {
                    header('Location: display_current_tasks.php');
                    exit();
                }
                else{
                    throw new Exception($connection->error);
                } 
            } 
            else{
                header('Location: make_task.php');
                exit();

            }
        }
        $connection->close();
            
        
}
catch(Exception $error)
{
   echo '<span style="color:red"> Wrong SQL query </span>';
}
?>
 
