<?php
/**
 * @version     1.0.0 Foxy Framework $
 * @package     Foxy Framework
 * @copyright   Copyright © 2014 - All rights reserved.
 * @license	    GNU/GPL
 * @author	    kim
 * @author mail kim@afi.cat
 * @website	    http://www.afi.cat
 *
*/

defined('_Foxy') or die ('restricted access');

?>

<section class="forms">
 <div class="container-fluid">
   <div class="row">

     <div class="col-lg-12 my-3">

       <div class="card">
         <div class="card-header d-flex align-items-center">
           <h4>API</h4>
         </div>
         <div class="card-body">
           <h3>API Rest</h3>
           <p>AfiFramework té una API Rest que et permet obtenir llistes de resultats conjunts o individuals, més abaix tens algun exemple preparat. Per fer servir l'api necessites una apikey que pots generar en el teu <a href="index.php?view=profile">perfil</a>.</p>
           <hr>
           <h3>Exemples</h3>
           <p>Recuperar la llista d'expedients.</p>
           <pre>http://afigest.aficat.com/index.php?task=api.getItems&apikey=[YOUR_APIKEY]&mode=raw</pre>
           <p>Recuperar un expedient per la seva ID.</p>
           <pre>http://afigest.aficat.com/index.php?task=api.getItemsById&apikey=[YOUR_APIKEY]&id=[ID]&mode=raw</pre>
         </div>
       </div>

    </div>
   </div>
 </div>
</section>
