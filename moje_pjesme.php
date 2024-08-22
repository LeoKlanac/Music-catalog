<?php
	session_start();

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
			<form id="unospjesme" method="POST">
				<input type="text" name="naziv" placeholder="Naziv">
				<input type="text" name="poveznica" placeholder="Poveznica">
				<input type="text" name="opis" placeholder="Opis">
				<input type="submit" value="Dodaj" name="submit">

				<?php 
					if(isset($_POST['submit'])) {
						$naziv = $veza->real_escape_string($_POST['naziv']);
						$poveznica = $veza->real_escape_string($_POST['poveznica']);
						$opis = $veza->real_escape_string($_POST['opis']);
						$userid = $_SESSION['id'];

						$upit = "INSERT INTO pjesma (naziv, poveznica, opis, datum_vrijeme_kreiranja, korisnik_id) ".
						"VALUES ('$naziv', '$poveznica', '$opis', CURRENT_TIMESTAMP(), $userid)";

						izvrsiUpit($veza, $upit);

						echo "Pjesma unesena.";
					}
				?>
			</form>
			<table id="listapjesama">
				<thead>
					<tr>
						<th>Naziv</th>
						<th>Opis</th>
						<th>Datum kreiranja</th>
						<th>Datum kupnje</th>
						<th>Zapis</th>
						<th>Status</th>
						<th>Radnje</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$korisnik = $_SESSION['id'];
						$upit = "SELECT pjesma_id, naziv, opis, datum_vrijeme_kreiranja, datum_vrijeme_kupnje, poveznica, medijska_kuca_id FROM pjesma WHERE korisnik_id = $korisnik";

						$rezultat = izvrsiUpit($veza, $upit);

					?>
					<?php while($row = mysqli_fetch_assoc($rezultat)) {
					?>
						<tr>
							<td>
								<?php echo $row['naziv']; ?>
							</td>
							<td>
								<?php echo $row['opis']; ?>
							</td>
							<td>
								<?php echo date("d.m.Y", strtotime($row['datum_vrijeme_kreiranja'])); ?>
							</td>
							<td>
								<?php 
									if(!empty($row['datum_vrijeme_kupnje']))
										echo date("d.m.Y", strtotime($row['datum_vrijeme_kupnje'])); 
									else 
										echo "Nema";
								?>
							</td>

							<td>
								<audio controls>
									<source src="<?php echo $row['poveznica']; ?>" type="audio/mpeg">
								</audio>
									
							</td>
							<td>
								<?php 
									if(!empty($row['datum_vrijeme_kupnje'])) {
										echo "Kupljena";
									} else if(!empty($row['medijska_kuca_id']) && empty($row['datum_vrijeme_kupnje'])) {
										echo "Zatrazena kupnja";
									} else {
										echo "Nije kupljena";
									}
								?>
							</td>
							<td>
								<?php
								if(!empty($row['medijska_kuca_id']) && empty($row['datum_vrijeme_kupnje'])) {
									echo "<a href='obradi_kupnju.php?id=".$row['pjesma_id']."&radnja=odobri'>Odobri kupnju</a>";
									echo "<br>";
									echo "<a href='obradi_kupnju.php?id=".$row['pjesma_id']."&radnja=odbij'>Odbij kupnju</a>";
								} 
								if(empty($row['medijska_kuca_id']) && empty($row['datum_vrijeme_kupnje'])) {
									echo "<a href='pjesma.php?id=".$row['pjesma_id']."&radnja=uredi'>Uredi</a>";
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