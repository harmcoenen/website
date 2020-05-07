<!DOCTYPE html>
<html lang="en">
<head>
    <title>Familie Coenen</title>
    <meta charset="UTF-8">
    <meta name="generator" content="Notepad++" />
    <meta name="copyright" content="(c) 2019 Harm Coenen" />
    <meta name="description" content="Familie Coenen WebSite - Informatie over de familie Coenen te Beugen" />
    <meta name="keywords" content="Coenen,familiecoenen,familie coenen,Beugen" />
    <meta name="author" content="Harm Coenen" />
    <meta name="robots" content="index,follow" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="w3-custom.css">
    <link rel="stylesheet" href="w3.css">
    <link rel="shortcut icon" type="image/x-icon" href="images/favicon.jpg" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body class="w3-pale-purple">

<?php

/* Define section */
define("MAIN_URL", "http://www.familiecoenen.nl");
define("MAIL_URL", "mailto:info@familiecoenen.nl");
define("GIL_GROUPS_DIR", "images/gil/");
define("GIL_EXTENSIONS", "*.{jpg,jpeg,gif,png}"); // to be used with GLOB_BRACE like: $gil_images = glob("/path/to/directory/*.{jpg,gif,png}", GLOB_BRACE); 


/* Function section */
function getBase($path) {
    $last_part = strrchr($path, "/");
    $base = str_replace($last_part, '', $path);
    return $base;
}

function getFile($path) {
    $last_part = strrchr($path, "/");
    $file = substr($last_part, 1);
    return $file;
}

function printPath() {
    print "<div class='w3-container w3-card w3-center w3-pale-purple w3-text-purple w3-margin-top w3-margin-bottom w3-padding-4'>";
    print "Path: " . getcwd();
    print "</div>";
}

function findGilObjectText($gil_object) {
    $text = $gil_object;
    $find = array(".jpg", ".jpeg", ".gif", ".png");
    $replace = ".txt";
    $txt_filename = str_ireplace($find, $replace, $gil_object); // Case-insensitive
    if (file_exists($txt_filename)) {
        /*
        $file_handle = fopen($txt_filename, "r");
        $text = fread($file_handle, filesize($txt_filename));
        fclose($file_handle);
        */
        $text = file_get_contents($txt_filename);
        if ($text === FALSE) {
            $text = $gil_object;
        }
    }
    return $text;
}

function presentGilObjects($dir) {
    if (is_dir($dir)) {
        $return_dir = getcwd();
        chdir($dir);

        if ($gil_objects = glob(GIL_EXTENSIONS, GLOB_BRACE)) {
            $objects_in_width = 4;
            //rsort($gil_objects);
            $n_gil_objects = count($gil_objects);

            for($index = 0; $index < $n_gil_objects; $index++) {
                $gil_object = $gil_objects[$index];
                if ($index == 0) {
                    print "<div class='w3-row-padding'>";
                } else {
                    if (($index % $objects_in_width) == 0) {
                        print "</div>";
                        print "<div class='w3-row-padding'>";
                    }
                }

                $gil_object_text = findGilObjectText($gil_object);
                print "<div class='w3-quarter'><div class='w3-card w3-btn w3-margin-top' onclick=\"document.getElementById('$dir$index').style.display='block'\">";
                print "<img src='" . GIL_GROUPS_DIR . "$dir/$gil_object' alt='' style='width:100%'>";
                print "<div class='w3-container' style='white-space: normal'><h5>$gil_object_text</h5></div>";
                print "</div></div>";
                print "<div id='$dir$index' class='w3-modal'><div class='w3-modal-content'>";
                print "<span onclick=\"document.getElementById('$dir$index').style.display='none'\" class='w3-button w3-display-topright'><b>&times;</b></span>";
                print "<img src='" . GIL_GROUPS_DIR . "$dir/$gil_object' alt='' style='width:100%'>";
                print "</div></div>";

            }
            print "</div>";

        } else {
            print "<div class='w3-container w3-card w3-center w3-pale-purple w3-text-purple w3-margin-top w3-margin-bottom w3-padding-4'>";
            print "<h3>No 'glas in lood' objects found in [$dir]</h3>";
            print "</div>";
        }

        chdir($return_dir);
    } else {
        print "<div class='w3-container w3-card w3-center w3-pale-purple w3-text-purple w3-margin-top w3-margin-bottom w3-padding-4'>";
        print "<h3>[$dir] is not a directory</h3>";
        print "</div>";
    }
}

function presentGilSlideshow($gil_groups) {
    // Create an empty array 
    $slides=array();

    $n_gil_groups = count($gil_groups);
    for($gIndex = 0; $gIndex < $n_gil_groups; $gIndex++) {
        $gil_group = $gil_groups[$gIndex];
        if (is_dir($gil_group)) {
            $return_dir = getcwd();
            chdir($gil_group);
            if ($gil_objects = glob(GIL_EXTENSIONS, GLOB_BRACE)) {
                $n_gil_objects = count($gil_objects);
                for($oIndex = 0; $oIndex < $n_gil_objects; $oIndex++) {
                    $gil_object = $gil_objects[$oIndex];
                    array_push($slides, GIL_GROUPS_DIR . "$gil_group/$gil_object"); 
                }
            }
            chdir($return_dir);
        }
    }

    $n_slides = count($slides);
    if ($n_slides > 0) {
        print "<div class='w3-row-padding w3-margin-top'>";
        print "<div class='w3-content w3-display-container w3-mobile' style='width:500px'>";

        for($sIndex = 0; $sIndex < $n_slides; $sIndex++) {
            $slide = $slides[$sIndex];
            print "<img class='slidesMiddle' src='$slide' style='width:100%;display:none'>";
        }

        print "<button class='w3-button w3-display-topleft w3-pale-purple w3-hover-text-purple' onclick=\"plusSlide(-1)\">&#10094;</button>";
        print "<button class='w3-button w3-display-topright w3-pale-purple w3-hover-text-purple' onclick=\"plusSlide(1)\">&#10095;</button>";
        print "</div>";
        print "</div>";

    } else {
        print "<div class='w3-container w3-card w3-center w3-pale-purple w3-text-purple w3-margin-top w3-margin-bottom w3-padding-4'>";
        print "<h3>No 'glas in lood' slides found</h3>";
        print "</div>";
    }
}

function presentGilGroups($dir) {
    if (is_dir($dir)) {
        $return_dir = getcwd();
        chdir($dir);

        if ($gil_groups = glob("*", GLOB_ONLYDIR)) {
            //rsort($gil_groups);
            $n_gil_groups = count($gil_groups);

            print "<div class='w3-bar w3-card-4 w3-purple w3-text-white'>";
            for($index = 0; $index < $n_gil_groups; $index++) {
                $gil_group = $gil_groups[$index];
                if ($index == 0) {
                    print "<button id=\"gil_tl_$index\" class='w3-bar-item w3-button w3-hover-teal w3-border-right tablink w3-lime' onclick=\"openItem(event,'gil_$gil_group')\">$gil_group</button>";
                } else {
                    print "<button id=\"gil_tl_$index\" class='w3-bar-item w3-button w3-hover-teal w3-border-right tablink' onclick=\"openItem(event,'gil_$gil_group')\">$gil_group</button>";
                }
            }
            print "<button id=\"gil_tl_slideshow\" class='w3-bar-item w3-button w3-hover-teal w3-border-right tablink' onclick=\"openItem(event,'gil_slideshow')\">Slideshow</button>";
            print "</div>";

            for($index = 0; $index < $n_gil_groups; $index++) {
                $gil_group = $gil_groups[$index];
                if ($index == 0) {
                    print "<div id='gil_$gil_group' class='w3-container item'>";
                } else {
                    print "<div id='gil_$gil_group' class='w3-container item' style='display:none'>";
                }
                presentGilObjects($gil_group);
                print "</div>";
            }
            print "<div id='gil_slideshow' class='w3-container item' style='display:none'>";
            presentGilSlideshow($gil_groups);
            print "</div>";

        } else {
            print "<div class='w3-bar w3-card-4 w3-purple w3-text-white w3-center w3-margin-top w3-margin-bottom w3-padding-4'>";
            print "<h3>No 'glas in lood' groups found in [$dir]</h3>";
            print "</div>";
        }

        chdir($return_dir);
    } else {
        print "<div class='w3-bar w3-card-4 w3-purple w3-text-white w3-center w3-margin-top w3-margin-bottom w3-padding-4'>";
        print "<h3>[$dir] is not a directory</h3>";
        print "</div>";
    }
}

/* Main program */
print "<header class='w3-container w3-card w3-center w3-pale-purple w3-text-purple w3-margin-top w3-margin-bottom w3-padding-4'>";
print "<div onclick=\"document.getElementById('titelfoto').style.display='block'\"><img class='w3-left w3-circle' src='images/titelfoto.tiny.jpg' alt='Family Picture' width='151' height='100' style='opacity:0.9' onmouseover='this.style.opacity=1' onmouseout='this.style.opacity=0.9'></div>";
print "<div class='w3-margin-bottom w3-margin-right w3-center w3-btn w3-text-purple w3-border w3-border-purple w3-round-large bodybutton w3-lime' onclick=\"openBody(event,'glasinlood')\">Glas in lood</div>";
print "<div class='w3-margin-bottom w3-margin-right w3-center w3-btn w3-text-purple w3-border w3-border-purple w3-round-large bodybutton' onclick=\"openBody(event,'links')\">Web links</div>";
print "<a class='w3-margin-bottom w3-margin-right w3-center w3-btn w3-text-purple w3-border w3-border-purple w3-round-large' href='" . MAIL_URL . "'>email <i class='far fa-envelope'></i></a>";
print "<a href='" . MAIN_URL . "'><h2 class='w3-right w3-text-purple' style='text-shadow:2px 2px 4px #444'><b>Website familie Coenen</b></h2></a>";
print "</header>";

print "<div id='titelfoto' class='w3-modal' style='display:none'><div class='w3-modal-content w3-animate-zoom'>";
print "<span onclick=\"document.getElementById('titelfoto').style.display='none'\" class='w3-button w3-display-topright'><b>&times;</b></span>";
print "<img src='images/titelfoto.small.jpg' alt='' style='width:100%'>";
print "</div></div>";

print "<div id='pepper_met_bal' class='w3-modal' style='display:none'><div class='w3-modal-content w3-animate-zoom'>";
print "<span onclick=\"document.getElementById('pepper_met_bal').style.display='none'\" class='w3-button w3-display-topright'><b>&times;</b></span>";
print "<img src='images/pepper_met_bal.jpg' alt='' style='width:100%'>";
print "</div></div>";

print "<div id='glasinlood' class='w3-container body'>";
presentGilGroups(GIL_GROUPS_DIR);
print "</div>";

print "<div id='links' class='w3-container body' style='display:none'>";
print "<div class='w3-bar w3-card-4 w3-purple w3-text-white'>";
print "<button id='lnk_tl001' class='w3-bar-item w3-button w3-hover-teal w3-border-right tablink w3-lime' onclick=\"openItem(event,'lnk_site')\">Site</button>";
print "<button id='lnk_tl002' class='w3-bar-item w3-button w3-hover-teal w3-border-right tablink' onclick=\"openItem(event,'lnk_algemeen')\">Algemeen</button>";
print "<button id='lnk_tl003' class='w3-bar-item w3-button w3-hover-teal w3-border-right tablink' onclick=\"openItem(event,'lnk_computer')\">Computer</button>";
print "<button id='lnk_tl004' class='w3-bar-item w3-button w3-hover-teal w3-border-right tablink' onclick=\"openItem(event,'lnk_sport')\">Sport</button>";
print "<button id='lnk_tl005' class='w3-bar-item w3-button w3-hover-teal w3-border-right tablink' onclick=\"openItem(event,'lnk_ict')\">ICT</button>";
print "<button id='lnk_tl006' class='w3-bar-item w3-button w3-hover-teal w3-border-right tablink' onclick=\"openItem(event,'lnk_domein')\">Domein</button>";
print "<button id='lnk_tl007' class='w3-bar-item w3-button w3-hover-teal w3-border-right tablink' onclick=\"openItem(event,'lnk_mijn')\">Mijn</button>";
print "</div>";
print "<div id='lnk_site' class='w3-container item'><div data-w3-include-html='pages/lnk_site.html'></div></div>";
print "<div id='lnk_algemeen' class='w3-container item' style='display:none'><div data-w3-include-html='pages/lnk_algemeen.html'></div></div>";
print "<div id='lnk_computer' class='w3-container item' style='display:none'><div data-w3-include-html='pages/lnk_computer.html'></div></div>";
print "<div id='lnk_sport' class='w3-container item' style='display:none'><div data-w3-include-html='pages/lnk_sport.html'></div></div>";
print "<div id='lnk_ict' class='w3-container item' style='display:none'><div data-w3-include-html='pages/lnk_ict.html'></div></div>";
print "<div id='lnk_domein' class='w3-container item' style='display:none'><div data-w3-include-html='pages/lnk_domein.html'></div></div>";
print "<div id='lnk_mijn' class='w3-container item' style='display:none'><div data-w3-include-html='pages/lnk_mijn.html'></div></div>";
print "</div>";

print "<footer class='w3-container w3-card w3-center w3-pale-purple w3-text-purple w3-margin-top w3-margin-bottom w3-padding-4'>";
print "<p id='copyrightyear' class='w3-left w3-text-black w3-small'></p>";
print "<a id='devbutton' class='w3-right w3-btn w3-text-pale-purple w3-border-0 w3-border-pale-purple w3-round-large' onclick='checkCTRL(event)'>________</a>";
print "</footer>";

print "<script src='index.js'></script>";

?>

</body>
</html>
