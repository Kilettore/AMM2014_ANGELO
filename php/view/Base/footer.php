<span class="footer_text">Computer Shop - All Rights Reserved</span>
<span id="date_time"></span>
<script>
var data_odierna = new Date();
var giorno = data_odierna.getDate();
var mese = data_odierna.getMonth()+1; //January is 0!
var anno = data_odierna.getFullYear();

// Test che aggiunge '0' ai giorni a singolo carattere numerico
if(giorno < 10) 
{
    giorno = '0'+ giorno
} 

// Stesso test per il mese
if(mese < 10) 
{
    mese ='0'+ mese
} 

data_odierna = mese+'/'+giorno+'/'+anno;
//document.write(data_odierna);
document.getElementById('date_time').innerHTML = data_odierna;
</script>
