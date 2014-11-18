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
	// Collegamento al database riuscito
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
                        <tr><td><b>Nome:</b> $row->nome </td></tr>
                        <tr><td><b>Tipo:</b> $row->tipologia </td><td><b>Schermo:</b> $row->schermo </td></tr>
                        <tr><td><b>Ram:</b> $row->ram </td></tr>
                        <tr><td><b>Cpu:</b> $row->cpu </td></tr>
                        <tr><td><b>Hard Disk:</b> $row->hard_disk </td></tr>
                        <tr><td><b>Sistema Operativo:</b> $row->os </td></tr>
                        <tr><td><b>Descrizione:</b> $row->descrizione </td></tr>
                        <tr><td><b>Quantità disponibile:</b> $row->art_disponibili </td></tr>
                        <tr><td><b>Prezzo:</b> $row->prezzo </td></tr>
                        </table>
                        \n\n";
                }
	}
}

//Chiudo la connessione al database
$mysqli->close();
?>

 
