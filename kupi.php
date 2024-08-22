<?php
session_start();

include_once("baza.php");
$veza = spojiSeNaBazu();

if(isset($_GET['id']) && isset($_GET['return']) && $_SESSION['tip'] === 1) {
    $pjesmaId = $veza->real_escape_string($_GET['id']);

    $korisnikId = $_SESSION['id'];
    $upitMedijskaKuca = "SELECT medijska_kuca_id FROM korisnik WHERE korisnik_id = $korisnikId";
    $rezultatMedijskaKuca = izvrsiUpit($veza, $upitMedijskaKuca);
    $medijskaKucaId = $rezultatMedijskaKuca->fetch_assoc()['medijska_kuca_id'];

    $upit = "UPDATE pjesma SET medijska_kuca_id = $medijskaKucaId WHERE pjesma_id = $pjesmaId";
    izvrsiUpit($veza, $upit);

    $return = $_GET['return'];
    header("Location: $return");
}
			
?>