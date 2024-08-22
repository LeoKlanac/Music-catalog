<?php
session_start();

include_once("baza.php");
$veza = spojiSeNaBazu();

if(isset($_GET['id']) && isset($_GET['return'])) {
    $pjesmaId = $veza->real_escape_string($_GET['id']);
    $upit = "UPDATE pjesma SET broj_svidanja = IFNULL(broj_svidanja, 0) + 1 WHERE pjesma_id = $pjesmaId";
    izvrsiUpit($veza, $upit);

    $return = $_GET['return'];
    header("Location: $return");
}
			
?>