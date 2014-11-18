<?php
// Creo un nuovo oggetto mysqli (serve per il collegamento al database)
$mysqli = new mysqli();

// Mi connetto al database locale inserendo i dati corretti
$mysqli->connect("localhost", "cadoniAngelo", "scimpanze72", "amm14_cadoniAngelo");

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
	echo "collegamento al db riuscito";
	$query = "SELECT * FROM prodotto";
	$result = $mysqli->query($query);
	if($mysqli->errno > 0)
	{
		// Errore durante la esecuzione della query
		error_log("Errore query $mysqli->errno : $mysqli->error", 0);
	}
	else
	{
		// La query è stata eseguita correttamente
		
		while($row = $result->fetch_object())
		{
			echo "
                        <table width=500 height=100 border=1>
                        <tr> $row->nome </tr>
                        <tr><td> $row->tipologia </td><td> $row->schermo </td></tr>
                        <tr> $row->ram </tr>
                        <tr> $row->cpu </tr>
                        <tr> $row->hard_disk </tr>
                        <tr> $row->os </tr>
                        <tr> $row->descrizione </tr>
                        <tr> $row->art_disponibili </tr>
                        <tr> $row->prezzo </tr>
                        </table>
                        \n\n";
                }
	}
}

//Chiudo la connessione al database
$mysqli->close();
?>

 
