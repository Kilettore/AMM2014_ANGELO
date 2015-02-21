<div class="add_form">
    <form action="index.php" method="post">
		
        <table>
        <tr><td><label for="nome">Nome</label></td>
    	<td><input class="mod" type="text" name="nome"></td>
            </tr>
        
        <tr><td>
                <label for="tipologia">Tipologia</label>
            </td>
            <td>
                <!-- Volevo implementare l' autofocus ma essedo una funzione introdotta in html 5 ho lasciato stare,
                     La versione di chrome presente nella macchina virtuale non è aggiornata,
                     firefox non è proprio compatibile -->
                <select id="tipologia" name="tipologia">
                    <option value="laptop">Laptop</option>
                    <option value="desktop">Desktop</option>
                    <option value="tablet">Tablet</option>
                    <option value="smartphone">Smartphone</option>
                </select>
            </td>
        </tr>
        
        <tr><td><label for="schermo">Schermo</label></td>
    	<td><input class="mod" type="text" name="schermo"></td>
        </tr>
        
        <tr><td><label for="ram">Ram</label></td>
    	<td><input class="mod" type="text" name="ram"></td>
        </tr>
        
        <tr><td><label for="cpu">Cpu</label></td>
    	<td><input class="mod" type="text" name="cpu"></td>
        </tr>
        
        <tr><td><label for="hard_disk">Hard Disk</label></td>
    	<td><input class="mod" type="text" name="hard_disk"></td>
        </tr>
        
        <tr><td><label for="os">Sistema Operativo</label></td>
    	<td><input class="mod" type="text" name="os"></td>
        </tr>
        
        <tr><td><label for="descrizione">Descrizione</label></td>
    	<td><textarea id="descrizione" name="descrizione"></textarea></td>
        </tr>
        
        <tr><td><label for="art_disponibili">Articoli Disponibili</label></td>
    	<td><input class="mod" type="text" name="art_disponibili"></td>
        </tr>
        
        <tr><td><label for="prezzo">Prezzo</label></td>
    	<td><input class="mod" type="text" name="prezzo"></td>
        </tr>
        
        <tr><td>
            </td>
            <td>
                <button class="update_button" type="submit" name="subpage" value="added">Aggiungi al Database</button>
            </td>
        </tr>
        </table>
    </form>
</div>

