<?php
	
	session_start();
	if(isset($_GET["odjava"])){
		unset($_SESSION["id"]);
		unset($_SESSION["tip"]);
		unset($_SESSION["ime"]);
		session_destroy();
	}
	
	if(isset($_POST["korime"]) && isset($_POST["lozinka"]))
	{
		 
		$korime = $_POST["korime"];
		if(isset($korime) &&  isset($korime) && 
		isset($_POST["lozinka"]) &&
		!empty($korime) &&
		!empty($_POST["lozinka"]));
		{
			include_once("baza.php");
			$veza = spojiSeNaBazu();
			$upit = "SELECT * FROM korisnik WHERE 
			korime = '".$veza->real_escape_string($korime)."'
			AND lozinka = '".$veza->real_escape_string($_POST["lozinka"])."'";

			$rezultat = izvrsiUpit($veza,$upit);
			$logiran=false;
			if($row = mysqli_fetch_array($rezultat)) {
				$_SESSION["id"] = $row[0];
				$_SESSION["ime"] = $row["ime"];
				$_SESSION["prezime"] = $row["prezime"];
				$_SESSION["tip"] = intval($row["tip_korisnika_id"]);
				$logiran=true;
			}
			
			zatvoriVezuNaBazu($veza);
			if($logiran)
			{
				header("Location:index.php");
				exit();
			}
			else{
				$greska = "Lozinka i Korisničko ime se ne podudaraju!";
			}
			
		}
	}  else {
		echo "Nisu poslani svi obavezni podaci";
	}
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
        include_once ("meni.php");
      ?>
    </section>
    <section class="sadrzaj">
    <?php
				if(isset($greska)){
					echo $greska;
				}
			?>
    <h1>Prijava:</h1>
			<form name="forma" id="forma" method="POST"
			action="<?php echo $_SERVER["PHP_SELF"] ?>" >
                <label for="korime">Korisničko ime: </label>
				<input name="korime" id="korime" type="text" />
				</br>
				<label for="lozinka">Lozinka: </label>
				<input name="lozinka" id="lozinka" type="password" />
                </br>
        <input type="submit" name="submit" id="submit" 
			 value="Unesi" />
             <input type="reset" name="reset" id="reset" 
				value="Reset" class="cursor" />
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