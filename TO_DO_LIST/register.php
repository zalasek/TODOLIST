<?php   
    session_set_cookie_params(0);
    session_start();
    if((isset($_POST['login_register'])) && (isset($_POST['email_register'])) && (isset($_POST['password1_register'])) && (isset($_POST['password2_register'])))
    {
        $correct =  true;
        $login = $_POST['login_register'];
        $email = $_POST['email_register'];
        $password1 = $_POST['password1_register'];
        $password2 = $_POST['password2_register'];


        if((strlen($login)<4) || (strlen($login) >20))
       {
            $correct = false;
            $_SESSION['error_register_login_length'] = '<span style="color:red"> Nickname must be between 4 and 20 chars! </br> </span>';
            
        }

        //sprawdzamy czy w loginie występują jedynie litery i cyfry
        if(ctype_alnum($login) == false)
        {
            $correct = false;
            $_SESSION['error_register_login_chars'] = '<span style="color:red"> Login must contain only alfanumeric chars! </br></span>';
        }

        //sprawdzamy poprawnosc adresu email
        $email_sanitized = filter_var($email, FILTER_SANITIZE_EMAIL); //usuwamy zbędne znaki
        if((filter_var($email_sanitized, FILTER_VALIDATE_EMAIL) == false) || ($email_sanitized!=$email)) //sprawdzamy czy wyczyszczony email zgadza się z wpisanym, oraz czy email jest poprawny
         {
            $correct = false;
            $_SESSION['error_register_email'] = '<span style="color:red"> Email must contain only alfanumeric chars and @! </br></span>';
        }

        //sprawdzamy poprawnosc hasła (ma miec miedzy 5 a 20 znakow)
        if((strlen($password1)<5) || (strlen($password1)>20)){
            $correct = false;
            $_SESSION['error_register_password1'] = '<span style="color:red"> Password must be between 5 and 20 chars! </br></span>';
        } 

        //sprawdzamy czy dwa razy zostało wpisane to samo hasło
        if($password1 != $password2)
        {
            $correct = false;
            $_SESSION['error_register_password2'] = '<span style="color:red"> Both passwords must be the same! </br></span>';
        }
            
        //sprawdzamy czy został zaznaczony checkbox
        if(isset($_POST['accept_register']) == false)
        {
            $correct = false;
            $_SESSION['error_register_accept'] = '<span style="color:red"> Terms must be accepted! </br></span>';
        } 

        //hashujemy hasło, aby nie było ono widoczne w bazie 
        $password_hash = password_hash($password1, PASSWORD_DEFAULT);

        //ŁĄCZYMY SIĘ Z BAZĄ
        require_once "database.php"; //wymagamy pliku "database.php" w kodzie, tylko raz(sprawdzi on czy plik nie został dodanyw cześniej)
        mysqli_report(MYSQLI_REPORT_STRICT);  //Enables or disables internal report functions (Report warnings from mysqli function calls)

        //tworzymy obiekt klasy mysqli 
        //(mysqli to nazwa metody będącej konstruktorem) 
        //(new = operator dynamicznego alokowania pamięci)
        //ustanawiamy połączenie z bazą danych przy pomocy obiektu klasy mysqli
        try{
            $connection =  new mysqli($host, $db_user, $db_password, $db_name);
            if($connection->connect_errno!=0)
            {
                throw new Exception(mysqli_connect_errno()); //rzucamy wyjątkiem, który zawiera kod błędu
            }
            else
            {
                //Czy email juz istnieje?
                $result_of_connection = $connection->query("SELECT user_ID FROM users WHERE email='$email'");
                if($result_of_connection == false) throw new Exception($connection->error);
                else{
                    //do obiektu result_of_connection skorzystamy z właściwości num_rows, która zwróci liczbę wyszukanych wierszy w bazie (czyli ilość emaili)
                    $num_of_emails = $result_of_connection->num_rows;
                    if($num_of_emails > 0) //gdy jest większe od 0 to znaczy że jest juz taki mail w bazie 
                    {
                        $correct = false;
                        $_SESSION['error_register_email_taken'] = '<span style="color:red"> This email is already taken </span>';
                        
                    }
                }
                //Czy login już istnieje?
                $result_of_connection = $connection->query("SELECT user_ID FROM users WHERE login='$login'");
                if($result_of_connection == false) throw new Exception($connection->error);
                else{
                    $num_of_logins = $result_of_connection->num_rows;
                    if($num_of_logins > 0){
                        $correct = false;
                        $_SESSION['error_register_login_taken'] = '<span style="color:red"> This email is already taken </span>';
                        
                    }
                }
                if($correct == true)
                {
                    //TESTY ZALICZONE
                    //echo "DZIAŁA"; exit();
                    if($connection->query("INSERT INTO users VALUES (NULL, '$login', '$email', '$password_hash')")){
                        $_SESSION['completed_registration']=true;
                        $_SESSION['login'] = $login;

                        unset($_SESSION['error_register_login_length']);
                        unset($_SESSION['error_register_login_chars']);
                        unset($_SESSION['error_register_login_taken']);
                        unset($_SESSION['error_register_email']);
                        unset($_SESSION['error_register_email_taken']);
                        unset($_SESSION['error_register_password1']);
                        unset($_SESSION['error_register_password2']);
                        unset($_SESSION['error_register_accept']);

                        header('Location: final_registration.php');
                        exit();
                    }
                    else{
                        throw new Exception($connection->error);
                    }  
                }
                $connection->close();
            }
    }
    catch(Exception $error)
    {
       echo '<span style="color:red"> Wrong SQL query </span>';
       
   }


}
?>

<!DOCTYPE HTML>
<html lang="pl"> 
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X_UA_Compatible" contetn = "IE=edge, chrome=1" />
    <title> TASK LIST - register </title>
    <link rel="stylesheet" href="style1.css" type="text/css" /> 
    

</head>
 <!--Formularz rejestracji nowego użytkownika-->
 <!--TODO: dodawania użytkownika do DB-->
<body>
   

   <div class="center">
       
   <div class="TODO"> </br>REGISTER NEW ACCOUNT </div>
   <div class="form">
        <form  method="post">
        <p1>Login:</p1> </br>
             <input type="text"  name = "login_register" /> 
            
       </br><p1>E-mail:</p1> </br>
             <input type="text"  name = "email_register" /> 
           
        </br><p1>Password:</p1> </br>
             <input type="password"  name = "password1_register" /> 
            
        </br><p1>Repeat password:</p1> </br>
            <input type="password"  name = "password2_register" /> 
            
        </br><p1>Accept terms:&nbsp;</p1> 
            <input type="checkbox" name = "accept_register"  /> 
            


        </br><br /> <button type="submit" class="button_menu"> REGISTER </button> 
        </form>
        <?php
        if(isset($_SESSION['error_register_login_length']))
        {
            echo $_SESSION['error_register_login_length'];
            unset($_SESSION['error_register_login_length']);
        }
        if(isset($_SESSION['error_register_login_chars']))
        {
            echo $_SESSION['error_register_login_chars'];
            unset($_SESSION['error_register_login_chars']);
        }
        if(isset($_SESSION['error_register_login_taken']))
        {
            echo $_SESSION['error_register_login_taken'];
            unset($_SESSION['error_register_login_taken']);
        }
        if(isset($_SESSION['error_register_email']))
        {
            echo $_SESSION['error_register_email'];
            unset($_SESSION['error_register_email']);
        }
        if(isset($_SESSION['error_register_email_taken']))
            {
                echo $_SESSION['error_register_email_taken'];
                unset($_SESSION['error_register_email_taken']);
            }

            if(isset($_SESSION['error_register_email']))
            {
                echo $_SESSION['error_register_email'];
                unset($_SESSION['error_register_email']);
            }
            if(isset($_SESSION['error_register_email_taken']))
                {
                    echo $_SESSION['error_register_email_taken'];
                    unset($_SESSION['error_register_email_taken']);
                }
                if(isset($_SESSION['error_register_password1']))
                {
                    echo $_SESSION['error_register_password1'];
                    unset($_SESSION['error_register_password1']);
                }
                if(isset($_SESSION['error_register_password1']))
                {
                    echo $_SESSION['error_register_password1'];
                    unset($_SESSION['error_register_password1']);
                }
                if(isset($_SESSION['error_register_password2']))
                {
                    echo $_SESSION['error_register_password2'];
                    unset($_SESSION['error_register_password2']);
                }
                if(isset($_SESSION['error_register_password2']))
                {
                    echo $_SESSION['error_register_password2'];
                    unset($_SESSION['error_register_password2']);
                }
                if(isset($_SESSION['error_register_password2']))
                {
                    echo $_SESSION['error_register_password2'];
                    unset($_SESSION['error_register_password2']);
                }
                if(isset($_SESSION['error_register_accept']))
                {
                    echo $_SESSION['error_register_accept'];
                    unset($_SESSION['error_register_accept']);
                }
    
        ?>

            </div>
    </div>

</body>
</html>