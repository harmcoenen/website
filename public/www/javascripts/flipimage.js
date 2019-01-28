<!--
  var transferTo = 2;
  var topleftImageState = 1;
  var toprightImageState = 1;
  var bottomleftImageState = 1;
  var bottomrightImageState = 1;

  function flipImage(image)
  {
    switch(image)
    {
      case 1:
        if (transferTo == 1)
        {
          document.getElementById("topleftImage").src="images/TopLeftFirst.png";
          topleftImageState = 1;
        }
        else
        {
          document.getElementById("topleftImage").src="images/TopLeftSecond.jpg";
          topleftImageState = 2;
        }
        break;
      case 2:
        if (transferTo == 1)
        {
          document.getElementById("toprightImage").src="images/TopRightFirst.png";
          toprightImageState = 1;
        }
        else
        {
          document.getElementById("toprightImage").src="images/TopRightSecond.jpg";
          toprightImageState = 2;
        }
        break;
      case 3:
        if (transferTo == 1)
        {
          document.getElementById("bottomleftImage").src="images/BottomLeftFirst.png";
          bottomleftImageState = 1;
        }
        else
        {
          document.getElementById("bottomleftImage").src="images/BottomLeftSecond.jpg";
          bottomleftImageState = 2;
        }
        break;
      case 4:
        if (transferTo == 1)
        {
          document.getElementById("bottomrightImage").src="images/BottomRightFirst.png";
          bottomrightImageState = 1;
        }
        else
        {
          document.getElementById("bottomrightImage").src="images/BottomRightSecond.jpg";
          bottomrightImageState = 2;
        }
        break;
      default:
    }

    if ((topleftImageState == 1) &&
        (toprightImageState == 1) &&
        (bottomleftImageState == 1) &&
        (bottomrightImageState == 1)) transferTo = 2;

    if ((topleftImageState == 2) &&
        (toprightImageState == 2) &&
        (bottomleftImageState == 2) &&
        (bottomrightImageState == 2)) transferTo = 1;
  }
//-->
