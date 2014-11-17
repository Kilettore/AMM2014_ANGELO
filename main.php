<?php
// Creo un nuovo oggetto mysqli (serve per il collegamento al database)
$mysqli = new mysqli();

// Mi connetto al database locale inserendo i dati corretti
$mysqli->connect("http://spano.sc.unica.it/phpmyadmin/index.php", "cadoniAngelo", "scimpanze72", "amm2014_cadoniAngelo");

// Verifico la presenza di errori, se correct_errno != 0 significa che c'è qualcosa che non va
if($mysqli->connect_errno != 0)
{
	// Salvo in idErrore l' errore generato dalla connessione
	$idErrore = $mysqli->connect_errno;

	// Faccio lo stesso per il messaggio
	$msg = $mysqli->connect_error;
	error_log("Errore nella connessione al server $idErrore : $msg", 0);
	echo "Errore nella connessione $msg";
}
else
{
	// Nessun errore
	$query = "SELECT * FROM prodotti";
	$result = $mysqli->query($query);
	if($mysqli->errno > 0)
	{
		// Errore durante la esecuzione della query
		error_log("Errore query $mysqli->errno : $mysqli->error", 0);
	}
	else
	{
		// La query è stata eseguita correttamente
		echo "<ul>\n";
		while($row = $result->fetch_row())
		{
			echo "<li> $row[0] </li>\n";
		}
		echo "</ul>\n";
	}
}

//Chiudo la connessione al database
$mysqli->close();
?>

 
