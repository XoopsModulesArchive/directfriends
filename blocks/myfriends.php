<?php
#####################################################
#  Direct Friends module for Xoops RC3
#  by P4
#  p4@directfriends.com - http://www.directfriends.com
#  Licence: GPL
#
# Thank you to leave this copyright in place...
#####################################################
//  This program is free software; you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation; either version 2 of the License, or
//  (at your option) any later version.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program; if not, write to the Free Software
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA
# --------------------------------------------------------------

function myfriends_show() {
global $xoopsConfig, $xoopsDB, $xoopsUser, $xoopsTheme, $xoopsLogger, $xoopsMF;
//########################
if(file_exists($xoopsConfig['root_path']."modules/directfriends/language/".$xoopsConfig['language']."/main.php")){
	include($xoopsConfig['root_path']."modules/directfriends/language/".$xoopsConfig['language']."/main.php");
}else{
	include($xoopsConfig['root_path']."modules/directfriends/language/english/main.php");
}
//########################
if ($xoopsUser) {
$block = array();
$block['title'] = _MF_TITLE;
$block['content'] = "";
        $myid=$xoopsUser->uid();
        $linkID = @mysql_connect($xoopsConfig["dbhost"],$xoopsConfig["dbuname"],$xoopsConfig["dbpass"]);
        mysql_select_db($xoopsConfig["dbname"],$linkID);
        $p = $xoopsConfig["prefix"];
        //select online / last users
        $sqlstr8="SELECT uid, time FROM ".$xoopsDB->prefix("sessions")."";
        $result8 = mysql_query($sqlstr8);
        while (list($uid, $time)=mysql_fetch_row($result8)) {
            //echo "e=$uid<br />";
            $isOnline[$uid]=1;
            }
        //select users
        $sqlstr ="SELECT uid, uname, actkey FROM ".$xoopsDB->prefix("users")." WHERE level>0 AND uid!=$myid";
        $resultID = mysql_query($sqlstr);
        //select my friends
        $sqlstr1="SELECT uid, fuid FROM ".$xoopsDB->prefix("myfriends")." WHERE uid=$myid ORDER BY fuid ASC";
        $req1=mysql_query($sqlstr1);
        $numfriends=0;
        $ismyfriend=array();
        while (list($uid,$fuid) = mysql_fetch_row($req1)) {
                $ismyfriend[$fuid]=1;
                $numfriends++;
                }
        //begin of html
        $block['content'].="<table cellspacing='0' cellpadding='3' border='0' align='center'>";
        $numfriends=$numfriends-1;
        while (list($uid,$uname, $actkey) = mysql_fetch_row($resultID)) {
               if ($ismyfriend[$uid]==1) {
                     $block['content'].="<tr><td valign='center'>";
                     if ($isOnline[$uid]==1) {
                           //$block['content'].="<b>!on!&nbsp;</b>";
                           $block['content'].="<img src=\"".$xoopsConfig['xoops_url']."/modules/directfriends/images/greendot.gif\">";
                           }
                     /*else {
                           $block['content'].="<img src=\"".$xoopsConfig['xoops_url']."/modules/myfriends/images/reddot.gif\">";
                           }*/
                     $block['content'].="</td><td valign='center'>";
                     $block['content'].="<a href='javascript:openWithSelfMain(\"".$xoopsConfig['xoops_url']."/pmlite.php?send2=1&amp;to_userid=".$uid."\",\"pmlite\",450,370);'>".ucfirst($uname)."</a>";
                     $block['content'].="<br />";
                     $block['content'].="</td></tr>";
                     }
               }
        $block['content'].="</table>";
        $block['content'].="<br /><center> [ <a href=\"".$xoopsConfig['xoops_url']."/modules/directfriends/index.php\">"._MF_BLOCKLIST."</a> ]</center>";
        return $block;
        }
}
?>
