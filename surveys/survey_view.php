<?php
/**
 * survey_view.php along with survey_view.php provides a sample web application
 *
 * The difference between demo_list.php and index.php is the reference to the 
 * Pager class which processes a mysqli SQL statement and spans records across multiple  
 * pages. 
 *
 * The associated view page, survey_view.php is virtually identical to demo_view.php. 
 * The only difference is the pager version links to the list pager version to create a 
 * separate application from the original list/view. 
 * 
 * @package nmPager
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 3.02 2011/05/18
 * @link http://www.newmanix.com/
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @see index.php
 * @todo none
 */

# '../' works for a sub-folder.  use './' for the root  
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
 
# check variable of item passed in - if invalid data, forcibly redirect back to index.php page
if(isset($_GET['id']) && (int)$_GET['id'] > 0){#proper data must be on querystring
	 $myID = (int)$_GET['id']; #Convert to integer, will equate to zero if fails
}else{
	myRedirect(VIRTUAL_PATH . "demo/index.php");
}

//sql statement to select individual item
$sql = "select MuffinName,Description,MetaDescription,MetaKeywords,Price from test_Muffins where MuffinID = " . $myID;
//---end config area --------------------------------------------------

$foundRecord = FALSE; # Will change to true, if record found!
   
# connection comes first in mysqli (improved) function
$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));

if(mysqli_num_rows($result) > 0)
{#records exist - process
	   $foundRecord = TRUE;	
	   while ($row = mysqli_fetch_assoc($result))
	   {
			$MuffinName = dbOut($row['MuffinName']);
			$Description = dbOut($row['Description']);
			$Price = (float)$row['Price'];
			$MetaDescription = dbOut($row['MetaDescription']);
			$MetaKeywords = dbOut($row['MetaKeywords']);
	   }
}

@mysqli_free_result($result); # We're done with the data!

if($foundRecord)
{#only load data if record found
	$config->titleTag = $MuffinName . " muffins made with PHP & love!"; #overwrite PageTitle with Muffin info!
	#Fills <meta> tags.  Currently we're adding to the existing meta tags in config_inc.php
	$config->metaDescription = $MetaDescription . ' Seattle Central\'s ITC280 Class Muffins are made with pure PHP! ' . $config->metaDescription;
	$config->metaKeywords = $MetaKeywords . ',Muffins,PHP,Fun,Bran,Regular,Regular Expressions,'. $config->metaKeywords;
}
/*
$config->metaDescription = 'Web Database ITC281 class website.'; #Fills <meta> tags.
$config->metaKeywords = 'SCCC,Seattle Central,ITC281,database,mysql,php';
$config->metaRobots = 'no index, no follow';
$config->loadhead = ''; #load page specific JS
$config->banner = ''; #goes inside header
$config->copyright = ''; #goes inside footer
$config->sidebar1 = ''; #goes inside left side of page
$config->sidebar2 = ''; #goes inside right side of page
$config->nav1["page.php"] = "New Page!"; #add a new page to end of nav1 (viewable this page only)!!
$config->nav1 = array("page.php"=>"New Page!") + $config->nav1; #add a new page to beginning of nav1 (viewable this page only)!!
*/
# END CONFIG AREA ---------------------------------------------------------- 

get_header(); #defaults to theme header or header_inc.php
?>
<h3 align="center"><?=smartTitle();?></h3>

<p>This page, along with <b>index.php</b>, demonstrate a List/View web application.</p>
<p>It was built on the mysqli shared web application page, <b>demo_shared.php</b></p>
<p>This page is to be used only with <b>index.php</b>, and is <b>NOT</b> the entry point of the application, meaning this page gets <b>NO</b> link on your web site.</p>
<p>Use <b>index.php</b> and <b>survey_view.php</b> as a starting point for building your own List/View web application!</p> 
<?php
if($foundRecord)
{#records exist - show muffin!
?>
	<h3 align="center">A Yummy <?=$MuffinName;?> Muffin!</h3>
	<div align="center"><a href="<?=VIRTUAL_PATH;?>demo/index.php">More Muffins?!?</a></div>
	<table align="center">
		<tr>
			<td><img src="<?=VIRTUAL_PATH;?>upload/m<?=$myID;?>.jpg" /></td>
			<td>We make fresh <?=$MuffinName;?> muffins daily!</td>
		</tr>
		<tr>
			<td colspan="2">
				<blockquote><?=$Description;?></blockquote>
			</td>
		</tr>
		<tr>
			<td align="center" colspan="2">
				<h3><i>ONLY!!:</i> <font color="red">$<?=number_format($Price,2);?></font></h3>
			</td>
		</tr>
	</table>
<?
}else{//no such muffin!
    echo '<div align="center">What! No such muffin? There must be a mistake!!</div>';
    echo '<div align="center"><a href="' . VIRTUAL_PATH . 'demo/index.php">Another Muffin?</a></div>';
}

get_footer(); #defaults to theme footer or footer_inc.php
?>
