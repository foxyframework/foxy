<?php
if(!isset($_GET['e'])) { $code = http_response_code(); } else { $code = $_GET['e']; }
?>
<!DOCTYPE html>
<html>

<head>

<meta charset="UTF-8">

<link type="text/css" rel="stylesheet" href="template/system/css/error.css" />  

<title><?= $code; ?> Error Page</title>

</head>

<body translate="no" >
    <div id="clouds">
        <div class="cloud x1"></div>
        <div class="cloud x1_5"></div>
        <div class="cloud x2"></div>
        <div class="cloud x3"></div>
        <div class="cloud x4"></div>
        <div class="cloud x5"></div>
    </div>
    <div class='c'>
        <div class='_404'><?= $code; ?></div>
        <hr>
        <div class='_1'>THE PAGE</div>
        <div class='_2'>WAS NOT FOUND</div>
        <a class='btn' href='index.php'>BACK TO EARTH</a>
    </div> 

</body>
</html>
 
