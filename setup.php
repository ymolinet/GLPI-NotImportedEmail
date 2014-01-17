<?php
function plugin_version_notimportedemail() {

   return array('name'           => 'Not Imported Email',
                'version'        => '0.84+1.0',
                'author'         => 'Dixinfor',
              //  'license'        => 'GPLv2+',
                'homepage'       => 'http://www.dixinfor.com/',// OR 'https://forge.indepnet.net/repositories/show/dixinfor' if open-source,
                'minGlpiVersion' => '0.84');// For compatibility / no install in version < 0.80

}
function plugin_notimportedemail_check_prerequisites() {

   if (version_compare(GLPI_VERSION,'0.83','lt') || version_compare(GLPI_VERSION,'0.85','gt')) {
      echo "This plugin requires GLPI >= 0.83 and GLPI < 0.85";
      return false;
   }
   return true;
}


function plugin_notimportedemail_check_config() {
      return true;
}

function plugin_init_notimportedemail() {
   global $PLUGIN_HOOKS, $CFG_GLPI;
    $Plugin = new Plugin();

    $PLUGIN_HOOKS['item_add']['notimportedemail'] = array('NotImportedEmail' => 'plugin_item_add_notimportedemail_notimportedemail');
    
    $PLUGIN_HOOKS['csrf_compliant']['notimportedemail'] = true;
    Plugin::registerClass('PluginNotimportedemailUser', array('addtabon' => array('User')));

}

function plugin_item_add_notimportedemail_notimportedemail($parm) {
    global $DB ; 
    
    if($parm->getField("reason") == 1) {
        $contenu = "<html style='font-family:Arial;font-size:14px;'><body><p>Bonjour, <br /><br />GLPI n'a pu traiter une demande reçu par email, voici les informations : <br /><br />
                                <ul><li>Raison du refus : Courriel absent. Import impossible ; </li>
                                    <li>Email du courriel : ".$parm->getField("from")." ; </li>
                                    <li>Sujet du mail : ".$parm->getField("subject")." ; </li>
                                    <li>Collecteur concerné : ".$parm->getField("to")." ; </li>
                                    <li>Date de réception : ".$parm->getField("date").".</li></ul><br />" ; 
        
        $entity = new Entity ; 
        $entity->getFromDB("0") ;
        $contenu .= nl2br($entity->getField("mailing_signature"))."</p></body></html>"  ; 
        
        $sujet = htmlspecialchars_decode(utf8_decode("[DIXINFOR] Erreur de traitement d'un email reçu")) ; 
        
        //-----------------------------------------------
        //GENERE LA FRONTIERE DU MAIL ENTRE TEXTE ET HTML
        //-----------------------------------------------

        $frontiere = '-----=' . md5(uniqid(mt_rand()));

        //-----------------------------------------------
        //HEADERS DU MAIL
        //-----------------------------------------------

        $headers = 'From: "DIXINFOR " <support@dixinfor.com>'."\n";
        $headers .= 'MIME-Version: 1.0'."\n";
        $headers .= 'Content-Type: multipart/alternative; boundary="'.$frontiere.'"';

        //-----------------------------------------------
        //MESSAGE HTML
        //-----------------------------------------------
        $message = '' ;
        $message .= '--'.$frontiere."\n";

        $message .= 'Content-Type: text/html; charset="iso-8859-1"'."\n";
        $message .= 'Content-Transfer-Encoding: 8bit'."\n\n";
        $message .= htmlspecialchars_decode(utf8_decode($contenu))."\n\n";

        $message .= '--'.$frontiere."\n";


        $query_select_contact = "SELECT users_id FROM glpi_plugin_collecteur_users 
        WHERE  notification = '1'" ; 
        foreach ($DB->request($query_select_contact) as $data) { 
            $user = new User ; 
            $user->getFromDB($data['users_id']) ; 
            mail($user->getDefaultEmail(),$sujet,$message, $headers) ;
        }
    }
    return true ; 
}

 ?>