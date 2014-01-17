<?php

if(!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', '../..');
}
include (GLPI_ROOT."/inc/includes.php");

Html::header("NotImportedEmail",$_SERVER["PHP_SELF"], "plugins",
             "notimportedemail");

Html::redirect(GLPI_ROOT ."/front/central.php");
Html::footer();

?>