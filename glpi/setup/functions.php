<?php
/*
 
  ----------------------------------------------------------------------
GLPI - Gestionnaire libre de parc informatique
 Copyright (C) 2002 by the INDEPNET Development Team.
 Bazile Lebeau, baaz@indepnet.net - Jean-Mathieu Dol�ans, jmd@indepnet.net
 http://indepnet.net/   http://glpi.indepnet.org
 ----------------------------------------------------------------------
 Based on:
IRMA, Information Resource-Management and Administration
Christian Bauer, turin@incubus.de 

 ----------------------------------------------------------------------
 LICENSE

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License (GPL)
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 To read the license please visit http://www.gnu.org/copyleft/gpl.html
 ----------------------------------------------------------------------
 Original Author of file:
 Purpose of file:
 ----------------------------------------------------------------------
*/
 

include ("_relpos.php");
// FUNCTIONS Setup

function showFormDropdown ($target,$name,$human) {

	GLOBAL $cfg_layout, $lang;
	
	echo "<div align='center'>&nbsp;<table class='tab_cadre' width='50%'>";
	echo "<tr><th colspan='3'>$human:</th></tr>";
	echo "<form method='post' action=\"$target\">";

	echo "<tr><td align='center' class='tab_bg_1'>";

	dropdown("glpi_dropdown_".$name, "ID");
        // on ajoute un input text pour entrer la valeur modifier
        echo"::>>";
        echo "<input type='text' maxlength='100' size='20' name='value'>";
        //
	echo "</td><td align='center' class='tab_bg_2'>";
	echo "<input type='hidden' name='tablename' value='glpi_dropdown_".$name."'>";
	//  on ajoute un bouton modifier
        echo "<input type='submit' name='update' value='".$lang["buttons"][14]."' class='submit'>";
        echo "</td><td align='center' class='tab_bg_2'>";
        //
        echo "<input type='submit' name='delete' value=\"".$lang["buttons"][6]."\" class='submit'>";
	echo "</td></form></tr>";
	echo "<form action=\"$target\" method='post'>";
	echo "<tr><td align='center'  class='tab_bg_1'>";
	echo "<input type='text' maxlength='100' size='20' name='value'>";
	echo "</td><td align='center' colspan='2' class='tab_bg_2'>";
	echo "<input type='hidden' name='tablename' value='glpi_dropdown_".$name."' >";
	echo "<input type='submit' name='add' value=\"".$lang["buttons"][8]."\" class='submit' class='submit'>";
	echo "</td></form></tr>";
	echo "</table></div>";
}

function showFormTypeDown ($target,$name,$human) {

	GLOBAL $cfg_layout, $lang;
	
	echo "<div align='center'>&nbsp;<table class='tab_cadre' width=50%>";
	echo "<tr><th colspan='3'>$human:</th></tr>";
	echo "<form method='post' action=\"$target\">";

	echo "<tr><td align='center' class='tab_bg_1'>";

	dropdown("glpi_type_".$name, "ID");
	// on ajoute un input text pour entrer la valeur modifier
        echo"::>>";
        echo "<input type='text' maxlength='100' size='20' name='value'>";

	echo "</td><td align='center' class='tab_bg_2'>";
	echo "<input type='hidden' name='tablename' value=\"glpi_type_".$name."\" />";
	//  on ajoute un bouton modifier
        echo "<input type='submit' name='update' value='".$lang["buttons"][14]."' class='submit'>";
	echo "</td><td align='center' class='tab_bg_2'>";
        echo "<input type='submit' name='delete' value=\"".$lang["buttons"][6]."\" class='submit'>";
	echo "</td></form></tr>";
	echo "<form action=\"$target\" method='post'>";
	echo "<tr><td align='center' class='tab_bg_1'>";
	echo "<input type='text' maxlength='100' size='20' name='value'>";
	echo "</td><td align='center' colspan='2' class='tab_bg_2'>";
	echo "<input type='hidden' name='tablename' value='glpi_type_".$name."'>";
	echo "<input type='submit' name='add' value=\"".$lang["buttons"][8]."\" class='submit'>";
	echo "</td></form></tr>";
	echo "</table></div>";
}

function updateDropdown($input) {
	$db = new DB;
	
	$query = "update ".$input["tablename"]." SET name = '".$input["value"]."' where ID = '".$input["ID"]."'";
	if ($result=$db->query($query)) {
		return true;
	} else {
		return false;
	}
}


function addDropdown($input) {

	$db = new DB;
	
	$query = "INSERT INTO ".$input["tablename"]." (NAME) VALUES ('".$input["value"]."')";
	if ($result=$db->query($query)) {
		return true;
	} else {
		return false;
	}
}

function deleteDropdown($input) {

	$db = new DB;
	$send = array();
	$send["tablename"] = $input["tablename"];
	$send["oldID"] = $input["ID"];
	$send["newID"] = "NULL";
	replaceDropDropDown($send);
}

//replace all entries for a dropdown in each items
function replaceDropDropDown($input) {
	$db = new DB;
	$name = getDropdownNameFromTable($input["tablename"]);
	switch($name) {
	case  "hdtype" : case "sndcard" : case "moboard" : case "gfxcard" : case "network" : case "ramtype" : case "processor" :
		$query = "update glpi_computers set ". $name ." = '". $input["newID"] ."'  where ". $name ." = '".$input["oldID"]."'";
		$db->query($query);
		$query = "update glpi_templates set ". $name ." = '". $input["newID"] ."'  where ". $name ." = '".$input["oldID"]."'";
		$db->query($query);
		break;
	case "os" :
		$query = "update glpi_computers set ". $name ." = '". $input["newID"] ."'  where ". $name ." = '".$input["oldID"]."'";
		$db->query($query);
		$query = "update glpi_templates set ". $name ." = '". $input["newID"] ."'  where ". $name ." = '".$input["oldID"]."'";
		$db->query($query);
		$query = "update glpi_software set ". $name ." = '". $input["newID"] ."'  where ". $name ." = '".$input["oldID"]."'";
		$db->query($query);
		break;
	case "iface" :
		$query = "update glpi_networking_ports set ". $name ." = '". $input["newID"] ."'  where ". $name ." = '".$input["oldID"]."'";
		$db->query($query);
		break;
	case "location" :
		$query = "update glpi_computers set ". $name ." = '". $input["newID"] ."'  where ". $name ." = '".$input["oldID"]."'";
		$result = $db->query($query);
		$query = "update glpi_templates set ". $name ." = '". $input["newID"] ."'  where ". $name ." = '".$input["oldID"]."'";
		$result = $db->query($query);
		$query = "update glpi_monitors set ". $name ." = '". $input["newID"] ."'  where ". $name ." = '".$input["oldID"]."'";
		$result = $db->query($query);
		$query = "update glpi_printers set ". $name ." = '". $input["newID"] ."'  where ". $name ." = '".$input["oldID"]."'";
		$result = $db->query($query);
		$query = "update glpi_software set ". $name ." = '". $input["newID"] ."'  where ". $name ." = '".$input["oldID"]."'";
		$result = $db->query($query);
		$query = "update glpi_networking set ". $name ." = '". $input["newID"] ."'  where ". $name ." = '".$input["oldID"]."'";
		$db->query($query);
		break;
	case "monitors" :
		$query = "update glpi_monitors set type = '". $input["newID"] ."'  where type = '".$input["oldID"]."'";
		$result = $db->query($query);
		break;
	case "computers" :
		
		$query = "update glpi_computers set type = '". $input["newID"] ."'  where type = '".$input["oldID"]."'";
		$result = $db->query($query);
		break;
	case "printers" :
		$query = "update glpi_printers set type = '". $input["newID"] ."'  where type = '".$input["oldID"]."'";
		$result = $db->query($query);
		break;
	case "networking" :
		$query = "update glpi_networking set type = '". $input["newID"] ."'  where type = '".$input["oldID"]."'";
		$result = $db->query($query);
		break;
	case "templates" :  
		$query = "update glpi_templates set type = '". $input["newID"] ."'  where type = '".$input["oldID"]."'";
		$result = $db->query($query);
		break;
	}
	$query = "delete from ". $input["tablename"] ." where ID = '". $input["oldID"] ."'";
	$db->query($query);
}

function showDeleteConfirmForm($target,$table, $ID) {
	global $lang;
	
	echo $lang["setup"][63];
	echo $lang["setup"][64];
	echo "<form action=\"". $target ."\" method=\"post\">";
	echo "<input type=\"hidden\" name=\"tablename\" value=\"". $table ."\"  />";
	echo "<input type=\"hidden\" name=\"ID\" value=\"". $ID ."\"  />";
	echo "<input type=\"hidden\" name=\"forcedelete\" value=\"1\" />";
	echo "<input type=\"submit\" name=\"delete\" value=\"Confirmer\" />";
	echo "<form action=\" ". $target ."\" method=\"post\">";
	echo "<input type=\"submit\" name=\"annuler\" value=\"Annuler\" />";
	echo "</form>";
	echo "<br />". $lang["setup"][65];
	echo "<form action=\" ". $target ."\" method=\"post\">";
	dropdownNoValue($table,"newID",$ID);
	echo "<input type=\"hidden\" name=\"tablename\" value=\"". $table ."\"  />";
	echo "<input type=\"hidden\" name=\"oldID\" value=\"". $ID ."\"  />";
	echo "<input type=\"submit\" name=\"replace\" value=\"Remplacer\" />";
	echo "</form>";
}


function getDropdownNameFromTable($table) {

	if(ereg("glpi_type_",$table)){
		$name = ereg_replace("glpi_type_","",$table);
	}
	else {
		if($table == "glpi_dropdown_locations") $name = "location";
		else {
			$name = ereg_replace("glpi_dropdown_","",$table);
		}
	}
	return $name;
}

//check if the dropdown $ID is used into item tables
function dropdownUsed($table, $ID) {

	$db = new DB;
	$name = getDropdownNameFromTable($table);
	$var1 = true;
	switch($name) {
	case  "hdtype" : case "sndcard" : case "moboard" : case "gfxcard" : case "network" : case "ramtype" : case "processor" :
		$query = "Select count(*) as cpt FROM glpi_computers where ". $name ." = ".$ID."";
		$result = $db->query($query);
		if($db->result($result,0,"cpt") > 0)  $var1 = false;
		$query = "Select count(*) as cpt FROM glpi_templates where ". $name ." = ".$ID."";
		$result = $db->query($query);
		if($db->result($result,0,"cpt") > 0)  $var1 = false;
		break;
	case "os" :
		$query = "Select count(*) as cpt FROM glpi_computers where ". $name ." = ".$ID."";
		$result = $db->query($query);
		if($db->result($result,0,"cpt") > 0)  $var1 = false;
		$query = "Select count(*) as cpt FROM glpi_templates where ". $name ." = ".$ID."";
		$result = $db->query($query);
		if($db->result($result,0,"cpt") > 0)  $var1 = false;	
		$query = "Select count(*) as cpt FROM glpi_software where platform = ".$ID."";
		$result = $db->query($query);
		if($db->result($result,0,"cpt") > 0)  $var1 = false;	
		break;
	case "iface" : 
		$query = "Select count(*) as cpt FROM glpi_networking_ports where ". $name ." = ".$ID."";
		$result = $db->query($query);
		if($db->result($result,0,"cpt") > 0)  $var1 = false;
		break;
	case "location" :
		$query = "Select count(*) as cpt FROM glpi_computers where ". $name ." = ".$ID."";
		$result = $db->query($query);
		if($db->result($result,0,"cpt") > 0)  $var1 = false;
		$query = "Select count(*) as cpt FROM glpi_templates where ". $name ." = ".$ID."";
		$result = $db->query($query);
		if($db->result($result,0,"cpt") > 0)  $var1 = false;
		$query = "Select count(*) as cpt FROM glpi_monitors where ". $name ." = ".$ID."";
		$result = $db->query($query);
		if($db->result($result,0,"cpt") > 0)  $var1 = false;
		$query = "Select count(*) as cpt FROM glpi_printers where ". $name ." = ".$ID."";
		$result = $db->query($query);
		if($db->result($result,0,"cpt") > 0)  $var1 = false;
		$query = "Select count(*) as cpt FROM glpi_software where ". $name ." = ".$ID."";
		$result = $db->query($query);
		if($db->result($result,0,"cpt") > 0)  $var1 = false;
		$query = "Select count(*) as cpt FROM glpi_networking where ". $name ." = ".$ID."";
		$result = $db->query($query);
		if($db->result($result,0,"cpt") > 0)  $var1 = false;
		break;
	case "monitors" :
		$query = "Select count(*) as cpt FROM glpi_monitors where type = '".$ID."'";
		$result = $db->query($query);
		if($db->result($result,0,"cpt") > 0)  $var1 = false;
		break;
	case "computers" :
		$query = "Select count(*) as cpt FROM glpi_computers where type = '".$ID."'";
		$result = $db->query($query);
		if($db->result($result,0,"cpt") > 0)  $var1 = false;
		break;
	case "printers" :
		$query = "Select count(*) as cpt FROM glpi_printers where type = '".$ID."'";
		$result = $db->query($query);
		if($db->result($result,0,"cpt") > 0)  $var1 = false;
		break;
	case "networking" :
		$query = "Select count(*) as cpt FROM glpi_networking where type = '".$ID."'";
		$result = $db->query($query);
		if($db->result($result,0,"cpt") > 0)  $var1 = false;
		break;
	case "templates" :
		$query = "Select count(*) as cpt FROM glpi_templates where type = '".$ID."'";
		$result = $db->query($query);
		if($db->result($result,0,"cpt") > 0)  $var1 = false;
		break;
	}
	return $var1;

}

function showPasswordForm($target,$ID) {

	GLOBAL $cfg_layout, $lang;
	
	$user = new User($ID);
	$user->getFromDB($ID);
		
	echo "<form method='post' action=\"$target\">";
	echo "<div align='center'>&nbsp;<table class='tab_cadre' cellpadding='5' width='30%'>";
	echo "<tr><th colspan='2'>".$lang["setup"][11]." '".$user->fields["name"]."':</th></tr>";
	echo "<tr><td width='100%' align='center' class='tab_bg_1'>";
	echo "<input type='password' name='password' size='10'>";
	echo "</td><td align='center' class='tab_bg_2'>";
	echo "<input type='hidden' name='name' value=\"".$user->fields["name"]."\">";
	echo "<input type='submit' name='changepw' value=\"".$lang["buttons"][14]."\" class='submit'>";
	echo "</td></tr>";
	echo "</table></div>";
	echo "</form>";

}

function showUser($back,$ID) {

	GLOBAL $cfg_layout, $lang;
	
	$user = new User($ID);
	$user->getFromDB($ID);

	echo "<center><table border='0' cellpadding='5'>";
	echo "<tr><th colspan='2'>".$lang["setup"][12].": ".$user->fields["name"]."</th></tr>";

	echo "<tr class='tab_bg_1'><td>".$lang["setup"][13].": </td>";
	echo "<td><b>".$user->fields["realname"]."</b></td></tr>";	

	echo "<tr class='tab_bg_1'><td>".$lang["setup"][14].":</td>";
	echo "<td><b><a href=\"mailto:".$user->fields["email"]."\">".$user->fields["email"]."</b></td></tr>";

	echo "<tr class='tab_bg_1'><td>".$lang["setup"][15].": </td>";
	echo "<td><b>".$user->fields["phone"]."</b></td></tr>";	

	echo "<tr class='tab_bg_1'><td>".$lang["setup"][16].": </td>";
	echo "<td><b>".$user->fields["location"]."</b></td></tr>";	

	echo "<tr class='tab_bg_1'><td>".$lang["setup"][17].": </td>";
	echo "<td><b>".$user->fields["type"]."</b></td></tr>";	

	echo "<tr><td colspan='2' height='10'></td></tr>";
	echo "<tr class='tab_bg_2'>";
	echo "<td colspan='2' align='center'><b><a href=\"$back\">".$lang["buttons"][13]."</a></b></td></tr>";
	echo "</table></center>";

}

function listUsersForm($target) {
	
	GLOBAL $cfg_layout,$cfg_install, $lang;
	
	$db = new DB;
	
	$query = "SELECT name FROM glpi_users where name <> 'Helpdesk' ORDER BY type DESC";
	
	if ($result = $db->query($query)) {
                echo "<div align='center'>";
		echo "<table class='tab_cadre'>";
		echo "<tr><th>".$lang["setup"][18]."</th><th>".$lang["setup"][19]."</th>";
		echo "<th>".$lang["setup"][13]."</th><th>".$lang["setup"][20]."</th>";
		echo "<th>".$lang["setup"][14]."</th><th>".$lang["setup"][15]."</th>";
		echo "<th>".$lang["setup"][16]."</th><th colspan='2'></th></tr>";
		
		$i = 0;
		while ($i < $db->numrows($result)) {
			$name = $db->result($result,$i,"name");
			$user = new User($name);
			$user->getFromDB($name);
			
                        
			echo "<tr class='tab_bg_1'><form method='post' action=\"$target\">";	
		
			echo "<td align='center'><b>".$user->fields["name"]."</b>";
			
                        echo "<input type='hidden' name='name' value=\"".$user->fields["name"]."\">";
			echo "</td>";
			echo "<td><input type='password' name='password' value=\"".$user->fields["password"]."\" size='6'></td>";
			
			echo "<td><input name='realname' size='10' value=\"".$user->fields["realname"]."\"></td>";

			echo "<td>";
			echo "<select name='type'>";
			echo "<option value='admin'";
				if ($user->fields["type"]=="admin") { echo " selected"; }
			echo ">Admin";
			echo "<option value=normal";
				if ($user->fields["type"]=="normal") { echo " selected"; }
			echo ">Normal";
			echo "<option value=\"post-only\"";
				if ($user->fields["type"]=="post-only") { echo " selected"; }
			echo ">Post Only";
			echo "</select>";
			echo "</td>";	
			echo "<td><input name='email' size='6' value=\"".$user->fields["email"]."\"></td>";
			echo "<td><input name='phone' size='6' value=\"".$user->fields["phone"]."\"></td>";
			echo "<td>";
				dropdownValue("glpi_dropdown_locations", "location", $user->fields["location"]);
			echo "</td>";
			echo "<td class='tab_bg_2'><input type='submit' name='update' value=\"".$lang["buttons"][7]."\" class='submit'></td>";
			echo "<td class='tab_bg_2'><input type='submit' name='delete' value=\"".$lang["buttons"][6]."\" class='submit'></td>";
			echo "</form></tr>";
			$i++;
		}

		echo "</table>";
		
                echo "<form method='post' action=\"$target\">";
		echo "<table border='0'>";
		echo "<tr><th>Login</th><th>".$lang["setup"][13]."</th><th>".$lang["setup"][20]."</th>";
		echo "<th>".$lang["setup"][14]."</th><th>".$lang["setup"][15]."</th>";
		echo "<th>".$lang["setup"][16]."</th></tr>";
		echo "<tr class='tab_bg_1'>";	
		
		echo "<td><input name='name' size='7' value=\"\"></td>";
		echo "<td><input name='realname' size='15' value=\"\"></td>";
		echo "<td>";
		echo "<select name='type'>";
		echo "<option value='admin'>Admin";
		echo "<option value='normal'>Normal";
		echo "<option value=\"post-only\">Post Only";
		echo "</select>";
		echo "</td>";	
		echo "<td><input name='email' size='15' value=\"\"></td>";
		echo "<td><input name='phone' size='10' value=\"\"></td>";
		echo "<td>";
			dropdownValue("glpi_dropdown_locations", "location", "");
		echo "</td>";
					
		echo "</tr>";
		echo "<tr class='tab_bg_2'>";
		echo "<td colspan='5' align='center'><i>".$lang["setup"][21]."</i></td>";
		echo "<td align='center'>";
		echo "<input type='submit' name='add' value=\"".$lang["buttons"][8]."\" class='submit'>";
		echo "</td>";
		echo "</tr>";

		echo "</table></form></div>";
	}
}


function addUser($input) {
	// Add User, nasty hack until we get PHP4-array-functions

	$user = new User($input["name"]);

	// dump status
	$null = array_pop($input);
	
	// fill array for update
	foreach ($input as $key => $val) {
		if (!isset($user->fields[$key]) || $user->fields[$key] != $input[$key]) {
			$user->fields[$key] = $input[$key];
		}
	}

	if ($user->addToDB()) {
		// Give him some default prefs...
		$query = "INSERT INTO glpi_prefs VALUES ('".$input["name"]."','','english')";
		$db = new DB;
		$result=$db->query($query);
		return true;
	} else {
		return false;
	}
}


function updateUser($input) {
	// Update User in the database

	$user = new User($input["name"]);
	$user->getFromDB($input["name"]); 

 	// dump status
	$null = array_pop($input);

	// password updated?
	if (empty($input["password"])) {
		$user->fields["password"]="";
	}

	// fill array for update
	$x=0;
	foreach ($input as $key => $val) {
		if (empty($input[$key]) ||  $input[$key] != $user->fields[$key]) {
			$user->fields[$key] = $input[$key];
			$updates[$x] = $key;
			$x++;
		}
	}
	if(!empty($updates)) {
		$user->updateInDB($updates);
	}
}

function deleteUser($input) {
	// Delete User
	
	$user = new User($input["name"]);
	$user->deleteFromDB($input["name"]);
} 


function showFormAssign($target)
{

	GLOBAL $cfg_layout,$cfg_install, $lang, $IRMName;
	
	$db = new DB;

	$query = "SELECT name FROM glpi_users where name <> 'Helpdesk' and name <> '".$_SESSION["glpiname"]."' ORDER BY type DESC";
	
	if ($result = $db->query($query)) {

		echo "<div align='center'><table class='tab_cadre'>";
		echo "<tr><th>".$lang["setup"][57]."</th><th colspan='2'>".$lang["setup"][58]."</th>";
		echo "</tr>";
		
		  $i = 0;
		  while ($i < $db->numrows($result)) {
			$name = $db->result($result,$i,"name");
			$user = new User($name);
			$user->getFromDB($name);
			
			echo "<tr class='tab_bg_1'>";	
			echo "<form method='post' action=\"$target\">";
			echo "<td align='center'><b>".$user->fields["name"]."</b>";
			echo "<input type='hidden' name='name' value=\"".$user->fields["name"]."\">";
			echo "</td>";
			echo "<td align='center'><strong>".$lang["setup"][60]."</strong><input type='radio' value='no' name='can_assign_job' ";
			if ($user->fields["can_assign_job"] == 'no') echo "checked ";
      echo ">";
      echo "<td align='center'><strong>".$lang["setup"][61]."</strong><input type='radio' value='yes' name='can_assign_job' ";
			if ($user->fields["can_assign_job"] == 'yes') echo "checked";
      echo ">";
			echo "</td>";
			echo "<td class='tab_bg_2'><input type='submit' name='update' value=\"".$lang["buttons"][7]."\"></td>";
						
                        echo "</form>";
	
      $i++;
			}
echo "</table></div>";}
}

function listTemplates($target) {

	GLOBAL $cfg_layout, $lang;

	$db = new DB;
	$query = "SELECT * FROM glpi_templates";
	if ($result = $db->query($query)) {
		
		echo "<div align='center'><table class='tab_cadre' width='50%'>";
		echo "<tr><th colspan='2'>".$lang["setup"][1].":</th></tr>";
		$i=0;
		while ($i < $db->numrows($result)) {
			$ID = $db->result($result,$i,"ID");
			$templname = $db->result($result,$i,"templname");
			
			echo "<tr>";
			echo "<td align='center' class='tab_bg_1'>";
			echo "<a href=\"$target?ID=$ID&showform=showform\">$templname</a></td>";
			echo "<td align='center' class='tab_bg_2'>";
			echo "<b><a href=\"$target?ID=$ID&delete=delete\">".$lang["buttons"][6]."</a></b></td>";
			echo "</tr>";		

			$i++;
		}

		echo "<tr>";
		echo "<td colspan='2' align='center' class='tab_bg_2'>";
		echo "<b><a href=\"$target?showform=showform\">".$lang["setup"][22]."</a></b>";
		echo "</td>";
		echo "</tr>";
		echo "</form>";
				
		echo "</table></div>";
	}
	

}

function showTemplateForm($target,$ID) {

	GLOBAL $cfg_install, $cfg_layout, $lang;

	$templ = new Template;
	
	if ($ID) {
		$templ->getfromDB($ID);
	}
	else {
		$templ->getEmpty();
	}
	

	echo "<div align='center'><table class='tab_cadre'>";
	echo "<form name='form' method='post' action=$target>";
	echo "<tr><th colspan='2'>";
	if ($ID) {
		echo $lang["setup"][23].": '".$templ->fields["templname"]."'";
	
	} else {
		echo $lang["setup"][23].": <input type='text' name='templname' size='10'>";
	}
	echo "</th></tr>";
	
	echo "<tr><td class='tab_bg_1' valign='top'>";
	echo "<table cellpadding='0' cellspacing='0' border='0'>\n";

	echo "<tr><td>".$lang["setup"][24].":		</td>";
	echo "<td><input type='text' name='name' value=\"".$templ->fields["name"]."\" size='12'></td>";
	echo "</tr>";

	echo "<tr><td>".$lang["setup"][25].": 	</td>";
	echo "<td>";
		dropdownValue("glpi_dropdown_locations", "location", $templ->fields["location"]);
	echo "</td></tr>";

	echo "<tr><td>".$lang["setup"][26].":		</td>";
	echo "<td><input type='text' name='contact_num' value=\"".$templ->fields["contact_num"]."\" size='12'>";
	echo "</td></tr>";
	
	echo "<tr><td>".$lang["setup"][27].":	</td>";
	echo "<td><input type='text' name='contact' size='12' value=\"".$templ->fields["contact"]."\">";
	echo "</td></tr>";

	echo "<tr><td>".$lang["setup"][28].":	</td>";
	echo "<td><input type='text' name='serial' size='12' value=\"".$templ->fields["serial"]."\">";
	echo "</td></tr>";

	echo "<tr><td>".$lang["setup"][29].":	</td>";
	echo "<td><input type='text' size='12' name='otherserial' value=\"".$templ->fields["otherserial"]."\">";
	echo "</td></tr>";

	echo "<tr><td valign='top'>".$lang["setup"][30].":</td>";
	echo "<td><textarea 0 rows='8' name='comments' >".$templ->fields["comments"]."</textarea>";
	echo "</td></tr>";

	echo "</table>";

	echo "</td>\n";	
	echo "<td class='tab_bg_1' valign='top'>\n";
	echo "<table cellpadding='0' cellspacing='0' border='0'";


	echo "<tr><td>".$lang["setup"][31].": 	</td>";
	echo "<td>";
		dropdownValue("glpi_type_computers", "type", $templ->fields["type"]);
	echo "</td></tr>";

	echo "<tr><td>".$lang["setup"][32].": 	</td>";
	echo "<td>";	
		dropdownValue("glpi_dropdown_os", "os", $templ->fields["os"]);
	echo "</td></tr>";
		
	echo "<tr><td>".$lang["setup"][33].":</td>";
	echo "<td><input type='text' size='8' name=osver value=\"".$templ->fields["osver"]."\">";
	echo "</td></tr>";
		
	echo "<tr><td>".$lang["setup"][34].":	</td>";
	echo "<td>";
		dropdownValue("glpi_dropdown_processor", "processor", $templ->fields["processor"]);
	echo "</td></tr>";
	
	echo "<tr><td>".$lang["setup"][35].":	</td>";
	echo "<td><input type='text' name='processor_speed' size='4' value=\"".$templ->fields["processor_speed"]."\">";
	echo "</td></tr>";
	
	echo "<tr><td>".$lang["setup"][49].":	</td>";
	echo "<td>";
		dropdownValue("glpi_dropdown_moboard", "moboard", $templ->fields["moboard"]);
	echo "</td></tr>";

	echo "<tr><td>".$lang["setup"][51].":	</td>";
	echo "<td>";
		dropdownValue("glpi_dropdown_sndcard", "sndcard", $templ->fields["sndcard"]);
	echo "</td></tr>";
		
	echo "<tr><td>".$lang["setup"][50].":	</td>";
	echo "<td>";
		dropdownValue("glpi_dropdown_gfxcard", "gfxcard", $templ->fields["gfxcard"]);
	echo "</td></tr>";
		
	echo "<tr><td>".$lang["setup"][36].":	</td>";
	echo "<td>";
		dropdownValue("glpi_dropdown_ram", "ramtype", $templ->fields["ramtype"]);
	echo "</td></tr>";
	
	echo "<tr><td>".$lang["setup"][37].":	</td>";
	echo "<td><input type='text' name='ram' value=\"".$templ->fields["ram"]."\" size=3>";
	echo "</td></tr>";

	echo "<tr><td>".$lang["setup"][52].":	</td>";
	echo "<td>";
		dropdownValue("glpi_dropdown_hdtype", "hdtype", $templ->fields["hdtype"]);
	echo "</td></tr>";

	echo "<tr><td>".$lang["setup"][38].":	</td>";
	echo "<td><input type='text' name='hdspace' size='3' value=\"".$templ->fields["hdspace"]."\">";
	echo "</td></tr>";

	echo "<tr><td>".$lang["setup"][39].":	</td>";
	echo "<td>";
		dropdownValue("glpi_dropdown_network", "network", $templ->fields["network"]);
	echo "</td></tr>";

//
	
	echo "<tr><td>".$lang["setup"][53].":	</td>";
	echo "<td><input type='text' name='achat_date' readonly size='10' value=\"". $templ->fields["achat_date"] ."\">";
	echo "&nbsp; <input name='button' type='button' class='button' onClick=\"window.open('mycalendar.php?form=form&elem=achat_date','Calendrier','width=200,height=220')\" value='".$lang["buttons"][15]."...'>";
	echo "&nbsp; <input name='button_reset' type='button' class='button' onClick=\"document.forms['form'].achat_date.value='0000-00-00'\" value='reset'>";
  echo "</td></tr>";
	
	echo "<tr><td>".$lang["setup"][54].":	</td>";
	echo "<td><input type='text' name='date_fin_garantie' readonly size='10' value=\"". $templ->fields["date_fin_garantie"] ."\">";
	echo "&nbsp; <input name='button' type='button' class='button' readonly onClick=\"window.open('mycalendar.php?form=form&elem=date_fin_garantie','Calendrier','width=200,height=220')\" value='".$lang["buttons"][15]."...'>";
	echo "&nbsp; <input name='button_reset' type='button' class='button' onClick=\"document.forms['form'].date_fin_garantie.value='0000-00-00'\" value='reset'>";
  echo "</td></tr>";
	
echo "<tr><td>".$lang["setup"][55].":	</td>";
		echo "<td>";
		if ($templ->fields["maintenance"] == 1) {
			echo " OUI <input type='radio' name='maintenance' value='1' checked>";
			echo "&nbsp; &nbsp; NON <input type='radio' name='maintenance' value='0'>";
		} else {
			echo " OUI <input type='radio' name='maintenance' value='1'>";
			echo "&nbsp; &nbsp; NON <input type='radio' name='maintenance' value='0' checked >";
		}
		echo "</td></tr>";


	echo "</table>";

	echo "</td>\n";	
	echo "</tr><tr>";

	if (!empty($ID)) {
		echo "<td class='tab_bg_2' align='center' valign='top' colspan='2'>\n";
		echo "<input type='hidden' name=\"ID\" value=\"".$ID."\">";
		echo "<input type='submit' name=\"update\" value=\"".$lang["buttons"][7]."\" class='submit'>";
		echo "</td></form>\n";	
	} else {
		echo "<td class='tab_bg_2' align=\"center\" valign=\"top\" colspan=\"2\">\n";
		echo "<input type='submit' name=\"add\" value=\"".$lang["buttons"][8]."\" class='submit'>";
		echo "</td></form>\n";	
	}
	
	echo "</tr>\n";
	echo "</table>\n";

	echo "</center>\n";

	echo "</table></div>";
}


function updateTemplate($input) {
	// Update a template in the database

	$templ = new Template;
	$templ->getFromDB($input["ID"],0);

	// dump status
	$null = array_pop($input);
	$updates = array();
	// fill array for update
	$x=0;
	foreach ($input as $key => $val) {
		if ($templ->fields[$key] != $input[$key]) {
			$templ->fields[$key] = $input[$key];
			$updates[$x] = $key;
			$x++;
		}
	}

	$templ->updateInDB($updates);

}

function addTemplate($input) {
	// Add template, nasty hack until we get PHP4-array-functions

	$templ = new Template;

	// dump status
	$null = array_pop($input);
	
	// fill array for update 
	foreach ($input as $key => $val) {
		if (empty($templ->fields[$key]) || $templ->fields[$key] != $input[$key]) {
			$templ->fields[$key] = $input[$key];
		}
	}
	$templ->addToDB();

}

function deleteTemplate($input) {
	// Delete Template
	
	$templ = new Template;
	$templ->deleteFromDB($input["ID"]);
	
} 	

function showSortForm($target,$ID) {

	GLOBAL $cfg_layout, $lang;
	
	$db = new DB;
	$query = "SELECT tracking_order FROM glpi_prefs WHERE (user = '$ID')";
	$result=$db->query($query);

	echo "<div align='center'>&nbsp;<table class='tab_cadre' cellpadding='5' width='30%'>";
	echo "<form method='post' action=\"$target\">";
	echo "<tr><th colspan='2'>".$lang["setup"][40]."</th></tr>";
	echo "<tr><td width='100%' align='center' class='tab_bg_1'>";
	echo "<select name='tracking_order'>";
	echo "<option value=\"yes\"";
	if ($db->result($result,0,"tracking_order")=="yes") { echo " selected"; }	
	echo ">".$lang["choice"][1];
	echo "<option value=\"no\"";
	if ($db->result($result,0,"tracking_order")=="no") { echo " selected"; }	
	echo ">".$lang["choice"][0];
	echo "</select>";
	echo "</td>";
	echo "<td align='center' class='tab_bg_2'>";
	echo "<input type='hidden' name='user' value=\"$ID\">";
	echo "<input type='submit' name='updatesort' value=\"".$lang["buttons"][14]."\" class='submit'>";
	echo "</td></tr>";
	echo "</form>";
	echo "</table></div>";
}

function updateSort($input) {

	$db = new DB;
	$query = "UPDATE glpi_prefs SET tracking_order = '".$input["tracking_order"]."' WHERE (user = '".$input["user"]."')";
	if ($result=$db->query($query)) {
		return true;
	} else {
		return false;
	}
}

function showLangSelect($target,$ID) {

	GLOBAL $cfg_layout, $cfg_install, $lang;
	
	$db = new DB;
	$query = "SELECT language FROM glpi_prefs WHERE (user = '$ID')";
	$result=$db->query($query);

	echo "<form method='post' action=\"$target\">";
	echo "<div align='center'>&nbsp;<table class='tab_cadre' cellpadding='5' width='30%'>";
	echo "<tr><th colspan='2'>".$lang["setup"][41].":</th></tr>";
	echo "<tr><td width='100%' align='center' class='tab_bg_1'>";
	echo "<select name='language'>";
	$i=0;
	while ($i < count($cfg_install["languages"])) {
		echo "<option value=\"".$cfg_install["languages"][$i]."\"";
		if ($db->result($result,0,"language")==$cfg_install["languages"][$i]) { 
			echo " selected"; 
		}
		echo ">".$cfg_install["languages"][$i];
		$i++;
	}
	echo "</select>";
	echo "</td>";
	echo "<td align='center' class='tab_bg_2'>";
	echo "<input type='hidden' name='user' value=\"$ID\">";
	echo "<input type='submit' name='changelang' value=\"".$lang["buttons"][14]."\" class='submit'>";
	echo "</td></tr>";
	echo "</table></div>";
	echo "</form>";
}

function updateLanguage($input) {

	$db = new DB;
	$query = "UPDATE glpi_prefs SET language = '".$input["language"]."' WHERE (user = '".$input["user"]."')";
	if ($result=$db->query($query)) {
		return true;
	} else {
		return false;
	}
}

?>
