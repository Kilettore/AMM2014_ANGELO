<?php
    include_once 'ViewDescriptor.php';
    
    if (!$vd->isJson()) 
    {
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title><?= $vd->getTitle() ?></title>
    
    <link rel="stylesheet" type="text/css" href="../../style.css">
   
    
        
        
        
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
    <div id="container_logo">
        <div id="logo">
            <?php
                $logo = $vd->getLogoFile();
                require "$logo";
            ?>
        </div>
            <?php
                 $loginContent = $vd->getLoginContent();
                 require "$loginContent";
            ?>
    </div>
    
    <div id="navbar">
	<?php
            $menu = $vd->getMenuFile();
            require "$menu";
        ?>
    </div>
    <div id="contenuto_main">
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

