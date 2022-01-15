<?php
session_start();
require_once "php_vari/utils.php";
require_once "php_vari/dbRicky.php";
use DB\DBAccess;


$pagina = file_get_contents("html/modificaPrezzi.html");
$pagina = Utils::skipNavBtn($pagina);
$pagina = Utils::addScrollBtn($pagina);

if(!Utils::checkPriv()){
    header("Location: index.php");
    die("Pagina riservata ad amministratori");
}

$curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1); 
$pagina = str_replace("[Menu]",Utils::buildNav($curPageName),$pagina);
$pagina = str_replace("['Imports']", Utils::globalImports(),$pagina);


$sql="SELECT Prezzo FROM `Skipass` ORDER BY Tipo,Durata";
$connessione = new DBAccess();
$connessione->openDBConnection();  
$res = $connessione->execQuery($sql);
$prezzi=[];
while($r = $res->fetch_assoc()){
    if(empty($prezzi))
        $pagina = str_replace("['num-def']",number_format($r['Prezzo'],2),$pagina);
    $prezzi[]=number_format($r['Prezzo'],2,',',' ').'€';
}

$find=["['i1']","['i3']","['i7']","['r1']","['r3']","['r7']"];
$pagina = str_replace($find,$prezzi,$pagina);


echo $pagina;


?>