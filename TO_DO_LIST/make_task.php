<!DOCTYPE HTML>
<html lang="pl">

<head>
    <meta charset="utf-8" />
    <title>ADD TASK</title>
    <link rel="stylesheet" href="style1.css" type="text/css" /> 
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
                <button type="submit" class="button_pressed">Add new task </button>
            </form>
            <form class="form1" method="get" action="account.php">
                <button type="submit" class="button_menu">Your account </button>
            </form>
            <form class="form1" method="get" action="logout.php">
                <button type="submit" class="button_menu">Log out </button>
            </form>
   </div>
    <div class="center">
        <div class="TODO"> What do You have to do? </div>
        <div class="form">
            <form action="task_form.php" method="post">
            <p1>TASK:</p1> </br>
                <input type="text" name="task" />
                <br />
            <p1>DUE DATE:</p1> </br>
                <input type="date" name="due_date" />
                <br />
            <p1>DUE TIME:</p1> </br>
                <input type="time" name="due_time" />
                <br />
                <button type="submit" class="button_menu"> ADD TASK </button> 
            </form>
        </div> 
        <?php
                session_set_cookie_params(0);
                session_start();
                if((!isset($_SESSION['logged'])) && ($_SESSION['logged'] == false)){
                    header('Location: index.php');
                    exit();
                }
                
               if(isset($_SESSION['t_error_task']))
                {
                    echo '<div class="add_task_errors">'.$_SESSION['t_error_task'].'</div>';
                    unset($_SESSION['t_error_task']);
                }
                if(isset($_SESSION['t_error_date']))
                {
                    echo '<div class="add_task_errors">'.$_SESSION['t_error_date'].'</div>';
                    unset($_SESSION['t_error_date']);
                }
                if(isset($_SESSION['t_error_time']))
                {
                    echo '<div class="add_task_errors">'.$_SESSION['t_error_time'].'</div>';
                    unset($_SESSION['t_error_time']);
                }    
                if(isset($_SESSION['t_error_date_past']))
                {
                    echo '<div class="add_task_errors">'.$_SESSION['t_error_date_past'].'</div>';
                    unset($_SESSION['t_error_date_past']);
                }
                if(isset($_SESSION['t_error_time_past']))
                {
                    echo '<div class="add_task_errors">'.$_SESSION['t_error_time_past'].'</div>';
                    unset($_SESSION['t_error_time_past']);
                } 
        ?>
    </div>
    
    
   


    
    
    
</body>
</html>