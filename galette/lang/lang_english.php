<?

// Install - Upgrade

$lang["Type d'installation inconnu"]="Installation mode unknown";
$lang["Type de base inconnu"]="Database type unknown";
$lang["Nom d'utilisateur vide"]="No user name";
$lang["Mot de passe vide"]="No password";
$lang["Nom de la base non pr�cis�"]="No database name";
$lang["Type d'installation"]="Installation mode";
$lang["Selectionnez le type d'installation � lancer"]="Select installation mode to launch";
$lang["Nouvelle installation :"]="New installation:";
$lang["Vous installez Galette pour la premi�re fois, ou vous souhaitez �craser une ancienne version de Galette sans conserver vos donn�es"]="You're installing Galette for the first time, or you wish to erase an older version of Galette without keeping your data";
$lang["Mise � jour :"]="Update:";
$lang["Vous souhaitez mettre � jour une version Galette inf�rieure ou �gale � 0.57. Attention : Pensez � sauvegarder votre base existante."]="You wish update a Galette of version 0.57 or older. Warning: Don't forget save your current database.";
$lang["Etape suivante"]="Next step";
$lang["Etape 2 - Type d'installation"]="Step 2 - Installation mode";
$lang["Permissions de fichiers"]="Files permissions";
$lang["V�rification des permissions des fichiers et dossier"]="Checking the files and directories permissions";
$lang["Le dossier"]="The directory";
$lang["Le fichier"]="The file";
$lang["n'est pas autoris� en �criture"]="isn't writable";
$lang["est autoris� en �criture"]="is writable";
$lang["Pour fonctionner correctement, Galette a besoin d'avoir les droits en �criture sur ces fichiers."]="For a correct functionning, Galette needs the Write permission on these files.";
$lang["Pour �tre mis � jour et fonctionner correctement, Galette a besoin d'avoir les droits en �criture sur ces fichiers."]="In order to be updated, Galette needs the Write permission on these files.";
$lang["Sous UNIX/Linux, vous pouvez donner ces droits par les commandes"]="Under UNIX/Linux, you can give the permissions using those commands";
$lang["utilisateur_apache"]="apache_user";
$lang["nom_fichier"]="file_name";
$lang["nom_dossier"]="direcory_name";
$lang["(pour un fichier)"]="(for a file)";
$lang["(pour un dossier)"]="(for a directory)";
$lang["Sous Windows, v�rifiez que les fichiers en question ne sont pas en lecture seule dans leurs propri�t�s."]="Under Windows, check these files are not in Read-Only mode in their property panel.";
$lang["R��ssayer"]="Retry";
$lang["Les permissions des fichiers sont correctes !"]="Files permissions are OK!";
$lang["Etape 3 - Permissions"]="Step 3 - Permissions";
$lang["Base de donn�es"]="Database";
$lang["Si ce n'est pas d�j� fait, cr�ez une base de donn�es et un utilisateur pour Galette."]="If it hadn't been made, create a database and a user for Galette.";
$lang["Veuillez entrer les param�tres de connexion � la base existante."]="Enter connection data for the existing database.";
$lang["Les droits n�cessaires sont CREATE, DROP, DELETE, UPDATE, SELECT et INSERT."]="The needed permissions are CREATE, DROP, DELETE, UPDATE, SELECT and INSERT.";
$lang["Type de base de donn�es :"]="Database type:";
$lang["Utilisateur :"]="User:";
$lang["H�te :"]="Host:";
$lang["Nom de la base :"]="Database:";
$lang["Etape 4 - Base de donn�es"]="Step 4 - Database";
$lang["V�rification de la base"]="Check of the database";
$lang["V�rification des param�tres et de l'existence de la base"]="Check the parameters and the existence of the database";
$lang["Connexion � la base impossible"]="Unable to connect to the database";
$lang["La connexion � la base est �tablie"]="Connection to database is OK";
$lang["La base n'est accessible. Veuillez revenir en arri�re pour saisir � nouveau les param�tres de connexion."]="Database can't be reached. Please go back to enter the connection parameters again.";
$lang["Retour"]="Go back";
$lang["La base existe et les param�tres de connexion sont corrects."]="Database exists and connection parameters are OK.";
$lang["Etape 5 - Acc�s � la base"]="Step 5 - Access to the database";
$lang["Permissions sur la base"]="Permissions on the base";
$lang["Pour fonctionner, Galette doit avoir un certain nombre de droits sur la base de donn�es (CREATE, DROP, DELETE, UPDATE, SELECT et INSERT)"]="To run, Galette needs a number of rights on the databse (CREATE, DROP, DELETE, UPDATE, SELECT and INSERT)";
$lang["Pour �tre mis � jour, Galette doit avoir un certain nombre de droits sur la base de donn�es (CREATE, DROP, DELETE, UPDATE, SELECT, INSERT et ALTER)"]="In order to be updated, Galette needs a number of rights on the databse (CREATE, DROP, DELETE, UPDATE, SELECT and INSERT)";
$lang["Op�ration DROP non autoris�e"]="DROP operation not allowed";
$lang["Op�ration DROP autoris�e"]="DROP operation allowed";
$lang["Op�ration CREATE non autoris�e"]="CREATE operation not allowed";
$lang["Op�ration CREATE autoris�e"]="CREATE operation allowed";
$lang["Op�ration INSERT non autoris�e"]="INSERT operation not allowed";
$lang["Op�ration INSERT autoris�e"]="INSERT operation allowed";
$lang["Op�ration UPDATE non autoris�e"]="UPDATE operation not allowed";
$lang["Op�ration UPDATE autoris�e"]="UPDATE operation allowed";
$lang["Op�ration SELECT non autoris�e"]="SELECT operation not allowed";
$lang["Op�ration SELECT autoris�e"]="SELECT operation allowed";
$lang["Op�ration ALTER non autoris�e"]="ALTER operation not allowed";
$lang["Op�ration ALTER autoris�e"]="ALTER operation allowed";
$lang["Op�ration DELETE non autoris�e"]="DELETE operation not allowed";
$lang["Op�ration DELETE autoris�e"]="DELETE operation allowed";
$lang["Galette ne dispose pas de droits suffisants sur la base de donn�es pour poursuivre l'installation."]="Galette hasn't got enough permissions on the database to continue the installation.";
$lang["Galette ne dispose pas de droits suffisants sur la base de donn�es pour poursuivre la mise � jour."]="Galette hasn't got enough permissions on the database to continue the update.";
$lang["Les droits d'acc�s � la base sont corrects."]="Permissions to database are OK.";
$lang["Etape 6 - Droits d'acc�s � la base"]="Step 6 - Access permissions to database";
$lang["Cr�ation de la base"]="Creation of the database";
$lang["Mise � jour de la base"]="Update of the database";
$lang["Compte rendu d'installation"]="Report of installation";
$lang["Compte rendu de mise � jour"]="Report of update";
$lang["(Les erreurs sur les op�rations DROP peuvent �tre ignor�es)"]="(Errors on DROP operations can be ignore)";
$lang["La base de donn�es n'a pas pu �tre totalement cr��e, il s'agit peut-�tre d'un probl�me de droits."]="The database isn't totally created, it's maybe a permissions problem.";
$lang["La base de donn�es n'a pas pu �tre totalement mise � jour, il s'agit peut-�tre d'un probl�me de droits."]="The database isn't totally updated, it's maybe a permissions problem.";
$lang["Votre base est peut-�tre inutilisable, essayez de restaurer une ancienne version."]="Your database is maybe not usable, try to restore the older version.";
$lang["La base de donn�es a �t� correctement cr��e."]="The database has been correctly created.";
$lang["La base de donn�es a �t� correctement mise � jour."]="The database has been correctly updated.";
$lang["La base de donn�es a �t� correctement mise � jour."]="The database has been correctly updated.";
$lang["Etape 7 - Cr�ation de la base"]="Step 7 - Creation of the database";
$lang["Etape 7 - Mise � jour de la base"]="Step 7 - Update of the database";
$lang["Param�tres administrateur"]="Admin settings";
$lang["Veuillez choisir les param�tres du compte administrateur Galette"]="Please chose the parameters of the admin account on Galette";
$lang["Etape 8 - Param�tres administrateur"]="Step 8 - Admin parameters";
$lang["Sauvegarde des param�tres"]="Save the parameters";
$lang["Fichier de configuration cr�e (includes/config.inc.php)"]="Configuration file created (includes/config.inc.php)";
$lang["Impossible de cr�er le fichier de configuration (includes/config.inc.php)"]="Unable to create configuration file (includes/config.inc.php)";
$lang["Param�tres sauvegard�s dans la base de donn�es"]="Parameters saved into the database";
$lang["Les param�tres n'ont pas pu �tre sauvegard�s dans la base de donn�es"]="Parameters couldn't be save into the database";
$lang[ "Les param�tres n'ont pas pu �tre sauvegard�s."]= "Parameters couldn't be saved.";
$lang["Ceci peut provenir des droits sur le fichier includes/config.inc.php ou de l'impossibilit� de faire un INSERT dans la base."]="This can come from the permissions on the file includes/config.inc.php or the impossibility to make an INSERT into the database.";
$lang["Etape 9 - Sauvegarde des param�tres"]="Step 9 - Saving of the parameters";
$lang["Fin de l'installation"]="End of the installation";
$lang["Galette a �t� install� avec succ�s !"]="Galette has been succesfully installed!";
$lang["Galette a �t� mis � jour avec succ�s !"]="Galette has been succesfully updated!";
$lang["Pour s�curiser le syst�me, veuillez supprimer le dossier install"]="For securing the system, please delete the install directory";
$lang["Page d'accueil"]="Homepage";
$lang["Etape 10 - Fin de l'installation"]="Step 10 - End of the installation";
$lang["Etape 10 - Fin de la mise � jour"]="Etape 10 - End of the update";



$lang["Identification"]="Login";
$lang["Identifiant :"]="Username:";
$lang["Mot de passe :"]="Password:";
$lang["- Champ obligatoire non renseign�."]="- Mandatory field empty.";
$lang["- Date non valide !"]="- Non valid date!";
$lang["- Mauvais format de date (jj/mm/aaaa) !"]="- Wrong date format (dd/mm/yyyy)!";
$lang["- Adresse E-mail non valide !"]="- Non-valid E-Mail adress!";
$lang["- Adresse web non valide ! Oubli du http:// ?"]="- Non-valid Website address! Maybe you've skipped the http:// ?";
$lang["- L'identifiant doit �tre compos� d'au moins 4 caract�res !"]="- The username must be composed of at least 4 characters!";
$lang["- Cet identifiant est d�j� utilis� par un autre adh�rent !"]="- This username is already used by another member!";
$lang["- Le mot de passe doit �tre compos� d'au moins 4 caract�res !"]="- The password must be of at least 4 characters!";
$lang["- La photo semble ne pas avoir �t� transmise correstement. L'enregistrement a cependant �t� effectu�."]="- The photo seems not to be transferred correctly. But registration has been made.";
$lang["- Le fichier transmis n'est pas une image valide (GIF, PNG ou JPEG). L'enregistrement a cependant �t� effectu�."]="- The transfered file isn't a valid image (GIF, PNG or JPEG). But registration has been made.";
$lang["- Le fichier transmis n'est pas une image valide (PNG ou JPEG). L'enregistrement a cependant �t� effectu�."]="- The transfered file isn't a valid image (PNG or JPEG). But registration has been made.";
$lang["Fiche adh�rent"]="Member Profile";
$lang["modification"]="modification";
$lang["cr�ation"]="creation";
$lang["- ERREUR -"]="- ERROR -";
$lang["- AVERTISSEMENT -"]="- WARNING -";
$lang["Titre :"]="Title:";
$lang["Mademoiselle"]="Miss";
$lang["Madame"]="Mrs";
$lang["Monsieur"]="Mister";
$lang["Nom :"]="Name:";
$lang["Photo"]="Picture";
$lang["[ pas de photo ]"]="[ no picture ]";
$lang["Pr�nom :"]="First name:";
$lang["Pseudo :"]="Nickname:";
$lang["Date de naissance :"]="birth date:";
$lang["(format jj/mm/aaaa)"]="(dd/mm/yyyy format)";
$lang["Statut :"]="Status:";
$lang["Profession :"]="Profession:";
$lang["Compte :"]="Account:";
$lang["Actif"]="Active";
$lang["Inactif"]="Inactive";
$lang["Photo :"]="Photo:";
$lang["Supprimer la photo"]="Delete the picture";
$lang["Admin Galette :"]="Galette Admin:";
$lang["Exempt de cotisation :"]="Freed of dues:";
$lang["Adresse :"]="Address:";
$lang["Code Postal :"]="Zip Code:";
$lang["Ville :"]="City:";
$lang["Pays :"]="Country:";
$lang["Tel :"]="Phone:";
$lang["GSM :"]="Mobile phone:";
$lang["E-Mail :"]="E-Mail:";
$lang["Site Web :"]="Website:";
$lang["MSN :"]="MSN:";
$lang["ICQ :"]="ICQ:";
$lang["Jabber :"]="Jabber:";
$lang["(au moins 4 caract�res)"]="(at least 4 characters)";
$lang["Date de cr�ation :"]="Creation date:";
$lang["Commentaire :"]="Comments:";
$lang["Autres informations (admin) :"]="Other informations (admin):";
$lang["Ce commentaire n'est visible que par les administrateurs."]="This comment is only displayed for admins.";
$lang["Autres informations :"]="Other informations:";
$lang["Ce commentaire est r�serv� � l'adh�rent."]="This comment is reserved to the member.";
$lang["Enregistrer"]="Save";
$lang["NB : Les champs obligatoires apparaissent en"]="NB : The mandatory fields are in";
$lang["rouge"]="red";
$lang["- V�rifiez que tous les champs obligatoires sont renseign�s."]="- Check that all mandatory fields are filled in.";
$lang["- La dur�e doit �tre un entier !"]="- The duration must be an integer!";
$lang["- Le montant doit �tre un chiffre !"]="- The amount must be an integer!";
$lang["Fiche contribution"]="Contribution card";
$lang["Contributeur :"]="Contributor:";
$lang["-- selectionner un nom --"]="-- select a name --";
$lang["Type de contribution :"]="Contribution type:";
$lang["Montant :"]="Amount:";
$lang["Prologation adh�sion :"]="Extension of adhesion:";
$lang["mois"]="months";
$lang["Date contribution :"]="Date of contribution:";
$lang["Gestion des adh�rents"]="Management of the members";
$lang["adh�rents"]="members";
$lang["adh�rent"]="member";
$lang["Pages :"]="Pages:";
$lang["Nom"]="Name";
$lang["Pseudo"]="Nickname";
$lang["Statut"]="Status";
$lang["Etat cotisations"]="State of dues";
$lang["aucun adh�rent"]="no member";
$lang["Exempt de cotisation"]="Freed of dues";
$lang["N'a jamais cotis�"]="Never contributed";
$lang["Dernier jour !"]="Last day!";
$lang["En retard de "]="Late of ";
$lang["[ Voir la fiche adh�rent ]"]="[ See member profile ]";
$lang["jours"]="days";
$lang["depuis le"]="since";
$lang["jour restant"]="day remaining";
$lang["jours restants"]="days remaining";
$lang["fin le"]="ending on";
$lang["[H]"]="[M]";
$lang["[F]"]="[W]";
$lang["[Mail]"]="[Mail]";
$lang["E-Mail"]="E-Mail";
$lang["[admin]"]="[admin]";
$lang["[mod]"]="[mod]";
$lang["[$]"]="[$]";
$lang[""]="";
$lang["[sup]"]="[del]";
$lang["Afficher :"]="Display:";
$lang["Tout les adh�rents"]="All members";
$lang["Les adh�rents � jour"]="Members up to date";
$lang["Les �ch�ances proches"]="Close expiries";
$lang["Les retardataires"]="Latecomers";
$lang["Tous  les comptes"]="All the accounts";
$lang["Comptes actifs"]="Active accounts";
$lang["Comptes d�sactiv�s"]="Inactive accounts";
$lang["Filtrer"]="Filter";
$lang["Gestion des contributions"]="Management of contributions";
$lang["contributions"]="contributions";
$lang["contribution"]="contribution";
$lang["Date"]="Date";
$lang["Adh�rent"]="Member";
$lang["Type"]="Type";
$lang["Montant"]="Amount";
$lang["Dur�e"]="Duration";
$lang["Actions"]="Actions";
$lang["aucune contribution"]="no contribution";
$lang["Voulez-vous vraiment supprimer cette contribution de la base ?"]="Do you really wan't to delete this contribution of the database ?";
$lang["En retard de"]="Late of";
$lang["Mes informations"]="My information";
$lang["Fiche adh�rent (modification)"]="Member Profile (modification)";
$lang["- CONFIRMATION -"]="- CONFIRMATION -";
$lang["Navigation"]="Navigation";
$lang["Liste des adh�rents"]="List of members";
$lang["Liste des contributions"]="List of contributions";
$lang["Ajouter un adh�rent"]="Add a member";
$lang["Ajouter une contribution"]="Add a contribution";
$lang["Mes contributions"]="My contributions";
$lang["D�connexion"]="Log off";
$lang["Historique"]="Logs";
$lang["L�gende"]="Legend";
$lang["Homme"]="Man";
$lang["Femme"]="Woman";
$lang["Envoyer un mail"]="Send a mail";
$lang["Administrateur"]="Admin";
$lang["Modification"]="Modification";
$lang["Contributions"]="Contributions";
$lang["Suppression"]="Deletion";
$lang["Compte actif"]="Active account";
$lang["Compte d�sactiv�"]="Inactive account";
$lang["Adh�sion en r�gle"]="Membership in order";
$lang["Adh�sion � �ch�ance (<30j)"]="Membership will expire soon (&lt;30d)";
$lang["Retard de cotisation"]="Late";
$lang["Cotisation"]="Cotisation";
$lang["Don"]="Gift";
$lang["R�alisation :"]="Realisation:";
$lang["Graphisme :"]="Graphism:";
$lang["Editeur :"]="Editor:";
$lang["Page affich�e en"]="Page displayed in";
$lang["Pr�sident"]="President";
$lang["Vice-pr�sident"]="Vice-president";
$lang["Tr�sorier"]="Treasurer";
$lang["Secr�taire"]="Secretary";
$lang["Membre actif"]="Active member";
$lang["Membre bienfaiteur"]="Benefactor member";
$lang["Membre fondateur"]="Founder member";
$lang["Ancien"]="Old-timer";
$lang["Personne morale"]="Legal entity";
$lang["Non membre"]="Non-member";
$lang["Cotisation annuelle normale"]="Normal annual cotisation";
$lang["Cotisation annuelle r�duite"]="Reduced annual cotisation";
$lang["Cotisation entreprise"]="Company cotisation";
$lang["Donation en nature"]="Donation in kind";
$lang["Donation p�cuni�re"]="Donation in money";
$lang["Partenariat"]="Partnership";
$lang["Envoi de mail :"]="Send a mail:";
$lang["(l'adh�rent recevra son identifiant et son mot de passe par mail, s'il a une adresse.)"]="(the member will recieve his username and password by email, if he has an address.)";
$lang["- Vous ne pouvez pas envoyer de confirmation par mail si l'adh�rent n'a pas d'adresse !"]="- You can't send a confirmation by email if the member hasn't got an address!";
$lang["Vos identifiants Galette"]="Your Galette's identifiers";
$lang["Bonjour,"]="Hello,";
$lang["Vous venez d'�tre inscrit sur le syst�me de gestion d'adh�rents de l'association."]="You've just been suscribed on the members management system of the association.";
$lang["Il vous est d�sormais possible de suivre en temps r�el l'�tat de votre adh�sion"]="It is now possible to follow in real time the state of your subscription";
$lang["et de mettre � jour vos coordonn�es par l'interface web pr�vue � cet effet."]="and to update your preferences from the web interface.";
$lang["Veuillez vous identifier � cette adresse :"]="Please Login at this address:";
$lang["A tr�s bient�t !"]="See you soon!";
$lang["(ce mail est un envoi automatique)"]="(this mail was sent automatically)";
$lang["M."]="Mr.";
$lang["Mme."]="Mrs.";
$lang["Mlle."]="Miss.";
$lang["Oui"]="Yes";
$lang["Non"]="No";
$lang["[ Contributions ]"]="[ Contributions ]";
$lang["[ Modification ]"]="[ Modification ]";
$lang["[ Ajouter une contribution ]"]="[ Add a contribution ]";
$lang["Effectuer un mailing"]="Do a mailing";
$lang["Mailing"]="Mailing";
$lang["Pr�visualiser"]="Preview";
$lang["Envoyer"]="Send";
$lang["Message :"]="Message:";
$lang["Objet :"]="Object:";
$lang["secondes."]="seconds.";
$lang["Veuillez indiquer un objet pour le message."]="Please type an object for the message.";
$lang["Veuillez saisir un message."]="Please enter a message.";
$lang["Veuillez s�lectionner au moins un adh�rent."]="Please select at least one member.";
$lang["(pr�visualisation)"]="(preview)";
$lang["Destinataires du mailing :"]="Recipients of the mailing:";
$lang["Adh�rents non joignables par email :"]="Members who can't be reachable by e-mail:";
$lang["Coordonn�es"]="Profile";
$lang["[ Tout cocher / d�cocher ]"]="[ Select / unselect all ]";
$lang["effectu� !"]="done!";
$lang["- ATTENTION -"]="- WARNING -";
$lang["Pensez � contacter les adh�rents ne disposant pas d'une adresse E-Mail par un autre moyen."]="Don't forget to contact the members who don't have an email address by another way.";
$lang["G�n�ration d'�tiquettes"]="Generate labels";
$lang["Pr�f�rences"]="Settings";
$lang["Nom (raison sociale) de l'association :"]="Name (corporate name) of the association:";
$lang["[ pas de logo ]"]="[ no logo ]";
$lang["Logo :"]="Logo:";
$lang["Compte administrateur (ind�pendant des adh�rents) :"]="Admin account (independant of members):";
$lang["Corps du texte :"]="Font size:";
$lang["Nombre de lignes d'�tiquettes :"]="Number of label lines:";
$lang["Nombre de colonnes d'�tiquettes :"]="Number of label columns:";
$lang["Hauteur �tiquette :"]="Label height:";
$lang["Largeur �tiquette :"]="Label width:";
$lang["Espacement vertical :"]="Vertical spacing:";
$lang["Espacement horizontal :"]="Horizontal spacing:";
$lang["Marges :"]="Margins:";
$lang["Param�tres de g�n�ration d'�tiquettes :"]="Label generation parameters:";
$lang["Email exp�diteur :"]="Sender Email:";
$lang["Nom exp�diteur :"]="Sender name:";
$lang["Param�tres mail :"]="Mail settings:";
$lang["Lignes / Page :"]="Lines / Page:";
$lang["Langue :"]="Language:";
$lang["Param�tres galette :"]="Galette's parameters:";
$lang["Informations g�n�rales :"]="General information:";
$lang["- Les nombres et mesures doivent �tre des entiers !"]="- The numbers and measures have to be integers!";
$lang["(Entier)"]="(Integer)";
$lang["- Langue non valide !"]="- Non-valid language!";
$lang["R�initialisation de l'historique"]="Flush the logs";
$lang["IP"]="IP";
$lang["Description"]="Description";
$lang["historique vide"]="logs are empty";
$lang["Echec authentification. Login :"]="Authentication failed. Login:";
$lang["Mise � jour de la fiche adh�rent :"]="Update the member card:";
$lang["Ajout de la fiche adh�rent :"]="Add the member card:";
$lang["Suppression de la fiche adh�rent (et cotisations) :"]="Delete the member card (and dues)";
$lang["Mise � jour d'une contribution :"]="Update a contribution:";
$lang["Suppression d'une contribution :"]="Delete a contribution:";
$lang["Mise � jour des pr�f�rences"]="Update profile";
$lang["Envoi d'un mailing intitul� :"]="Send of a mailing titled:";
$lang["destinataires"]="recipients";
$lang["G�n�ration de "]="Generation of ";
$lang["�tiquette(s)"]="label(s)";
$lang["Niveau d'historique :"]="Logging level:";
$lang["Nul"]="Disabled";
$lang["Normal"]="Normal";
$lang["D�taill�"]="Detailed";
$lang["ligne"]="line";
$lang["lignes"]="lines";
$lang["Assurez vous que l'utilisateur galette a les droits n�cessaires (SELECT, INSERT, UPDATE, CREATE, ALTER et DELETE) !"]="Verify that galette user has enought permissions (SELECT, INSERT, UPDATE, CREATE, ALTER and DELETE) !"
$lang["filtrer sur la date"]="filter the date";

$lang["XXX"]="XXXen";
?>
