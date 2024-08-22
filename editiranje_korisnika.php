<?php
	session_start();
	if($_SESSION['tip'] !== 0) {
		echo "Nemate pristup";
		exit;
	}
	
	include_once("baza.php");
	$veza = spojiSeNaBazu();
	$id_novi_korisnik = "";
	

	if(isset($_GET['id'])) {
		$id_update_korisnik = $_GET['id'];
		if(!$veza){
			echo "greška prilikom spajana na bazu";
		} else {
			if(isset($_POST["submit"])) {
				$korime = $_POST["korime"];
				$lozinka = $_POST["lozinka"];
				$ime = $_POST["ime"];
				$prezime = $_POST["prezime"];
				$email = $_POST["email"];
				$tip_korisnika_id = $_POST["tip_korisnika_id"];
				$greska = "";
				if(!isset($korime) || empty($korime))
				{
					$greska.= "Unesite korisničko ime!<br>";
					
				}
				if(!isset($lozinka) || empty($lozinka))
				{
					$greska.= "Unesite lozinku!<br>";
					
				}
				if(!isset($ime) || empty($ime))
				{
					$greska.= "Unesite ime! <br>";
					
				}
				if(!isset($prezime) || empty($prezime))
				{
					$greska.= "Unesite prezime!<br>";
					
				}
				if(!isset($email) || empty($email))
				{
					$greska.= "Unesite email!<br>";
					
				}
				$append = "";
				if(isset($_POST['medijska_kuca'])) {
					$medijskaKuca = $_POST['medijska_kuca'];
					$append = ", medijska_kuca_id = $medijskaKuca";
				}
				if(empty($greska))
				{
					$poruka="Ažurirali ste korisnika";
					$upit = "UPDATE korisnik SET `tip_korisnika_id`=".$tip_korisnika_id
					.", `ime`='".$ime."',`prezime`='".$prezime."', `email`='".$email."', `korime`='".$korime
					."', `lozinka`='".$lozinka."' $append WHERE korisnik_id = ".$id_update_korisnik;
					izvrsiUpit($veza,$upit);
				}
			}
		}
		$upit = "SELECT * FROM korisnik WHERE korisnik_id = ".$id_update_korisnik;
		$rezultat = izvrsiUpit($veza,$upit);
		$rezultat_ispis = mysqli_fetch_assoc($rezultat);
		
		$upit = "SELECT * FROM tip_korisnika";
		$tipovi = izvrsiUpit($veza,$upit);

		$medijskeKuceUpit = "SELECT medijska_kuca_id, naziv FROM medijska_kuca";
		$medijskeKuce = izvrsiUpit($veza, $medijskeKuceUpit);
	} else {
		$upit = "SELECT * FROM korisnik";
		$rezultatKorisnici = izvrsiUpit($veza, $upit);



	}



		
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
			<div style="color:red;" >
			<?php
				if(isset($greska)){
					echo $greska;
				}
				
				if(isset($poruka)){
					echo $poruka;
				}
				
				if(!empty($id_novi_korisnik))
				{
					echo "Ažuriran je korisnik pod ključem: ".$id_novi_korisnik;
				}
			?>
			</div>
			<?php 
			if(isset($_GET['id'])) { ?>
				<form name="forma" id="forma" method="POST" 
				action="<?php echo $_SERVER["PHP_SELF"]."?id=".$id_update_korisnik; ?>" >
					<label for="tip_korisnika_id">Tip korisnika: </label>
					<select name="tip_korisnika_id" id="tip_korisnika_id">
						<?php
							while($row = mysqli_fetch_array($tipovi))
							{
								echo "<option value=\"".$row["tip_korisnika_id"]."\"";
								if($rezultat_ispis['tip_korisnika_id'] == $row["tip_korisnika_id"])
								{
									echo " selected=\"selected\" ";
								}
								echo ">".$row["naziv"]."</option>";
							}
						?>
					</select>
					
					<?php if($rezultat_ispis["tip_korisnika_id"] == 1) { ?>
						<br/>
						<label for="medijska_kuca">Medijska kuća</label>
						<select name="medijska_kuca" id="medijska_kuca">
							<option disabled value <?php echo (!$row['medijska_kuca_id']) ? "selected" : ""; ?>>Nema</option>
							<?php while($row = $medijskeKuce->fetch_assoc()) { ?>
								<option 
									value="<?php echo $row['medijska_kuca_id']; ?>"
									<?php echo ($rezultat_ispis['medijska_kuca_id'] === $row['medijska_kuca_id']) ? "selected" : ""; ?>
								>
									<?php echo $row['naziv']; ?>
								</option>
							<?php } ?>
						</select>
					
					<?php } ?>
					
					<br/>
					<label for="ime">Ime: </label>
					<input name="ime" id="ime" type="text" value="<?php echo $rezultat_ispis['ime']; ?>" />
					<br/>
					<label for="prezime">Prezime: </label>
					<input name="prezime" id="prezime" type="text" value="<?php echo $rezultat_ispis['prezime']; ?>" />
					<br/>
					<label for="email">e-mail</label>
					<input name="email" id="email" type="text" value="<?php echo $rezultat_ispis['email']; ?>" />
					<br/>
					<label for="korime">Korisničko ime: </label>
					<input name="korime" id="korime" type="text" value="<?php echo $rezultat_ispis['korime']; ?>" />
					<br/>
					<label for="lozinka">Lozinka: </label>
					<input name="lozinka" id="lozinka" type="password" value="<?php echo $rezultat_ispis['lozinka']; ?>" />
					<br/>
					<input type="submit" name="submit" id="submit" 
						value="Unesi" /><a href="registracija_korisnik.php">Natrag</a>
				</form>
			<?php 
			} else { ?>
			<div id="listakorisnika">
				Lista korisnika
				<ul class="listaKorisnika">
					<?php 
					while($row = mysqli_fetch_assoc($rezultatKorisnici)) { ?>
						<li>
							<a href="editiranje_korisnika.php?id=<?php echo $row['korisnik_id'] ?>"><?php echo $row['ime']; ?></a>
						</li>
					<?php 
					} ?>
				</ul>
			</div>
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