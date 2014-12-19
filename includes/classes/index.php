<?php
//session_start();
require_once("includes/application-top.php"); 
date_default_timezone_set('UTC');

$dbObj = new DB();
$dbObj->fun_db_connect();

$catObj = new Category();
$linkObj = new Links();
$listObj = new Listing();
$newsObj = new News();




$googObj = new Googlead();


$siteObj = new SitePages();


$arr6=$catObj->funGetActivatorsCategory8();

$arr7=$catObj->funGetActivatorsCategory8();

$rsgoog=$googObj->fun_getGoogleadMain();

$rowgoogle=mysql_fetch_array($rsgoog);


$getH7Page=$siteObj->funGetSitePagesInfo('14');

$getH7CorporatePage=$siteObj->funGetSitePagesInfo('127');

$getH7latestPage=$siteObj->funGetSitePagesInfo('124');

$sqlsnap="select * from ".TABLE_EVENT_PHOTO." order by updated_on desc limit 0,1 ";
$rssnap=mysql_query($sqlsnap) or die(mysql_error());
$snapshot=mysql_fetch_array($rssnap);

$sqlsnap1="select * from h7_image where status=1 and approve=1 order by image_last_update desc limit 0,1 ";
$rssnap1=mysql_query($sqlsnap1) or die(mysql_error());
$snapshot1=mysql_fetch_array($rssnap1);

if(strtotime($snapshot['updated_on'])>strtotime($snapshot1['image_last_update']))
{
$showimage=$snapshot['photo_url'];
$showcat=$snapshot['main_cat_id'];

}

else
{
$showimage=$snapshot1['photo_url'];
$showcat=$snapshot1['main_cat_id'];

}

//echo ">>>".$showimage;

$latestblog="select * from wp_h7posts where post_status='publish' and post_type='post'  order by post_modified desc limit 0,5";
$rsblog=mysql_query($latestblog) or die(mysql_error());

$num=mysql_num_rows($arr6);

$mod=$num%3;
if($mod==0)
$googlect=0;
else

$googlect=3-$mod;

//print_r($arr);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome to H7 Activate</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/styles.css" rel="stylesheet" type="text/css" />

<!--dropdown-->
<script type="text/javascript" src="js/menu.js"></script>
<link rel="stylesheet" type="text/css" href="css/menu.css" />
<!--dropdwon ends-->

<!--js code start-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="js/ddsmoothmenu.js">

/***********************************************
* Smooth Navigational Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

</script>
<script type="text/javascript" src="Scripts/ddaccordion.js"></script>
<script type="text/javascript">

//Initialize first demo:
ddaccordion.init({
	headerclass: "mypets", //Shared CSS class name of headers group
	contentclass: "thepet", //Shared CSS class name of contents group
	revealtype: "mouseover", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
	mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
	collapseprev: true, //Collapse previous content (so only one open at any time)? true/false 
	defaultexpanded: [3], //index of content(s) open by default [index1, index2, etc]. [] denotes no content.
	onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
	animatedefault: false, //Should contents open by default be animated into view?
	persiststate: false, //persist state of opened contents within browser session?
	toggleclass: ["", "openpet"], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
	togglehtml: ["prefix", " ", " "], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
	animatespeed: "fast", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
	oninit:function(expandedindices){ //custom code to run when headers have initalized
		//do nothing
	},
	onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
		//do nothing
	}
})

</script>
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="./fancybox/jquery.mousewheel-3.0.2.pack.js"></script>
	<script type="text/javascript" src="./fancybox/jquery.fancybox-1.3.1.js"></script>
	<link rel="stylesheet" type="text/css" href="./fancybox/jquery.fancybox-1.3.1.css" media="screen" />
	<script type="text/javascript">
		$(document).ready(function() {
			/*
			*   Examples - images
			*/

			$("#various1").fancybox({
				'titleShow'		: false,
				'transitionIn'		: 'none',
				'transitionOut'		: 'none'
			});
		});
	</script>
<!--js code close-->

</head>


<body>

<!--wrapper start here-->

<div id="wrapper">
<div id="header">


<?php include_once("includes/header.php");?>
</div>
<!--container start here-->
<div id="container">

<!--left container-->
<div class="left-container">


<!--one row-->
<div class="one-row">



<?php 
$cnt=0;
while($rowcatdesc=mysql_fetch_array($arr6))
 {
 ?>
<!--box one-->
<div class="one-box">

<div class="bar">
<table border="0" cellspacing="0" cellpadding="4">
		<tr>
				<td valign="middle"><div class="img"><?php if( $rowcatdesc['category_image']!=""){?><a href="activators.php?catid=<?php echo $rowcatdesc['category_id'];?>"><img src="<?php echo SITE_IMAGES;?>category/<?php echo $rowcatdesc['category_image'];?>" width="50" height="40" alt="<?php echo $rowcatdesc['category_image_alt'];?>" title="<?php echo $rowcatdesc['category_image_alt'];?>" /></a><?php }?></div></td>
				<td valign="middle"><h1><a href="activators.php?catid=<?php echo $rowcatdesc['category_id'];?>"><?php echo $rowcatdesc['category_name'];?></a></h1></td>
		</tr>
</table>
</div>
</h1>
<!--middle box-->
<div class="middle-box"> 
<ul>
<?php if( $rowcatdesc['feature_name1']!=""){?>
<li>&nbsp;<?php if($rowcatdesc['url_1']!=""){?><a href="http://<?php echo str_replace("http://",'',$rowcatdesc['url_1']);?>" target="_blank"><?php }?><?php echo $rowcatdesc['feature_name1'];?><?php if($rowcatdesc['url_1']!=""){?></a><?php }?></li>
<?php } if( $rowcatdesc['feature_name2']!=""){?>
<li>&nbsp;<?php if($rowcatdesc['url_2']!=""){?><a href="http://<?php echo str_replace("http://",'',$rowcatdesc['url_2']);?>" target="_blank"><?php }?><?php echo $rowcatdesc['feature_name2'];?><?php if($rowcatdesc['url_2']!=""){?></a><?php }?></li>
<?php } if( $rowcatdesc['feature_name3']!=""){?>
<li>&nbsp;<?php if($rowcatdesc['url_3']!=""){?><a href="http://<?php echo str_replace("http://",'',$rowcatdesc['url_3']);?>" target="_blank"><?php }?><?php echo $rowcatdesc['feature_name3'];?><?php if($rowcatdesc['url_3']!=""){?></a><?php }?></li>
<?php } if( $rowcatdesc['feature_name4']!=""){?>
<li>&nbsp;<?php if($rowcatdesc['url_4']!=""){?><a href="http://<?php echo str_replace("http://",'',$rowcatdesc['url_4']);?>" target="_blank"><?php }?><?php echo $rowcatdesc['feature_name4'];?><?php if($rowcatdesc['url_4']!=""){?></a><?php }?></li>
<?php } if( $rowcatdesc['feature_name5']!=""){?>
<li>&nbsp;<?php if($rowcatdesc['url_5']!=""){?><a href="http://<?php echo str_replace("http://",'',$rowcatdesc['url_5']);?>" target="_blank"><?php }?><?php echo $rowcatdesc['feature_name5'];?><?php if($rowcatdesc['url_5']!=""){?></a><?php }?></li>
<?php } if( $rowcatdesc['feature_name6']!=""){?>
<li>&nbsp;<?php if($rowcatdesc['url_6']!=""){?><a href="http://<?php echo str_replace("http://",'',$rowcatdesc['url_6']);?>" target="_blank"><?php }?><?php echo $rowcatdesc['feature_name6'];?><?php if($rowcatdesc['url_6']!=""){?></a><?php }?></li>
<?php }?>

</ul>

<!--buy training-->
<div class="buy-training">
<?php
if($rowcatdesc['category_show_shop']==1)
{?>
<a href="products.php?catid=<?php echo $rowcatdesc['category_id'];?>">
<?php
}
else
{?>
<a href="#">
<?php
}?>
Buy Training, Experiences & Equipment
</a></div><br class="clear" />
<!--buy training close-->
<p class="prof"><a href="professional-sign-up.php">Professionals: Get H7 Activated.....</a></p>
</div>
<!--middle box close--><div class="middle-bottom"><a href="activators.php?catid=<?php echo $rowcatdesc['category_id'];?>">more...</a></div><br class="clear" />
</div>

<?php
$cnt++;

if($cnt%3==0)
{?>

<!-- after 3-->
<!--box one close-->
<br class="clear" />
</div>
<div class="one-row">
<!--one row close-->
<br class="clear" />
<!--one row-->
<?php
}


}?>


<?php
for($ct=0;$ct<$googlect;$ct++)
{?>
<div class="google">
<h1>Google Ads</h1>
<!--middle box-->
<div class="google-middle-box">
<?php echo $rowgoogle['google_script'];?>
<!--img src="images/ads.jpg" width="200" height="212" /-->
<!--buy training-->
<br class="clear" />
<!--buy training close-->

</div>
<!--middle box close--><div class="google-bottom">&nbsp;</div><br class="clear" />
</div>

<?php
}?>

</div>
<!--one row-->

<!--one row close-->

<!--one row-->
<div class="one-row">

<!--box one-->
<div class="corporate">
<h1>Corporate <span>Packages</span></h1>
<!--middle box-->
<div class="corporate-middle">
<p><?php echo substr($getH7CorporatePage['pages_content'],0,100);?></p>


</div>
<!--middle box close--><div class="corporate-bottom"><p align="right" class="alignright clear"><a href="display.php?pageid=127" class="morebtn">more inside</a>
</p></div><br class="clear" />
</div>
<!--box one close-->

<!--box one-->
<div class="corporate">
<h1>Daily<span> Snapshot</span></h1> 
<!--middle box-->
<div class="corporate-middle"><img src="upload/property_images/large/<?php echo $showimage;?>" width="198" height="183" /><br class="clear" />
<p class="mores alignright clear"><a href="activators.php?catid=<?php echo $showcat;?>">View All</a></p>
<p class="upload" align="left" style="padding:0; margin:0;">
<?php if($_SESSION['session_user_userid']!=""){?>

<a id="various1" href="photo-form.php" class="mylink">Upload your photos</a>
<!--<a href="#" onclick="window.open('photo-form.php','mywindow','width=400,height=400,status=1')" class="mylink">Upload your photos</a>--><?php  }?>


<img src="images/snapshotsponsorlogo.jpg" align="absmiddle" style="margin-top:7px; margin-left:5px;" /></p>
</div>
<!--middle box close--><div class="corporate-bottom">&nbsp;</div><br class="clear" />
</div>
<!--box one close-->

<!--box one-->
<div class="corporate">
<h1>Blog <span>Spot</span></h1>
<!--middle box-->
<div class="corporate-middle">
<ul><?php
			while($rowblog=mysql_fetch_array($rsblog))
			{?>
			
              <li><a href="blog/?p=<?php echo $rowblog['ID'];?>"><?php echo $rowblog['post_title'];?></a></li>
		   <?php
			 }?> 
</ul>

</div>
<!--middle box close--><div class="corporate-bottom"><p  class="alignright clear">
<a href="http://clientprojects.com.php5-17.websitetestlink.com/H7/blog" class="morebtn">more inside</a>
</p></div><br class="clear" />
</div>
<!--box one close--><br class="clear" />

</div>
<!--one row close--><br class="clear" />


<br class="clear" />
<div class="outer-about">
<!--about h7 activate-->
<div class="about">

<h1>About <span><?php echo $getH7Page['pages_title'];?></span></h1>
<p><?php echo substr($getH7Page['pages_content'],0,200);?></p>
<p align="right" class="mores"><a href="display.php?pageid=14">read more...</a>
</p>
<h1><?php echo $getH7latestPage['pages_title'];?></span></h1>

<!--activator-->
<div class="activatorvk">

<div class="activatorvk-top">
&nbsp;
</div>

<div class="activatorvk-midle">

<p><?php echo substr($getH7latestPage['pages_content'],0,250);?></p>
<p align="right" class="mores"><a href="display.php?pageid=124">read more...</a>
</p></div><br class="clear" />
<div class="activatorvk-bottom">&nbsp;</div>
</div>
<!--activator close--><br class="clear" />

</div>
<!--about h7 activate close-->
</div>

<p align="right"><strong><u>Find us Online</u></strong><img src="images/logos.jpg" border="0" align="absmiddle" usemap="#Map2" style="margin: 10px 14px 0px 15px;" />
<map name="Map2" id="Map2"><area shape="rect" coords="3,6,29,28" href="#" /><area shape="rect" coords="40,6,62,27" href="#" /><area shape="rect" coords="75,7,94,27" href="#" /><area shape="rect" coords="105,4,163,25" href="#" /></map></p>


</div>
<!--left container close-->



<!--right container-->
<div class="right-container">
<?php include_once("includes/right.php");?>

</div><!--right container close-->


</div>

<!--container close here-->

<div class="clear"></div>

<!--footer start here-->
<div id="footer">

<?php include_once("includes/footer.php");
?>

</div>
<!--footer close here-->

</div>

<!--wrapper close here-->

</body>
</html>
