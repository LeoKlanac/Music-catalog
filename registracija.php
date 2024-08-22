<?php
	session_start();
	if($_SESSION['tip'] !== 0) {
		echo "Nemate pristup";
		exit;
	}

	include_once("baza.php");
	$veza = spojiSeNaBazu();
	$id_novi_korisnik = "";
	

	if(isset($_POST["submit"]))
	{
		$korime = $_POST["korime"];
		$lozinka = $_POST["lozinka"];
		$ime = $_POST["ime"];
		$prezime = $_POST["prezime"];
		$email = $_POST["email"];
		$greska = "";

		$upitPostoji = "SELECT korisnik_id FROM korisnik WHERE korime = '".$veza->real_escape_string($korime)."' LIMIT 1";
		$postojeciKorisnici = izvrsiUpit($veza, $upitPostoji);
		if($postojeciKorisnici->num_rows !== 0) {
			$greska .= "Taj korisnik vec postoji";
		}

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
		if(empty($greska))
		{
			$poruka="Kreirali ste račun";
			$upit = "INSERT INTO korisnik(`tip_korisnika_id`,`ime`,`prezime`,`email`,`korime`,`lozinka`) 
			VALUES(2,'".$veza->real_escape_string($ime)."','".$veza->real_escape_string($prezime)."','".$veza->real_escape_string($email)."','".$veza->real_escape_string($korime)."','".$veza->real_escape_string($lozinka)."')";
			izvrsiUpit($veza,$upit);
			$id_novi_korisnik = mysqli_insert_id($veza);
		}
	}
	$upitPostoji = "SELECT * FROM korisnik";
	$postojeciKorisnici = izvrsiUpit($veza,$upitPostoji);
?>


<!DOCTYPE html>
<html lang="hr">
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
        include ("meni.php");
      ?>
    </section>
    <section class="sadrzaj">
	<?php
				if(isset($greska)){
					echo $greska;
				}
				
				if(!empty($id_novi_korisnik))
				{
					echo "Unesen je novi korisnik pod ključem: ".$id_novi_korisnik;
				}
			?>
    <h1>Registracija korisnika:</h1>
    <form name="forma" id="forma" method="POST" 
			action="<?php echo $_SERVER["PHP_SELF"] ?>" >
				<label for="ime">Ime: </label>
				<input name="ime" id="ime" type="text" />
				<br/>
				<label for="prezime">Prezime: </label>
				<input name="prezime" id="prezime" type="text" />
				<br/>
				<label for="email">e-mail</label>
				<input name="email" id="email" type="email" />
				<br/>
				<label for="korime">Korisničko ime: </label>
				<input name="korime" id="korime" type="text" />
				<br/>
				<label for="loznika">Lozinka: </label>
				<input name="lozinka" id="lozinka" type="password" />
				<br/>
				<input type="submit" name="submit" id="submit" 
					   value="Unesi" />
                <input type="reset" name="reset" id="reset" 
				value="Reset" class="cursor" />
	</form>   
	<table>
				<thead>
				<tr><th>Korisnik id</th><th>Ime</th><th>Prezime</th><th>Ažuriraj</th><tr>
				</thead>
				<tbody>
				<h1>Editiranje korisnika</h1>
					<?php
					
	if($postojeciKorisnici)
	{
		while($row = mysqli_fetch_array($postojeciKorisnici))
		{
			echo "<tr>";
			echo "<td>".$row[0]."</td>";
			echo "<td>".$row["ime"]."</td>";
			echo "<td>".$row["prezime"]."</td>";
			echo "<td><a href=\"editiranje_korisnika.php?id=".$row["korisnik_id"]."\">Ažuriraj</a></td>";
			echo "</tr>";
		}
	}
	else{
		echo "Greška";
	}
					?>
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