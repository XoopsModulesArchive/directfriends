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
include("../../mainfile.php");
include("../../header.php");

$xoopsOption['show_rblock'] =0;
	
	OpenTable();
//************************************************************

include("home.php");

//************************************************************	
	CloseTable();
include("../../footer.php");
?>