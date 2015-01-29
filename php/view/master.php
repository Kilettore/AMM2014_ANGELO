<?php
    echo ("sono dentro MASTER<br>");
    include_once 'ViewDescriptor.php';
    include_once basename(__DIR__) . '/../Impostazioni.php';
    
    if (!$vd->isJson()) 
    {
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?= $vd->getTitle() ?></title>
    <base href="<?= Impostazioni::getApplicationPath() ?>php/"/>
    
    <link rel="stylesheet" type="text/css" href="style.css">
   
    
        
        
        
    <?php
        /*foreach ($vd->getScripts() as $script) 
        {
    ?>
            <script type="text/javascript" src="<?= $script ?>"></script>
    <?php
        }*/
    ?>
            
    
    
</head>
<body>
    <!-- Il div main è quello che contiene tutti gli altri div della pagina -->
<div id="main">
    <div id="logo">
        <?php
            $logo = $vd->getLogoFile();
            require "$logo";
        ?>
	<div id="login">
            <?php
                $loginContent = $vd->getLoginContent();
                require "$loginContent";
            ?>
	</div>
    </div>
    
    <div id="navbar"><ul>
		<ul>
		<li class="pagina_corrente">
        		<a href="#">Home Page</a>
    		</li>
    		<li>
        		<a href="lol.html">Chi siamo</a>
    		</li>
    		<li>
        		<a href="lol.html">Perchè</a>
    		</li>
    		<li>
        		<a href="lol.html">Lel</a>
    		</li>
		</ul>
	</div>
	<div id="leftbar">
            <?php
                $left = $vd->getLeftBarFile();
                require "$left";
            ?>
        </div>
	<div id="content">
	<?php
		$mainContent = $vd->getContentFile();
                require "$mainContent";
	?>
	</div>
</div>
</body>
</html>

<?php
    } 
    else 
    {
        header('Cache-Control: no-cache, must-revalidate');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-type: application/json');
    
        $content = $vd->getContentFile();
        require "$content";
    }
?>

