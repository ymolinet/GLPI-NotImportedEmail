<?php 

class PluginNotimportedemailUser extends CommonDBTM {

    function showForm($id, $options=array()) {
        if (!defined('GLPI_ROOT')) {
           define('GLPI_ROOT', '../../..');
        }
        include_once (GLPI_ROOT . "/inc/includes.php");
        $target = $this->getFormURL();
        if (isset($options['target'])) {
        $target = $options['target'];
        }
        global $DB;
        echo "<form action=\"$target\" method='post'>";
        echo "<table class='tab_cadre_fixe'>";
        echo "<tr><th colspan='2' class='center b'>Notifier de l'erreur de collecteur : ";
        echo "</th></tr>";
        echo "<tr class='tab_bg_2'>";
        echo "<td>Notifications</td><td>";
        $result = $data = array();
        $query = "SELECT notification FROM glpi_plugin_notimportedemail_users WHERE users_id = ".$id;
        $result = $DB->query($query) or die("Erreur lors de la lecture de la table <strong>profiles</strong> pour NotImportedEmail : ". $DB->error());
        $data = $DB->fetch_assoc($result);
        echo "<select name='notification' id='notification'>";
        echo "<option value='0'";
        if($data['notification']=='0') echo ' selected="selected"';
        echo ">Non</option>";
        echo "<option value='1'";
        if($data['notification']=='1') echo ' selected="selected"';
        echo ">Oui</option>";
        echo "</select>"; 
        echo "</td></tr>";
        echo "<tr class='tab_bg_1'>";	
        echo "<td class='center' colspan='2'>";
        echo "<input type='hidden' name='id' value=$id>";
        echo "<input type='submit' value='Envoyer' class='submit' name='à jour' >";
        echo "</td></tr>";

        echo "</table>";

        Html::closeForm();
   }
   
      function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if ($item->getType() == 'User') {
         return "Email non importé";
      }
      return '';
     }
      static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getType() == 'User') {
         $me = new self();
         $ID = $item->getField('id');
         $me->showForm($ID);
      }
      return true;
	}
	


	
	
  }
  
?>