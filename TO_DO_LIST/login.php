-<?php
    session_set_cookie_params(0);
    session_start(); //rozpoczynamy sesje do wymiany danych między plikami
    //jesli nie ma stworzonej zmiennej login/password (czyli nie został wypełniony formularz, to przekieruje nas do index.php)
    if((!isset($_POST['login'])) || (!isset($_POST['password'])))
    {
        header('Location: index.php');
        exit();
    }
    require_once "database.php"; //wymagamy pliku "database.php" w kodzie, tylko raz(sprawdzi on czy plik nie został dodanyw cześniej)
    mysqli_report(MYSQLI_REPORT_STRICT);
    //tworzymy obiekt "$connection" łączący się z bazą danych
    //w bloku try zawarte są 'podjerzane' funkcje, podczas wykonywania się których może wystąpić błąd, 
    //robimy to aby zamiast zakonczyc działanie programu, my zwracamy wyjątek za pomocą polecenia throw, 
    //czyli komunikat o błędzie który jest wyłapywany w bloku catch 
    try{   
        //tworzymy obiekt klasy mysqli 
        //(mysqli to nazwa metody będącej konstruktorem) 
        //(new = operator dynamicznego alokowania pamięci)
        //ustanawiamy połączenie z bazą danych przy pomocy obiektu klasy mysqli
        $connection = new mysqli($host, $db_user, $db_password, $db_name);
        //gdy connect_erno = 0, to znaczy że połączenie z bazą zakończyło się sukcesem,
        // więc jesli jest różny od zera to zwróci błąd
        if($connection->connect_erno!=0) 
        {
            throw new Exception(mysqli_connect_errno()); //rzucamy wyjątkiem, który zawiera kod błędu
        }
        else //w przypadku gdy połączenie zostało nawiązane
        {
            $login = $_POST['login'];         //odczytujemy login który przyszedł metodą POST
            $password = $_POST['password'];   //odczytujemy hasło który przyszedł metodą POST
           
            //tworzymy obiekt result_of_connection który równa się zastosowaniu kwerendy SQL w obiekcie connection przy pomocy metody query
            $result_of_connection = $connection->query("SELECT * FROM users WHERE login='$login'" );
            if($result_of_connection == false)
            {
                throw new Exception($connection->error);
            }
            else
            {
                //do obiektu result_of_connection skorzystamy z właściwości num_rows, która zwróci liczbę wyszukanych wierszy w bazie (czyli ilość uzytkowników)
                $num_of_users = $result_of_connection->num_rows;
                if($num_of_users > 0) //gdy znajdziemy użytkownika o podanym loginie w bazie to wykonujemy:
                {
                    //tworzymy zmienna row - reprezentuje ona pobrany rekord z bazy, jest to tablica przechowująca wszystkie pobrane z bazy kolumny\
                    //do obiektu result_of_connection użyjemy metody fetch_assoc(), która stworzy tablicę asocjacyjną, do której
                    //zostaną włożone zmienne o takich samych nazwach, jak nazwy kolumn w bazie
                    $row = $result_of_connection->fetch_assoc();

                    if(password_verify($password, $row['password']))
                    {
                        $_SESSION['logged'] = true;                //do zmiennej sesyjnej wkładamy wartość true dla logged, w przypadku gdy jesteśmy pomyślnie zalogowani (do przekierowania do account.php)
                        $_SESSION['user_ID'] = $row['user_ID'];              //do zmiennej sesyjnej przekazujemy zmienną z tablicy asocjacyjnej 'row'
                        $_SESSION['login'] = $row['login'];        //do zmiennej sesyjnej przekazujemy zmienną z tablicy asocjacyjnej 'row'
                        $_SESSION['password'] = $row['password'];  //do zmiennej sesyjnej przekazujemy zmienną z tablicy asocjacyjnej 'row'
                        $_SESSION['email'] = $row['email'];        //do zmiennej sesyjnej przekazujemy zmienną z tablicy asocjacyjnej 'row'

                        unset($_SESSION['error_log_login']);       //gdy już uda się pomyślnie zalogować, usuwamy zmienną reprezentującą błąd związany z loginem
                        unset($_SESSION['error_log_password']);    //gdy już uda się pomyślnie zalogować, usuwamy zmienną reprezentującą błąd związany z loginem
                        $result_of_connection->free_result();      //dynamicznie zwalniamy pamięć
                        header('Location: account.php');           //w przypadku pomyślnego zalogowania, przekierowujemy do strony z kontem      
                    }
                    else
                    {
                        //w przypadku gdy wpisane hasło nie jest zgodne z hasłem z bazy
                        $_SESSION['error_log_password'] = '<span style="color:red"> WRONG PASSWORD </span>'; //komunikat o błędnym hasle
                        header('Location: index.php'); //przekierowanie na strone startową
                    } 
                }
                else { //nie znalezlismy w bazie usera o takim loginie przy pomocy kwerendy SELECT
                    $_SESSION['error_log_login'] = '<span style="color:red"> WRONG LOGIN </span>';  //komunikat o błędnym loginie
                    header('Location: index.php'); //przekierowanie na strone startową
                }
            }
            $connection->close();  //zamykamy połączenie z bazą
        }
    }
    //łapiemy rzucone wyjątki 
    catch(Exception $error){
        echo '<span style="color:red;"> ERROR - SQL query is not correct <span/>';
    }
?>







        