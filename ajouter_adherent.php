<? // -*- Mode: PHP; tab-width: 2; indent-tabs-mode: nil; c-basic-offset: 4 -*-
/* ajouter_adherent.php
 * - Saisie d'un adh�rent
 * Copyright (c) 2004 Fr�d�ric Jaqcuot
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 */
 
	include("includes/config.inc.php");
	include(WEB_ROOT."includes/database.inc.php"); 
	include(WEB_ROOT."includes/functions.inc.php"); 
        include_once("includes/i18n.inc.php"); 
	include(WEB_ROOT."includes/session.inc.php"); 
        include(WEB_ROOT."includes/categories.inc.php");

        
	if ($_SESSION["logged_status"]==0) 
		header("location: index.php");
		
	// On v�rifie si on a une r�f�rence => modif ou cr�ation
	$id_adh = "";
	$date_crea_adh = "";
	if (isset($_GET["id_adh"]))
		if (is_numeric($_GET["id_adh"]))
			$id_adh = $_GET["id_adh"];
	if (isset($_POST["id_adh"]))
		if (is_numeric($_POST["id_adh"]))
			$id_adh = $_POST["id_adh"];

	// Si c'est un user qui est logg�, on va � sa fiche
	if ($_SESSION["admin_status"]!=1) 
		$id_adh = $_SESSION["logged_id_adh"];

	$req = "SELECT pref_lang FROM ".PREFIX_DB."adherents
			WHERE id_adh=".$id_adh;
        $pref_lang = &$DB->Execute($req);
        $pref_lang = $pref_lang->fields[0];
	include(WEB_ROOT."includes/lang.inc.php"); 
	// variables d'erreur (pour affichage)	    
 	$error_detected = "";
 	$warning_detected = "";
 	$confirm_detected = "";

	 //
	// DEBUT parametrage des champs
	//  On recupere de la base la longueur et les flags des champs
	//   et on initialise des valeurs par defaut
    
	// recuperation de la liste de champs de la table
	$fields = &$DB->MetaColumns(PREFIX_DB."adherents");
	while (list($champ, $proprietes) = each($fields))
	{
		$proprietes_arr = get_object_vars($proprietes);
		// on obtient name, max_length, type, not_null, has_default, primary_key,
		// auto_increment et binary		
		
		$fieldname = $proprietes_arr["name"];
				
		// on ne met jamais a jour id_adh
		if ($fieldname!="id_adh" && $fieldname!="date_echeance")
			$$fieldname= "";
			
	  $fieldlen = $fieldname."_len";
	  $fieldreq = $fieldname."_req";

	  // definissons  aussi la longueur des input text
	  $max_tmp = $proprietes_arr["max_length"];
	  if ($max_tmp == "-1")
	  	$max_tmp = 10;
	  $fieldlen = $fieldname."_len";
	  $$fieldlen=$max_tmp;

	  // et s'ils sont obligatoires (� partir de la base)
	  if ($proprietes_arr["not_null"]==1)
		  $$fieldreq = "style=\"color: #FF0000;\"";
		else
		  $$fieldreq = "";
	}
	reset($fields);

	// et les valeurs par defaut
	$id_statut = "4";
	$titre_adh = "1";

	  //
	 // FIN parametrage des champs
	// 	    	    
    
    //
   // Validation du formulaire
  //
  
  if (isset($_POST["valid"]))
  {
        if(!UniqueLogin($DB,$_POST["login_adh"])){
	  if (isset($_POST["id_adh"]) && $_POST["id_adh"] != ""){
	    // on v�rifie que le login n'est pas d�j� utilis�
	    $requete = "SELECT id_adh FROM ".
	      PREFIX_DB."adherents WHERE login_adh=". 
	      $DB->qstr($_POST["login_adh"], true).
	      " AND id_adh!=" . $DB->qstr($id_adh, true);
	    $result = &$DB->Execute($requete);
	    if (!$result->EOF || $_POST["login_adh"]==PREF_ADMIN_LOGIN){
	      $error_detected .= "<LI>"._T("- This username is already used by another member !")."</LI>";
	    } 
          } else {
	    $error_detected .=_T("Sorry, ").$_POST["login_adh"]._T(" is a username already used by another member, please select another one\n");
          }
	}
  	// verification de champs
  	$update_string = "";
  	$insert_string_fields = "";
  	$insert_string_values = "";
  
  	// recuperation de la liste de champs de la table
	  while (list($champ, $proprietes) = each($fields))
		{
			$proprietes_arr = get_object_vars($proprietes);
			// on obtient name, max_length, type, not_null, has_default, primary_key,
			// auto_increment et binary		
		
			$fieldname = $proprietes_arr["name"];
			
			// on pr�cise les champs non modifiables
			if (
			        ($_POST["self_adherent"]==1) || //!!!faudra restreindre plus !
				($_SESSION["admin_status"]==1 && $fieldname!="id_adh"
							      && $fieldname!="date_echeance") ||
			    	($_SESSION["admin_status"]==0 && $fieldname!="date_crea_adh"
			    				      && $fieldname!="id_adh"
			    				      && $fieldname!="titre_adh"
			    				      && $fieldname!="id_statut"
			    				      && $fieldname!="nom_adh"
			    				      && $fieldname!="prenom_adh"
			    				      && $fieldname!="activite_adh"
			    				      && $fieldname!="bool_exempt_adh"
			    				      && $fieldname!="bool_admin_adh"
			    				      && $fieldname!="date_echeance"
			    				      && $fieldname!="info_adh")
			   )
			{			
				if (isset($_POST[$fieldname]))
				  $post_value=trim($_POST[$fieldname]);
				else			
					$post_value="";
					
				// on declare les variables pour la pr�saisie en cas d'erreur
				$$fieldname = htmlentities(stripslashes($post_value),ENT_QUOTES);
				$fieldreq = $fieldname."_req";
		
				// v�rification de la pr�sence des champs obligatoires
				if ($$fieldreq!="" && $post_value=="")
				  $error_detected .= "<LI>"._T("- Mandatory field empty.")."</LI>";
				else
				{
					// validation des dates				
					if($proprietes_arr["type"]=="date" && $post_value!="")
					{
					  	if (ereg("^([0-9]{2})/([0-9]{2})/([0-9]{4})$", $post_value, $array_jours) || $post_value=="")
					  	{
							if (checkdate($array_jours[2],$array_jours[1],$array_jours[3]) || $post_value=="")
								$value="'".$array_jours[3]."-".$array_jours[2]."-".$array_jours[1]."'";
							else
								$error_detected .= "<LI>"._T("- Non valid date!")."</LI>";
						}
					  	else
					  		$error_detected .= "<LI>"._T("- Wrong date format (dd/mm/yyyy)!")."</LI>";
					}
 					elseif ($fieldname=="email_adh")
 					{
 						$post_value=strtolower($post_value);
						if (!is_valid_email($post_value) && $post_value!="")
					  	$error_detected .= "<LI>"._T("- Non-valid E-Mail address!")."</LI>";
						else
		 					$value = $DB->qstr($post_value, true);
		 					
		 				if ($post_value=="" && isset($_POST["mail_confirm"]))
		 					$error_detected .= "<LI>"._T("- You can't send a confirmation by email if the member hasn't got an address!")."</LI>";
					}
 					elseif ($fieldname=="url_adh")
 					{
 						if (!is_valid_web_url($post_value) && $post_value!="" && $post_value!="http://")
					  	$error_detected .= "<LI>"._T("- Non-valid Website address! Maybe you've skipped the http:// ?")."</LI>";
						else
						{
							if ($post_value=="http://")
								$post_value="";
		 					$value = $DB->qstr($post_value, true);
						}
					}
 					elseif ($fieldname=="login_adh")
 					{
 						if (strlen($post_value)<4)
 							$error_detected .= "<LI>"._T("- The username must be composed of at least 4 characters!")."</LI>";
 						else
 						{
 							// on v�rifie que le login n'est pas d�j� utilis�
 							$requete = "SELECT id_adh
 								    FROM ".PREFIX_DB."adherents
 								    WHERE login_adh=". $DB->qstr($post_value, true);
 							if ($id_adh!="")
 								$requete .= " AND id_adh!=" . $DB->qstr($id_adh, true);

 							$result = &$DB->Execute($requete);
							if (!$result->EOF || $post_value==PREF_ADMIN_LOGIN)
	 							$error_detected .= "<LI>"._T("- This username is already used by another member !")."</LI>";
							else
	 							$value = $DB->qstr($post_value, true);
						}
 					}
 					elseif ($fieldname=="mdp_adh")
 					{
 						if (strlen($post_value)<4)
 							$error_detected .= "<LI>"._T("- The password must be of at least 4 characters!")."</LI>";
 						else
 							$value = $DB->qstr($post_value, true);
 					}
 					else
 					{
 						// on se contente d'escaper le html et les caracteres speciaux
							$value = $DB->qstr($post_value, true);
					}

					// mise � jour des chaines d'insertion/update
					if ($value=="''")
						$value="NULL";
					$update_string .= ",".$fieldname."=".$value;
					$insert_string_fields .= ",".$fieldname;
					$insert_string_values .= ",".$value;		
				}
			}
		}
		reset($fields);
                                
  	// modif ou ajout
  	if ($error_detected=="")
  	{  	
 		 	if ($id_adh!="")
 		 	{
 		 		// modif
 		 		
				$requete = "UPDATE ".PREFIX_DB."adherents
 		 			    SET " . substr($update_string,1) . " 
 		 			    WHERE id_adh=" . $id_adh;
				$DB->Execute($requete);
				dblog(_T("Member card update:")." ".strtoupper($_POST["nom_adh"])." ".$_POST["prenom_adh"], $requete);

				$date_fin = get_echeance($DB, $id_adh);
				if ($date_fin!="")
					$date_fin_update = $DB->DBDate(mktime(0,0,0,$date_fin[1],$date_fin[0],$date_fin[2]));
				else
					$date_fin_update = "NULL";
				$requete = "UPDATE ".PREFIX_DB."adherents
					    SET date_echeance=".$date_fin_update."
					    WHERE id_adh=" . $id_adh;
  			}
 		 	else
 		 	{
  			// ajout
 			$insert_string_fields = substr($insert_string_fields,1);
			$insert_string_values = substr($insert_string_values,1);
  			$requete = "INSERT INTO ".PREFIX_DB."adherents
  				    (" . $insert_string_fields . ") 
  				    VALUES (" . $insert_string_values . ")";
			dblog(_T("Member card added:")." ".strtoupper($_POST["nom_adh"])." ".$_POST["prenom_adh"], $requete);
  							
  		}
			$DB->Execute($requete);
			
			// il est temps d'envoyer un mail
			if (isset($_POST["mail_confirm"]))
				if ($_POST["mail_confirm"]=="1")
					if ($email_adh!="")
					{
						$mail_subject = _T("Your Galette identifiers");
						$mail_text =  _T("Hello,")."\n";
						$mail_text .= "\n";
						$mail_text .= _T("You've just been subscribed on the members management system of the association.")."\n";
						$mail_text .= _T("It is now possible to follow in real time the state of your subscription")."\n";
						$mail_text .= _T("and to update your preferences from the web interface.")."\n";
						$mail_text .= "\n";
						$mail_text .= _T("Please login at this address:")."\n";
						$mail_text .= "http://".$_SERVER["SERVER_NAME"].dirname($_SERVER["REQUEST_URI"])."\n";
						$mail_text .= "\n";
						$mail_text .= _T("Username:")." ".custom_html_entity_decode($login_adh)."\n";
						$mail_text .= _T("Password:")." ".custom_html_entity_decode($mdp_adh)."\n";
						$mail_text .= "\n";
						$mail_text .= _T("See you soon!")."\n";
						$mail_text .= "\n";
						$mail_text .= _T("(this mail was sent automatically)")."\n";
						$mail_headers = "From: ".PREF_EMAIL_NOM." <".PREF_EMAIL.">\nContent-Type: text/plain; charset=iso-8859-15\n";
						mail ($email_adh,$mail_subject,$mail_text, $mail_headers);
					}
				
			// r�cup�ration du max pour insertion photo
			// ou passage en mode modif apres insertion
			if ($id_adh=="")
			{
				$requete = "SELECT max(id_adh)
										AS max
										FROM ".PREFIX_DB."adherents";
				$max = &$DB->Execute($requete);
				$id_adh_new = $max->fields["max"];
			}	
			else
				$id_adh_new = $id_adh;
			
			if (isset($_FILES["photo"]["tmp_name"]))
                        if ($_FILES["photo"]["tmp_name"]!="none" &&
                            $_FILES["photo"]["tmp_name"]!="") 
			{ 

				if ($_FILES['photo']['type']=="image/jpeg" || 
				    (function_exists("ImageCreateFromGif") && $_FILES['photo']['type']=="image/gif") || 
				    $_FILES['photo']['type']=="image/png" ||
				    $_FILES['photo']['type']=="image/x-png")
				{
					$tmp_name = $HTTP_POST_FILES["photo"]["tmp_name"];
						
					// extension du fichier (en fonction du type mime)
					if ($_FILES['photo']['type']=="image/jpeg")
						$ext_image = ".jpg";
					if ($_FILES['photo']['type']=="image/png" || $_FILES['photo']['type']=="image/x-png")
						$ext_image = ".png";
					if ($_FILES['photo']['type']=="image/gif")
						$ext_image = ".gif";
						
					// suppression ancienne photo
					// NB : une verification sur le type de $id_adh permet d'eviter une faille
					//      du style $id_adh = "../../../image"
					@unlink(WEB_ROOT . "photos/".$id_adh_new.".jpg");
					@unlink(WEB_ROOT . "photos/".$id_adh_new.".gif");
					@unlink(WEB_ROOT . "photos/".$id_adh_new.".jpg");
					@unlink(WEB_ROOT . "photos/tn_".$id_adh_new.".jpg");
					@unlink(WEB_ROOT . "photos/tn_".$id_adh_new.".gif");
					@unlink(WEB_ROOT . "photos/tn_".$id_adh_new.".jpg");
						
					// copie fichier temporaire			 		
					if (!@move_uploaded_file($tmp_name,WEB_ROOT . "photos/".$id_adh_new.$ext_image))
						$warning_detected .= "<LI>"._T("- The photo seems not to be transferred correctly. But registration has been made.")."</LI>";
				 	else
						resizeimage(WEB_ROOT . "photos/".$id_adh_new.$ext_image,WEB_ROOT . "photos/tn_".$id_adh_new.$ext_image,130,130);
			 	}
			 	else
				{
					if (function_exists("imagegif"))
			 			$warning_detected .= "<LI>"._T("- The transfered file isn't a valid image (GIF, PNG or JPEG). But registration has been made.")."</LI>"; 
					else
			 			$warning_detected .= "<LI>"._T("- The transfered file isn't a valid image (PNG or JPEG). But registration has been made.")."</LI>"; 
				}
			}
                               
                        // Ajout des champs dynamiques
                        $requete = "SELECT id_cat, index_cat, name_cat, perm_cat, type_cat, size_cat FROM $info_cat_table";
                        if ($_SESSION["admin_status"] != 1)
                            $requete .= " WHERE perm_cat=$perm_all";
                        $res_cat = $DB->Execute($requete);
                        while (!$res_cat->EOF) {
                            $id_cat = $res_cat->fields[0];
                            $name_cat = $res_cat->fields[1];
                            $perm_cat = $res_cat->fields[2];
                            $type_cat = $res_cat->fields[3];
                            $size_cat = $res_cat->fields[4];
                            if ($_SESSION["admin_status"] != 1 && $perm_cat == $perm_admin)
                                continue;
                            $current_size = $_POST["info_field_size_$id_cat"];
                            $ins_idx =1;
                            for ($i = 0; $i < $current_size; ++$i) {
                                $field_name = "info_field_".$id_cat."_".$i;
                                $val = "";
                                if (isset($_POST[$field_name]))
                                    $val = $_POST[$field_name];
                                set_adh_info($DB, $id_adh_new, $id_cat, $ins_idx, $val);
                                if ($val != "")
                                    ++$ins_idx;
                            }
                            while($ins_idx <= $current_size) {
                                set_adh_info($DB, $id_adh_new, $id_cat, $ins_idx, "");
                                ++$ins_idx;
                            }
                            $res_cat->MoveNext();
                        }
                        $res_cat->Close();
			
			// retour � la liste ou passage � la contribution
			if ($warning_detected=="" && $id_adh=="")
			{
				header("location: ajouter_contribution.php?id_adh=".$id_adh_new);
				die();
			}
			elseif ($warning_detected=="" && !isset($_FILES["photo"]))
			{
				header("location: gestion_adherents.php");
				die();
			}
			elseif ($warning_detected=="" && ($_FILES["photo"]["tmp_name"]=="none" || $_FILES["photo"]["tmp_name"]==""))
			{
				header("location: gestion_adherents.php");
				die();
			}
			$id_adh=$id_adh_new;
  	    }
      
      }
  
 	// suppression photo
	if (isset($_POST["del_photo"]))
	{
 		@unlink(WEB_ROOT . "photos/" . $id_adh . ".jpg");
 		@unlink(WEB_ROOT . "photos/" . $id_adh . ".png");
 		@unlink(WEB_ROOT . "photos/" . $id_adh . ".gif");
 		@unlink(WEB_ROOT . "photos/tn_" . $id_adh . ".jpg");
 		@unlink(WEB_ROOT . "photos/tn_" . $id_adh . ".png");
 		@unlink(WEB_ROOT . "photos/tn_" . $id_adh . ".gif");
 	} 	
	
	  //	
	 // Pr�-remplissage des champs
	//  avec des valeurs issues de la base
	//  -> donc uniquement si l'enregistrement existe et que le formulaire
	//     n'a pas d�ja �t� post� avec des erreurs (pour pouvoir corriger)
	
	if (!isset($_POST["valid"]) || (isset($_POST["valid"]) && $error_detected==""))
	if ($id_adh != "")


	{
		// recup des donn�es
		$requete = "SELECT * 
								FROM ".PREFIX_DB."adherents 
			  				WHERE id_adh=$id_adh";
		$result = &$DB->Execute($requete);
        	if ($result->EOF)
	                header("location: index.php");
			                                                                                                                    
																	    
			
		// recuperation de la liste de champs de la table
	  //$fields = &$DB->MetaColumns(PREFIX_DB."cotisations");
	  while (list($champ, $proprietes) = each($fields))
		{
			//echo $proprietes_arr["name"]." -- (".$result->fields[$proprietes_arr["name"]].")<br>";


			$val="";
			$proprietes_arr = get_object_vars($proprietes);
			// on obtient name, max_length, type, not_null, has_default, primary_key,
			// auto_increment et binary		
		
		  // d�claration des variables correspondant aux champs
		  // et reformatage des dates.
			
			// on doit faire cette verif pour une enventuelle valeur "NULL"
			// non renvoy�e -> ex: pas de societe membre
			// sinon on obtient un warning
			if (isset($result->fields[$proprietes_arr["name"]]))
				$val = $result->fields[$proprietes_arr["name"]];

			if($proprietes_arr["type"]=="date" && $val!="")
			{
			  list($a,$m,$j)=split("-",$val);
			  $val="$j/$m/$a";
			}
		  	$$proprietes_arr["name"]=htmlentities(stripslashes(addslashes($val)), ENT_QUOTES);
		}
		reset($fields);
	}
	else
	{
		// initialisation des champs
			
	}

	// la date de creation de fiche, ici vide si nouvelle fiche
	if ($date_crea_adh=="")
		$date_crea_adh = date("d/m/Y");
	if ($url_adh=="")
		$url_adh = "http://";
	if ($mdp_adh=="")
		$mdp_adh = makeRandomPassword(7);

	// variable pour la desactivation de champs		
	if ($_SESSION["admin_status"]==0)
		$disabled_field = "disabled";
	else
		$disabled_field = "";


	include("header.php");

?> 
 
						<H1 class="titre"><? echo _T("Member Profile"); ?> (<? if ($id_adh!="") echo _T("modification"); else echo _T("creation"); ?>)</H1>
						<FORM action="ajouter_adherent.php" method="post" enctype="multipart/form-data" name="form"> 
						
<?
	// Affichage des erreurs
	if ($error_detected!="")
	{
?>
  	<DIV id="errorbox">
  		<H1><? echo _T("- ERROR -"); ?></H1>
  		<UL>
  			<? echo $error_detected; ?>
  		</UL>
  	</DIV>
<?
	}
	if ($warning_detected!="")
	{
?>
	<DIV id="warningbox">
  		<H1><? echo _T("- WARNING -"); ?></H1>
  		<UL>
  			<? echo $warning_detected; ?>
  		</UL>
  	</DIV>
<?
	}
?>						
						<BLOCKQUOTE>
						<DIV align="center">
						<TABLE border="0" id="input-table"> 
							<TR> 
								<TH <? echo $titre_adh_req ?> id="libelle"><? echo _T("Title:"); ?></TH> 
								<TD colspan="3">
									<INPUT type="radio" name="titre_adh" value="3"<? isChecked($titre_adh,"3") ?> <? echo $disabled_field; ?>> <? echo _T("Miss"); ?>&nbsp;&nbsp;
									<INPUT type="radio" name="titre_adh" value="2"<? isChecked($titre_adh,"2") ?> <? echo $disabled_field; ?>> <? echo _T("Mrs"); ?>&nbsp;&nbsp;
									<INPUT type="radio" name="titre_adh" value="1"<? isChecked($titre_adh,"1") ?> <? echo $disabled_field; ?>> <? echo _T("Mister"); ?>&nbsp;&nbsp;
								</TD> 
						  </TR> 
							<TR> 
								<TH <? echo $nom_adh_req ?> id="libelle"><? echo _T("Name:"); ?></TH> 
								<TD><INPUT type="text" name="nom_adh" value="<? echo $nom_adh; ?>" maxlength="<? echo $nom_adh_len; ?>" <? echo $disabled_field; ?>></TD> 
								<TD colspan="2" rowspan="4" align="center" width="130">
<?
	$image_adh = "";
	if (file_exists(WEB_ROOT . "photos/tn_" . $id_adh . ".jpg"))
		$image_adh = WEB_ROOT . "photos/tn_" . $id_adh . ".jpg";
	elseif (file_exists(WEB_ROOT . "photos/tn_" . $id_adh . ".gif"))
		$image_adh = WEB_ROOT . "photos/tn_" . $id_adh . ".gif";
	elseif (file_exists(WEB_ROOT . "photos/tn_" . $id_adh . ".png"))
		$image_adh = WEB_ROOT . "photos/tn_" . $id_adh . ".png";

    if (function_exists("ImageCreateFromString"))
    {
        if ($image_adh != "")
            $imagedata = getimagesize($image_adh);
        else
            $imagedata = getimagesize(WEB_ROOT . "photos/default.png");
    }
    else
        $imagedata = array("130","");
?>
									<IMG src="photo.php?tn=1&id_adh=<? echo $id_adh."&nocache=".time(); ?>" border="1" alt="<? echo _T("Picture"); ?>" width="<? echo $imagedata[0]; ?>" height="<? echo $imagedata[1]; ?>">
								</TD>
						  </TR>
						  <TR>
								<TH <? echo $prenom_adh_req ?> id="libelle"><? echo _T("First name:"); ?></TH> 
								<TD><INPUT type="text" name="prenom_adh" value="<? echo $prenom_adh; ?>" maxlength="<? echo $prenom_adh_len; ?>" <? echo $disabled_field; ?>></TD> 
							</TR>						   
							<TR> 
								<TH <? echo $pseudo_adh_req ?> id="libelle"><? echo _T("Nickname:"); ?></TH> 
								<TD><INPUT type="text" name="pseudo_adh" value="<? echo $pseudo_adh; ?>" maxlength="<? echo $pseudo_adh_len; ?>"></TD> 
						  </TR> 
							<TR> 
								<TH <? echo $ddn_adh_req ?> id="libelle"><? echo _T("birth date:"); ?><br>&nbsp;</TH> 
								<TD><INPUT type="text" name="ddn_adh" value="<? echo $ddn_adh; ?>" maxlength="10"><BR><DIV class="exemple"><? echo _T("(dd/mm/yyyy format)"); ?></DIV></TD>
							</TR>
							<TR>
							  <TH <? echo $prof_adh_req ?> id="libelle"><? echo _T("Profession:"); ?></TH> 
								<TD><input type="text" name="prof_adh" value="<? echo $prof_adh; ?>" maxlength="<? echo $prof_adh_len; ?>"></TD> 
								<TH id="libelle"><? echo _T("Photo:"); ?></TH> 
								<TD> 
								<?
									if (file_exists(WEB_ROOT . "photos/" . $id_adh . ".jpg") ||
											file_exists(WEB_ROOT . "photos/" . $id_adh . ".png") ||
											file_exists(WEB_ROOT . "photos/" . $id_adh . ".gif"))
									{
								?>
									<INPUT type="submit" name="del_photo" value="<? echo _T("Delete the picture"); ?>">
								<?
									}
									else
									{
								?>
									<INPUT type="file" name="photo"><BR>
								<?
									}
								?>
								</TD> 
							</TR> 
							<TR>
								<TH id="libelle"><? echo _T("Be visible in the<br /> members list :"); ?></TH>
								<TD><input type="checkbox" name="bool_display_info" value="1"<? isChecked($bool_display_info,"1") ?>></TD> 
								<TH id="libelle"><? echo _T("Language:") ?></TH>
                <TD>
                  <script language="javascript" type="text/javascript">
                    <!--
                    function updatelanguage(){
                        document.cookie = "pref_lang="+document.form.pref_lang.value;
                        window.location.reload()
                    }
                    -->
                  </script>
                  <SELECT NAME="pref_lang" onChange="updatelanguage()">
<?
			$path = "lang";
			$dir_handle = @opendir($path);
			while ($file = readdir($dir_handle))
			{
				if (substr($file,0,5)=="lang_" && substr($file,-4)==".php")
				{
		        $file = substr(substr($file,5),0,-4);
?>
                                    <OPTION value="<? echo $file; ?>" <? isSelected($pref_lang,$file) ?> style="padding-left: 30px; background-image: url(lang/<? echo $file.".gif"; ?>); background-repeat: no-repeat"><? echo ucfirst(_T($file)); ?></OPTION>
<?
				}
			}
			closedir($dir_handle);
?>
                                </SELECT>
                                </TD>
							</TR>
<?
	if ($_SESSION["admin_status"]!=0)
	{
?>
							<TR> 
								<TH colspan="4" id="header">&nbsp;</TH> 
							</TR>
							<TR>
								<TH <? echo $activite_adh_req ?> id="libelle"><? echo _T("Account:"); ?></TH> 
								<TD>
								  <SELECT name="activite_adh">
								  	<OPTION value="1"<? isSelected($activite_adh,"1") ?>><? echo _T("Active"); ?></OPTION>
								  	<OPTION value="0"<? isSelected($activite_adh,"0") ?>><? echo _T("Inactive"); ?></OPTION>
									</SELECT>
								</TD>
								<TH id="header" colspan="2">&nbsp;</TH>
							</TR>
							<TR> 
								<TH <? echo $id_statut_req ?> id="libelle"><? echo _T("Status:"); ?></TH> 
								<TD>
									<SELECT name="id_statut">
									<?
										$requete = "SELECT *
		 									    FROM ".PREFIX_DB."statuts
		 									    ORDER BY priorite_statut";
										$result = &$DB->Execute($requete);
										while (!$result->EOF)
										{									
									?>
										<OPTION value="<? echo $result->fields["id_statut"] ?>"<? isSelected($id_statut,$result->fields["id_statut"]) ?>><? echo gettext($result->fields["libelle_statut"]); ?></OPTION>
									<?
											$result->MoveNext();
										}
										$result->Close();
									?>
									</SELECT>
								</TD>
								<TH id="header" colspan="2">&nbsp;</TH>
							</TR>
							<TR> 
								<TH id="libelle"><? echo _T("Galette Admin:"); ?></TH> 
								<TD><input type="checkbox" name="bool_admin_adh" value="1"<? isChecked($bool_admin_adh,"1") ?>></TD> 
								<TH id="header" colspan="2">&nbsp;</TH>
						  	</TR> 
							<TR> 
								<TH id="libelle"><? echo _T("Freed of dues:"); ?></TH> 
								<TD><INPUT type="checkbox" name="bool_exempt_adh" value="1"<? isChecked($bool_exempt_adh,"1") ?>></TD> 
								<TH id="header" colspan="2">&nbsp;</TH>
						  	</TR>
<?
	}
?>
							<TR> 
								<TH colspan="4" id="header">&nbsp;</TH> 
							</TR>
							<TR> 
								<TH id="libelle" <? echo $adresse_adh_req ?>><? echo _T("Address:"); ?></TH> 
								<TD colspan="3">
									<INPUT type="text" name="adresse_adh" value="<? echo $adresse_adh; ?>" maxlength="<? echo $adresse_adh_len; ?>" size="63"><BR>
									<INPUT type="text" name="adresse2_adh" value="<? echo $adresse2_adh; ?>" maxlength="<? echo $adresse2_adh_len; ?>" size="63">
								</TD> 
						  </TR> 
							<TR> 
								<TH id="libelle" <? echo $cp_adh_req ?>><? echo _T("Zip Code:"); ?></TH> 
								<TD><INPUT type="text" name="cp_adh" value="<? echo $cp_adh; ?>" maxlength="<? echo $cp_adh_len; ?>"></TD> 
								<TH id="libelle" <? echo $ville_adh_req ?>><? echo _T("City:"); ?></TH> 
								<TD><INPUT type="text" name="ville_adh" value="<? echo $ville_adh; ?>" maxlength="<? echo $ville_adh_len; ?>"></TD> 
						  </TR> 
							<TR> 
								<TH id="libelle" <? echo $pays_adh_req ?>><? echo _T("Country:"); ?></TH> 
								<TD><INPUT type="text" name="pays_adh" value="<? echo $pays_adh; ?>" maxlength="<? echo $pays_adh_len; ?>"></TD> 
								<TH id="libelle" <? echo $tel_adh_req ?>><? echo _T("Phone:"); ?></TH> 
								<TD><INPUT type="text" name="tel_adh" value="<? echo $tel_adh; ?>" maxlength="<? echo $tel_adh_len; ?>"></TD> 
						  </TR> 
							<TR> 
								<TH id="libelle" <? echo $gsm_adh_req ?>><? echo _T("Mobile phone:"); ?></TH> 
								<TD><INPUT type="text" name="gsm_adh" value="<? echo $gsm_adh; ?>" maxlength="<? echo $gsm_adh_len; ?>"></TD> 
								<TH id="libelle" <? echo $email_adh_req ?>><? echo _T("E-Mail:"); ?></TH> 
								<TD><INPUT type="text" name="email_adh" value="<? echo $email_adh; ?>" maxlength="<? echo $email_adh_len; ?>" size="30"></TD> 
						  </TR> 
							<TR> 
								<TH id="libelle" <? echo $url_adh_req ?>><? echo _T("Website:"); ?></TH> 
								<TD><INPUT type="text" name="url_adh" value="<? echo $url_adh; ?>" maxlength="<? echo $url_adh_len; ?>" size="30"></TD> 
								<TH id="libelle" <? echo $icq_adh_req ?>><? echo _T("ICQ:"); ?></TH> 
								<TD><INPUT type="text" name="icq_adh" value="<? echo $icq_adh; ?>" maxlength="<? echo $icq_adh_len; ?>"></TD> 
						  </TR> 
							<TR> 
								<TH id="libelle" <? echo $jabber_adh_req ?>><? echo _T("Jabber:"); ?></TH> 
								<TD><INPUT type="text" name="jabber_adh" value="<? echo $jabber_adh; ?>" maxlength="<? echo $jabber_adh_len; ?>" size="30"></TD> 
								<TH id="libelle" <? echo $msn_adh_req ?>><? echo _T("MSN:"); ?></TH> 
								<TD><INPUT type="text" name="msn_adh" value="<? echo $msn_adh; ?>" maxlength="<? echo $msn_adh_len; ?>" size="30"></TD> 
						  </TR> 
              <TR> 
                <TH id="libelle" <? echo $gpgid_req ?>><? echo _T("Id GNUpg (GPG):"); ?></TH> 
                <TD><INPUT type="text" name="gpgid" value="<? echo $gpgid; ?>" maxlength="<? echo $gpgid_len; ?>" size="8"></TD> 
                <TH id="libelle" <? echo $fingerprint_req ?>><? echo _T("fingerprint:"); ?></TH> 
                <TD><INPUT type="text" name="fingerprint" value="<? echo $fingerprint; ?>" maxlength="<? echo $fingerprint_len; ?>" size="30"></TD> 
              </TR> 
							<TR> 
								<TH colspan="4" id="header">&nbsp;</TH> 
							</TR>
							<TR> 
								<TH id="libelle" <? echo $login_adh_req ?>><? echo _T("Username:"); ?><BR>&nbsp;</TH> 
								<TD><INPUT type="text" name="login_adh" value="<? echo $login_adh; ?>" maxlength="<? echo $login_adh_len; ?>"><BR><DIV class="exemple"><? echo _T("(at least 4 characters)"); ?></DIV></TD> 
								<TH id="libelle" <? echo $mdp_adh_req ?>><? echo _T("Password:"); ?><BR>&nbsp;</TH> 
								<TD><INPUT type="text" name="mdp_adh" value="<? echo $mdp_adh; ?>" maxlength="<? echo $mdp_adh_len; ?>"><BR><DIV class="exemple"><? echo _T("(at least 4 characters)"); ?></DIV></TD> 
						</TR>
<?
	if ($_SESSION["admin_status"]!=0)
	{
?>
						<TR> 
								<TH id="libelle"><? echo _T("Send a mail:"); ?><BR>&nbsp;</TH> 
								<TD colspan="3"><INPUT type="checkbox" name="mail_confirm" value="1" <? if ($id_adh=="") echo "CHECKED"; ?>><BR><DIV class="exemple"><? echo _T("(the member will receive his username and password by email, if he has an address.)"); ?></DIV></TD> 
						  </TR> 
							<TR> 
								<TH id="libelle"><? echo _T("Creation date:"); ?><BR>&nbsp;</TH> 
								<TD colspan="3"><INPUT type="text" name="date_crea_adh" value="<? echo $date_crea_adh; ?>" maxlength="10"><BR><DIV class="exemple"><? echo _T("(dd/mm/yyyy format)"); ?></DIV></TD> 
						  </TR> 
							<TR> 
								<TH id="libelle" <? echo $info_adh_req ?>><? echo _T("Other informations (admin):"); ?></TH> 
								<TD colspan="3"><TEXTAREA name="info_adh" cols="61" rows="6"><? echo $info_adh; ?></TEXTAREA><BR><DIV class="exemple"><? echo _T("This comment is only displayed for admins."); ?></DIV></TD> 
						  </TR> 
<?
	}
?>
							<TR> 
								<TH id="libelle" <? echo $info_public_adh_req ?>><? echo _T("Other informations:"); ?></TH> 
								<TD colspan="3">
									<TEXTAREA name="info_public_adh" cols="61" rows="6"><? echo $info_public_adh; ?></TEXTAREA>
<?
	if ($_SESSION["admin_status"]!=0)
	{
?>	
									<BR><DIV class="exemple"><? echo _T("This comment is reserved to the member."); ?></DIV>
<?
	}
?>	
								</TD> 
						  </TR> 
<?
    $requete = "SELECT id_cat, index_cat, name_cat, perm_cat, type_cat, size_cat FROM $info_cat_table";
    if ($_SESSION["admin_status"] != 1)
       $requete .= " WHERE perm_cat=$perm_all";
    $requete .= " ORDER BY index_cat";
    $res_cat = $DB->Execute($requete);
    while (!$res_cat->EOF)
    {
        $id_cat = $res_cat->fields[0];
        $rank_cat = $res_cat->fields[1];
        $name_cat = $res_cat->fields[2];
        $perm_cat = $res_cat->fields[3];
        $type_cat = $res_cat->fields[4];
        $size_cat = $res_cat->fields[5];
    
        if ($type_cat == $category_separator) {
            for ($i = 0; $i < $size_cat; ++$i) {
?>                                                
                                                    <TR><TH colspan="4" id="header">&nbsp;</TH></TR> 
<?
            }
        } else {
            $cond = "id_cat=$id_cat";
            if (is_numeric($id_adh))
                $cond .= " and id_adh=$id_adh";
	    else
	    	$cond .= " and 1=2";
	    // Cette condition est stupide
	    // Je l'ai rajoutee pour eviter d'avoir des valeurs a la creation de nouvelles fiches
	    // TODO : recoder proprement
	    
            $res_info = $DB->Execute("SELECT val_info, index_info FROM ".PREFIX_DB."adh_info WHERE $cond ORDER BY index_info");
            $current_size = $size_cat;
            if ($size_cat == 0)
                $current_size = $res_info->RecordCount() + 1;
            for ($i = 0; $i < $current_size; ++$i) {
?> 
                                                <TR>
<?
                if ($i == 0) {
?> 
                                                    <TH id="libelle" rowspan="<?php echo $current_size; ?>" <?php echo $info_public_adh_req ?> >
                                                        <INPUT type="hidden" name="info_field_size_<?php echo $id_cat; ?>" value="<?php echo $current_size; ?>" >
                                                        <?php echo $name_cat."&nbsp;:"; ?> 
                                                    </TH> 
<?
                }
                $field_name = "info_field_".$id_cat."_".$i;
                $val = $res_info->EOF ? "" : $res_info->fields[0];
?> 
                                                    <TD colspan="3">
<?
                if ($type_cat == $category_text) {
?> 
                                                        <TEXTAREA name="<?php echo $field_name; ?>" cols="61" rows="6"><?php echo $val; ?></TEXTAREA>
<?
                } elseif ($type_cat == $category_field) {
?> 
                                                        <INPUT type="text" name="<?php echo $field_name; ?>" value="<? echo $val; ?>" size="63">
<?
                }
?> 
                                                    </TD> 
                                                </TR>
<?
                $res_info->MoveNext();
            }
            $res_info->Close();
        }
        $res_cat->MoveNext();
    }
    $res_cat->Close();
?> 
							<TR> 
								<TH align="center" colspan="4"><BR><INPUT type="submit" name="valid" value="<? echo _T("Save"); ?>"></TH> 
						  </TR> 
                                                        </TABLE> 
						</DIV>
						<BR> 
						<? echo _T("NB : The mandatory fields are in"); ?> <FONT style="color: #FF0000"><? echo _T("red"); ?></FONT>. 
						</BLOCKQUOTE> 
						<INPUT type="hidden" name="id_adh" value="<? echo $id_adh ?>"> 
						</FORM> 
<? 
  include("footer.php") 
?>
