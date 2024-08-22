<?php
	session_start();

    if($_SESSION['tip'] !== 1) {
        echo "Dostupno samo moderatorima";
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
    
		<section id="sadrzaj">
			<table id="listapjesama">
				<thead>
					<tr>
						<th>Pjesma</th>
						<th>Korisnik</th>
						<th>Naziv</th>
						<th>Broj svidanja</th>
                        <th>Status</th>
					</tr>
				</thead>
				<tbody>
					<?php
                        $korisnikId = $_SESSION['id'];
                        $upitMedijskaKuca = "SELECT medijska_kuca_id FROM korisnik WHERE korisnik_id = $korisnikId";
                        $rezultatMedijskaKuca = izvrsiUpit($veza, $upitMedijskaKuca);
                        $medijskaKucaId = $rezultatMedijskaKuca->fetch_assoc()['medijska_kuca_id'];


						$upit = "SELECT p.poveznica, k.korime, p.naziv, p.broj_svidanja, p.datum_vrijeme_kupnje, p.medijska_kuca_id FROM pjesma p LEFT JOIN korisnik k ON p.korisnik_id = k.korisnik_id WHERE p.medijska_kuca_id = $medijskaKucaId ORDER BY p.broj_svidanja DESC";

						$rezultat = izvrsiUpit($veza, $upit);

					?>
					<?php while($row = mysqli_fetch_assoc($rezultat)) {
					?>
						<tr>
							<td>
								<audio controls>
									<source src="<?php echo $row['poveznica']; ?>" type="audio/mpeg">
								</audio>
									
							</td>
							<td>
								<?php echo $row['korime']; ?>
							</td>
							<td>
								<?php echo $row['naziv']; ?>
							</td>
							<td>
								<?php echo $row['broj_svidanja']; ?>
							</td>
                            <td>
                                <?php
                                    if(!empty($row['datum_vrijeme_kupnje'])) {
										echo "Kupljena";
									} else {
                                        echo "Čeka odobrenje";
                                    }
                                ?>
                            </td>
						</tr>
					<?php 
					} ?>
				</tbody>
			</table>
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