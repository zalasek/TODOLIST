<?php      
        session_set_cookie_params(0);
    session_start(); //rozpoczynamy sesje 
    //gdy zmienna sesyjna logged jest ustawiona na true (czyli gdy pomyslnie sie zalogowalismy) przekierowuje automatycznie na strone account.php
    if((isset($_SESSION['logged'])) && ($_SESSION['logged'] == true)){
        header('Location: account.php');
        exit();
    }



?>

<!DOCTYPE HTML>
<html lang="pl"> 
<head>

    <link rel="stylesheet" href="style1.css" type="text/css" /> 

    <meta charset="utf-8" />
    <meta http-equiv="X_UA_Compatible" contetn = "IE=edge, chrome=1" />
    <title> TASK LIST </title>
</head>

<body>
    <div class="center">
        
        <div class="TODO">TO-DO LIST </div> 
        </br>
        <div class="form">
            <form action="login.php" method="post">
                Login:     <br/><input type="text" name="login" /> <br/>
                Password:  <br/><input type="password" name="password" /> <br/>
                <button type="submit" class="button_menu" > LOG IN </button>
            </form>
            <form method="get" action="register.php">
                <button type="submit" class="button_menu">REGISTER </br> NEW ACCOUNT</button>
            </form>
            <br/>
            <?php //zwracamy komunikat o błędnym loginie
                if(isset($_SESSION['error_log_login'])){
                    echo $_SESSION['error_log_login'];
                    unset($_SESSION['error_log_login']);       
                }
            ?>
            <br/>
            <?php //zwracamy komunikat o błędnym haśle
                if(isset($_SESSION['error_log_password'])){
                    echo $_SESSION['error_log_password'];
                    unset($_SESSION['error_log_password']);
                }
            ?>
            <br/>
            
        </div>
    </div>

    
    
            
    

</body>
</html>

