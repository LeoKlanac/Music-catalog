<?php
	session_start();

	if($_SESSION['tip'] !== 0) {
		echo "Samo za administratore";
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
			<h2>Kreiranje medijske kuće:</h2>
			<form method="POST">
				<input type="text" name="naziv" placeholder="Naziv">
				<input type="text" name="opis" placeholder="Opis">
				<input type="submit" name="submit" value="Kreiraj">

				<?php
					if(isset($_POST['submit'])) {
						$naziv = $veza->real_escape_string($_POST['naziv']);
						$opis = $veza->real_escape_string($_POST['opis']);

						$upit = "INSERT INTO medijska_kuca (naziv, opis) VALUES ('$naziv', '$opis')";
						izvrsiUpit($veza, $upit);

						echo "Dodana medijska kuća";
					}
				?>
			</form>
			<h2 class="text-center">Medijske kuće</p>
			<table id="listapjesama">
				<thead>
					<tr>
						<th>Medijska kuća</th>
						<th>Opis</th>
						<th>Broj sviđanja</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$upit = "SELECT COALESCE(SUM(p.broj_svidanja), 0) as brojSvidanja, m.naziv, m.opis, m.medijska_kuca_id FROM pjesma p RIGHT JOIN medijska_kuca m ON p.medijska_kuca_id = m.medijska_kuca_id GROUP BY m.medijska_kuca_id ORDER BY brojSvidanja DESC;";

						$rezultat = izvrsiUpit($veza, $upit);

					?>
					<?php while($row = mysqli_fetch_assoc($rezultat)) {
					?>
						<tr>
							<td>
								<a href="medijska_kuca.php?id=<?php echo $row['medijska_kuca_id']; ?>">
									<?php echo $row["naziv"]; ?>
								</a>
									
							</td>
							<td>
                                <?php echo $row["opis"]; ?>
							</td>
							<td>
                                <?php echo $row["brojSvidanja"]; ?>
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