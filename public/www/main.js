function checkCTRL(event) {
    var devbutton = document.getElementById("devbutton")
    if (event.ctrlKey) {
        if (event.shiftKey) {
            document.getElementById("pepper_met_bal").style.display="block";
        } else {
            devbutton.setAttribute("href", "http://www.familiecoenen.nl/develop.html");
        }
    } else {
        devbutton.setAttribute("href", "http://www.familiecoenen.nl/ip-address.php");
    }
}

function includeHTML() {
    var z, i, elmnt, file, xhttp;

    /* Loop through a collection of all HTML elements */
    z = document.getElementsByTagName("*");
    for (i = 0; i < z.length; i++) {
        elmnt = z[i];
        /* Search for elements with a certain atrribute */
        file = elmnt.getAttribute("data-w3-include-html");
        if (file) {
            /* Make an HTTP request using the attribute value as the file name */
            xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4) {
                    if (this.status == 200) {elmnt.innerHTML = this.responseText;}
                    if (this.status == 404) {elmnt.innerHTML = "Page not found.";}
                    /* Remove the attribute, and call this function once more */
                    elmnt.removeAttribute("data-w3-include-html");
                    includeHTML();
                }
            }
            xhttp.open("GET", file, true);
            xhttp.send();
            return;
        }
    }
};

function openBody(event, bodyName) {
    var i, bodies, bodybuttons;

    /* Set all bodies to invisible */
    bodies = document.getElementsByClassName("body");
    for (i = 0; i < bodies.length; i++) {
        bodies[i].style.display = "none";
    }
    /* Remove the selection color from all bodybuttons */
    bodybuttons = document.getElementsByClassName("bodybutton");
    for (i = 0; i < bodybuttons.length; i++) {
        bodybuttons[i].className = bodybuttons[i].className.replace(" w3-lime", "");
    }
    /* Make the selected body visible */
    document.getElementById(bodyName).style.display = "block";
    /* Add the selection color to the selected bodybutton */
    event.currentTarget.className += " w3-lime";
}

function openItem(event, itemName) {
    var i, items, tablinks;
    var prefix = itemName.substring(0, 3); /* Get first three characters from itemName */

    /* Set all items, matching the itemName prefix, to invisible */
    items = document.getElementsByClassName("item");
    for (i = 0; i < items.length; i++) {
        if (items[i].id.startsWith(prefix)) {
            items[i].style.display = "none";
        }
    }
    /* Remove the selection color from all tablinks, matching the itemName prefix */
    tablinks = document.getElementsByClassName("tablink");
    for (i = 0; i < tablinks.length; i++) {
        if (tablinks[i].id.startsWith(prefix)) {
            tablinks[i].className = tablinks[i].className.replace(" w3-lime", "");
        }
    }
    /* Make the selected item visible */
    document.getElementById(itemName).style.display = "block";
    /* Add the selection color to the selected tablink */
    event.currentTarget.className += " w3-lime";

    /* If the selection is "gil_slideshow" present the first slide */
    if (document.getElementById(itemName).id == "gil_slideshow") {
        showSlides(slideIndex = 1);
    }
}

var slideTimer;
var slideIndex = 1;

function plusSlide(n) {
    showSlides(slideIndex += (n-1));
}

function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("dots");

    if (n > slides.length) {slideIndex = 1}
    if (n < 1) {slideIndex = slides.length}
    for (i = 0; i < slides.length; i++) { slides[i].style.display = "none"; }
    for (i = 0; i < dots.length; i++) { dots[i].className = dots[i].className.replace(" w3-white", ""); }
    slides[slideIndex-1].style.display = "block";
    dots[slideIndex-1].className += " w3-white";
    clearTimeout(slideTimer);
    slideTimer = setTimeout(showSlides, 3000, (slideIndex += 1)); /* Change image every 3 seconds */
}

/* Create the copyright signature using the current year */
var d = new Date();
document.getElementById("copyrightyear").innerHTML = "&copy; " + d.getFullYear() + " Familie Coenen";

/* Initial call to fill all items with their corresponding html pages */
includeHTML();
