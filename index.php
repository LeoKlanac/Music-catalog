<?php

session_start();


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
    <section class="header">
      <?php
        include ("meni.php");
      ?>
      <?php 
      $danas = date("d.m.Y");
      echo "Danas je:".$danas;
				 $vrijeme = strtotime($danas);
				  echo " Vrijeme iz strtotime ".$vrijeme;
				echo " Vrijeme je: ".date("d.m.Y",$vrijeme);
			?>
			<br>
			Dobro došli <?php 
      if (isset($_SESSION["id"])){
			echo $_SESSION["ime"];
			echo $_SESSION["prezime"];
            echo $_SESSION["tip"];}; 
   
			?>
    </section>
   
    <?php 
 
    ?>
    <footer>
			Leo Klanac
			<a href="mailto:leo.klanac@gmail.com">
				leo.klanac@gmail.com
			</a>
		</footer>
    </body>
</html>