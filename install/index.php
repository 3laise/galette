<?
        if (!isset($_POST["install_lang"])) $pref_lang="english";
        else $pref_lang=$_POST["install_lang"];
	define("WEB_ROOT", realpath(dirname($_SERVER["SCRIPT_FILENAME"])."/../")."/");
        include_once("../includes/i18n.inc.php"); 
	session_start();
	$step="1";
	$error_detected="";
	
	// traitement page 1 - language
	if (isset($_POST["install_lang"]))
	{
		$lang_inc = WEB_ROOT . "lang/lang_" . $_POST["install_lang"] . ".php";
		if ($lang_inc)
		{
			define("PREF_LANG",$_POST["install_lang"]);
			$step="2";
			include ($lang_inc);
		}
		else
	  		$error_detected .= "<LI>Unknown language</LI>";
	 }

	if ($error_detected=="" && isset($_POST["install_type"]))
	{
		if ($_POST["install_type"]=="install")
			$step="i3";
		elseif (substr($_POST["install_type"],0,7)=="upgrade")
			$step="u3";
		else
	  		$error_detected .= "<LI>"._T("Installation mode unknown")."</LI>";
	 }

	if ($error_detected=="" && isset($_POST["install_permsok"]))
	{
		if ($_POST["install_type"]=="install")
			$step="i4";
		elseif (substr($_POST["install_type"],0,7)=="upgrade")
			$step="u4";
		else
	  		$error_detected .= "<LI>"._T("Installation mode unknown")."</LI>";
	 }

	if ($error_detected=="" && isset($_POST["install_dbtype"])  
		&& isset($_POST["install_dbhost"]) 
		&& isset($_POST["install_dbuser"]) 
		&& isset($_POST["install_dbpass"]) 
		&& isset($_POST["install_dbname"])
		&& isset($_POST["install_dbprefix"]))
	{
		if ($_POST["install_dbtype"]!="mysql" && $_POST["install_dbtype"]!="pgsql")
	  		$error_detected .= "<IMG src=\"no.gif\" width=\"6\" height=\"10\" border=\"0\" alt=\"\"> "._T("Database type unknown")."<BR>";
		if ($_POST["install_dbuser"]=="")
	  		$error_detected .= "<IMG src=\"no.gif\" width=\"6\" height=\"10\" border=\"0\" alt=\"\"> "._T("No user name")."<BR>";
		if ($_POST["install_dbpass"]=="")
	  		$error_detected .= "<IMG src=\"no.gif\" width=\"6\" height=\"10\" border=\"0\" alt=\"\"> "._T("No password")."<BR>";
		if ($_POST["install_dbname"]=="")
	  		$error_detected .= "<IMG src=\"no.gif\" width=\"6\" height=\"10\" border=\"0\" alt=\"\"> "._T("No database name")."<BR>";
		if ($error_detected=="")
		{
			if (isset($_POST["install_dbconn_ok"]))
			{
				include(WEB_ROOT."/includes/adodb/adodb.inc.php");
				$DB = ADONewConnection($_POST["install_dbtype"]);
				$DB->debug = false;
				$permsdb_ok = true;
				@$DB->Connect($_POST["install_dbhost"], $_POST["install_dbuser"], $_POST["install_dbpass"], $_POST["install_dbname"]);
				if ($_POST["install_type"]=="install")
					$step="i6";
				elseif (substr($_POST["install_type"],0,7)=="upgrade")
					$step="u6";
					
				if (isset($_POST["install_dbperms_ok"]))
				if ($_POST["install_type"]=="install")
					$step="i7";					
				elseif (substr($_POST["install_type"],0,7)=="upgrade")
					$step="u7";
					
				if (isset($_POST["install_dbwrite_ok"]))
				if ($_POST["install_type"]=="install")
					$step="i8";					
				elseif (substr($_POST["install_type"],0,7)=="upgrade")
					$step="u8";
					
				if (isset($_POST["install_adminlogin"]) && isset($_POST["install_adminpass"]))
				{
					if ($_POST["install_adminlogin"]=="")
				  		$error_detected .= "<IMG src=\"no.gif\" width=\"6\" height=\"10\" border=\"0\" alt=\"\"> "._T("No user name")."<BR>";
					if ($_POST["install_adminpass"]=="")
				  		$error_detected .= "<IMG src=\"no.gif\" width=\"6\" height=\"10\" border=\"0\" alt=\"\"> "._T("No password")."<BR>";
					if ($error_detected=="")
					if ($_POST["install_type"]=="install")
						$step="i9";					
					elseif (substr($_POST["install_type"],0,7)=="upgrade")
						$step="u9";
						
					if (isset($_POST["install_prefs_ok"]))
					if ($_POST["install_type"]=="install")
						$step="i10";					
					elseif (substr($_POST["install_type"],0,7)=="upgrade")
						$step="u10";
				}					
			}
			else
				$step="i5";
		}
	 }
	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> 
<HTML> 
<HEAD> 
	<TITLE>Galette Installation</TITLE> 
	<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1"> 
	<LINK rel="stylesheet" type="text/css" href="../galette.css" > 
</HEAD> 
<H1 class="titreinstall">Galette installation</H1>
<DIV id="installpage" align="center">
<BR>
	
<?
	switch ($step)
	{
		case "1":
?>

	<H1>Welcome to the Galette Install!</H1>
	<P>Please select your administration language</P>
	<FORM action="index.php" method="POST">
		<SELECT name="install_lang">
<?
			$path = "../lang";
			$dir_handle = @opendir($path);
			while ($file = readdir($dir_handle))
			{
				if (substr($file,0,5)=="lang_" && substr($file,-4)==".php")
				{
		        $file = substr(substr($file,5),0,-4);
?>
		<OPTION value="<? echo $file; ?>"><? echo ucfirst($file); ?></OPTION>
<?
				}
			}
			closedir($dir_handle);
?>
		</SELECT>
		<P id="submitbutton3">
			<INPUT type="submit" value="Next Page">
		</P>
	</FORM>
	<BR>
	</DIV>
	<H1 class="footerinstall">Step 1 - Language</H1>

<?
			break;
		case "2":
?>

	<H1><? echo _T("Installation mode"); ?></H1>
	<P><? echo _T("Select installation mode to launch"); ?></P>
	<FORM action="index.php" method="POST">
		<P>
			<INPUT type="radio" name="install_type" value="install" SELECTED> <? echo _T("New installation:"); ?><BR>
		 	<? echo _T("You're installing Galette for the first time, or you wish to erase an older version of Galette without keeping your data"); ?>
		</P>
<?
			$dh = opendir("sql");
			$update_scripts = array();
			while (($file = readdir($dh)) !== false)
			{
				if (ereg("upgrade-to-(.*)-mysql.sql",$file,$ver))
					$update_scripts[] = $ver[1];
			}
			closedir($dh);
			asort($update_scripts);
			$last = "0.00";
			while (list ($key, $val) = each ($update_scripts))
			{
?>
		<P>
			<INPUT type="radio" name="install_type" value="upgrade-<? echo $val; ?>"> <? echo _T("Update:"); ?><BR>
<?
				if ($last!=number_format($val-0.01,2))
					echo _T("Your current Galette version is comprised between")." ".$last." "._T("and")." ".number_format($val-0.01,2)."<br>";
				else
					echo _T("Your current Galette version is")." ".number_format($val-0.01,2)."<br>";
				$last = $val;
				echo _T("Warning: Don't forget to backup your current database.");
?>
		</P>
<?
			}
?>
		<P id="submitbutton3">
			<INPUT type="submit" value="<? echo _T("Next step"); ?>">
		</P>
		<INPUT type="hidden" name="install_lang" value="<? echo $_POST["install_lang"]; ?>">
	</FORM>
	<BR>
	</DIV>
	<H1 class="footerinstall"><? echo _T("Step 2 - Installation mode"); ?></H1>

<?
			break;
?>

<?
			break;
		case "i3":
		case "u3":
?>

	<H1><? echo _T("Files permissions"); ?></H1>
	<P>
         <? echo "files permissions are automatically set with the debian package, enjoy ;c)"; ?>
        </P>
<?
			$perms_ok = true;
			if (!$perms_ok)
			{
?>
	<P>
		<? if ($step=="i3") echo _T("For a correct functioning, Galette needs the Write permission on these files."); ?>
		<? if ($step=="u3") echo _T("In order to be updated, Galette needs the Write permission on these files."); ?>
	</P>
	<P>
		<? echo _T("Under UNIX/Linux, you can give the permissions using those commands"); ?><BR>
		<CODE>chown <I><? echo _T("apache_user"); ?></I> <I><? echo _T("file_name"); ?></I><BR>
		chmod 600 <I><? echo _T("file_name"); ?></I> <? echo _T("(for a file)"); ?><BR>
		chmod 700 <I><? echo _T("direcory_name"); ?></I> <? echo _T("(for a directory)"); ?></CODE>
	<P>
	<P>
		<? echo _T("Under Windows, check these files are not in Read-Only mode in their property panel."); ?>
	<P>
	<FORM action="index.php" method="POST">
		<P id="submitbutton2">
			<INPUT type="submit" value="<? echo _T("Retry"); ?>">
		</P>
		<INPUT type="hidden" name="install_lang" value="<? echo $_POST["install_lang"]; ?>">
		<INPUT type="hidden" name="install_type" value="<? echo $_POST["install_type"]; ?>">
	</FORM>		
<?
			}
			else
			{
?>
	<P><? echo _T("Files permissions are OK!"); ?></P>
	<FORM action="index.php" method="POST">
		<P id="submitbutton3">
			<INPUT type="submit" value="<? echo _T("Next step"); ?>">
		</P>
		<INPUT type="hidden" name="install_lang" value="<? echo $_POST["install_lang"]; ?>">
		<INPUT type="hidden" name="install_type" value="<? echo $_POST["install_type"]; ?>">
		<INPUT type="hidden" name="install_permsok" value="1">
	</FORM>
<?
			}
?>
	<BR>
	</DIV>
	<H1 class="footerinstall"><? echo _T("Step 3 - Permissions"); ?></H1>

<?
			break;
			case "i4":
			case "u4";
?>

	<H1><? echo _T("Database"); ?></H1>
	<P>
<?
				if ($error_detected!="")
					echo "<TABLE><TR><TD>".$error_detected."</TD></TR></TABLE><BR>";
?>	
		<? if ($step=="i4") echo _T("If it hadn't been made, create a database and a user for Galette."); ?><BR>
		<? if ($step=="u4") echo _T("Enter connection data for the existing database."); ?><BR>
		<? echo _T("The needed permissions are CREATE, DROP, DELETE, UPDATE, SELECT and INSERT."); ?></P>
	<FORM action="index.php" method="POST">
		<TABLE>
			<TR>
				<TD><? echo _T("Database type:"); ?></TD>
				<TD>
					<SELECT name="install_dbtype">
						<OPTION value="mysql">MySQL</OPTION>
						<OPTION value="pgsql">PostgreSQL</OPTION>
					</SELECT>
				</TD>
			</TR>
			<TR>
				<TD><? echo _T("Host:"); ?></TD>
				<TD>
					<INPUT type="text" name="install_dbhost" value="<? if(isset($_POST["install_dbhost"])) echo $_POST["install_dbhost"]; ?>">
				</TD>
			</TR>
			<TR>
				<TD><? echo _T("User:"); ?></TD>
				<TD>
					<INPUT type="text" name="install_dbuser" value="<? if(isset($_POST["install_dbuser"])) echo $_POST["install_dbuser"]; ?>">
				</TD>
			</TR>
			<TR>
				<TD><? echo _T("Password:"); ?></TD>
				<TD>
					<INPUT type="password" name="install_dbpass" value="<? if(isset($_POST["install_dbpass"])) echo $_POST["install_dbpass"]; ?>">
				</TD>
			</TR>
			<TR>
				<TD><? echo _T("Database:"); ?></TD>
				<TD>
					<INPUT type="text" name="install_dbname" value="<? if(isset($_POST["install_dbname"])) echo $_POST["install_dbname"]; ?>">
				</TD>
			</TR>					
                        <TR>
                                <TD>
					<? echo _T("Table prefix:"); ?>
				</TD>
                                <TD>
                                        <INPUT type="text" name="install_dbprefix" value="<? if(isset($_POST["install_dbprefix"])) echo $_POST["install_dbprefix"]; else echo "galette_" ?>">
                                </TD>
			</TR>
			<?
				if (substr($_POST["install_type"],0,8)=="upgrade-")
				{
			?>
			<TR>
				<TD colspan="2" style="color: #FF0000; font-weight: bold;">
					<? echo _T("(Indicate the CURRENT prefix of your Galette tables)"); ?>
				</TD>
			</TR>
			<?
				}
			?>
		</TABLE>
		<P id="submitbutton3">
			<INPUT type="submit" value="<? echo _T("Next step"); ?>">
		</P>
		<INPUT type="hidden" name="install_lang" value="<? echo $_POST["install_lang"]; ?>">
		<INPUT type="hidden" name="install_type" value="<? echo $_POST["install_type"]; ?>">
		<INPUT type="hidden" name="install_permsok" value="1">
	</FORM>
	<BR>
	</DIV>
	<H1 class="footerinstall"><? echo _T("Step 4 - Database"); ?></H1>
	
<?
			break;
			case "i5":
			case "u5":
?>

	<H1><? echo _T("Check of the database"); ?></H1>
	<P><? echo _T("Check the parameters and the existence of the database"); ?></P>
<?
				include(WEB_ROOT."/includes/adodb/adodb.inc.php");
				$DB = ADONewConnection($_POST["install_dbtype"]);
				$DB->debug = false;
				$permsdb_ok = true;
				if(!@$DB->Connect($_POST["install_dbhost"], $_POST["install_dbuser"], $_POST["install_dbpass"], $_POST["install_dbname"]))
				{
					$permsdb_ok = false;
					echo "<IMG src=\"no.gif\" width=\"6\" height=\"12\" border=\"0\" alt=\"\"> "._T("Unable to connect to the database")."<BR>";
				}
				else
				{
					echo "<IMG src=\"yes.gif\" width=\"6\" height=\"12\" border=\"0\" alt=\"\"> "._T("Connection to database is OK")."<BR>";
					$DB->Close();
				}

				if (!$permsdb_ok)
				{
?>
	<P><? echo _T("Database can't be reached. Please go back to enter the connection parameters again."); ?></P>
	<FORM action="index.php" method="POST">
		<P id="submitbutton2">
			<INPUT type="submit" value="<? echo _T("Go back"); ?>">
		</P>
		<INPUT type="hidden" name="install_lang" value="<? echo $_POST["install_lang"]; ?>">
		<INPUT type="hidden" name="install_type" value="<? echo $_POST["install_type"]; ?>">
		<INPUT type="hidden" name="install_permsok" value="1">
	</FORM>		
<?
				}
				else
				{
?>
	<P><? echo _T("Database exists and connection parameters are OK."); ?></P>
	<FORM action="index.php" method="POST">
		<P id="submitbutton3">
			<INPUT type="submit" value="<? echo _T("Next step"); ?>">
		</P>
		<INPUT type="hidden" name="install_lang" value="<? echo $_POST["install_lang"]; ?>">
		<INPUT type="hidden" name="install_type" value="<? echo $_POST["install_type"]; ?>">
		<INPUT type="hidden" name="install_permsok" value="1">
		<INPUT type="hidden" name="install_dbtype" value="<? echo $_POST["install_dbtype"]; ?>">
		<INPUT type="hidden" name="install_dbhost" value="<? echo $_POST["install_dbhost"]; ?>">
		<INPUT type="hidden" name="install_dbuser" value="<? echo $_POST["install_dbuser"]; ?>">
		<INPUT type="hidden" name="install_dbpass" value="<? echo $_POST["install_dbpass"]; ?>">
		<INPUT type="hidden" name="install_dbname" value="<? echo $_POST["install_dbname"]; ?>">
                <INPUT type="hidden" name="install_dbprefix" value="<? echo $_POST["install_dbprefix"]; ?>">
		<INPUT type="hidden" name="install_dbconn_ok" value="1">
	</FORM>
<?
				}
?>

	<BR>
	</DIV>
	<H1 class="footerinstall"><? echo _T("Step 5 - Access to the database"); ?></H1>
	

<?
			break;
			case "i6":
			case "u6":
?>


	<H1><? echo _T("Permissions on the base"); ?></H1>
	<P>
		<? if ($step=="i6") echo _T("To run, Galette needs a number of rights on the database (CREATE, DROP, DELETE, UPDATE, SELECT and INSERT)"); ?>
		<? if ($step=="u6") echo _T("In order to be updated, Galette needs a number of rights on the database (CREATE, DROP, DELETE, UPDATE, SELECT and INSERT)"); ?>
	</P>
<?
				$result = "";
				
				// drop de table (si 'test' existe)
				$tables = $DB->MetaTables('TABLES');
				while (list($key,$value)=each($tables))
				{
					if ($value=="galette_test")
					{
						$droptest =1;
						$requete = "DROP table ".$value;
						$DB->Execute($requete);
						if($DB->ErrorNo())
						{
							$error = 1;
							$result = "<IMG src=\"no.gif\" width=\"6\" height=\"12\" border=\"0\" alt=\"\"> "._T("DROP operation not allowed")."<BR>";
						}
						else
							$result = "<IMG src=\"yes.gif\" width=\"6\" height=\"12\" border=\"0\" alt=\"\"> "._T("DROP operation allowed")."<BR>";
					}
				}
					
				// cr�ation de table
				if (!isset($error))
				{	
					// � adapter selon le type de base
					$requete="CREATE table galette_test (testcol text)";
					$DB->Execute($requete);
					if($DB->ErrorNo())
					{
						$result .= "<IMG src=\"no.gif\" width=\"6\" height=\"12\" border=\"0\" alt=\"\"> "._T("CREATE operation not allowed")."<BR>";
						$error = 1;
					}
					else
						$result .= "<IMG src=\"yes.gif\" width=\"6\" height=\"12\" border=\"0\" alt=\"\"> "._T("CREATE operation allowed")."<BR>";
				}
				
				// cr�ation d'enregistrement
				if (!isset($error))
				{	
					// � adapter selon le type de base
					$requete="INSERT INTO galette_test VALUES (".$DB->qstr("test").")";
					$DB->Execute($requete);
					if($DB->ErrorNo())
					{
						$result .= "<IMG src=\"no.gif\" width=\"6\" height=\"12\" border=\"0\" alt=\"\"> "._T("INSERT operation not allowed")."<BR>";
						$error = 1;
					}
					else
						$result .= "<IMG src=\"yes.gif\" width=\"6\" height=\"12\" border=\"0\" alt=\"\"> "._T("INSERT operation allowed")."<BR>";
				}				

				// mise � jour d'enregistrement
				if (!isset($error))
				{	
					// � adapter selon le type de base
					$requete="UPDATE galette_test SET testcol=".$DB->qstr("test");
					$DB->Execute($requete);
					if($DB->ErrorNo())
					{
						$result .= "<IMG src=\"no.gif\" width=\"6\" height=\"12\" border=\"0\" alt=\"\"> "._T("UPDATE operation not allowed")."<BR>";
						$error = 1;
					}
					else
						$result .= "<IMG src=\"yes.gif\" width=\"6\" height=\"12\" border=\"0\" alt=\"\"> "._T("UPDATE operation allowed")."<BR>";
				}				

				// selection d'enregistrement
				if (!isset($error))
				{	
					// � adapter selon le type de base
					$requete="SELECT * FROM galette_test";
					$DB->Execute($requete);
					if($DB->ErrorNo())
					{
						$result .= "<IMG src=\"no.gif\" width=\"6\" height=\"12\" border=\"0\" alt=\"\"> "._T("SELECT operation not allowed")."<BR>";
						$error = 1;
					}
					else
						$result .= "<IMG src=\"yes.gif\" width=\"6\" height=\"12\" border=\"0\" alt=\"\"> "._T("SELECT operation allowed")."<BR>";
				}

				// alter pour la mise � jour
				if (!isset($error) && $step=="u6")
				{	
					// � adapter selon le type de base
					$requete="ALTER TABLE galette_test ADD testalter text";
					$DB->Execute($requete);
					if($DB->ErrorNo())
					{
						$result .= "<IMG src=\"no.gif\" width=\"6\" height=\"12\" border=\"0\" alt=\"\"> "._T("ALTER operation not allowed")."<BR>";
						$error = 1;
					}
					else
						$result .= "<IMG src=\"yes.gif\" width=\"6\" height=\"12\" border=\"0\" alt=\"\"> "._T("ALTER operation allowed")."<BR>";
				}

				// suppression d'enregistrement
				if (!isset($error))
				{	
					// � adapter selon le type de base
					$requete="DELETE FROM galette_test";
					$DB->Execute($requete);
					if($DB->ErrorNo())
					{
						$result .= "<IMG src=\"no.gif\" width=\"6\" height=\"12\" border=\"0\" alt=\"\"> "._T("DELETE operation not allowed")."<BR>";
						$error = 1;
					}
					else
						$result .= "<IMG src=\"yes.gif\" width=\"6\" height=\"12\" border=\"0\" alt=\"\"> "._T("DELETE operation allowed")."<BR>";
				}				

				// suppression de table
				if (!isset($error))
				{	
					// � adapter selon le type de base
					$requete="DROP TABLE galette_test";
					$DB->Execute($requete);
					if (!isset($droptest))
					if($DB->ErrorNo())
					{
						$result .= "<IMG src=\"no.gif\" width=\"6\" height=\"12\" border=\"0\" alt=\"\"> "._T("DROP operation not allowed")."<BR>";
						$error = 1;
					}
					else
						$result .= "<IMG src=\"yes.gif\" width=\"6\" height=\"12\" border=\"0\" alt=\"\"> "._T("DROP operation allowed")."<BR>";
				}				

				if ($result!="")
					echo "<TABLE><TR><TD>".$result."</TD></TR></TABLE>";

				if (isset($error))
				{		
?>
	<P>
		<? if ($step=="i6") echo _T("Galette hasn't got enough permissions on the database to continue the installation."); ?>
		<? if ($step=="u6") echo _T("Galette hasn't got enough permissions on the database to continue the update."); ?>
	</P>
	<FORM action="index.php" method="POST">
		<P id="submitbutton2">
			<INPUT type="submit" value="<? echo _T("Retry"); ?>">
		</P>
		<INPUT type="hidden" name="install_lang" value="<? echo $_POST["install_lang"]; ?>">
		<INPUT type="hidden" name="install_type" value="<? echo $_POST["install_type"]; ?>">
		<INPUT type="hidden" name="install_permsok" value="1">
		<INPUT type="hidden" name="install_dbtype" value="<? echo $_POST["install_dbtype"]; ?>">
		<INPUT type="hidden" name="install_dbhost" value="<? echo $_POST["install_dbhost"]; ?>">
		<INPUT type="hidden" name="install_dbuser" value="<? echo $_POST["install_dbuser"]; ?>">
		<INPUT type="hidden" name="install_dbpass" value="<? echo $_POST["install_dbpass"]; ?>">
		<INPUT type="hidden" name="install_dbname" value="<? echo $_POST["install_dbname"]; ?>">
		<INPUT type="hidden" name="install_dbprefix" value="<? echo $_POST["install_dbprefix"]; ?>">
		<INPUT type="hidden" name="install_dbconn_ok" value="1">
	</FORM>
<?
				}
				else
				{
?>
	<P><? echo _T("Permissions to database are OK."); ?></P>
	<FORM action="index.php" method="POST">
		<P id="submitbutton3">
			<INPUT type="submit" value="<? echo _T("Next step"); ?>">
		</P>
		<INPUT type="hidden" name="install_lang" value="<? echo $_POST["install_lang"]; ?>">
		<INPUT type="hidden" name="install_type" value="<? echo $_POST["install_type"]; ?>">
		<INPUT type="hidden" name="install_permsok" value="1">
		<INPUT type="hidden" name="install_dbtype" value="<? echo $_POST["install_dbtype"]; ?>">
		<INPUT type="hidden" name="install_dbhost" value="<? echo $_POST["install_dbhost"]; ?>">
		<INPUT type="hidden" name="install_dbuser" value="<? echo $_POST["install_dbuser"]; ?>">
		<INPUT type="hidden" name="install_dbpass" value="<? echo $_POST["install_dbpass"]; ?>">
		<INPUT type="hidden" name="install_dbname" value="<? echo $_POST["install_dbname"]; ?>">
		<INPUT type="hidden" name="install_dbprefix" value="<? echo $_POST["install_dbprefix"]; ?>">
		<INPUT type="hidden" name="install_dbconn_ok" value="1">
		<INPUT type="hidden" name="install_dbperms_ok" value="1">
	</FORM>
<?
				}
?>
	<BR>
	</DIV>
	<H1 class="footerinstall"><? echo _T("Step 6 - Access permissions to database"); ?></H1>
	
<?
			break;
		case "i7":
		case "u7":
?>

	<H1>
		<? if ($step=="i7") echo _T("Creation of the database"); ?>
		<? if ($step=="u7") echo _T("Update of the database"); ?>
	</H1>
	<P>
		<? if ($step=="i7") echo _T("Installation Report"); ?>
		<? if ($step=="u7") echo _T("Update Report"); ?>
	</P>
	<TABLE><TR><TD>
<?
			// BEGIN : copyright (2002) The phpBB Group (support@phpbb.com)	
			// Load in the sql parser
			include("sql_parse.php");
			
			$prefix = "";
			$table_prefix = $_POST["install_dbprefix"];
			if ($step=="u7")
			{
				$prefix="upgrade-to-";
				//echo $_POST["install_type"];

				$dh = opendir("sql");
       	                	$update_scripts = array();
				$first_file_found = false;
				while (($file = readdir($dh)) !== false)
				{
					if (ereg("upgrade-to-(.*)-".$_POST["install_dbtype"].".sql",$file,$ver))
					{
						if (substr($_POST["install_type"],8)<=$ver[1])
							$update_scripts[$ver[1]] = $file;
					}
				}
				ksort($update_scripts);
			}
			else
				$update_scripts["current"] = $_POST["install_dbtype"].".sql";

			ksort($update_scripts);
			$sql_query = "";
			while(list($key,$val)=each($update_scripts))
				$sql_query .= @fread(@fopen("sql/".$val, 'r'), @filesize("sql/".$val))."\n";
			
			$sql_query = preg_replace('/galette_/', $table_prefix, $sql_query);
			$sql_query = remove_remarks($sql_query);
			
			$sql_query = split_sql_file($sql_query, ";");
                                                                                                                                                  
			for ($i = 0; $i < sizeof($sql_query); $i++)
			{
				$query = trim($sql_query[$i]);
				if ($query != '' && $query[0] != '-')
				{
					$DB->Execute($query);
					@list($w1, $w2, $w3, $extra) = split(" ", $query, 4);
					if ($extra!="") $extra="...";
					if ($DB->ErrorNo())
					{
						echo "<IMG src=\"no.gif\" width=\"6\" height=\"12\" border=\"0\" alt=\"\"> ".$w1." ".$w2." ".$w3." ".$extra."<BR>";
						if (trim($w1) != "DROP" && trim($w1) != "RENAME") $error = true;
					}
					else
						echo "<IMG src=\"yes.gif\" width=\"6\" height=\"12\" border=\"0\" alt=\"\"> ".$w1." ".$w2." ".$w3." ".$extra."<BR>";
				}
			}
			// END : copyright (2002) The phpBB Group (support@phpbb.com)

?>	
	</TD></TR></TABLE>
	<P><? echo _T("(Errors on DROP and RENAME operations can be ignored)"); ?></P>
	<?
			if (isset($error))
			{
?>
	<P>
		<? if ($step=="i7") echo _T("The database isn't totally created, it's maybe a permission problem."); ?>
		<? if ($step=="u7") echo _T("The database isn't totally updated, it's maybe a permission problem."); ?>
		<? if ($step=="u7") echo _T("Your database is maybe not usable, try to restore the older version."); ?>
	</P>
	<FORM action="index.php" method="POST">
		<P id="submitbutton2">
			<INPUT type="submit" value="<? echo _T("Retry"); ?>">
		</P>
		<INPUT type="hidden" name="install_lang" value="<? echo $_POST["install_lang"]; ?>">
		<INPUT type="hidden" name="install_type" value="<? echo $_POST["install_type"]; ?>">
		<INPUT type="hidden" name="install_permsok" value="1">
		<INPUT type="hidden" name="install_dbtype" value="<? echo $_POST["install_dbtype"]; ?>">
		<INPUT type="hidden" name="install_dbhost" value="<? echo $_POST["install_dbhost"]; ?>">
		<INPUT type="hidden" name="install_dbuser" value="<? echo $_POST["install_dbuser"]; ?>">
		<INPUT type="hidden" name="install_dbpass" value="<? echo $_POST["install_dbpass"]; ?>">
		<INPUT type="hidden" name="install_dbname" value="<? echo $_POST["install_dbname"]; ?>">
		<INPUT type="hidden" name="install_dbprefix" value="<? echo $_POST["install_dbprefix"]; ?>">
		<INPUT type="hidden" name="install_dbconn_ok" value="1">
		<INPUT type="hidden" name="install_dbperms_ok" value="1">
	</FORM>
<?
			}
			else
			{
?>	
	<P>
		<? if ($step=="i7") echo _T("The database has been correctly created."); ?>
		<? if ($step=="u7") echo _T("The database has been correctly updated."); ?>
	</P>
	<FORM action="index.php" method="POST">
		<P id="submitbutton3">
			<INPUT type="submit" value="<? echo _T("Next step"); ?>">
		</P>
		<INPUT type="hidden" name="install_lang" value="<? echo $_POST["install_lang"]; ?>">
		<INPUT type="hidden" name="install_type" value="<? echo $_POST["install_type"]; ?>">
		<INPUT type="hidden" name="install_permsok" value="1">
		<INPUT type="hidden" name="install_dbtype" value="<? echo $_POST["install_dbtype"]; ?>">
		<INPUT type="hidden" name="install_dbhost" value="<? echo $_POST["install_dbhost"]; ?>">
		<INPUT type="hidden" name="install_dbuser" value="<? echo $_POST["install_dbuser"]; ?>">
		<INPUT type="hidden" name="install_dbpass" value="<? echo $_POST["install_dbpass"]; ?>">
		<INPUT type="hidden" name="install_dbname" value="<? echo $_POST["install_dbname"]; ?>">
		<INPUT type="hidden" name="install_dbprefix" value="<? echo $_POST["install_dbprefix"]; ?>">
		<INPUT type="hidden" name="install_dbconn_ok" value="1">
		<INPUT type="hidden" name="install_dbperms_ok" value="1">
		<INPUT type="hidden" name="install_dbwrite_ok" value="1">
	</FORM>
	<?
			}
?>	
	<BR>
	</DIV>
	<H1 class="footerinstall">
		<? if ($step=="i7") echo _T("Step 7 - Database Creation"); ?>
		<? if ($step=="u7") echo _T("Step 7 - Database Update"); ?>
	</H1>
	
<?
			break;
		case "i8":
		case "u8":
?>

	<H1><? echo _T("Admin settings"); ?></H1>
<?
				if ($error_detected!="")
					echo "<P><TABLE><TR><TD>".$error_detected."</TD></TR></TABLE></P>";
?>	
	<P><? echo _T("Please chose the parameters of the admin account on Galette"); ?></P>
	<FORM action="index.php" method="POST">
		<TABLE>
			<TR>
				<TD><? echo _T("Username:"); ?></TD>
				<TD>
					<INPUT type="text" name="install_adminlogin" value="<? if(isset($_POST["install_adminlogin"])) echo $_POST["install_adminlogin"]; ?>">
				</TD>
			</TR>
			<TR>
				<TD><? echo _T("Password:"); ?></TD>
				<TD>
					<INPUT type="text" name="install_adminpass" value="<? if(isset($_POST["install_adminpass"])) echo $_POST["install_adminpass"]; ?>">
				</TD>
			</TR>
		</TABLE>
		<P id="submitbutton3">
			<INPUT type="submit" value="<? echo _T("Next step"); ?>">
		</P>
		<INPUT type="hidden" name="install_lang" value="<? echo $_POST["install_lang"]; ?>">
		<INPUT type="hidden" name="install_type" value="<? echo $_POST["install_type"]; ?>">
		<INPUT type="hidden" name="install_permsok" value="1">
		<INPUT type="hidden" name="install_dbtype" value="<? echo $_POST["install_dbtype"]; ?>">
		<INPUT type="hidden" name="install_dbhost" value="<? echo $_POST["install_dbhost"]; ?>">
		<INPUT type="hidden" name="install_dbuser" value="<? echo $_POST["install_dbuser"]; ?>">
		<INPUT type="hidden" name="install_dbpass" value="<? echo $_POST["install_dbpass"]; ?>">
		<INPUT type="hidden" name="install_dbname" value="<? echo $_POST["install_dbname"]; ?>">
		<INPUT type="hidden" name="install_dbprefix" value="<? echo $_POST["install_dbprefix"]; ?>">
		<INPUT type="hidden" name="install_dbconn_ok" value="1">
		<INPUT type="hidden" name="install_dbperms_ok" value="1">
		<INPUT type="hidden" name="install_dbwrite_ok" value="1">
	</FORM>
	<BR>
	</DIV>
	<H1 class="footerinstall"><? echo _T("Step 8 - Admin parameters"); ?></H1>
	
<?
			break;
		case "i9";
		case "u9";
?>

	<H1><? echo _T("Save the parameters"); ?></H1>
	<P><TABLE><TR><TD>
<?
			// cr�ation du fichier de configuration
			
			if($fd = @fopen (WEB_ROOT ."includes/config.inc.php", "w"))
			{
				$data = "<?
define(\"TYPE_DB\", \"".$_POST["install_dbtype"]."\");
define(\"HOST_DB\", \"".$_POST["install_dbhost"]."\");
define(\"USER_DB\", \"".$_POST["install_dbuser"]."\");
define(\"PWD_DB\", \"".$_POST["install_dbpass"]."\");
define(\"NAME_DB\", \"".$_POST["install_dbname"]."\");
define(\"WEB_ROOT\", \"".WEB_ROOT."\");
define(\"PREFIX_DB\", \"".$_POST["install_dbprefix"]."\");
?>";
				fwrite($fd,$data);
				fclose($fd);	
				echo "<IMG src=\"yes.gif\" width=\"6\" height=\"12\" border=\"0\" alt=\"\"> "._T("Configuration file created (includes/config.inc.php)")."<BR>";
			}
			else
			{
				echo "<IMG src=\"no.gif\" width=\"6\" height=\"12\" border=\"0\" alt=\"\"> "._T("Unable to create configuration file (includes/config.inc.php)")."<BR>";
				$error = true;
			}

			// sauvegarde des parametres
			$default = "DELETE FROM ".$_POST["install_dbprefix"]."preferences";
			$DB->Execute($default);			
			$default = "INSERT INTO ".$_POST["install_dbprefix"]."preferences VALUES (1,'pref_nom','Galette')";
			$DB->Execute($default);			
			$default = "INSERT INTO ".$_POST["install_dbprefix"]."preferences VALUES (2,'pref_adresse','-')";
			$DB->Execute($default);		
			$default = "INSERT INTO ".$_POST["install_dbprefix"]."preferences VALUES (3,'pref_adresse2','')";
			$DB->Execute($default);
			$default = "INSERT INTO ".$_POST["install_dbprefix"]."preferences VALUES (4,'pref_cp','-')";
			$DB->Execute($default);			
			$default = "INSERT INTO ".$_POST["install_dbprefix"]."preferences VALUES (5,'pref_ville','-')";
			$DB->Execute($default);			
			$default = "INSERT INTO ".$_POST["install_dbprefix"]."preferences VALUES (6,'pref_pays','-')";
                        $DB->Execute($default);
			$default = "INSERT INTO ".$_POST["install_dbprefix"]."preferences VALUES (7,'pref_lang',".$DB->qstr($_POST["install_lang"]).")";
			$DB->Execute($default);			
			$default = "INSERT INTO ".$_POST["install_dbprefix"]."preferences VALUES (8,'pref_numrows','30')";
			$DB->Execute($default);			
			$default = "INSERT INTO ".$_POST["install_dbprefix"]."preferences VALUES (9,'pref_log','2')";
			$DB->Execute($default);			
			$default = "INSERT INTO ".$_POST["install_dbprefix"]."preferences VALUES (10,'pref_email_nom','Galette')";
			$DB->Execute($default);			
			$default = "INSERT INTO ".$_POST["install_dbprefix"]."preferences VALUES (11,'pref_email','mail@domain.com')";
			$DB->Execute($default);			
			$default = "INSERT INTO ".$_POST["install_dbprefix"]."preferences VALUES (12,'pref_etiq_marges','10')";
			$DB->Execute($default);			
			$default = "INSERT INTO ".$_POST["install_dbprefix"]."preferences VALUES (13,'pref_etiq_hspace','10')";
			$DB->Execute($default);			
			$default = "INSERT INTO ".$_POST["install_dbprefix"]."preferences VALUES (14,'pref_etiq_vspace','5')";
			$DB->Execute($default);			
			$default = "INSERT INTO ".$_POST["install_dbprefix"]."preferences VALUES (15,'pref_etiq_hsize','90')";
			$DB->Execute($default);			
			$default = "INSERT INTO ".$_POST["install_dbprefix"]."preferences VALUES (16,'pref_etiq_vsize','35')";
			$DB->Execute($default);			
			$default = "INSERT INTO ".$_POST["install_dbprefix"]."preferences VALUES (17,'pref_etiq_cols','2')";
			$DB->Execute($default);			
			$default = "INSERT INTO ".$_POST["install_dbprefix"]."preferences VALUES (18,'pref_etiq_rows','7')";
			$DB->Execute($default);			
			$default = "INSERT INTO ".$_POST["install_dbprefix"]."preferences VALUES (19,'pref_etiq_corps','12')";
			$DB->Execute($default);			
			$default = "INSERT INTO ".$_POST["install_dbprefix"]."preferences VALUES (20,'pref_admin_login',".$DB->qstr($_POST["install_adminlogin"]).")";
			$DB->Execute($default);			
			$default = "INSERT INTO ".$_POST["install_dbprefix"]."preferences VALUES (21,'pref_admin_pass',".$DB->qstr($_POST["install_adminpass"]).")";
			$DB->Execute($default);			
			$default = "INSERT INTO ".$_POST["install_dbprefix"]."preferences VALUES (22,'pref_mail_method','0')";
			$DB->Execute($default);			
			$default = "INSERT INTO ".$_POST["install_dbprefix"]."preferences VALUES (23,'pref_mail_smtp','0')";
			$DB->Execute($default);			
			$default = "INSERT INTO ".$_POST["install_dbprefix"]."preferences VALUES (24,'pref_membership_ext','12')";
			$DB->Execute($default);			
			$default = "INSERT INTO ".$_POST["install_dbprefix"]."preferences VALUES (25,'pref_beg_membership','')";
			
			// NB: il faudrait am�liorer cette partie car la d�tection
			// d'erreur ne s'effectue que sur le dernier insert. Pr�voir une boucle.
			
			$DB->Execute($default);
			if (!$DB->ErrorNo())
				echo "<IMG src=\"yes.gif\" width=\"6\" height=\"12\" border=\"0\" alt=\"\"> "._T("Parameters saved into the database")."<BR>";
			else
			{
				echo "<IMG src=\"no.gif\" width=\"6\" height=\"12\" border=\"0\" alt=\"\"> "._T("Parameters couldn't be save into the database")."<BR>";
				$error = true;
			}
?>
	</TD></TR></TABLE></P>
<?			
			if (!isset($error))
			{
?>
	<FORM action="index.php" method="POST">
		<P id="submitbutton3">
			<INPUT type="submit" value="<? echo _T("Next step"); ?>">
		</P>
		<INPUT type="hidden" name="install_lang" value="<? echo $_POST["install_lang"]; ?>">
		<INPUT type="hidden" name="install_type" value="<? echo $_POST["install_type"]; ?>">
		<INPUT type="hidden" name="install_permsok" value="1">
		<INPUT type="hidden" name="install_dbtype" value="<? echo $_POST["install_dbtype"]; ?>">
		<INPUT type="hidden" name="install_dbhost" value="<? echo $_POST["install_dbhost"]; ?>">
		<INPUT type="hidden" name="install_dbuser" value="<? echo $_POST["install_dbuser"]; ?>">
		<INPUT type="hidden" name="install_dbpass" value="<? echo $_POST["install_dbpass"]; ?>">
		<INPUT type="hidden" name="install_dbname" value="<? echo $_POST["install_dbname"]; ?>">
		<INPUT type="hidden" name="install_dbprefix" value="<? echo $_POST["install_dbprefix"]; ?>">
		<INPUT type="hidden" name="install_dbconn_ok" value="1">
		<INPUT type="hidden" name="install_dbperms_ok" value="1">
		<INPUT type="hidden" name="install_dbwrite_ok" value="1">
		<INPUT type="hidden" name="install_adminlogin" value="<? echo $_POST["install_adminlogin"]; ?>">
		<INPUT type="hidden" name="install_adminpass" value="<? echo $_POST["install_adminpass"]; ?>">
		<INPUT type="hidden" name="install_prefs_ok" value="1">
	</FORM>
<?
			}
			else
			{
?>
	<FORM action="index.php" method="POST">
		<P><? echo _T("Parameters couldn't be saved."); ?></P>
		<P><? echo _T("This can come from the permissions on the file includes/config.inc.php or the impossibility to make an INSERT into the database."); ?></P>
		<P id="submitbutton2">
			<INPUT type="submit" value="<? echo _T("Retry"); ?>">
		</P>
		<INPUT type="hidden" name="install_lang" value="<? echo $_POST["install_lang"]; ?>">
		<INPUT type="hidden" name="install_type" value="<? echo $_POST["install_type"]; ?>">
		<INPUT type="hidden" name="install_permsok" value="1">
		<INPUT type="hidden" name="install_dbtype" value="<? echo $_POST["install_dbtype"]; ?>">
		<INPUT type="hidden" name="install_dbhost" value="<? echo $_POST["install_dbhost"]; ?>">
		<INPUT type="hidden" name="install_dbuser" value="<? echo $_POST["install_dbuser"]; ?>">
		<INPUT type="hidden" name="install_dbpass" value="<? echo $_POST["install_dbpass"]; ?>">
		<INPUT type="hidden" name="install_dbname" value="<? echo $_POST["install_dbname"]; ?>">
		<INPUT type="hidden" name="install_dbprefix" value="<? echo $_POST["install_dbprefix"]; ?>">
		<INPUT type="hidden" name="install_dbconn_ok" value="1">
		<INPUT type="hidden" name="install_dbperms_ok" value="1">
		<INPUT type="hidden" name="install_dbwrite_ok" value="1">
		<INPUT type="hidden" name="install_adminlogin" value="<? echo $_POST["install_adminlogin"]; ?>">
		<INPUT type="hidden" name="install_adminpass" value="<? echo $_POST["install_adminpass"]; ?>">
	</FORM>
<?
			}
?>
	<BR>
	</DIV>
	<H1 class="footerinstall"><? echo _T("Step 9 - Saving of the parameters"); ?></H1>

<?
			break;
		case "i10":
		case "u10":
?>

	<H1>
		<? if ($step=="i10") echo _T("Installation complete !"); ?>
		<? if ($step=="u10") echo _T("Update complete !"); ?>
	</H1>
	<P>
		<? if ($step=="i10") echo _T("Galette has been successfully installed!"); ?>
		<? if ($step=="u10") echo _T("Galette has been successfully updated!"); ?>
	</P>
	<P><? echo _T("For securing the system, please delete the install directory"); ?></P>
	<FORM action="../index.php" method="GET">
		<P id="submitbutton3">
			<INPUT type="submit" value="<? echo _T("Homepage"); ?>">
		</P>
	</FORM>
	<BR>
	</DIV>
	<H1 class="footerinstall">
		<? if ($step=="i10") echo _T("Step 10 - End of the installation"); ?>
		<? if ($step=="u10") echo _T("Step 10 - End of the update"); ?>
	</H1>






<?
			break;
?>


<?
	}
?>	
	
</BODY>
</HTML>
