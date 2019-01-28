<!--
//Hide from older browsers 
if (document.getElementById&&!document.layers)
{
    var ieType=(typeof window.innerWidth != 'number');
    var docComp=(document.compatMode);
    var docMod=(docComp && docComp.indexOf("CSS") != -1);
    var ieRef=(ieType && docMod)?document.documentElement:document.body;

    fCol='#000000';     //circling text colour.
    del=0.6;            //Follow mouse speed.
    ref=40;             //Run speed (timeout).

    N='HARM * * * * ASTRID * * * * IMKE * * * * LOTTE * * * *';
    N=N.split(" ");
    F=N.length;
    siz=100;
    eqf=360/F;
    cursorOffset=0;
    step=0.06;
    currStep=0;
    tmr=null;
    vis=false;
    mouseY=0;
    mouseX=0;
    dy=new Array();
    dx=new Array();
    zy=new Array();
    zx=new Array();
    tmpf=new Array(); 
    var sum=parseInt(F)+1;
    for (i=0; i < sum; i++)
    {
        dy[i]=0;
        dx[i]=0;
        zy[i]=0;
        zx[i]=0;
    }

    for (i=0; i < F; i++)
    {
        document.write('<div id="_face'+i+'" class="circling" style="color:'+fCol+'">'+N[i]+'<\/div>');
        tmpf[i]=document.getElementById("_face"+i).style; 
    }

    function circlingOnOff()
    {
        if (vis)
        { 
            vis=false;
            document.getElementById("circlingControl").value="Circling names ON";
        }
        else
        { 
            vis=true;
            document.getElementById("circlingControl").value="Circling names OFF";
            Delay();
        }
        kill();
    }

    function kill()
    {
        if (vis)
        {
            document.onmousemove=mouse;
        }
        else 
        {
            document.onmousemove=null;
        }
    } 

    function mouse(e)
    {
        var msy = (!ieType)?window.pageYOffset:0;
        
        if (!e) e = window.event;    
        if (typeof e.pageY == 'number')
        {
            mouseY = e.pageY + cursorOffset - msy;
            mouseX = e.pageX + cursorOffset;
        }
        else
        {
            mouseY = e.clientY + cursorOffset - msy;
            mouseX = e.clientX + cursorOffset;
        }
        if (!vis) kill();
    }

    document.onmousemove=mouse;

    function winDims()
    {
        winH=(ieType)?ieRef.clientHeight:window.innerHeight; 
        winW=(ieType)?ieRef.clientWidth:window.innerWidth;
    }

    winDims();
    window.onresize=new Function("winDims()");

    function ClockAndAssign()
    {
        for (i=0; i < F; i++)
        {
            tmpf[i].top=dy[i]+siz*0.5*Math.sin(currStep+i*eqf*Math.PI/180)+scrollY+"px";
            tmpf[i].left=dx[i]+siz*1.5*Math.cos(currStep+i*eqf*Math.PI/180)+"px";
        }
        currStep-=step;
        if (!vis) clearTimeout(tmr);
    }

    buffW=(ieType)?80:90;

    function Delay()
    {
        scrollY=(ieType)?ieRef.scrollTop:window.pageYOffset;
        if (!vis)
        {
            dy[0]=-100;
            dx[0]=-100;
        }
        else
        {
            zy[0]=Math.round(dy[0]+=((mouseY)-dy[0])*del);
            zx[0]=Math.round(dx[0]+=((mouseX)-dx[0])*del);
        }

        for (i=1; i < sum; i++)
        {
            if (!vis)
            {
                dy[i]=-100;
                dx[i]=-100;
            }
            else
            {
                zy[i]=Math.round(dy[i]+=(zy[i-1]-dy[i])*del);
                zx[i]=Math.round(dx[i]+=(zx[i-1]-dx[i])*del);
            }
            if (dy[i-1] >= winH-80) dy[i-1]=winH-80;
            if (dx[i-1] >= winW-buffW) dx[i-1]=winW-buffW;
        }

        tmr=setTimeout('Delay()',ref);
        ClockAndAssign();
    }

    window.onload=Delay;
}
//-->
