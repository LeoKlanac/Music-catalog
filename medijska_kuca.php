<?php
	session_start();
	if($_SESSION['tip'] !== 0) {
		echo "Nemate pristup";
		exit;
	}
	
	include_once("baza.php");
	$veza = spojiSeNaBazu();



		
?>
<!DOCTYPE html>
<html>
<head>
        <title>Glazbeni katalog</title>
        <meta charset="UTF-8"/>
        <meta name="author" content="Leo Klanac"/>
        <meta name="date" content="17.6.2022."/>
        <link href="dizajn.css" rel="stylesheet" type="text/css"/>
        <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400&display=swap" rel="stylesheet">
    </head>
	<body>
	<section class="sub-header">
    <?php
        include_once ("meni.php");
      ?>
    </section>
		<section id="sadrzaj">
            <?php 
                $medijskaKucaId = $veza->real_escape_string($_GET['id']);
                if(isset($_POST['submit'])) {
                    $naziv = $veza->real_escape_string($_POST['naziv']);
                    $opis = $veza->real_escape_string($_POST['opis']);
                    $upit = "UPDATE medijska_kuca SET naziv = '$naziv', opis = '$opis' WHERE medijska_kuca_id = $medijskaKucaId";
                    izvrsiUpit($veza, $upit);
                    echo "Spremljeno";
                }
            ?>


			<?php
                $upit = "SELECT naziv, opis FROM medijska_kuca WHERE medijska_kuca_id = $medijskaKucaId";
                $res = izvrsiUpit($veza, $upit);
                $row = $res->fetch_assoc();
            ?>

            <form id="forma" method="POST">
                <div>
                    <label for="naziv">Naziv:</label>
                    <input type="text" name="naziv" id="naziv" placeholder="Naziv" value="<?php echo $row['naziv']; ?>">
                </div>
                <div>
                    <label for="opis">Opis:</label>
                    <input type="text" name="opis" id="opis" placeholder="opis" value="<?php echo $row['opis']; ?>">
                </div>
                <input type="submit" name="submit" value="Spremi">
            </form>
		</section>
		<footer>
			Leo Klanac
			<a href="mailto:leo.klanac@gmail.com">
            leo.klanac@gmail.com
			</a>
		</footer>
	</body>
</html>
<?php
zatvoriVezuNaBazu($veza);
?>