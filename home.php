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

include("header.php");
if (!$xoopsUser) {
        header("Location: /index.php");
}

//include("include/config.php");
echo "<br /><h1>"._MF_TITLE."</h1>";

function friendExists() {
echo "<br />"._MF_EXISTS."<br /><br />";
echo "<a href='index.php'>"._MF_BACKTOLIST."</a>";
}

//########################################----------------------------------###################################

function displayFriendsList($beg_in) {
global $xoopsConfig, $xoopsDB, $xoopsUser, $xoopsTheme, $xoopsLogger, $xoopsMF;
$myid=$xoopsUser->uid();
$p = $xoopsConfig["prefix"];
//##
if (is_numeric($beg_in)==false) {
        $beg_in=0;
        }
else {
if ($beg_in<1) {
        $beg_in=0;
        }
}
//##
$tranche=20;
$inf=$beg_in;
$sup=$beg_in+$tranche;
//select my friends
$sqlstr="SELECT fuid FROM ".$xoopsDB->prefix("myfriends")." WHERE uid=$myid LIMIT $inf, $tranche";
//count my friends
$sqlstr2="SELECT Count(*) FROM ".$xoopsDB->prefix("myfriends")." WHERE uid=$myid";
$result2 = $xoopsDB->query($sqlstr2) or die($xoopsDB->error() );
while (list($rep) = $xoopsDB->fetchRow($result2)) {
        $numfriends = $rep;
        }
$resultzz = $xoopsDB->query($sqlstr) or die($xoopsDB->error() );
//##links for next/previous pages
$prevlink="";
$nextlink="";
if ($sup<$numfriends) {
$nextlink="<a href='index.php?beg=$sup'>"._MF_NEXT."</a>";
$aff_sup=$sup;
}
else {
$aff_sup=$numfriends;
}
$prevn=$inf-$tranche;
if ($prevn>=0) {
$prevlink="<a href='index.php?beg=$prevn'>"._MF_PREVIOUS."</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$aff_inf=$inf+1;
}
else {
$aff_inf=1;
}
//num pages
$numz=0;
$numpage=1;
$pagesLinks="";
while ($numz<$numfriends) {
$pagesLinks.="<a href='index.php?&beg=$numz'>$numpage</a>&nbsp;&nbsp;";
$numz+=$tranche;
$numpage++;
}
//##
//begin of html
echo "<h3>"._MF_FRIENDSLIST."</h3>";
echo "<br /><center>";
echo "[ <a href='index.php?action=mod'>"._MF_FRIENDSLIST_MOD."</a> ]";
echo "<br /><br />";
echo "</center>";
echo "<table cellspacing='0' cellpadding='3' border='1' align='center' width='70%'>";
echo "<tr><td colspan='4' align='center' class='even'><br />"._MF_FRIENDSLIST_HAVE."<b>$numfriends</b>"._MF_FRIENDSLIST_ACTUAL."<br /><br /></td></tr>";
while ($userinfo = $xoopsDB->fetchArray($resultzz) ) {
        $userinfo = new XoopsUser($userinfo['fuid']);
        $zuid=$userinfo->uid();
        $zuname=$userinfo->uname();
        $zavatar=$userinfo->user_avatar();
        echo "<tr><td align='center'>";
        echo "<img src=\"".$xoopsConfig['xoops_url']."/uploads/".$zavatar."\" name=\"avatar\" id=\"avatar\">";
        echo "</td><td>";
        echo "<a href='/userinfo.php?uid=$zuid'>".ucfirst($zuname)."</a>";
        echo "</td><td>";
        echo "<a href='javascript:openWithSelfMain(\"".$xoopsConfig['xoops_url']."/pmlite.php?send2=1&amp;to_userid=".$zuid."\",\"pmlite\",450,370);'>"._MF_FRIENDSLIST_SENDPM."</a>";
        echo "</td><td>";
        echo "<a href=\"index.php?action=retirer&uid=$zuid\">"._MF_FRIENDSLIST_OUT."</a>";
        echo "</td></tr>";
        }
echo "</table>";
echo "<br /><br /><br /><center>";
if (!isset($let_in)) {
        echo _MF_TITLE." $aff_inf "._MF_TO." $aff_sup<br /><br />";
        echo $prevlink.$nextlink;
        if ($numpage>2) {
                echo "<br /><br /> "._MF_PAGES." ";
                echo $pagesLinks;
                }
        }
echo "</center>";
}

//########################################----------------------------------###################################

function displayUsersList($beg_in, $let_in) {
global $xoopsConfig, $xoopsDB, $xoopsUser, $xoopsTheme, $xoopsLogger, $xoopsMF;
$myid=$xoopsUser->uid();
$p = $xoopsConfig["prefix"];
//##
if (is_numeric($beg_in)==false) {
        $beg_in=0;
        }
else {
if ($beg_in<1) {
        $beg_in=0;
        }
}
if ($let_in) {
$let_in=strip_tags($let_in);
}
//##
$tranche=20;
$inf=$beg_in;
$sup=$beg_in+$tranche;
//select users
$sqlstr ="SELECT uid, uname, level FROM ".$xoopsDB->prefix("users")." WHERE level>0 AND uid!=$myid LIMIT $inf, $tranche";
//select my friends
$sqlstr1="SELECT uid, fuid FROM ".$xoopsDB->prefix("myfriends")." WHERE uid=$myid ORDER BY fuid ASC";
$result1 = $xoopsDB->query($sqlstr1) or die($xoopsDB->error() );
//count my friends
$sqlstr2="SELECT Count(*) from ".$xoopsDB->prefix("users")." WHERE level>0 AND uid!=$myid";
$result2 = $xoopsDB->query($sqlstr2) or die($xoopsDB->error() );
while (list($rep) = $xoopsDB->fetchRow($result2)) {
        $numusers = $rep;
        }
$ismyfriend=array();
while (list($uid,$fuid) = $xoopsDB->fetchRow($result1) ) {
        $ismyfriend[$fuid]=1;
        }
//letters
if ($let_in) {
$let_in1=strtoupper($let_in);
$let_in2=strtolower($let_in);
$sqlstr="SELECT uid, uname, level FROM ".$xoopsDB->prefix("users")." WHERE (((uname LIKE '$let_in1%') OR (uname LIKE '$let_in2%')) AND level>0 AND uid!=$myid)";
}
$result = $xoopsDB->query($sqlstr) or die($xoopsDB->error() );
//##links for next/previous pages
$prevlink="";
$nextlink="";
if ($sup<$numusers) {
$nextlink="<a href='index.php?action=mod&beg=$sup'>"._MF_NEXT."</a>";
$aff_sup=$sup;
}
else {
$aff_sup=$numusers;
}
$prevn=$inf-$tranche;
if ($prevn>=0) {
$prevlink="<a href='index.php?action=mod&beg=$prevn'>"._MF_PREVIOUS."</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$aff_inf=$inf+1;
}
else {
$aff_inf=1;
}
//num pages
$numz=0;
$numpage=1;
$pagesLinks="";
while ($numz<$numusers) {
$pagesLinks.="<a href='index.php?action=mod&beg=$numz'>$numpage</a>&nbsp;&nbsp;";
$numz+=$tranche;
$numpage++;
}
//##
//begin of html
echo "<h3>"._MF_FRIENDSLIST_MOD."</h3>";
echo "<br /><center>";
echo "[ <a href='index.php'>"._MF_BACKTOLIST."</a> ]";
echo "<br /><br />";
echo "<a href='index.php?action=mod'>"._MF_ALL."</a>&nbsp;&nbsp;";
echo "<a href='index.php?action=mod&letter=A'>A</a>&nbsp;&nbsp;";
echo "<a href='index.php?action=mod&letter=B'>B</a>&nbsp;&nbsp;";
echo "<a href='index.php?action=mod&letter=C'>C</a>&nbsp;&nbsp;";
echo "<a href='index.php?action=mod&letter=D'>D</a>&nbsp;&nbsp;";
echo "<a href='index.php?action=mod&letter=E'>E</a>&nbsp;&nbsp;";
echo "<a href='index.php?action=mod&letter=F'>F</a>&nbsp;&nbsp;";
echo "<a href='index.php?action=mod&letter=G'>G</a>&nbsp;&nbsp;";
echo "<a href='index.php?action=mod&letter=H'>H</a>&nbsp;&nbsp;";
echo "<a href='index.php?action=mod&letter=I'>I</a>&nbsp;&nbsp;";
echo "<a href='index.php?action=mod&letter=J'>J</a>&nbsp;&nbsp;";
echo "<a href='index.php?action=mod&letter=K'>K</a>&nbsp;&nbsp;";
echo "<a href='index.php?action=mod&letter=L'>L</a>&nbsp;&nbsp;";
echo "<a href='index.php?action=mod&letter=M'>M</a>&nbsp;&nbsp;";
echo "<a href='index.php?action=mod&letter=N'>N</a>&nbsp;&nbsp;";
echo "<a href='index.php?action=mod&letter=O'>O</a>&nbsp;&nbsp;";
echo "<a href='index.php?action=mod&letter=P'>P</a>&nbsp;&nbsp;";
echo "<a href='index.php?action=mod&letter=Q'>Q</a>&nbsp;&nbsp;";
echo "<a href='index.php?action=mod&letter=R'>R</a>&nbsp;&nbsp;";
echo "<a href='index.php?action=mod&letter=S'>S</a>&nbsp;&nbsp;";
echo "<a href='index.php?action=mod&letter=T'>T</a>&nbsp;&nbsp;";
echo "<a href='index.php?action=mod&letter=U'>U</a>&nbsp;&nbsp;";
echo "<a href='index.php?action=mod&letter=V'>V</a>&nbsp;&nbsp;";
echo "<a href='index.php?action=mod&letter=W'>W</a>&nbsp;&nbsp;";
echo "<a href='index.php?action=mod&letter=X'>X</a>&nbsp;&nbsp;";
echo "<a href='index.php?action=mod&letter=Y'>Y</a>&nbsp;&nbsp;";
echo "<a href='index.php?action=mod&letter=Z'>Z</a>&nbsp;&nbsp;";
echo "<br /><br />";
echo "</center>";
echo "<table cellspacing='0' cellpadding='3' border='1' align='center' width='70%'>";
while ($userinfo = $xoopsDB->fetchArray($result) ) {
        $userinfo = new XoopsUser($userinfo['uid']);
        $zuid=$userinfo->uid();
        $zuname=$userinfo->uname();
        $zavatar=$userinfo->user_avatar();
        echo "<tr><td align='center'>";
        echo "<img src=\"".$xoopsConfig['xoops_url']."/uploads/".$zavatar."\" name=\"avatar\" id=\"avatar\">";
        echo "</td><td>";
        if ($ismyfriend[$zuid]!=1) {
                echo ucfirst($zuname);
                }
        else {
                echo "<font color='#D13313'><b>".ucfirst($zuname)."</b></font>";
                }
        echo "</td><td>";
        if ($ismyfriend[$zuid]!=1) {
                echo "<a href=\"index.php?action=ajouter&uid=$zuid\">"._MF_FRIENDSLIST_IN."</a>";
                }
        else {
                echo "<a href=\"index.php?action=retirer&uid=$zuid\">"._MF_FRIENDSLIST_OUT."</a>";
                }
        echo "</td></tr>";
        }
echo "</table>";
echo "<br /><br /><br /><center>";
if (!isset($let_in)) {
echo _MF_MEMBERS." ".$aff_inf." ". _MF_TO." ".$aff_sup."<br /><br />";
echo $prevlink.$nextlink;
if ($numpage>2) {
        echo "<br /><br /> "._MF_PAGES." ";
        echo $pagesLinks;
        }
}
echo "</center>";
}

function addFriend($fuid_in) {
global $xoopsConfig, $xoopsDB, $xoopsUser, $xoopsTheme, $xoopsLogger, $xoopsMF;
$myid=$xoopsUser->uid();
$linkID = @mysql_connect($xoopsConfig["dbhost"],$xoopsConfig["dbuname"],$xoopsConfig["dbpass"]);
mysql_select_db($xoopsConfig["dbname"],$linkID);
$p = $xoopsConfig["prefix"];
//control if $fuid is not already a friend
$sqlstr1="SELECT uid, fuid FROM ".$xoopsDB->prefix("myfriends")." WHERE uid=$myid ORDER BY fuid ASC";
$req1=mysql_query($sqlstr1);
while (list($uid, $fuid) = mysql_fetch_row($req1)) {
        if ($fuid==$fuid_in) {
                friendExists();
                $doNot=1;
                }
        }
//add a friend in database
//& secure? &
if (is_numeric($fuid_in)==false) {
        header("Location: ./index.php");
        }
//&&
if ($doNot!=1) {
        $sqlstr ="INSERT INTO ".$xoopsDB->prefix("myfriends")." (uid, fuid) VALUES ($myid, $fuid_in)";
        $req=mysql_query($sqlstr);
        if ($req) {
                echo "<table align='center' cellpadding='0' border='0'><tr><td align='center'>";
                echo "<br /><br /><b>"._MF_FRIENDADDED."</b>";
                echo "<br /><br /><br /><a href='index.php?action=mod'>"._MF_BACKTOMODPAGE."</a>";
                echo "<br /><br /><a href='index.php'>"._MF_BACKTOLIST."</a>";
                echo "</td></tr></table>";
                }
        else {
                echo _MF_PROBLEM;
                }
        }
}

function deleteFriend($fuid_in) {
//& secure? &
if (is_numeric($fuid_in)==false) {
        header("Location: ./index.php");
        }
global $xoopsConfig, $xoopsDB, $xoopsUser, $xoopsTheme, $xoopsLogger, $xoopsMF;
$myid=$xoopsUser->uid();
$linkID = @mysql_connect($xoopsConfig["dbhost"],$xoopsConfig["dbuname"],$xoopsConfig["dbpass"]);
mysql_select_db($xoopsConfig["dbname"],$linkID);
$p = $xoopsConfig["prefix"];
$sqlstr="DELETE FROM ".$xoopsDB->prefix("myfriends")." WHERE (uid=$myid AND fuid=$fuid_in)";
$req1=mysql_query($sqlstr);
echo "<table align='center' cellpadding='0' border='0'><tr><td align='center'>";
if ($req1) {
        echo "<br /><br /><b>"._MF_FRIEND_DELETED."</b>";
        }
echo "<br /><br /><br /><a href='index.php?action=mod'>"._MF_BACKTOMODPAGE."</a>";
echo "<br /><br /><a href='index.php'>"._MF_BACKTOLIST."</a>";
echo "</td></tr></table>";
}

switch ($action) {
default:
        displayFriendsList($beg);
        break;
case "mod":
        displayUsersList($beg, $letter);
        break;
case "ajouter":
        addFriend($uid);
        break;
case "retirer":
        deleteFriend($uid);
        break;
}
?>
