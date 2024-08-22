
<nav>
	<a href="index.php"><img alt="logo" src="slike/logo.png" width="250px" height="112px"></a>
	<div class="navigacija">
		<ul>
			<li><a href="index.php">Početna</a><br></li>
			<li>
				<?php 
				if(!isset($_SESSION["id"])) { ?>
					<a href="prijava.php" class="veza" >
						Prijava
					</a>
				<?php 
				} else { ?>
					<a href="prijava.php?odjava=1" class="veza" >
						<?php
							$uloge = Array(
								"Administrator",
								"Moderator",
								"Registrirani korisnik"
							)
						?>
						Odjava (<?php echo $_SESSION['ime']." ".$_SESSION['prezime']." - ".$uloge[$_SESSION['tip']] ?>)
					</a>
				<?php 
				} ?>
			</li>
			<li><a href="o_autoru.html">O autoru</a><br></li> 
			<li><a href="pjesme_medijskih_kuca.php">Pjesme medijskih kuća</a></li> 
			<?php 
			if(isset($_SESSION['id'])) { ?>
				<li><a href="moje_pjesme.php">Moje pjesme</a></li>
				<li><a href="sve_pjesme.php">Sve pjesme</a></li> 
			<?php 
			} ?>
			<?php 
			if(isset($_SESSION["tip"]) && $_SESSION["tip"] === 0) { ?>
				<li>
					<a href="editiranje_korisnika.php" class="veza" >
						Editiranje korisnika
					</a>
				</li>
				<li>
					<a href="registracija.php" class="veza" >
						Registracija korisnika
					</a>
				</li>
				<li>
					<a href="medijske_kuce.php" class="veza" >
						Medijske kuće
					</a>
				</li>
			<?php  
			} ?>
			<?php if(isset($_SESSION["tip"]) && $_SESSION["tip"] === 1) { ?>
				<li>
					<a href="moja_medijska_kuca.php" class="veza" >
						Moja medijska kuća
					</a>
				</li>
			<?php } ?>
			
		</ul>
	</div>
	
</nav>