
function validateform(){
    return true;
    frm=document.frmSiteDetails;
    if(frm.filelogo.value=="" && document.getElementById("preview").innerHTML=="&nbsp;"){
        alert("Please upload your logo");
        return false;
    }else if(frm.companyname.value==""){
        alert("Please enter your company name");
        return false;
    }else if(frm.captionname.value==""){
        alert("Please enter your caption name");
        return false;
    }

    return true;
}
function insertourlogo(imageid,siteId){
    var imgtag="<img src='"+imageid+"' width='60' height='40' >";
    if(siteId > 0){
        document.getElementById("jQLogo").innerHTML=imgtag;
        document.getElementById("preview").innerHTML='';
    }else{
        document.getElementById("preview").innerHTML=imgtag;
        document.getElementById("jQLogo").innerHTML='';
    }
}

function logosample(){
    winname="LogoSample";
    winurl="logosample.php";
    //alert(winurl);
    var t= "logooption(0)";

    document.getElementById("logooption2").checked=true;
    window.open(winurl,winname,'width=400,height=560,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,copyhistory=no,resizable=no');
}


function checkupload(){
    document.getElementById("logooption1").checked=true;
}

function changecolor(currentid){
    winname="SiteBuilderchangecolor";
    winurl="chnagefontcolor.php";
    window.open(winurl,winname,'width=200,height=220,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,copyhistory=no,resizable=no,minimize=no');
}

function changesitecolor(currentid){

    winname="SiteBuilderchangesitecolor";
    winurl="chnagesizecolor.php";
    window.open(winurl,winname,'width=200,height=220,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,copyhistory=no,resizable=no,minimize=no');
}


function setclrvalue(x){
    document.getElementById("fontcolor").style.backgroundColor=x;
    document.getElementById("fntclr").value=x;
    changecompanyfont('');
}


function setsiteclrvalue(x){
    document.getElementById("sitecolor").style.backgroundColor=x;
    //alert(x);
    document.getElementById("stclr").value=x;
}

function changecaptioncolor(currentid){
    winname="SiteBuilderchangecaptioncolor";
    winurl="chnagecaptionfontcolor.php";
    window.open(winurl,winname,'width=200,height=220,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,copyhistory=no,resizable=no,minimize=no');
}

function setcaptionfontclrvalue(x){
    document.getElementById("captfontcolor").style.backgroundColor=x;
    document.getElementById("captfntclr").value=x;
    changecaptionfont('');
}


function changecompanyfont(obj){
	 
    var company_name=document.getElementById("companyname").value; 
    var font_name=document.getElementById("compfont").value;
    var font_size=document.getElementById("fontsize").value;
    var font_clr=document.getElementById("fntclr").value;

    company_name= encodeURI(company_name); 
    var requesturl;
//    requesturl="showcompanyimages.php?act=company&company_name="+company_name+"&font_name="+font_name+"&font_size="+font_size+"&font_clr="+font_clr.slice(1)+"&";
    requesturl="generatestyletext.php?act=company&company_name="+company_name+"&font_name="+font_name+"&font_size="+font_size+"&font_clr="+font_clr.slice(1)+"&";
 
    var http;
    if (window.XMLHttpRequest) {
        http = new XMLHttpRequest();
    } else {
        try {
            http = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                http = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                http = false;
            }
        }
    }
    http.open("GET",requesturl,false)
    http.send();
  //  alert(http.responseText);
    var newfilename=http.responseText;
   // $('#companypriview').load(requesturl);
   document.getElementById("companypriview").innerHTML= newfilename ;
    
     //document.getElementById("companypriview").innerHTML=" <img src='"+requesturl+"'>";

}

function changecaptionfont(obj){
    var caption_name=document.getElementById("captionname").value;
    var font_name=document.getElementById("captionfont").value;
    var font_size=document.getElementById("captionfontsize").value;
    var font_clr=document.getElementById("captfntclr").value;
    caption_name=encodeURI(caption_name);
    var requesturl;
   // requesturl="showcompanyimages.php?act=caption&company_name="+caption_name+"&font_name="+font_name+"&font_size="+font_size+"&font_clr="+font_clr.slice(1)+"&";
    requesturl="generatestyletext.php?act=caption&company_name="+caption_name+"&font_name="+font_name+"&font_size="+font_size+"&font_clr="+font_clr.slice(1)+"&";
   
    var http;
    if (window.XMLHttpRequest) {
        http = new XMLHttpRequest();
    } else {
        try {
            http = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                http = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                http = false;
            }
        }
    }
    http.open("GET",requesturl,false)
    http.send();

    var newfilename=http.responseText;
 
     document.getElementById("captionpriview").innerHTML=  newfilename;
  //  document.getElementById("captionpriview").innerHTML=" <img src='"+requesturl+"'>";

}
 



function changeTextColor(color){
	document.getElementById('fntclr').value = color;
	changecompanyfont();
	
}

function changeDesriptionTextColor(color){
	document.getElementById('captfntclr').value = color;
	changecaptionfont();
	
}