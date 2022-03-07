

<!DOCTYPE HTML>
<html lang="pl"> 
<head>
    <link rel="stylesheet" href="style1.css" type="text/css" /> 
    <meta charset="utf-8" />
    <meta http-equiv="X_UA_Compatible" content = "IE=edge, chrome=1" />
    <title> COMPLETED TASKS: </title>
</head>
 <!--STRONA STARTOWA-->
<body>

</br>
</br>

    <div class="menu"> 
            <form class="form1" method="get" action="display_completed_tasks.php">
                <button type="submit" class="button_pressed">Completed tasks</button>
            </form>
            <form class="form1" method="get" action="display_current_tasks.php">
                <button type="submit" class="button_menu">Active tasks</button>
            </form>
            <form class="form1" method="get" action="make_task.php">
                <button type="submit" class="button_menu">Add new task</button>
            </form>
            <form class="form1" method="get" action="account.php">
                <button type="submit" class="button_menu">Your account</button>
            </form>
            <form class="form1" method="get" action="logout.php">
                <button type="submit" class="button_menu">Log out</button>
            </form>
   </div>
   <div class="center_table">
  
   <?php   
        session_start();
        if((!isset($_SESSION['logged'])) && ($_SESSION['logged'] == false)){
            header('Location: index.php');
            exit();
        }
        
        $user_ID = $_SESSION['user_ID'];
        require_once "database.php";
        // Create connection
        $connection = new mysqli($host, $db_user, $db_password, $db_name);
        // Check connection
        if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
        }
        $sql = "SELECT *  FROM tasks WHERE user_ID='$user_ID' AND state='completed' ORDER BY completion_time DESC";
        $result = $connection->query($sql);  

        echo "<table border='1' class='table_finished'>
        <thead>
            <tr>
                <th align='left' width='500px'>Task</th>
                <th width='90px'>Result</th>
                <th width='180px'>Comments</th>
                <th width='150px'>Due date</th>
                <th width='150px'>Due time</th>
                <th width='200px'>Created</th>
                <th width='200px'>Completed</th>
            </tr>
        </thead>
        <tbody>";
        
        while($row = $result->fetch_assoc()) 
        {
            echo "<tr>";
            echo "<td align='left'>".$row['task'] ."</td>";
            echo "<td >".$row['result'] ."</td>";
            echo "<td >".$row['comment'] ."</td>";
            echo "<td >" . date("Y-m-d", strtotime($row['due_date'])) . "</td>";
            echo "<td >" . substr($row['due_time'],0,-3) . "</td>";
            echo "<td >" .date("Y-m-d", strtotime($row['cur_time']))."</br>" .date("G:i ", strtotime($row['cur_time'])). "</td>";  
            echo "<td >" .date("Y-m-d", strtotime($row['completion_time']))."</br>" .date("G:i ", strtotime($row['completion_time'])). "</td>";  
            echo "</tr>"; 
        }
    ?>
       </tbody>
    </table>
    </div>
</body>
</html>