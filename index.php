<html>
<head>
<link rel="shortcut icon" href="favicon.ico">
<Style>

#bar { width: 100px; height: 100px; z-index: 100;
height: expression(document.body.clientHeight + 'px' );
width: expression(document.body.clientWidth + 'px' );
z-index:1;
display:block;
position:fixed;
top:0;
left:0;
width:100%;
height:100%;
background: url('bgcolor.png');
 }

* html #bar { /*\*/position: absolute; top: expression(((ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop)) + 'px'); right: expression(((ignoreMe2 = document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft)) + 'px');/**/ }

#foo > #bar { position: fixed;}
</style>



<Style>

#bar1 { width: 100px; height: 100px; z-index: 100;
height: expression(document.body.clientHeight + 'px' );
width: expression(document.body.clientWidth + 'px' );
z-index:1;
display:block;
position:fixed;
bottom:0;
left:0;


 }

* html #bar1 { /*\*/position: absolute; top: expression(((ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop)) + 'px'); right: expression(((ignoreMe2 = document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft)) + 'px');/**/ }

#foo1 > #bar1 { position: fixed;}
</style>





<body>

<div style="position: absolute;z-index:10; display:block;position:absolute;    top:0;    left:0;    width:100%;    height:100%;        
height: expression(document.body.clientHeight + 'px');
width: expression(document.body.clientWidth + 'px');
background: url('logo.png');
background-repeat: no-repeat;">
</div>

<div id='foo'><div id='bar'>
</div>
</div>

<div class="Logo" style="position:absolute;z-index:200;left:55px; top: 40px;width:88px;height: 91px;" onclick="location.href='http://www.picsauce.com';" style="cursor:pointer;"></div>

<div class="search" style="position:absolute;z-index:100;left:100px; top:200px;">
<form name="input" action="submitsite.php" method="get">
Enter the URL of an Image: 
<input type="text" name="url" size=60>
<input type="submit" value="Submit">
</form>
</div>
</html>
