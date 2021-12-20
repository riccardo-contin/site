<?php
//include_once "register.html";
require_once "utils.php";
require_once "dbRicky.php";
use DB\DBAccess;
$paginaHTML = file_get_contents("register.html");
$replaceMsg="";
$replaceUser="";
$replaceLink="";

$connessione = new DBAccess();
$connessioneOK = $connessione->openDBConnection();

if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['ripetiPassword'])) {

    if($_POST['password'] == $_POST['ripetiPassword']){


        $replaceUser=$_POST['username'];
     
        //validazione credenziali
        $username = Utils::valida($_POST['username']);
        $pass = Utils::valida($_POST['password']);
     
        
        if (!$connessione->isUsernameTaken($username)) {
            //se non c'é nessun utente con questo usrname
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $query = "INSERT INTO `Utenti` (`Username`, `Password`, `Privilegi`) VALUES ('$username', '$hash', '0');";
            $result = $connessione->execQuery($query);
            if (!$result) {
                //se si verifica un errore
                $replaceMsg =  "Si é verificato un errore, riprovare piú tardi";
            }else{
                //tutto bene
                $replaceMsg = "Registrato con successo";
            }
        }else{
            //username giá utilizzato
            $replaceMsg = "Username giá in uso";
        }
    }else{
        $replaceMsg="Password diverse";
    }
}else{//form non riempito
    $replaceMsg = "";
}


if (Utils::checkPriv()){
    $replaceLink = "<li class='right'><a href='dashboard.php'>Dashboard Admin</a></li>";
}

echo str_replace(array("['UsrVal']","['UsrMsg']","['LinkDashboard']"),array($replaceUser,$replaceMsg,$replaceLink),$paginaHTML);
$connessione->closeConnection();
?>
