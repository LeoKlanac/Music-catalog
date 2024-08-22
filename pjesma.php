<?php
	session_start();

	
	include_once("baza.php");
	$veza = spojiSeNaBazu();
	

		
?>
<!DOCTYPE html>
<html>
<head>
        <title>Uredi pjesmu</title>
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
				$pjesmaId = $veza->real_escape_string($_GET['id']);
				if(isset($_POST['submit'])) {
					$naziv = $veza->real_escape_string($_POST['naziv']);
					$opis = $veza->real_escape_string($_POST['opis']);
					$poveznica = $veza->real_escape_string($_POST['poveznica']);
					$medijskaKucaId = (empty($_POST['medijska_kuca_id'])) ? "NULL" : $veza->real_escape_string($_POST['medijska_kuca_id']);
					$upit = "UPDATE pjesma SET naziv = '$naziv', opis = '$opis', poveznica = '$poveznica', medijska_kuca_id = $medijskaKucaId WHERE pjesma_id = $pjesmaId";
					izvrsiUpit($veza, $upit);
					echo "Azurirano";
				}
			?>
			<?php 
			
			if(isset($_GET['id']) && isset($_GET['radnja']) && $_GET['radnja'] === "uredi") { ?>
				<?php
					
					$upit = "SELECT naziv, opis, poveznica, medijska_kuca_id FROM pjesma WHERE pjesma_id = $pjesmaId";
					$rezultat = izvrsiUpit($veza, $upit);
					if($rezultat->num_rows !== 0) {
						$row = $rezultat->fetch_assoc();
					} else {
						echo "Ta pjesma ne postoji";
					}
					
				?>
				<form method="POST">
					<input type="text" name="naziv" placeholder="Naziv" value="<?php echo $row['naziv']; ?>">
					<input type="text" name="opis" placeholder="Opis" value="<?php echo $row['opis']; ?>">
					<input type="text" name="poveznica" placeholder="Poveznica" value="<?php echo $row['poveznica']; ?>">
					<input type="text" name="medijska_kuca_id" placeholder="ID medijske kuce" value="<?php echo $row['medijska_kuca_id']; ?>">
					
					<input type="submit" value="Uredi" name="submit">

					
				</form>
			<?php 
			} else { ?>
				

				<?php
					$upit = "SELECT p.naziv, p.datum_vrijeme_kreiranja, p.broj_svidanja, p.opis, k.ime, k.prezime, m.naziv AS naziv_kuce ".
					"FROM pjesma p ".
					"LEFT JOIN korisnik k ON p.korisnik_id = k.korisnik_id ".
					"LEFT JOIN medijska_kuca m ON p.medijska_kuca_id = m.medijska_kuca_id ".
					"WHERE pjesma_id = $pjesmaId";
					$rezultat = izvrsiUpit($veza, $upit);
					$row = $rezultat->fetch_assoc();

					$link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

				?>
				<ul>
					<li>Naziv: <?php echo $row['naziv']; ?></li>
					<li>Datum i vrijeme kreiranja: <?php echo date("d.m.Y", strtotime($row['datum_vrijeme_kreiranja'])); ?></li>
					<li>Broj svidanja: <?php echo (empty($row['broj_svidanja'])) ? "Nema" : $row['broj_svidanja']; ?></li>
					<li>Opis: <?php echo $row['opis']; ?></li>
					<li><a href="<?php echo "like.php?id=$pjesmaId&return=$link" ?>">Lajk</a></li>
					<li>Ime i prezime: <?php echo $row['ime']." ".$row['prezime']; ?></li>
					<li>Naziv kuce: <?php echo (empty($row['naziv_kuce'])) ? "Nema" : $row['naziv_kuce']; ?></li>
				</ul>
			<?php 
			} ?>
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