
<?php    
    
    session_start();   //rozpoczynamy sesje 
    session_unset();   //kasujemy zmienne sesyjne    
    header('Location: index.php'); //wracamy na stone poczatkowa 
?>