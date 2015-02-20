<span class="footer_text">Computer Shop - All Rights Reserved</span>
<span id="date_time"></span>

<script>
display_date_time();
function display_date_time()
{
    var data_ora = new Date();
    var giorno = data_ora.getDate();
    var mese = data_ora.getMonth() + 1; //Gennaio è 0, quindi incremento di 1
    var anno = data_ora.getFullYear();

    var secondi = data_ora.getSeconds();
    var minuti = data_ora.getMinutes();
    var ore = data_ora.getHours();

    // Test che aggiunge '0' ai giorni a singolo carattere numerico (vale anche per tutti i test successivi)
    if(giorno < 10) 
    {
        giorno = '0'+ giorno
    } 

    if(mese < 10) 
    {
        mese ='0'+ mese
    }

    if(secondi < 10) 
    {
        secondi ='0'+ secondi
    }

    if(minuti < 10) 
    {
        minuti ='0'+ minuti
    }

    if(ore < 10) 
    {
        ore ='0'+ ore
    }

    data_ora = giorno+'/'+mese+'/'+anno+' - '+ore+':'+minuti+':'+secondi;
    //document.write(data_odierna);
    document.getElementById('date_time').innerHTML = data_ora;
    
    display_refresh();
}

function display_refresh()
{
    var refresh = 1000; //Tempo di refresh in millisecondi
    mytime = setTimeout('display_date_time()',refresh);
}
</script>
