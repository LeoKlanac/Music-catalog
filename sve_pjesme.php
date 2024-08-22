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
            <form method="GET">
				<?php
				$rezultat = izvrsiUpit($veza, "SELECT medijska_kuca_id, naziv FROM medijska_kuca");
				?>
				<select name="medijska_kuca">
					<option value="-1">Sve</option>
					<?php while($row = $rezultat->fetch_assoc()) { ?>
						<option 
							value="<?php echo $row['medijska_kuca_id']; ?>" 
							<?php echo (isset($_GET['medijska_kuca']) && $_GET['medijska_kuca'] === $row['medijska_kuca_id']) ? "selected" : "" ?>
						>
							<?php echo $row['naziv']; ?>
						</option>
					<?php } ?>
				</select>
				<input type="text" name="trazi_od" placeholder="Od..." value="<?php echo (isset($_GET['trazi_od'])) ? $_GET['trazi_od'] : "" ?>">
                <input type="text" name="trazi_do" placeholder="Do..." value="<?php echo (isset($_GET['trazi_do'])) ? $_GET['trazi_do'] : "" ?>">
                <input type="submit" value="Filtriraj">
            </form>
			<table id="listapjesama">
				<thead>
					<tr>
						<th>Pjesma</th>
						<th>Korisnik</th>
						<th>Naziv</th>
						<th>Broj svidanja</th>
					</tr>
				</thead>
				<tbody>
					<?php
                        $add1 = "";
                        if(isset($_GET['medijska_kuca']) && !empty($_GET['medijska_kuca']) && $_GET['medijska_kuca'] != -1) {
                            $medijskaKuca = $_GET['medijska_kuca'];
                            $add1 = "m.medijska_kuca_id = '$medijskaKuca'";
                        }
                        $add2 = "";
                        if(isset($_GET['trazi_od']) || isset($_GET['trazi_do'])) {
                            $datumOd = "01-01-1970";
                            if(isset($_GET['trazi_od']) && !empty($_GET['trazi_od']))
                                $datumOd = date("Y-m-d H:i:s", strtotime($_GET['trazi_od']));
                            
                            $datumDo = date("Y-m-d H:i:s");
                            if(isset($_GET['trazi_do']) && !empty($_GET['trazi_do']))
                                $datumDo = date("Y-m-d H:i:s", strtotime($_GET['trazi_do']));
                            $add2 = "p.datum_vrijeme_kreiranja BETWEEN '$datumOd' AND '$datumDo'";
                        }

                        

                        $add = "";
                        if($add1 !== "" && $add2 === "") {
                            $add = "WHERE $add1";
                        } else if($add1 === "" && $add2 !== "")  {
                            $add = "WHERE $add2";
                        } else if($add1 !== "" && $add2 !== "")  {
                            $add = "WHERE $add1 AND $add2";
                        } else {
                            $add = "";
                        }

						$upit = "SELECT p.pjesma_id, p.poveznica, k.korime, p.naziv, p.broj_svidanja, p.datum_vrijeme_kupnje, p.medijska_kuca_id FROM pjesma p LEFT JOIN korisnik k ON p.korisnik_id = k.korisnik_id LEFT JOIN medijska_kuca m ON p.medijska_kuca_id = m.medijska_kuca_id $add ORDER BY p.broj_svidanja DESC";
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
								<?php
									$naProdaju = false;
									if($_SESSION['tip'] === 1) {
										if(empty($row['datum_vrijeme_kupnje']) && (empty($row['medijska_kuca_id']) || !empty($row['datum_vrijeme_kupnje']))) {
											$naProdaju = true;
										}
									}
									$link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
								?>
								<?php echo "<a href='pjesma.php?id=".$row['pjesma_id']."'>".$row['naziv']."</a>"; ?>
								<?php if($naProdaju) { ?>
									(NIJE KUPLJENO - <a href="kupi.php?id=<?php echo $row['pjesma_id']; ?>&return=<?php echo $link; ?>">KUPI</a>)
								<?php } ?>
							</td>
							<td>
								<?php echo $row['broj_svidanja']; ?>
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