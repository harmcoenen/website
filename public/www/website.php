<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US" xml:lang="en-US">
  <head>
    <title>Familie Coenen</title>
    <meta name="generator" content="Notepad++" />
    <meta name="copyright" content="(c) 2009 Harm Coenen" />
    <meta name="description" content="Familie Coenen WebSite - Informatie over de familie Coenen te Beugen" />
    <meta name="keywords" content="Coenen,familiecoenen,familie coenen,Beugen" />
    <meta name="author" content="Harm Coenen" />
    <meta name="robots" content="index,follow" />
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <link rel="shortcut icon" type="image/x-icon" href="favicon.jpg" /> 
    <link rel="stylesheet" type="text/css" href="mystylesheet.css" />
    <script type="text/javascript" src="javascripts/constructaddr.js"></script>
  </head>
  <body>

    <div id="wrapper">
      <div id="header">
        <a href="http://www.familiecoenen.nl">
          <img width="151" height="100" alt="Family Picture" src="images/titelfoto.jpg"
          style="opacity:0.7" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.7" />
        </a>
        <h1 style="float: right">Website: Familie Coenen</h1>
      </div>
      <div id="menu">
		<a href="index.html">Nieuws</a>
		<a href="website.php">Info</a>
		<script type="text/javascript">construct_ftp_address("dynamic.ziggo.nl")</script>
		<a href="#">Links</a>
		<a href="#">Contact</a>
		<a href="thanks.html">Thanks</a>
      </div>
      <div id="mainbody">
        <!-- MAIN AREA -->
        <center>
        <?php
          echo "Hello World <br />";
          echo "You are using browser: <br />";
          echo $_SERVER['HTTP_USER_AGENT'];
          echo "<br />";
        ?>
        </center>
        <?php phpinfo(); ?>
      </div>
      <div id="footer">
        <script type="text/javascript">construct_email_address("info")</script>
      </div>
    </div>
   
  </body>
</html>