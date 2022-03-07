<!DOCTYPE HTML>
<html lang="pl"> 
<head>
    <link rel="stylesheet" href="style1.css" type="text/css" /> 
    <meta charset="utf-8" />
    <meta http-equiv="X_UA_Compatible" content = "IE=edge, chrome=1" />
    <title> CURRENT TASKS: </title>

<style>
    .modal {
               /* Hidden by default */
            position: fixed; /* Stay in place */
            display: none;
            line-height: 1.3;
            height: auto;
            width: auto;
            margin: 0;
            
            top: 50%;
            left: 50%;
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            z-index: 1;  
              
            }

            .modal-content {
                line-height: 1.4;
                height: 100%;
                width: 100%;
                margin: 0;
                position: relative;
                background-color: darkgray;
                border: 5px solid powderblue;
                border-radius: 25px;
                
            }

            .task{
                font-size: 20;
                
                margin: auto;
                color: black;
                font-family: Arial, Helvetica, sans-serif;
                background-color: lightgray;
                font-style: italic;
                padding: 5px 5px;
                
                border-radius: 20px;
                border-width: 5px;
                max-width: 250px;
                text-align: left;
                font-weight: 600;
            }

            .task_headline{
                text-align: center;
                color: black;
                font-weight: 900;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 35px;
            }

            .result{
                font-size: 20px;
                font-weight: 600;
                font-family: Arial, Helvetica, sans-serif;
            }

            .button_modal_submit{
                font-size: 33px;
                font-weight: bold;
   
                color: black;
                background-color: lightgray;
                border-radius: 15px;
                border-color: darkgray;
            }
            .button_modal_submit:hover {
                background-color: black;
                border-color: darkgray;
                color: lightgray;
                font-weight: bold;

            }
            
</style>





</head>
 <!--STRONA STARTOWA-->
<body>

</br>
</br>
   <div class="menu"> 
            <form class="form1" method="get" action="display_completed_tasks.php">
                <button type="submit" class="button_menu">Completed tasks</button>
            </form>
            <form class="form1" method="get" action="display_current_tasks.php">
                <button type="submit" class="button_pressed">Active tasks</button>
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
        $connection = new mysqli($host, $db_user, $db_password, $db_name);
        if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
        }
        
        $sql = "SELECT *  FROM tasks WHERE user_ID='$user_ID' AND state='not_completed' ORDER BY due_date ASC, due_time ASC";
        $result = $connection->query($sql);  
        echo "<table border='1' class='table_finished'>
        <thead>
            <tr>
                <th align='left' width='770px'>Task</th>
                <th width='150px'>Due date</th>
                <th width='150px'>Due time</th>
                <th width='200px'>Created</th>
                <th width='200px'>End task</th>
            </tr>
        </thead>
        <tbody>";
        $i=0;
        while($row = $result->fetch_assoc()) 
        {
            echo "<tr>";
            echo "<td align='left'>".$row['task'] ."</td>";
            echo "<td >" . date("Y-m-d", strtotime($row['due_date'])) . "</td>";
            echo "<td >" . substr($row['due_time'],0,-3) . "</td>";
            echo "<td >" .date("Y-m-d", strtotime($row['cur_time']))."</br>" .date("G:i ", strtotime($row['cur_time'])). "</td>";  
            echo "<td>".'
          
                  <button id="completion_state_button'.$i.'" class="button_finish">Finish task</button>


                  <div id="myModal'.$i.'" class="modal">
                    <div class="modal-content" >
                          <form action="update_tasks.php" method="post"> 
                          <input type="hidden" name="task_id" value="'.$row["task_ID"].'" /> 
                          <div class="task_headline"> Task: </div>
                          <div class="task">-'.$row['task'].' </div>
                          <div class="result">SUCCESS  <input type="checkbox" name="result1" value="success" /> </div>
                          <div class="result">FAILURE &nbsp;  <input type="checkbox" name="result2" value="failure"/>  </div>
                          <div class = "comment">  <input type="text" name="comment" placeholder="optional comment"/> </div> 
                          <div class = "comment"> <button type="submit" class="button_modal_submit"> SUBMIT </button> </div> 
                          <div class = "comment"> <button   class="button_modal_submit" >X</button> </div> 
                          </form>
                          
                         
                    </div>
                  </div>

                  <script>

                      var modal'.$i.' = document.getElementById("myModal'.$i.'");
                      var btn'.$i.' = document.getElementById("completion_state_button'.$i.'");
                      var X_button'.$i.' = document.getElementById("close'.$i.'");

                      btn'.$i.'.onclick = function() {
                        modal'.$i.'.style.display = "block";
                      }

                      X_button'.$i.'.onclick = function() {
                        modal'.$i.'.style.display = "none";
                      }

                  </script>
                  ' ;
            

            echo "</tr>";
            $i++;
        }
    ?>
       </tbody>
    </table>
    </div>




</body>
</html>