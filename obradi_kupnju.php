<?php
    session_start();
    include_once("baza.php");
    $veza = spojiSeNaBazu();

    


    if(isset($_GET['id'])) {
        $pjesmaId = $veza->real_escape_string($_GET['id']);
        if($_GET['radnja'] === "odobri") {
            $upit = "UPDATE pjesma SET datum_vrijeme_kupnje = CURRENT_TIMESTAMP() WHERE pjesma_id = $pjesmaId";
            izvrsiUpit($veza, $upit);
        } else if($_GET['radnja'] === "odbij") {
            $upit = "UPDATE pjesma SET medijska_kuca_id = NULL WHERE pjesma_id = $pjesmaId";
            izvrsiUpit($veza, $upit);
        }
        header("Location: moje_pjesme.php");
    }
    


?>