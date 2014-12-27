var theform; 
if (window.navigator.appName.toLowerCase().indexOf("netscape") > -1) { 
    theform = document.forms["Form1"]; 
} 
else { 
    theform = document.Form1; 
}

function isWebApp() {
    return (window.podium != null) && (window.podium.webApp != null);
}

/*
'************************************************************
'*	Added on 20 April 2007 by ECD/BS.
'*
'*	These two functions below:
'*      pd_PostBack
'*      pd_DoPostBackWithOptions
'*  override:
'*      __doPostBack
'*      WebForm_DoPostBackWithOptions
'*  We do this in order to get support in IE for autocomplete
'*	field value saving.
'*  In IE only, autocomplete values are only saved on a
'*  form submit, which isn't fired by our link buttons.
'************************************************************
*/
// Var to hold whether browse control is using the dual selector.
var hasDualSelect = false;
// Override the base __doPostBack function
var orig_doPostBack;
var orig_WebForm_DoPostBackWithOptions;
var from_WebForm_DoPostBackWithOptions = false;

function pd_PostBack(Param1, Param2) { 
    if (Param1 && Param1.toString().indexOf('Save') > -1) {
        pdChangeCheck.saving = 1;
    }
    if (pdChangeCheck.confirmNav()) {
        if (from_WebForm_DoPostBackWithOptions == false) {
            // If IE, we need to explicitly run AutoComplete
            // so form values are remembered.
            // ECD: 05/18/2007, added try/catch, was causing
            // issues in AOL Browser and Opera.
            try
            {
                if (document.all)
                {
                    window.external.AutoCompleteSaveForm(theform);
                }
            }
            catch (e)
            {
                // Do nothing, continue on...
            }    
            
            // If the browse control is using dual selector, we
            // need to select the values in the selectors on submit.
            if (hasDualSelect == true)
            {
                if (Param2 == '-')
                {
                    bSelectInverse(); 
                }
                else
                { 
                    bSelectAll();
                } 
            }
        }
        
        // Set back to original __doPostBack.
        __doPostBack = orig_doPostBack;
        __doPostBack(Param1, Param2);
    }
}


function pd_DoPostBackWithOptions(options) { 
    if (options.eventTarget && options.eventTarget.indexOf('Save') > -1) {
        pdChangeCheck.saving = 1;
    }
     if (pdChangeCheck.confirmNav()) {
        from_WebForm_DoPostBackWithOptions = true;

        // If IE, we need to explicitly run AutoComplete
        // so form values are remembered.
        // ECD: 05/18/2007, added try/catch, was causing
        // issues in AOL Browser and Opera.
        try
        {
            if (document.all)
            {
                window.external.AutoCompleteSaveForm(theform);
            }
        }
        catch (e)
        {
            // Do nothing, continue on...
        }

        // If the browse control is using dual selector, we 
        // need to select the values in the selectors on submit.
        if (hasDualSelect == true)
        {
            if (options.eventArgument == '-')
            {
                bSelectInverse(); 
            }
            else
            { 
                bSelectAll();
            } 
        }

        // Set back to original WebForm_DoPostBackWithOptions.
        WebForm_DoPostBackWithOptions = orig_WebForm_DoPostBackWithOptions;
        WebForm_DoPostBackWithOptions(options);
    }
}


function __pdPostbackFuncs() {
    orig_doPostBack = __doPostBack;
    __doPostBack = pd_PostBack; 

    if (window.WebForm_DoPostBackWithOptions != null) {
        orig_WebForm_DoPostBackWithOptions = WebForm_DoPostBackWithOptions;
        WebForm_DoPostBackWithOptions = pd_DoPostBackWithOptions;
    }
}


// __pdLoadTask JAVASCRIPT.  USED TO LOAD NEW TASK
function __pdL(taskid, taskname, tasktypeid, taskargs, path, clearhistory, subtaskid, subtaskname, formaction){ 
    if (pdChangeCheck.confirmNav()) {
        
        var customUrl = '';
        if (pdGlobal && pdGlobal.customUrls[0]) {
             for (i=0; i<pdGlobal.customUrls.length; i++) { 
                if (pdGlobal.customUrls[i].id == taskid) {
                    customUrl = pdGlobal.customUrls[i].url;
                    break;
                }
             }
        }
        
        if (customUrl != '') {
            theform.action = customUrl;
        } else {
             if (formaction != "") { 
                if (!formaction.indexOf("/podium/") == 0) {
                    formaction = "/podium/" + formaction;
                }
    	        theform.action = formaction + "?t=" + taskid;
            } else {
		        theform.action = "/podium/default.aspx?t=" + taskid;
            }
        }
            
        if (formaction == 'defaultupload.aspx') {
             // Needed for ABCUpload
            AddUploadID()
        }
        
        if (taskargs != "") {
            if (taskid == 52830 || taskid == 52562) {
                var sep = '?';
                if (theform.action.indexOf('?') > -1) {
                    sep = '&';
                } 
                theform.action += sep + taskargs.replace(/~/g, '&');
            } else {
                var argArray = taskargs.split('~');
                for (i = 0; i < argArray.length; i++) {
                    if (argArray[i].substring(0,4) == 'nid=') {
                        theform.action += '&' + argArray[i]
                    }
                }
            }
        }
    	 
        theform.__TASKID.value = taskid; 
        theform.__TASKNAME.value = taskname; 
        theform.__TASKTYPEID.value = tasktypeid; 
        theform.__TASKARGS.value = taskargs; 
        theform.__APPPATH.value = path; 
        theform.__CLEARHISTORY.value = clearhistory; 
        theform.__SUBTASKID.value = subtaskid; 
        theform.__SUBTASKNAME.value = subtaskname; 
        
        if (taskid == 52830) {
            ClearViewstate(); 
        } else {
            ClearForm(); 
        }
        
        theform.submit(); 
    }
} 


// OLD PDLOADTASK STILL CALLED BY HTML BANNERS
function __pdLT(taskid, taskname, tasktypeid, taskargs, path, clearhistory, subtaskid, subtaskname){ 
  __pdL(taskid, taskname, tasktypeid, taskargs, path, clearhistory, subtaskid, subtaskname, '')
} 


function pause(numberMillis) {
        var now = new Date();
        var exitTime = now.getTime() + numberMillis;
        while (true) {
            now = new Date();
            if (now.getTime() > exitTime)
                return;
        }
    }



// __pdLTN JAVASCRIPT.  USED TO LOAD NEW TASK IN A NEW WINDOW 
function __pdLTN(taskid, taskname, tasktypeid, taskargs, path, clearhistory, subtaskid, subtaskname, formaction, windowname, windowfeatures){ 
    var winref;
    winref = window.open("/podium/blank.html", windowname, windowfeatures);
    
    var formString = '<form name="Form2" method="post" target="' + windowname + '" style="position:absolute;bottom:0px;left:0px;"></form>';
    var $form2 = $(formString);
    $('BODY').append($form2);
    
    $('<input type="hidden" name="__TASKID">').appendTo($form2).val(taskid);
    $('<input type="hidden" name="__TASKNAME">').appendTo($form2).val(taskname);
    $('<input type="hidden" name="__TASKTYPEID">').appendTo($form2).val(tasktypeid);
    $('<input type="hidden" name="__TASKARGS">').appendTo($form2).val(taskargs);
    $('<input type="hidden" name="__APPPATH">').appendTo($form2).val(path);
    $('<input type="hidden" name="__CLEARHISTORY">').appendTo($form2).val(clearhistory);
    $('<input type="hidden" name="__SUBTASKID">').appendTo($form2).val(subtaskid);
    $('<input type="hidden" name="__SUBTASKNAME">').appendTo($form2).val(subtaskname);
    $('<input type="hidden" name="__pdVIEWSTATE">').appendTo($form2).val(theform.__pdVIEWSTATE.value);
    
    var pdVal;
    if (taskargs.indexOf('gm_fv=1') > -1) {
        pdVal = 'gm_fv'; 
    } else if (taskargs.indexOf('gm_p=1') > -1) {
        pdVal = 'gm_p'; 
    } else {
        pdVal = 'co'; 
    }
    $('<input type="hidden" name="__PD">').appendTo($form2).val(pdVal);
        
    $form2.submit(); 
    $form2.remove();
} 

// __pdLoadSubTask JAVASCRIPT.  USED TO LOAD NEW SUB TASK
function __pdLST(subtaskid, subtaskname){ 
    theform.__SUBTASKID.value = subtaskid; 
    theform.__SUBTASKNAME.value = subtaskname; 
    ClearForm(); 
    theform.submit(); 
} 



// __pdSetVal JAVASCRIPT.  USED TO SET VARIOUS SYSTEM VALUES
function __pdSetVal(val){ 
    theform.__PD.value = val; 
    if ((val == 'gm_p' || val == 'gm_mtl')) {
        if (theform.__EVENTTARGET) {
		    theform.__EVENTTARGET.name = "__NOEVENTTARGET"; 
	    }
	    if (theform.__EVENTARGUMENT) {
		    theform.__EVENTARGUMENT.name = "__NOEVENTARGUMENT"; 
	    }
		theform.target = '_blank'
    }
    
   // ClearForm(); 
    theform.submit(); 
    
    if ((val == 'gm_p' || val == 'gm_mtl')) {
         if (theform.__NOEVENTTARGET) {
		    theform.__NOEVENTTARGET.name = "__EVENTTARGET"; 
	    }
	    if (theform.__NOEVENTARGUMENT) {
		    theform.__NOEVENTARGUMENT.name = "__EVENTARGUMENT"; 
	    }
		theform.target = ''
		theform.__PD.value = ''; 
	}
} 

// __pdIFPrint JAVASCRIPT.  USED TO Print Iframes
function __pdIFPrint(ifID){ 
	try {
        window.open(frames[ifID].location.href);
    }
    catch (e)
    {
        // Do nothing, continue on...
    }    
} 


// __pdTransfer JAVASCRIPT.  USED TO Transfer Applications
function __pdTransfer(url){ 
	if (url != "") { 
		theform.action = url;
	}
	ClearForm(); 
    theform.submit(); 
} 

function __pdCheckTransfer() {
   if (__pdDoTransferURL.length > 0) __pdTransfer(__pdDoTransferURL);
   // alert('h1');
}
var __pdDoTransferURL = '';

function __pdTransferToSSL(sslurl) {
  var loc = document.location.toString();
  if (loc.substring(0,4) == 'http') { 
    if (loc.match('#')) {
        sslurl = sslurl + '#' + loc.split('#')[1];
    }
    location.replace(sslurl); // get rid of current page in history
  }  
}

function __pdTransferFromSSL(url) {
  var loc = document.location.toString();
  if (loc.substring(0,5) == 'https') { 
    if (loc.match('#')) {
        url = url + '#' + loc.split('#')[1];
    }
    location.replace(url); // get rid of current page in history
  }  
}

// __pdSiteSearch JAVASCRIPT.  USED TO GET SITE SEARCH RESULTS 
function __pdSiteSearch(pageTitle, searchTerm) {
    __pdL(52392, pageTitle, 1, 'q=' + searchTerm, '', 'true', 0, '', '');
}


// CLEAR VIEWSTATE AND POSTBACK EVENTS
function ClearViewstate(){ 
	if (theform.__EVENTTARGET) {
		theform.__EVENTTARGET.name = "__NOEVENTTARGET"; 
	}
	if (theform.__EVENTARGUMENT) {
		theform.__EVENTARGUMENT.name = "__NOEVENTARGUMENT"; 
	}
	var elem = theform.__VIEWSTATE; 
	elem.name = "__NOVIEWSTATE"; 
	// BTS - commented out this like because it seemed to cause viewstate errors when back-button is clicked.
	// elem.value = ""; 
} 

function ClearForm(){ 
    ClearViewstate();
	if (theform.elements.length < 100) {
	    var formElements = theform.elements; 
	    for (i=0; i<formElements.length; i++) { 
		    elem = formElements[i]; 
		    if (elem.type != "file" && elem.type != "button") {
			    if (elem.name.substring(0,2) != "__" && elem.name.indexOf("_PU_PN_") == -1  ) { 
				    elem.value="";
				    elem.id="";
				    elem.name="";
			    } 
		    }
	    } 
	}
} 

function enc(str) {
	//StartFragment 
	var the_res="";//the result will be here
	for(i=0;i<str.length;++i)
	{
		the_res+=String.fromCharCode(5^str.charCodeAt(i));
	}
	return the_res;
}



// Function for ABCUpload
function AddUploadID() {
    var theUniqueID = Math.floor(Math.random() * 1000000) * ((new Date()).getTime() % 1000);
    var thePos = theForm.action.indexOf('&uplid')
    if (thePos > 0) {
        theForm.action = theForm.action.substring(0, thePos);
    }
    theForm.action += '&uplid=' + theUniqueID;
}



//
// PDJscript.js
//

//*******************************************************************************************************
// Timeout
//*******************************************************************************************************
var pdTimerBase = 20;
var pdTimerDone = false;
var pdTime;
var pdTimerDisableClient = false;

function PDTimerReset() {
   if ((theform) && (theform.__PDTIMER)) {
       theform.__PDTIMER.value = pdTimerBase;
       PDTimerDisplay(pdTimerBase);
       pdTimerDone = false;
       window.clearTimeout(pdTime);
       PDTimerRun();
   }
}

function PDTimerEndNow() {
    if ((theform) && (theform.__PDTIMER)) {
        theform.__PDTIMER.value = 1;
        PDTimerInc();
    }
}

function PDTimerKey() {
   if (pdTimerDone == false) PDTimerReset();
}
if (window.document.body) {
    window.document.body.onmouseup = PDTimerKey;
    window.document.body.onkeyup = PDTimerKey;
}

function PDTimerInc() {
    var s = pdTimerBase;
    if (window.pdTimerTimeoutFunc != null) {
        if (theform.__PDTIMER.value.length > 0) s = theform.__PDTIMER.value;
        s = s - 1;
        theform.__PDTIMER.value = s;
        PDTimerDisplay(s);
        if (s <= 0) {
            pdTimerDone = true;
            window.clearTimeout(pdTime);
            if (pdTimerDisableClient == false) pdTimerTimeoutFunc();
        } else {
            PDTimerRun();
        }
    }
}

function PDTimerDisplay(val) {
    var td = document.getElementById('pdTimerD');
    if (td) td.innerHTML = val;
}

function PDTimerInit(disableClient) {
    PDTimerReset();
    pdTimerDisableClient = disableClient;
}

function PDTimerRun() {
   pdTime = window.setTimeout("PDTimerInc()", 60000);
}

function PDKeepAlive() {
    $.get('/podium/KeepAlive.aspx');
}

function PDCSecLShow() {
    $("#pdCSec").css({width:"200"});
    PDCSecShow();
    scrollToTop();

    if (isWebApp()) {

    } else {
        window.offresize = window.onresize;
        window.onresize = PDCSecShow;
    }
    
    window.setTimeout("document.getElementById('pdCSecPass').focus()", 500);
}

function PDCSecRShow() {
    PDCSecShow();
    window.setTimeout("document.getElementById('pdCSecEM').focus()", 500);
}

function PDCSecShow() {
    $("#pdCSec").css({display:"block", "z-index":"9999999"});
    $("#pdCSecFade").css({display:"block", "z-index":"9999998"});    
    
    pdInitIF('pdCSecFade');
    pdShowIF();
    PDCSecSize()
 }
 
function PDCSecSize() {
    var w,h,l,t,x,y;
    var pdCSec = $("#pdCSec");
    var pdCSecCtnt = $("#pdCSecCtnt");
    
    pdCSecCtnt.css({height:""});
    if (pdCSec.width() > 600) pdCSec.css({width:"600"})
    if (pdCSec.height() > 600) pdCSecCtnt.css({height:"500",overflow:"auto"})

    if (isWebApp()) {
        podium.webApp.center(pdCSec);
        jQuery(window).bind('resize', function () {
            podium.webApp.center(pdCSec);
        });
    } else {
        w = pdCSec.width();
        h = pdCSec.height();

        l = (document.body.clientWidth / 2) - (w / 2);
        t = (document.body.clientHeight / 2) - (h / 2);
        if (document.body && document.body.scrollHeight) {
            x = document.body.scrollWidth;
            if (document.body.clientHeight) {
                y = (document.body.scrollHeight < document.body.clientHeight) ? document.body.clientHeight : document.body.scrollHeight;
            } else {
                y = document.body.scrollHeight
            }
        } else if (self.outerHeight) {
            x = self.outerWidth;
            y = self.outerHeight;
        } else if (document.height) {
            x = document.width;
            y = document.height;
        }

        l = (l < 0) ? 0 : l;
        t = (t < 0) ? 0 : t;

        pdCSec.css({ left: l, top: t });
        $("#pdCSecFade").css({ width: x, height: y });    

    }
}

function PDCSecHide() {
    $("#pdCSec").css({display:"none"});
    $("#pdCSecFade").css({display:"none"});
    pdHideIF();
    if(window.offresize){ window.onresize = window.offresize;}
}

//*******************************************************************************************************
// Scroll window to top (used when loading iframes)
//*******************************************************************************************************
function scrollToTop() {
    scroll(0,0);
} 

//*******************************************************************************************************
// PRODUCTION FUNCTIONS
//*******************************************************************************************************

// SET L1 Navigation visibility
function SetTaskStay(taskid) {
	var docSpans = document.getElementsByTagName('span'); 
	var taskSpanID = 't_' + taskid;
	var taskSpanIDStay = 'ts_' + taskid;
	var hasStay = false;
	for (var i = 0; i < docSpans.length; i++) {
		if (docSpans[i].id.substring(0,taskSpanIDStay.length) == taskSpanIDStay) {
			hasStay = true;
			break;
		}
	}
	if (hasStay == true) {
		for (var i = 0; i < docSpans.length; i++) {
			if (docSpans[i].id.substring(0,2) == 't_') {
				if (docSpans[i].id.substring(0,taskSpanID.length) == taskSpanID) {
					docSpans[i].style.display = 'none';
				} else {
					//docSpans[i].style.display = '';
				}
			}
			if (docSpans[i].id.substring(0,3) == 'ts_') {
				if (docSpans[i].id.substring(0,taskSpanIDStay.length) == taskSpanIDStay) {
					docSpans[i].style.display = '';
				} else {
					docSpans[i].style.display = 'none';
				}
			}
		}
	}
}


//*******************************************************************************************************
// COMPONENTART FUNCTIONS
//*******************************************************************************************************

// SET height of rotator
function SetRotatorHeight(rid) {
	var docDivs = document.getElementsByTagName('div'); 
	var rSlideItemID = rid + '_slide';
	var maxHeight = 0;
	var sc = new getObj(rid);
	sc.style.height = '';
}

//*******************************************************************************************************
// OBJECT FUNCTIONS
//*******************************************************************************************************

// getObj - finds an object based on id
function getObj(name)
{
	if (document.getElementById) {
		this.obj = document.getElementById(name);
		if(this.obj)this.style = document.getElementById(name).style;
	} else if (document.all) {
		this.obj = document.all[name];
		if(this.obj)this.style = document.all[name].style;
	} else if (document.layers) {
		this.obj = document.layers[name];
		if(this.obj)this.style = document.layers[name];
	}
}



function makeId(path) {
    return path.substring( path.lastIndexOf("/") + 1, path.lastIndexOf("."));
}


function MM_findObj(n, d) { //v3.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;
}


//*******************************************************************************************************
// FORM FUNCTIONS
//*******************************************************************************************************

// CATCH THE ENTER KEY
var txtFocus = false;
function CaptureEnter(evt)
{
	if (txtFocus == false) {
		if (evt.keyCode == 13)
		{
			evt.cancelBubble = true;
			evt.returnValue = false;
		}
	}
}
function SettxtFocus(f) {
	txtFocus = f;
}

// ConfirmClick - shows confirmation box and allows user to confirm or cancel
function ConfirmClick(msg){ 
    if (confirm(msg)) { 
        return true; 
    } 
    else { 
        return false; 
    } 
} 

// Enable Disable a control
function EnableDisable(chkid, objid) {
	var c = new getObj(chkid);
	var f = new getObj(objid);
	if ((f.obj) && (c.obj)) {
	    f.obj.disabled = !c.obj.checked;
	}
}

function DisableEnable(chkid, objid) {
	var c = new getObj(chkid);
	var f = new getObj(objid);
	if ((f.obj) && (c.obj)) {
	    f.obj.disabled = c.obj.checked;
	}
}

// Select all checkboxes or Unselect all
function Check_ALL(List_name){
	var i =0;
	var flag = true; 
	var elem = document.getElementById("List_name");
	while (flag)
	{ 
		var id = List_name + "_" + i; 
		var ChildElem= document.getElementById(id);
		if (ChildElem != null)
			ChildElem.checked = true;
		else
			flag = false;
		i++;
	}
}
function Clear_ALL(List_name){
	var i =0;
	var flag = true; 
	var elem = document.getElementById("List_name");
	while (flag)
	{ 
		var id = List_name + "_" + i; 
		var ChildElem= document.getElementById(id);
		if (ChildElem != null)
			ChildElem.checked = false;
		else
			flag = false;
		i++;
	}
}

function SetSelected(obj_name){
   var control = document.getElementById(obj_name);  
   if( control != null ) control.checked = true; 
}

function SetSelectedOnOff(id){
   var cb=document.getElementById(id);
   if (cb && !cb.disabled) cb.checked = (cb.checked) ? false : true;
}

function SynchControlSelections(changed_obj_name,synch_obj_name){
   var changed_control = document.getElementById(changed_obj_name);  
   var synch_control = document.getElementById(synch_obj_name);  
   if( changed_control != null && synch_control != null) synch_control.checked = changed_control.checked; 
}

function clearTexBox(tbl) {
	
	var aTBs = tbl.split('|')
	for (i=0;i<aTBs.length;i++){
		var objTB = document.getElementById(aTBs[i])
		if(objTB) objTB.value = ''
	}
}

function Check_ALL2(List_name){
	
	for (var i=0; i < document.forms[0].elements.length; i++)
	{
		var elem = document.forms[0].elements[i];
		if (elem.name.indexOf(List_name) != -1)
		{
			elem.checked = true;
		}
	}
}
function Clear_ALL2(List_name){
	for (var i=0; i < document.forms[0].elements.length; i++)
	{
		var elem = document.forms[0].elements[i];
		if (elem.name.indexOf(List_name) != -1)
		{
			elem.checked = false;
		}
	}
}

// Select all checkboxes or Unselect all
function Check_ALL3(List_name,check){
var i, n_elems, elems = document.getElementsByTagName("input"); 
n_elems = elems.length; 

for (i = 0; i < n_elems; i++)
	{ 

	if (elems[i].type == "checkbox")
	    {
	    if (elems[i].name.indexOf(List_name) != -1)
		    {
			    elems[i].checked = check;
		    }
		}
	}
	i++;
}

// Select all radio button
function Check_ALL4(value){
    
    var oInput = document.getElementsByTagName("input"); 
       for (i = 0; i < oInput.length; i++) { 
       if (oInput[i].type == "radio") { 
            if (oInput[i].value == value) oInput[i].checked = true;
        }
    }
}

function Check_All5(id, check) {
    var o=document.getElementById(id);
    var l=o.getElementsByTagName("input")
    for(i=0;i<l.length;i++)
        if (l[i].type=="checkbox"&&!l[i].disabled)
           l[i].checked=check;   
}

//fix for radiobutton groupname not working in repeaters
function CheckUniqueRadioButton(name, oRb) {
    var re=new RegExp(name)
    var l=document.getElementsByTagName('input')
    for(i=0;i<l.length;i++)
        if(l[i].type=='radio')
            if(re.test(l[i].name))
                l[i].checked=false;
    oRb.checked=true;             
}
      

//*******************************************************************************************************
// IMAGE FUNCTIONS
//*******************************************************************************************************

// IMAGE SWAP
function ImageSwapPD(daImage, daSrc){
 	var obj;
 	if(document.images){
		// Check to see whether you are using a name, number, or object
		if (typeof(daImage) == 'string') {
	   		obj = 'document.' + daImage;
	   		obj = eval(obj);
	   		obj.src = daSrc;
	   	} 
		else if ((typeof(daImage) == 'object') && daImage && daImage.src) {
			daImage.src = daSrc;
	   	}
 	}
}		

function loadImages() {
    for (var i = 0; i < arguments.length; i++) {
        var temp = makeId(arguments[i]);
        eval(temp + "= new Image()");
        eval(temp + ".src ='"+ arguments[i] + "'");
    }
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}


//*******************************************************************************************************
// SHOW-HIDE FUNCTIONS
//*******************************************************************************************************

// ShowHide - changes display of an object
function ShowHide(objid) {
	var f = new getObj(objid);
	if (f.obj) {
		if (f.style.display == 'none'){	
			f.style.display = ''; 
		}
		else { 				
			f.style.display = 'none'; 
		}
	}
}

// Show 
function Show(objid) {
	var f = new getObj(objid);
	if (f.obj)f.style.display = ''; 
}
// Hide 
function Hide(objid) {
	var f = new getObj(objid);
	if (f.obj)f.style.display = 'none'; 
}

// ShowHide2 - shows/hides div while changing clicked anchor text
function ShowHide2(ShowObjID, ClickObjID, ToShowString, ToHideString) {
	var ShowObj = new getObj(ShowObjID);
	var ClickObj = new getObj(ClickObjID);
	if (ShowObj.style.display == 'none'){	
		ShowObj.style.display = ''; 
		ClickObj.obj.innerHTML = ToHideString;
	}
	else { 				
		ShowObj.style.display = 'none'; 
		ClickObj.obj.innerHTML = ToShowString;
	}
}

// ShowHide2 - shows/hides div while changing clicked anchor text
function ShowHide3(ShowObjID, ClickObj, ToShowString, ToHideString) {
	var ShowObj = new getObj(ShowObjID);
 
	if (ShowObj.style.display == 'none'){	
		ShowObj.style.display = ''; 
		ClickObj.innerHTML = ToShowString
	}
	else { 				
		ShowObj.style.display = 'none'; 
		ClickObj.innerHTML = ToHideString
	}
}

// ShowHideUNav - changes display of User Navigation
function ShowHideUNav() {
	var theform; 
	if (window.navigator.appName.toLowerCase().indexOf("netscape") > -1) { 
		theform = document.forms["Form1"]; 
	} 
	else { 
		theform = document.Form1; 
	} 
	var f = new getObj("unavdiv");
	var i = new getObj("unavimg");
	if (f.style.display == 'none'){				
		f.style.display = ''; 
		i.obj.innerHTML = '-';
		theform.__UNAVDM.value = '0'; 
    }
	else { 				
		f.style.display = 'none'; 
		theform.__UNAVDM.value = '1'; 
		i.obj.innerHTML = '+';
	 }
}

//Given an array of client ids this will set the other divs to invisible while setting the supplied to visible
function SetVisible(myObj, clArray){
	var item_ids = new Array();
	var s = (clArray);
	item_ids = s.split(',');
	var i = 0;
	for (i; i < item_ids.length; i++)
	{
		var elem = document.getElementById(item_ids[i]);
		if(!elem)continue;	
		if (myObj == elem.id){
			elem.style["display"] = '';
		} else {
			elem.style["display"] = 'none';
			}
	} 
}


// Show Edit Mode
var ShowObjs = '';
function ShowEM(ShowObjId, HighlightObjId) {
    if (ShowObjs.indexOf('|' + ShowObjId + '|', 0) == -1) {
        var s = new getObj(ShowObjId);
	    var h = new getObj(HighlightObjId);
	    if (s.obj) {
	        if (s.style.display != '') {
	            s.style.display = ''; 
	        }
	    }
	    if (h.obj) {
	        if (h.style.border != '1px dashed #c1c1c1') {
	            h.style.border = '1px dashed #c1c1c1'; 
	        }
	    }
	    ShowObjs += '|' + ShowObjId + '|'
    } 
}

function HideEM(ShowObjId, HighlightObjId) {
    ShowObjs = ShowObjs.replace('|' + ShowObjId + '|', '');
    window.setTimeout("doHideEM('" + ShowObjId + "', '" + HighlightObjId + "')", 250);
}

function doHideEM(ShowObjId, HighlightObjId) {
    if (ShowObjs.indexOf('|' + ShowObjId + '|', 0) == -1) {
	    var s = new getObj(ShowObjId);
        var h = new getObj(HighlightObjId);
        if (s.obj) {
            if (s.style.display != 'none') {
                s.style.display = 'none'; 
            }
        }
        if (h.obj) {
            if (h.style.border != '0px') {
                h.style.border = '0px'; 
            }
        }
    }
}

//*******************************************************************************************************
// CHANNEL FUNCTIONS
//*******************************************************************************************************

// setCBStyle - sets properties for channnel boxes
function setCBStyle(objid, maxheight, displaymode)
{
	var d = new getObj(objid);
	if (maxheight > 0) {
		if (d.obj.offsetHeight > maxheight) {
			d.style.height = maxheight + 'px';
		}
	}
	if (displaymode == 'none') {
		d.style.display = displaymode;
	}
}

// ShowHideCB - sets display for channel boxes
function ShowHideCB(cbobjid, shobj, img1, img2) {
	ShowHideCBData(cbobjid, shobj, img1, img2, null);
}

function ShowHideCBData(cbobjid, shobj, img1, img2, dataFunc) {
	var cb = new getObj(cbobjid);
	if (cb.style.display == 'none'){				
		cb.style.display = ''; 
		shobj.src = img2;
		SetUserCBMode(cbobjid, 0);
		if (dataFunc != null) {
		    eval(dataFunc);
		}
		shobj.title = 'Minimize';
	}
	else { 				
		cb.style.display = 'none'; 
		shobj.src = img1;
		SetUserCBMode(cbobjid, 1);
		shobj.title = 'Maximize';
	}
}

function SetUserCBMode(cbobjid, mode) {
    var curVal = getCookieValue('UCM');
	if (curVal.length > 0) {
		var c = '';
		var str = new Array();
		var strmatch = cbobjid + ':';
		var found = false;
		str = curVal.split(",");
		for (var i = 0; i < str.length; i++) {
			if (c.length > 0) {
				c += ",";
			}
			if (str[i].substring(0, strmatch.length) == strmatch) {
				c += strmatch + mode;
				found = true;
			} else {
				c += str[i];
			}
		} 
		if (found == false) {
			if (c.length > 0) {
				c += ",";
			}
			c += strmatch + mode;
		}
		setCookieValue('UCM', c);
	} else {
		setCookieValue('UCM', cbobjid + ':' + mode);
	}
}

//*******************************************************************************************************
// COOKIE FUNCTIONS
//*******************************************************************************************************
function getCookieValue(name) {
    var ca = document.cookie.split(';');
    var val = '';
    for(var i=0;i < ca.length;i++) {
	    var c = ca[i];
	    while (c.charAt(0)==' ') c = c.substring(1,c.length);
	    if (c.indexOf(name) == 0) {
	        val = c.substring(name.length + 1,c.length);
	        break;
	    }
    }
    return val;
}

function setCookieValue(name, val) {
    var date = new Date();
  	date.setTime(date.getTime()+(30*24*60*60*1000));
  	var expires = "; expires="+date.toGMTString();
	document.cookie = name+"="+val+expires+"; path=/";
}


//*******************************************************************************************************
// WINDOW FUNCTIONS
//*******************************************************************************************************

// pdOpenWindow - shows a popup window with content
function pdOpenWindow(content, width, height) {
	generator = window.open('','Podium','width='+width+',height=600,location=0,status=0, toolbar=0, location=0, menubar=0, directories=0, resizable=1, scrollbars=0');
	generator.document.write('<html><head><title>Podium</title></head>');
	generator.document.write('<body style="margin:0px;border:0px;padding:0px;">');
	generator.document.write('<table width="100%" height="100%" cellpadding="0" cellspacing="0"><tr><td width="100%" height="100%" align="center" valign="middle">');
	generator.document.write('<div align="center" valign="middle" id="pdiv"><span id="pspan">');
	generator.document.write(content);
	generator.document.write('</span></div>');
	generator.document.write('</td></tr></table>');
	generator.document.write('</body></html>');
	generator.document.close();
	if (!width) {
		//var odiv = generator.document.getElementById('pdiv');
		//var ospan = generator.document.getElementById('pspan');
		window.setTimeout("generator.resizeTo((generator.document.getElementById('pspan').offsetWidth + 27),(generator.document.getElementById('pdiv').offsetHeight + 66))",300);
	}	
}

/*
'************************************************************
'*	Added on 7 Oct 2005 by ECD
'*
'*	This is the functionality the cImage control now uses. The above function
'*	was very limited, this function has autoclose, and title functionality.
'************************************************************
*/
// Set the horizontal and vertical position for the popup
PositionX = 100;
PositionY = 100;

// Set these value approximately 20 pixels greater than the
// size of the largest image to be used (needed for Netscape)
defaultWidth  = 500;
defaultHeight = 500;

// Set autoclose true to have the window close automatically
// Set autoclose false to allow multiple popup windows
var AutoClose = true;

if (parseInt(navigator.appVersion.charAt(0))>=4)
{
	var isNN=(navigator.appName=="Netscape")?1:0;
	var isIE=(navigator.appName.indexOf("Microsoft")!=-1)?1:0;
}

var optNN='scrollbars=no,width='+defaultWidth+',height='+defaultHeight+',left='+PositionX+',top='+PositionY;
var optIE='scrollbars=no,width=150,height=100,left='+PositionX+',top='+PositionY;

function pdOpenImagePopup(imageURL,imageTitle)
{
	if (isNN){imgWin=window.open('about:blank','',optNN);}
	if (isIE){imgWin=window.open('about:blank','',optIE);}
	
	if (imageTitle==null)
	{
		imageTitle='Podium';
	}
	
	with (imgWin.document)
	{
		writeln('<html><head><title>Loading...</title><style>body{margin:0px;}</style>');
		writeln('<sc'+'ript>');
		writeln('var isNN,isIE;');
		writeln('if (parseInt(navigator.appVersion.charAt(0))>=4){');
		writeln('isNN=(navigator.appName=="Netscape")?1:0;');
		writeln('isIE=(navigator.appName.indexOf("Microsoft")!=-1)?1:0;}');
		writeln('function reSizeToImage(){');
		writeln('if (isIE){');
		writeln('width=document.images["Podium"].width;');
		writeln('height=35+document.images["Podium"].height;');
		writeln('window.resizeTo(width,height);');
		writeln('}');
		writeln('if (isNN){');       
		writeln('window.innerWidth=document.images["Podium"].width;');
		writeln('window.innerHeight=document.images["Podium"].height;}}');
		writeln('function doTitle(){document.title="'+imageTitle+'";}');
		writeln('</sc'+'ript>');
		
		if (!AutoClose)
		{
			writeln('</head><body bgcolor=000000 scroll="no" onload="reSizeToImage();doTitle();self.focus()">')
		}
		else
		{
			writeln('</head><body bgcolor=000000 scroll="no" onload="reSizeToImage();doTitle();self.focus()" onblur="self.close()">');
		}
		
		writeln('<img name="Podium" src='+imageURL+' style="display:block"></body></html>');
		close();		
	}
}

function iFrameHeight(id) {
	   
	 try {
        var iframe=new iFrame(id);
        iframe.SetFrameHeight();
     }
     catch (e) {
        var ifr = document.getElementById(id)
        if (ifr) {
            ifr.style.height = 5000;
        }
     }

}

function iFrame(id) {
    
    this.oFrame = document.getElementById(id);
    this.cBody = this.oFrame.contentWindow.document.getElementById('__pdIFRAMEarea');
    if (this.cBody) {
        this.cBody.cBody=this.cBody;this.cBody.oFrame=this.oFrame;
        this.cBody.onclick=this.SetFrameHeight; 
    }
    
}

iFrame.prototype.SetFrameHeight = function() {

	try {
		this.oFrame.style.height=(this.cBody)?this.cBody.offsetHeight:5000;
	}
	catch (e) {
		this.oFrame.style.height = 5000
	}
}

//function 

//*******************************************************************************************************
// CURRENCY FUNCTIONS
//*******************************************************************************************************

// Given an array of client ids and total client id this will maintain the total values
function AddItem(clArray, total,sepChar,decChar){
	var t = document.getElementById(clArray);
	var item_ids = new Array();
	var s = (t.value);
	item_ids = s.split(',');

	var i = 0, vtemp = 0, vresult = 0;
	for (i; i < item_ids.length; i++)
		{
			var elem = document.getElementById(item_ids[i]);
			if(!elem)continue;	

			vtemp=elem.value;
			//get rid of group seperators
			str = replaceAll(elem.value,sepChar,'');
			//replace current culture decimal seperator with US
			str = replaceAll(str,decChar,'.');
			vtemp = makePos(Number(str));

			if (!isNaN(vtemp)) vresult += vtemp;
			elem.value = formatCurrency(vtemp,sepChar,decChar);
		} 
	try{
	    var elem1 = document.getElementById(total);
	    vtemp = 0;
	    str = replaceAll(elem1.value,sepChar,'');
	    str = replaceAll(str,decChar,'.');
	    vtemp = Number(str);
    	
	   // if(vresult > 0){
	        elem1.value = (s)?formatCurrency(vresult,sepChar,decChar):formatCurrency(elem1.value,sepChar,decChar);
       // }
        //else {  
         //       elem1.value = formatCurrency(vtemp,sepChar,decChar);
        //}
   }
   catch(err){
      return 0;
   }
}

function makePos(value){
    var newVal = value;
    if(!isNaN(newVal)){
        if(newVal < 0) {
            newVal = newVal * -1
         }
    }
    return newVal;
}

function replaceAll(source, searchVal, newVal) {

    if (searchVal == newVal) return source;
	var strReplaceAll = source;
    var intIndexOfMatch = strReplaceAll.indexOf( searchVal );
 
    while (intIndexOfMatch != -1){
    strReplaceAll = strReplaceAll.replace( searchVal, newVal )
     
    intIndexOfMatch = strReplaceAll.indexOf( searchVal );
    }
     
    return strReplaceAll;

}

function SetBold(o){
    var l=o.getElementsByTagName('TD');
    for(i=0;i<l.length;i++)
        l[i].style.fontWeight=(l[i].style.fontWeight=='bold')?'normal':'bold';
}

function formatCurrency(num,sepChar,decChar) {
	num = num.toString().replace(/\$|\,/g,'');
	if(isNaN(num))
	num = "0";
	sign = (num == (num = Math.abs(num)));
	num = Math.floor(num*100+0.50000000001);
	cents = num%100;
	num = Math.floor(num/100).toString();
	if(cents<10)
	cents = "0" + cents;
	for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
	num = num.substring(0,num.length-(4*i+3))+sepChar+
	num.substring(num.length-(4*i+3));
	return (((sign)?'':'-') + num + decChar + cents);
}

function showCVV(obj){
	var objIF = document.getElementById('tCVVinfo')
	var objCVV = document.getElementById('iCVVinfo')
	
	if(obj){
		var ol=obj.offsetLeft;var ot=obj.offsetTop;
		while ((obj=obj.offsetParent) != null) {ol += obj.offsetLeft; ot += obj.offsetTop; }
		
		objIF.style.left = ol-6;
		objCVV.style.left = ol-6;
		objIF.style.top = ot+18
		objCVV.style.top = ot+18
	}
	
	objIF.style.display = (objIF.style.display == 'none')?'':'none';
	objCVV.style.display = (objCVV.style.display == 'none')?'':'none';

}

function SetPaymentOption(obj, tCC, tSP, tSA){
	
	var aChkBoxes = obj.getElementsByTagName('input');
	var ccVal = false;
	var checkVal = false;
	var saccountVal = false;
	
	for(i=0;i<aChkBoxes.length;i++) {
		if(aChkBoxes[i].checked) {
		    if (aChkBoxes[i].value=='check') {
		        checkVal = true;
		    } else if (aChkBoxes[i].value=='saccount') {
		        saccountVal = true
		    } else {
		        ccVal = true;
		    }
		}
	}
			
	var objCC = document.getElementById(tCC)
	var objSP = document.getElementById(tSP)
	var objSA = document.getElementById(tSA)
	if(objCC && objSP && objSA)objCC.style.display = (ccVal)?'block':'none';
	if(objCC && objSP && objSA)objSP.style.display = (checkVal)?'block':'none';
	if(objCC && objSP && objSA)objSA.style.display = (saccountVal)?'block':'none';
	
	var textInputs=objCC.getElementsByTagName('input');
	for(j=0;j<textInputs.length;j++){
		textInputs[j].disabled=!ccVal;
	}
	
	var textInputs=objCC.getElementsByTagName('select');
	for(j=0;j<textInputs.length;j++){
		textInputs[j].disabled=!ccVal;
	}
	
	var stextInputs=objSA.getElementsByTagName('input');
	for(j=0;j<stextInputs.length;j++){
		stextInputs[j].disabled=!saccountVal;
	}
}

/*Fix for dissappearing dropdowns in Multipage tabs for Safari*/
function ShowDropDowns()
{
    if (window.ComponentArt_Snap_Page_Loaded != null)
    {
        if (ComponentArt_Snap_Page_Loaded == true)
        {
           var DropDowns = document.getElementsByTagName("select");
           for (i = 0; i < DropDowns.length; i++)
           {
                if (DropDowns[i].style.visibility == "hidden") 
                    DropDowns[i].style.visibility = "visible";
           }
        }
    }    
}

function Copy2ClipAlert(pCopyText)
{
    if (window.clipboardData)
    {
        window.clipboardData.setData("Text", pCopyText);
    }
    else if (window.netscape)
    {
        // you have to sign the code to enable this, or see notes below
        netscape.security.PrivilegeManager.enablePrivilege('UniversalXPConnect');

        var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);
        if (!clip) return;

        var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);
        if (!trans) return;

        trans.addDataFlavor('text/unicode');
    
        var str = new Object();
        var len = new Object();
        var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);
        var copytext=pCopyText;

        str.data=copytext;
        trans.setTransferData("text/unicode",str,copytext.length*2);

        var clipid=Components.interfaces.nsIClipboard;

        if (!clip) return false;

        clip.setData(trans,null,clipid.kGlobalClipboard);
    }
    alert("The following info was copied to your clipboard:\n\n" + pCopyText);
    return false;
}

// E-Mail

//just retruns decoded email address
function pdDecodeEmail(address){
    var aryAddress = new Array
    var mailTo = new String;
    address = address.replace(/x/g, ',64');address = address.replace(/y/g, ',46');address = address.replace(/z/g, ',');
    aryAddress = address.split(',') 
    for (i=1; i<aryAddress.length;i++) {  
          mailTo = mailTo + String.fromCharCode(aryAddress[i]);   
    }
    return mailTo;
}

function pdSendEmail(address,mode,subject) {
    var aryAddress = new Array
    var mailTo = new String;
    address = address.replace(/x/g, ',64');address = address.replace(/y/g, ',46');address = address.replace(/z/g, ',');
    aryAddress = address.split(',') 
    for (i=1; i<aryAddress.length;i++) {  
          mailTo = mailTo + String.fromCharCode(aryAddress[i]);
      }

      // BTS 05/11/11 - NS 30249 - removing spaces from mailto link.   These spaces are being encoded to %20 which is causing
      //        the mailto link to exceed the maximum length
      mailTo = mailTo.replace(/ /g, '');
    
    var locStr = '';
    switch (mode) {
       case 'normal': //normal
            if(subject.length > 0){
                locStr='mailto:'+mailTo+'?subject='+subject;
            }
            else{
                locStr='mailto:'+mailTo;
            }
            window.location.replace(locStr);
            break;
       case 'cc': //cc
            if(subject.length > 0){
                locStr='mailto:?cc='+mailTo+'&subject='+subject;
            }
            else{
                locStr='mailto:?cc='+mailTo;
            }
            window.location.replace(locStr);
            break;
       case 'bcc': //bcc
            if(subject.length > 0){
                locStr='mailto:?bcc='+mailTo+'&subject='+subject;
            }
            else{
                locStr='mailto:?bcc='+mailTo;
            }
            window.location.replace(locStr);
            break;
      default:
            locStr='mailto:'+mailTo;
            break;
        //window.alert('Error in pdSendEMail: A valid mode was not specified.');
    }
    //linkto.href = 'mailto:'+mailTo;
    //window.location.replace(locStr) //'mailto:'+qstr+mailTo);
    //window.location.replace('mailto:'+mailTo);
    
}  

//validates an inputed condition before showing confirm message
function conditionalConfirm(value1, value2, operator, confirmMsg){
    var show_confirm = false;
  
    switch (operator)
    {
        case '==' :
            if (value1 == value2){ show_confirm = true; }else{ show_confirm = false; }
            break;
        case '!=' :
            if (value1 != value2){ show_confirm = true; }else{ show_confirm = false; }
            break;
        case '<' :
            if (value1 < value2){ show_confirm = true; }else{ show_confirm = false; }
            break;
        case '<=' :
            if (value1 <= value2){ show_confirm = true; }else{ show_confirm = false; }
            break;
        case '>' :
            if (value1 > value2){ show_confirm = true; }else{ show_confirm = false; }
            break;
        case '>=' :
            if (value1 >= value2){ show_confirm = true; }else{ show_confirm = false; }
            break;
        default:
    }
    
    if(show_confirm == true){
        if(window.confirm(confirmMsg)){
            return true;
        }
        else{
            return false;
         }
    }
    else{
        return true;
    }
}

//selects appropriate event location option
function change_ev_loc_opt(LocItemToSel,OtherItem){
    getObj(LocItemToSel);
    this.obj.disabled = false;;
    getObj(OtherItem);
    this.obj.disabled = true;
   
}


//fcRegistration - reg item
function CheckItem2(id,txtObj){
    var val = txtObj.value; //document.getElementById(txtObj).value;
    val = val.replace(' ','');
    alert(val);
    var chk = new getObj(id); 
    if(isNaN(val) || val.length==0 || val==0){
       txtObj.value = '';
    }
}

function CheckItem(id,totVal,oneVal) {    
   var chk = new getObj(id); 
   chk.obj.checked = true;
   if(oneVal == true){
        ItemChangeOneVal(id,totVal);
   }
   else{
        ItemChange(id,totVal);
   }
}

/* don't need for now
function CheckItemClick(cbObj, item_att_str){
   try{  
         var cbArr = item_att_str.split(",");
         var re = new RegExp('inc',"gi");
         var tbIdBase = cbObj.id.replace(re,'num');
         var tbOjb = ''
         var tot = 0;
          
         for(i=0; i<cbArr.length; i++){
            tbObj = tbIdBase + '_' + cbArr[i].substr(cbArr[i].indexOf('_')+1) + '_txtnum_' + cbArr[i];
            val = document.getElementById(tbObj).value;
            if(!isNaN(val)){
                tot = tot + Number(val);
            }
         }
        alert('tot='+tot);
        if(tot < 1){
            cbObj.checked = false;
        }
        else{
            cbObj.checked = true;
         } 
   }
   catch(err){
       cbObj.checked = false;
       return 0;
   }
}*/

function ItemChange2(cbid,tbobj,item_att_str){
    var chk = new getObj(cbid);
    try{    
        var cbArr = item_att_str.split(",");
        var cbOBj='';
        var currObj='';
        var tot = 0;
        var val='';
        var tmpOut = '';
        for(i=0; i<cbArr.length; i++){
            if(tbobj.id.indexOf(cbArr[i]) > -1){
                currObj = cbArr[i];
                break;
            }    
        } 
        var re = new RegExp(currObj,"gi");
        for(i=0; i<cbArr.length; i++){
            cbObj = tbobj.id.replace(re,cbArr[i]);
            val = document.getElementById(cbObj).value;
            if(!isNaN(val)){
                tot = tot + Number(val);
            }
           // tmpOut = tmpOut + cbObj + ' = ' + val+'\n';
        }  
       //tmpOut = tmpOut + ' total: ' +tot+'\n';
       //alert('tot='+tot+'\n\n\n'+tbobj.id+'\n\n'+item_att_str+'\n\n\n\ncurrObj='+currObj+'\n\n'+tmpOut);
       
       if(tot < 1){
            chk.obj.checked = false;
         }
        else{
            chk.obj.checked = true;
        }
    }
    catch(err){
       chk.obj.checked = false;
       return 0;
   } 
}

function ItemChange(id,totIDs){
    //var itemTot = document.getElementById(totID);
    //alert(document.getElementById(totIDs).value.replace(',','\n'));
    var idArr = document.getElementById(totIDs).value.split(",")
    var chk = new getObj(id);
    var tot = 0;
    var dbStr = '';
    var tbObjVal
    //alert(idArr.length+'\n'+idArr[0]+'\n'+idArr[1]);
    for(i=0; i<idArr.length; i++){
        tbObjVal = document.getElementById(idArr[i]).value.replace(' ','');
        dbStr = dbStr + idArr[i]+' value='+tbObjVal+'\n';
        if(isNaN(tbObjVal) || tbObjVal.length==0 || tbObjVal==0){
            tot = Number(tot) + 0;
        }
        else{
            tot = Number(tot) + Number(tbObjVal);
        }
    }
     
    //alert(idArr.length+'\n'+dbStr+'\nitemTot='+tot); 
    //alert(dbStr);
    if(tot < 1){
        chk.obj.checked = false;
     }
    else{
        chk.obj.checked = true;
    } 
  
}

function ItemChangeOneVal(id,tbVal){
    //var itemTot = document.getElementById(totID);
    //alert(tbVal);
    var chk = new getObj(id);
    var tot = 0;
    tbVal = tbVal.replace(' ','');
    if(isNaN(tbVal) || tbVal.length==0 || tbVal==0){
        tot = Number(tbVal) + 0;
    }
    else{
        tot = Number(tot) + Number(tbVal);
    }
    //alert(idArr.length+'\n'+dbStr+'\nitemTot='+tot); 
    if(tot < 1){
        chk.obj.checked = false;
     }
    else{
        chk.obj.checked = true;
    }  
  
}


 //NS 20697 - found this function on the web.  Used to override
 //the 2 digit to 4 digit date conversion in DateTextBox to
 //fix issue with this not working in Firefox when client side
 //Compare Validator is used
 //
 //http://www.bennadel.com/blog/490-Ask-Ben-Cleaning-Two-Digit-Years-Using-Javascript-And-Regular-Expressions.htm
 //
 // This takes a date string that MIGHT have a two digit year
 // as the last two digits. If it does, this function replaces
 // the two digit year with what it *assumes* is the proper
 // four digit year.
 function CleanDate(strDate){
  
    // Return the cleaned date.
     return(
         strDate.replace(
             // This regular expression will search for a slash
             // followed by EXACTLY two digits at the end of
             // this date string. The two digits are being
             // grouped together for future referencing.
             new RegExp( "/(\\d{1,2})$", "" ),
              
             // We are going to pass the match made by the
             // regular expression off to this function literal.
             // Our arguments are as follows:
             // $0 : The entire match found.
             // $1 : The first group within the match.
             function( $0, $1 ){
                 
                 //If length of $1 is 1 char then replace
                 //with 200 (ie 2000 - 2009) so it will
                 //work like peter's DateTextBox.
                 //Then Check to see if our first group begins with
                 // a zero,1,2,3,or 4(peter's DateTextBox formats up   
                 //to 2049). If so, replace with 20 else
                 // replace with 19.
                 
                 if($1.length==1){
                    //Replace with 200
                    return("/200" + $1);
                 }
                 else{
                     if ($1.match( new RegExp( "^[01234]{1}", "" ) )){
                      
                        // Replace with 20.
                        return( "/20" + $1 );
                      
                     } 
                     else {
                      
                        // Replace with 19.
                         return( "/19" + $1 );
                      
                     }
                }
             }
         )
    );
  
 }
 
 
 //xml base object useful for handling client side xml string for Ajax
 //create new object creating an object new xml();

function xml(){
    this.data;
}

xml.prototype.loadXml = function(xml){
    if (window.ActiveXObject) {
       this.data = new ActiveXObject("Microsoft.XMLDOM"); 
       this.data.async = false; 
       this.data.loadXML(xml);
    } else if (document.implementation && document.implementation.createDocument) {
        this.data= document.implementation.createDocument("","",null);
        var xmlParser = new DOMParser(); 
        this.data = xmlParser.parseFromString(xml, 'text/xml'); 
    } 
}

xml.prototype.returnXml = function() {
    return (this.data.xml)?this.data.xml:this.getXml();
}

xml.prototype.getXml = function() {
    var s=new XMLSerializer();
    return s.serializeToString(this.data);
}

xml.prototype.addNode = function(name) {
    var n=this.data.createElement(name);
    this.data.firstChild.appendChild(n);
    return n;
}

xml.prototype.removeNode = function(index) {
    this.data.firstChild.removeChild(this.data.firstChild.childNodes[index]);
}

xml.prototype.addAttr = function (n, v) {
    var a=this.data.createAttribute(n);
    a.nodeValue=v;
    return a;
}


//
// pdAjax.js
//


function pdAjaxOnLoad() {
    window.setTimeout("RunRefreshFunc()", 100);
}

function RunRefreshFunc() {
    if (theform.__PDAJAXREFRESHFUNC.value != '') {
        var isSafari = /Safari/.test(navigator.userAgent);
        if (!isSafari) {
            theform.__pdVIEWSTATE.value = theform.__PDAJAXREFRESHVS.value;
            fInitFormChange();
            eval(theform.__PDAJAXREFRESHFUNC.value);
        }
    }
}

function UpdateRefreshFunc(val) {
    theform.__PDAJAXREFRESHFUNC.value = val;
    theform.__PDAJAXREFRESHVS.value = theform.__pdVIEWSTATE.value;
    fInitFormChange();
}

function GetCallbackData(result, context) {
    var xmlDoc;
    if (window.ActiveXObject) {
       xmlDoc = new ActiveXObject("Microsoft.XMLDOM"); 
       xmlDoc.async = false; 
       xmlDoc.loadXML(result);
    } else if (document.implementation && document.implementation.createDocument) {
        xmlDoc= document.implementation.createDocument("","",null);
        var xmlParser = new DOMParser(); 
        xmlDoc = xmlParser.parseFromString(result, 'text/xml'); 
    } 
    var DATA = xmlDoc.getElementsByTagName('ITEM');
    for (var i=0;i<DATA.length;i++) {
        var objType = DATA[i].getElementsByTagName('TYPE')[0].firstChild.nodeValue;
        var objID = DATA[i].getElementsByTagName('ID')[0].firstChild.nodeValue;
        var objParam = DATA[i].getElementsByTagName('UPDATEPARAM')[0].firstChild.nodeValue;
        var objValue = DATA[i].getElementsByTagName('VALUE')[0].firstChild.nodeValue;
                 
        if (objID == 'pdViewState') {
           // .Net 2.0 holds form data in __theFormPostData for sending to a callback post.
           // In order for pdViewState to be maintained between callback, we must
           // re-init __theFormPostData.  First set it to a blank value, then call WebForm_InitCallback
           // to populate with updated form values.
           theform.__pdVIEWSTATE.value = objValue;
           fInitFormChange();
        } else if (objType == 'js') {
            eval(objValue); 
        } else {
            var o = new getObj(objID);
            if (o.obj) {
                if(objParam == 'eval'){eval('o.obj'+objValue);} 
                    else {o.obj[objParam] = objValue;};
            }  
        }
         
    }
    pdChangeCheck.pdChangeCheckBind();        
}

function StartCallback(objid) { 
    fInitFormChange();
    var o = new getObj(objid); 
    if (o.obj) {
        o.obj.innerHTML = '<div style="padding:50px 0px 50px 9px;text-align:center;font-style:italic;"><img src="/podium/images/spinner.gif" width="16px" height="16px" align="absmiddle"> Loading</div>';
    }
}

function StartCallbackNoText(objid) {
    fInitFormChange();
    var o = new getObj(objid);
    if (o.obj) {
        o.obj.innerHTML = '<img src="/podium/images/spinner.gif" width="16px" height="16px" align="absmiddle">';
    }
}

function StartCallbackCust(objid, CustText) { 
    fInitFormChange();
    var o = new getObj(objid); 
    if (o.obj) {
        o.obj.innerHTML = CustText
    }
}

function fInitFormChange() {
   __theFormPostData = '';
    fWebForm_InitCallback();
}

function ShowAjaxStatus() {
    ShowAjaxStatusMsg('Loading...');
}

function ShowAjaxStatusMsg(msg) {
    fInitFormChange();
    var $pdAJS = $('#pdAjaxStatus');

    if (isWebApp()) {
        if ($pdAJS.length == 0) {
            $pdAJS = $('<div id="pdAjaxStatus" class="pd3LargeTxt pd3ColorTxt"></div>');
            $('BODY').append($pdAJS);
        }
        $pdAJS.html('<span>' + msg + '</span>');
    } else {
         if ($pdAJS.length == 0) {
            $pdAJS = $('<div id="pdAjaxStatus" class="maintext" style="position:absolute;background-color:#ffffff;border:1px solid #c1c1c1;color:#000000 !important;padding: 20px;text-align:center;"></div>');
            $('BODY').append($pdAJS);
        }
        $pdAJS.html('<div style="float:left;margin-right:5px;"><img src="/podium/images/spinner.gif" width="16px" height="16px" align="absmiddle"></div><div style="float:left;"><i>' + msg + '</i></div>');
        var left = ((document.body.offsetWidth / 2) - ($pdAJS.width() / 2));
        var top = document.body.scrollTop + ((document.body.offsetHeight / 2) - ($pdAJS.height() / 2));
        $pdAJS.css({'z-index':'9999999','left':left,'top':top});
    }
}

function HideAjaxStatus() {
    $('#pdAjaxStatus').remove();
}

// Created a custom version of this function to fix bug with muti-select list.
function fWebForm_InitCallback() {
    var count = theForm.elements.length;
    var element;
    for (var i = 0; i < count; i++) {
        element = theForm.elements[i];
        var tagName = element.tagName.toLowerCase();
        if (tagName == "input") {
            var type = element.type;
            if ((type == "text" || type == "hidden" || type == "password" ||
                ((type == "checkbox" || type == "radio") && element.checked)) &&
                (element.id != "__EVENTVALIDATION")) {
                WebForm_InitCallbackAddField(element.name, element.value);
            }
        }
        else if (tagName == "select") {
            var selectCount = element.options.length;
            for (var j = 0; j < selectCount; j++) {
                var selectChild = element.options[j];
                if (selectChild.selected == true) {
                    WebForm_InitCallbackAddField(element.name, selectChild.value);
                }
            }
        }
        else if (tagName == "textarea") {
            WebForm_InitCallbackAddField(element.name, element.value);
        }
    }
}



//
// mml.js
//
function openMML(consoleid, galleryid, mediaid, mediatype) {

	var pGallery = (galleryid > 0)?'&gallery_id=' + galleryid:'';
	var pMedia = (mediaid > 0)?'&mediatype=' + mediatype +'&mediaid=' + mediaid:'';
	
	window.open('/podium/MediaLibrary/player/default.aspx?console_id=' + consoleid + pGallery + pMedia + '&browse=yes&recent=yes', '_blank', 'location=no, menubar=no, scrollbar=no, status=no, resizable=no, height=497, width=700');
	
}


//
// pdMenu.js
//
// finds the top left position of an element
function findPos(obj)
{
	var curleft = curtop = 0;
	if (obj.offsetParent) {
		curleft = obj.offsetLeft
		curtop = obj.offsetTop
		while (obj = obj.offsetParent) {
		    curleft += obj.offsetLeft
			curtop += obj.offsetTop
		}
	}
	return [curleft,curtop];
}

function IETrueBody()
{
	return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body 
}

function pdMenuSetMaxHeight(objid, height) {
	var d = new getObj(objid);
	if (d.obj.offsetHeight > height) d.style.height = height + 'px';
}

function pdMenuSetWidth(objid, width) {
	var d = new getObj(objid);
	d.style.width = width + 'px';
}

// WH CONSOLE
var WHEXPIN = false;
function initWHEXPIN(val) {
    WHEXPIN = val;
}

function SetWHEXPIN() {
    var WHEXA = document.getElementById('WHEXPINA'); 
    if (WHEXPIN == false) {
        WHEXPIN = true;
        WHEXA.innerHTML = 'autohide';
    } else {
         WHEXPIN = false;
         WHEXA.innerHTML = 'lock';
    }
}

var WHConShow = false;
function ShowWHCon() {
    Show('whWrap');
    WHConShow = true;
}

function HideWHCon() {
    WHConShow = false;
    window.setTimeout("HideWHConDo()", 600);
}

function HideWHConDo() {
    if ((WHConShow == false) && (WHEXPIN == false)) {
        Hide('whWrap');
    }
}
                  

// IFrame //
function pdNeedIf() {
    return ($('select').length > 0);
}

function pdGetIF() {
    var $pdIF = $('#pdIFrameDD');
    if ($pdIF.length == 0) {
        $pdIF = $('<iframe align="center" vspace="0" hspace="0" id="pdIFrameDD" frameborder="no" scrolling="no" src="/podium/blank.html" style="vertical-align:middle;position:absolute;visibility:hidden;bottom:0px;left:0px;height:0px;"></iframe>');
        $('BODY').append($pdIF);
    }
    return $pdIF;
}

function pdInitIF(ShowObjID) {
     if (pdNeedIf()) {
        var $pdIF = pdGetIF();
        var objShow = document.getElementById(ShowObjID);
        var zIndex = objShow.style.zIndex - 10;
        $pdIF.css({'z-index':zIndex,'left':objShow.style.left,'top':objShow.style.top,'width':objShow.offsetWidth,'height':objShow.offsetHeight});
    }
}

function pdShowIF() {
    if (pdNeedIf()) {
        var $pdIF = pdGetIF();
        $pdIF.css({'visibility':'visible'});
    }
}

function pdHideIF() {
    var $pdIF = $('#pdIFrameDD')
    if ($pdIF.length > 0) {
        $pdIF.remove();
    }
}

/* DROP MENU */
var openDrop = '';
var openDropButton = '';
function pdMenuShowDrop(ButtonID, DropDownID, AlignObjID, AlignType, PopulateFunction, OffSetTop, MyChannelID) {
        
    var objAlign = document.getElementById(AlignObjID);
    var pos = findPos(objAlign);
    var posLeft = pos[0];
    var posTop = pos[1]; 
        
    var objDrop = document.getElementById(DropDownID);
    var doShow = true;
    
    if (openDrop != '') {
        if (openDrop == DropDownID) doShow = false;
        pdMenuHideDrop(openDrop);
    }
    
    if (doShow == true) {
    
        var objDropStyle = objDrop.style;
                 
        openDropButton = ButtonID;
        openDrop = DropDownID;
        
        // show iframce before callback so it is visible and can be sized
        pdShowIF();
                
        if (PopulateFunction != '') eval(PopulateFunction);
        
        if(MyChannelID)var MyChannel=document.getElementById(MyChannelID);
        var ScrollTop=(MyChannel)?MyChannel.scrollTop:0;

//        if (isWebApp()) {
//            $('#Form1').append($(objDrop));
//        } else {
//            if (MyChannelID) {
//                objDrop.parentNode.removeChild(objDrop);
//                document.body.appendChild(objDrop);
//            }
//        }

        // Move drop region to form object for positioning.  using form object instead of body so buttons and inputs still work.
        $('#Form1').append($(objDrop));

        objDropStyle.display = '';

        posTop = posTop + objAlign.scrollHeight + OffSetTop - ScrollTop;
        
        if (AlignType == 'left') {
            posLeft = posLeft;
        } else if (AlignType == 'right') {
            posLeft = (posLeft + objAlign.offsetWidth) - objDrop.offsetWidth;
        }
        
        objDropStyle.left = posLeft;
        objDropStyle.top = posTop;
        objDropStyle.overflow = 'auto';
        objDropStyle.position = 'absolute';
        if(!objDropStyle.zIndex)objDropStyle.zIndex = '999999';
         
        if (PopulateFunction != '') {
            // if using AJAX, delay the sizing of the iframe so content is populated
            window.setTimeout("pdInitIF('" + DropDownID + "')", 500);
        } else {
             pdInitIF(DropDownID);
        }
        
    }
}

function pdMenuHideDrop(DropDownID) {
    var objDrop = document.getElementById(DropDownID);
    if (objDrop) {            
        objDrop.style.display = 'none';
        objDrop.style.overflow = '';
        pdHideIF();
     }
     openDropButton = '';
     openDrop = '';
}

function pdMenuCheckDrop(e) {
    if (openDrop != '') {
        var objDrop = document.getElementById(openDrop);
        if ((objDrop) && (objDrop.style.display == '')) {
            if (!e) var e = window.event;
            var tg = (window.event) ? e.srcElement : e.target;
            var HideDrop = true;
            var pos = findPos(objDrop);
            var posLeft = pos[0];
            var posTop = pos[1]; 
            var clickX;
            var clickY;
            var btnClicked = false;
            
            var btnObj = tg;
            if (btnObj.id == openDropButton) {
                HideDrop = false;
                btnClicked = true;
            } else {
                 if (btnObj.offsetParent) {
                   while (btnObj = btnObj.offsetParent) {
                        //alert(tmpObj.id + ',' + openDrop);
		                if (btnObj.id == openDropButton) {
		                    // set hidedrop to false if dropdown button click again
		                    // the showDrop function will then close it
		                    HideDrop = false;
		                    btnClicked = true;
		                    break;
		                }
	                }
                }
            }
                      
            if (btnClicked == false) {
            
                if (e.pageX) {
                    clickX = e.pageX;
                    clickY = e.pageY;
                } else {
                    clickX = event.x+IETrueBody().scrollLeft;
                    clickY = event.y+IETrueBody().scrollTop;
                }
                
               if ((clickX > posLeft) && (clickX < (posLeft + objDrop.offsetWidth))) {
                    if ((clickY > posTop) && (clickY < (posTop + objDrop.offsetHeight))) {
                        HideDrop = false;
                    }
                }
                        
                // prevent hiding dropdown when clicking on scrollbar
                if (HideDrop == true) {
                    if (clickX > document.body.clientWidth) HideDrop = false;
                }
                 if (HideDrop == true) {
                    if (clickY > document.body.clientHeight) HideDrop = false;
                }

                if (HideDrop == true) {
                    if ($(tg).parents().hasClass('ui-datepicker')) {
                        HideDrop = false;
                    } else  if ((tg.parentNode) && (tg.parentNode.type == 'select-one')) {
                        HideDrop = false;
                    } else if (tg.type == 'select-one') {
                        HideDrop = false;
                    } else if (tg.className.substring(0,6) == 'wheBtn') {
                        HideDrop = false;
                    } else if (tg.id == openDrop) {
                        HideDrop = false;
                    }
                }
                                    
                if (HideDrop == true) {
                   var tmpObj = tg;
                   if (tmpObj.offsetParent) {
                        while (tmpObj = tmpObj.offsetParent) {
                            //alert(tmpObj.id + ',' + openDrop);
			                if (tmpObj.id == openDrop) {
			                    HideDrop = false;
			                    break;
			                }
		                }
	                }
	             }
                   
                if (HideDrop == true) {
                    var t = 0;
                }
            
            }
                                           
            if (HideDrop == true) pdMenuHideDrop(openDrop);
         }
    }
}
document.onmousedown = pdMenuCheckDrop;


// TASK MENU //
function TMTabOver(taskid) {
	for (var i = 1;i <= 4; i++) {
	    var objItem = document.getElementById('tmt' + i + '_' + taskid);
	    if (objItem && (objItem.className == 'tmt' + i)) objItem.className = 'tmt' + i + 'o';
	}
}

function TMTabOff(taskid) {
    for (var i = 1;i <= 4; i++) {
	    var objItem = document.getElementById('tmt' + i + '_' + taskid);
	    if (objItem && (objItem.className == 'tmt' + i + 'o')) objItem.className = 'tmt' + i;
    }
}

var tipOn = 'notset';
function TMShowTip(txt) {
    if (tipOn == 'notset') {
        var val = getCookieValue('TMTT');
        if (val == '') val = '1';
        tipOn = (val == '1') ? 'on' : 'off';
	 }
	 if (tipOn == 'on') ShowTip(txt);
}

function TMToolTipInit(objid) {
    var obj = document.getElementById(objid);
  	var value;
  	if (obj.checked == true) {
  	    value = '1';
  	    tipOn = 'on';
  	} else {
  	    value = '0';
  	    tipOn = 'off';
  	}
  	setCookieValue('TMTT', value);
}

//
// pdToolTip.js
// 
var tipobj = null
var tipifobj
var offsetxpoint=10 //Customize x offset of tooltip
var offsetypoint=20 //Customize y offset of tooltip
var ie=document.all
var ns6=document.getElementById && !document.all
var enabletip=false
var gbX=0
var gbY=0

// For info popups (roster cards, equipment info cards, etc.)
// ECD - 18 Aug 2005
var infoWidth
                                          
function GetTipObj() {
    if (tipobj == null) {
        if (ie||ns6)
        {
            if ($('#tooltip').length == 0) {
                $('BODY').append('<div id="tooltip" class="tooltip" style="position:absolute;visibility:hidden;width: 200px; z-index: 9999999;left:-700px;top:0px;"></div>');
            }
	        tipobj=document.all? document.all["tooltip"] : document.getElementById? document.getElementById("tooltip") : ""
	        if ($('#pdIFrameTT').length == 0) {
	            $('BODY').append('<iframe align="center" vspace="0" hspace="0" id="pdIFrameTT" frameborder="no" scrolling="no" src="/podium/blank.html" style="vertical-align:middle;position: absolute;visibility:hidden;bottom:0px;left:0px;height:0px;"></iframe>');
	        }
	        tipifobj=document.all? document.all["pdIFrameTT"] : document.getElementById? document.getElementById("pdIFrameTT") : ""
        }
    }
}

function ietruebody()
{
	return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body 
}
 
function ShowTip(thetext)
{ 
    if (ns6||ie)
	{
	    GetTipObj();
		tipobj.innerHTML=thetext;
		enabletip=true
		infoWidth=200
		return false
	}
}
 
function ShowTipNoWidth(thetext)
{ 
    if (ns6||ie)
	{
	    GetTipObj();
		tipobj.innerHTML=thetext;
		enabletip=true
		infoWidth=-1;
		return false
	}
}
 
function ShowTipGB(thetext, x, y)
{ 
   if (ns6||ie)
	{
	    GetTipObj();
	    gbX=x;gbY=y;
		tipobj.innerHTML=thetext;
		enabletip=true
		infoWidth=200
		tipifobj.style.zIndex=eval(tipobj.style.zIndex-1);
		return false
	}
}

// For info popups (roster cards, equipment info cards, etc.)
// ECD - 18 Aug 2005
function ShowInfoTip(pText, pWidth)
{
   if (ns6||ie)
	{
	    GetTipObj();
		tipobj.innerHTML=pText;
		enabletip=true
		infoWidth=pWidth
		return false
	}
}

function PositionTip(e)
{
	if (enabletip)
	{ 
	    GetTipObj();
		var curX=(ns6)?e.pageX : event.x+ietruebody().scrollLeft+gbX;
		var curY=(ns6)?e.pageY : event.y+ietruebody().scrollTop+gbY;

		//Find out how close the mouse is to the corner of the window
		var rightedge=ie&&!window.opera? ietruebody().clientWidth-event.clientX-offsetxpoint : window.innerWidth-e.clientX-offsetxpoint-20
		var bottomedge=ie&&!window.opera? ietruebody().clientHeight-event.clientY-offsetypoint : window.innerHeight-e.clientY-offsetypoint-20
		var leftedge=(offsetxpoint<0)? offsetxpoint*(-1) : -1000

		//if the horizontal distance isn't enough to accomodate the width of the context menu
		if (rightedge<tipobj.offsetWidth)
			//move the horizontal position of the menu to the left by it's width
			tipobj.style.left=ie? ietruebody().scrollLeft+event.clientX-tipobj.offsetWidth+"px" : window.pageXOffset+e.clientX-tipobj.offsetWidth+"px"
		else if (curX<leftedge)
			tipobj.style.left="5px"
		else

		//position the horizontal position of the menu where the mouse is positioned
		tipobj.style.left=curX+offsetxpoint+"px"

        //same concept with the vertical position
        if (bottomedge < tipobj.offsetHeight)
		{
            tipobj.style.top=ie? ietruebody().scrollTop+event.clientY-tipobj.offsetHeight-offsetypoint+"px" : window.pageYOffset+e.clientY-tipobj.offsetHeight-offsetypoint+"px"
        }
		else
		{
            tipobj.style.top=curY+offsetypoint+"px"
         }
         
        tipobj.style.visibility="visible"

		// For info popups (roster cards, equipment info cards, etc.)
		// ECD - 18 Aug 2005
		
		if (infoWidth > 0)
		{
			tipobj.style.width = infoWidth;		
			infoWidth = 0;
		}else if (infoWidth == -1){
			tipobj.style.width='';		
			infoWidth = 0;
		}
        
        //if ie on pc and there are selects then use iframe (tipifobj) and set its dimensions and coordinates to those of the tool tip div
		//if (navigator.platform.indexOf('MacPPC') == -1 && ie && selCount.length>0)
		//{           
			tipifobj.style.visibility="visible";
			tipifobj.style.left = tipobj.style.left;
			tipifobj.style.top = tipobj.style.top;
			tipifobj.style.width = tipobj.scrollWidth;
			tipifobj.style.height = tipobj.scrollHeight;  
		//}		
	}
}

function HideTip()
{
	if (ns6||ie)
	{
	    $('#tooltip').css({'visibility':'hidden','left':'-1000px'});
	    $('#pdIFrameTT').css({'visibility':'hidden','left':'-1000px'});
	    enabletip=false;
        gbX=0;gbY=0;   
    }
}

document.onmousemove=PositionTip

//
// pdGallery.js
//
function GalleryChangeImage(galid, direction, showcaption) { 
    var imageGallery = eval(galid + 'Gallery');
    var $image = $('#' + galid + 'image');
    var $count = $('#' + galid + 'count');
    var $caption = $('#' + galid + 'caption');
    
    if (imageGallery && $image.length > 0) {
         imageGallery.curIndex += direction;
         
         // Make sure current image index fits within the object array bounds
         if (imageGallery.curIndex < 0) {
            imageGallery.curIndex = (imageGallery.images.length - 1);
         } else if (imageGallery.curIndex > (imageGallery.images.length - 1)) {
            imageGallery.curIndex = 0;
         }
         
         // Get the current image object from the array
         var curImage = imageGallery.images[imageGallery.curIndex];
         
         // Set the new gallery
         $image.html('<img id="' + galid + 'img" src="' +  curImage.src + '" width="' + curImage.width + '" height="' + curImage.height + '" align="absbottom" />')
         
         // Set the count
         if ($count.length > 0) {
            var existingHTML = $count.html();
            var countString =  (imageGallery.curIndex + 1) + ' of ' + imageGallery.images.length;
            if (existingHTML.substring(0,5) == 'Image') {
	            $count.html('Image ' + countString);
	        } else {
	            $count.html(countString);
	        }
         }
                  
         // Set the caption
         if ($caption.length > 0) {
            if (showcaption == 'True' && curImage.caption.length > 0) {
                var caption = curImage.caption.replace(/\&#34;/g, '"').replace(/\<a/gi,"<a class='cchlnk'");
                $caption.html(caption);
                
                if (curImage.zSrc.length > 0) {
                   if (caption.length > 0) {
                        caption += '<br><br>';
                    }
                    caption += 'Click for Zoom...';
                }
                
                $image.bind('mouseover', function(){
                    ShowTip('<div>' + caption + '</div>');
                });
                
                $image.bind('mouseout', function(){
                    HideTip();
                });
                
                $caption.show();
            } else {
                $image.unbind('mouseover').unbind('mouseout');
                $caption.hide()
            }
         }
         
         if (curImage.zSrc.length > 0) {
             $image.css({'cursor':'pointer'});
             $image.bind('click', function(){
                pdOpenWindow('<img id="content" src="' + curImage.zSrc + '" width="' + curImage.zWidth + '" height="' + curImage.zHeight + '">', '', '');
             });
         } else {
             $image.css({'cursor':'normal'});
             $image.unbind('click');   
         }
         
         return true;
    } else {
        return false;
    }
} 


//
// fcInquiry.js
//

function ColapseGroup(cell, id, name) { 
    var s=cell.innerHTML
    cell.innerHTML = (s.indexOf('collapse.gif')==-1)?s.replace('expand.gif', 'collapse.gif'):s.replace('collapse.gif', 'expand.gif');
    var o=document.getElementById(id)
    if(o){
        var list=o.getElementsByTagName('tr')
        for(i=0;i<list.length;i++){
            if(list[i].getAttribute('name')==name)
                list[i].style.display=(list[i].style.display=='none')?'':'none';
        }
    }
}


function GetAddress(checked, name) {

    var a1,a2,city,province,region,zip,phone;
    var o_a1,o_a2,o_city,o_province,o_region,o_zip,o_phone;
    var statetb,o_statetb,o_statedd,o_kidstatedd,x,countryCode;
     
    var list=document.getElementsByTagName('input')
    for(i=0;i<list.length;i++){
        if(list[i].id.indexOf('txtf_'+name)>0){
           if (list[i].id.indexOf('txtf_'+name+'_address_1')>0){
                o_a1=list[i]
            }
            else if (list[i].id.indexOf('txtf_'+name+'_address_2')>0){
                o_a2=list[i];
            }
            else if (list[i].id.indexOf('txtf_'+name+'_city')>0){
                o_city=list[i];
            }
            else if (list[i].id.indexOf('txtf_'+name+'_province')>0){
                o_province=list[i];
            }
            else if (list[i].id.indexOf('txtf_'+name+'_region')>0){
                o_region=list[i];
            }
            else if (list[i].id.indexOf('txtf_'+name+'_zip')>0){
                o_zip=list[i];
            }
            else if (list[i].id.indexOf('txtf_'+name+'_phone')>0){
                o_phone=list[i];
            }
            else if (list[i].id.indexOf('txtf_'+name+'1_phone')>0){
                o_phone=list[i];
            }
        }
        else if (list[i].id.indexOf('txtf_address_1')>0){
            if(checked)a1=list[i].value;
        }
        else if (list[i].id.indexOf('txtf_address_2')>0){
            if(checked)a2=list[i].value;
        }
        else if (list[i].id.indexOf('txtf_city')>0){
            if(checked)city=list[i].value;
        }
        else if (list[i].id.indexOf('txtf_province')>0){
            if(checked)province=list[i].value;
        }
        else if (list[i].id.indexOf('txtf_region')>0){
            if(checked)region=list[i].value;
        }
        else if (list[i].id.indexOf('txtf_zip')>0){
            if(checked)zip=list[i].value;
        }
        else if (list[i].id.indexOf('txtf_phone_home')>0){
            if(checked)phone=list[i].value;
        }
        else if (list[i].id.indexOf('f_state_tbProvince')>0){
            if(checked)statetb=list[i].value;
        }
        else if (list[i].id.indexOf('f_' + name + '_state_tbProvince')>0){
            o_statetb=list[i];
        }
    }
   
    var country,o_country;
    list=document.getElementsByTagName('select')
    for(i=0;i<list.length;i++){
        if(list[i].name.indexOf('f_'+name + '_country')>0){
            o_country=list[i]
        }
        else if (list[i].name.indexOf('f_country')>0){
            if(checked)country=list[i].selectedIndex;
            if(checked)countryCode = list[i].value;
        }
        else if (list[i].name.indexOf('f_'+name + '_state')>0){
            o_statedd=list[i]
        }
        else if (list[i].name.indexOf('f_state')>0){
            o_kidstatedd=list[i]
        }
    }

//have 2 state fields
    if (o_kidstatedd&&o_statedd){
        //add try catch to mult state field section to handle wierd bug
        //with ie 6 
        try
        {
            if(checked){
                if (o_kidstatedd.style.display=='none'){
                    //show and populate the textbox
                    o_statetb.style.display='';
                    o_statetb.value=(statetb)?statetb:'';
                    //clear and hide the dropdown
                    for (var q=o_statedd.options.length;q>=0;q--) o_statedd.options[q]=null;
                    o_statedd.style.display = 'none';
                }
                else{
                   //show and populate dropdown from kids dropdown
                   o_statedd.style.display = '';
                   for (var q=o_statedd.options.length;q>=0;q--) o_statedd.options[q]=null;
                   var myEle ;
                   for (var q=0; q<o_kidstatedd.options.length;q++){
                    // alert(o_kidstatedd.options[q].text+'|'+o_kidstatedd.options[q].text);
                    //if (o_kidstatedd.options[q]) alert(o_kidstatedd.options[q].text+'|'+o_kidstatedd.options[q].text);
                        myEle = document.createElement("option") ;
                        myEle.setAttribute("value",o_kidstatedd.options[q].value);
                        var txt = document.createTextNode(o_kidstatedd.options[q].text);
                        myEle.appendChild(txt);
                        o_statedd.appendChild(myEle); 
                   }
                       o_statedd[o_kidstatedd.selectedIndex].selected = true;
                       //clear and hide textbox
                       o_statetb.value = ''
                       o_statetb.style.display = 'none';
                }
            }
           else{
                //clear correct state province control based on which is visible
                if (o_statedd.style.display=='none'){
                    o_statetb.value = ''
                }
                else{
                    o_statedd[0].selected=true;
                }
            }
       }
       catch(err)
       {
          //do noting. we're just supressing the error
       }
     }   
     else if (o_statedd&&o_country&&checked&&country){
        if(country!=o_country.selectedIndex){
            //state not visible for kid, but country changed, synch state/prov for new country
            for (var q=o_statedd.options.length;q>=0;q--) o_statedd.options[q]=null;            
            if (countryCode=="AU"||countryCode=="CA"||countryCode=="GB"||countryCode=="US") 
            {
                o_statetb.value=''
                o_statetb.style.display = 'none';
                o_statedd.style.display = '';
                myEle=document.createElement("option");
                var theText=document.createTextNode("");
                myEle.appendChild(theText);
                myEle.setAttribute("value","");
                o_statedd.appendChild(myEle);
                for ( x = 0 ; x < arrProvince.length  ; x++ ) {
                    prov = arrProvince[x]
                    if ( prov[0] == countryCode ) {
                        myEle = document.createElement("option") ;
                        myEle.setAttribute("value",prov[2]);
                        var txt = document.createTextNode(prov[1]);
                        myEle.appendChild(txt);
                        o_statedd.appendChild(myEle);
                    }
                }
            }
            else{
                o_statetb.style.display = '';
                o_statedd.style.display = 'none';
            }
        }      
     }
     
    if(o_a1)o_a1.value=(a1)?a1:'';
    if(o_a2)o_a2.value=(a2)?a2:'';
    if(o_city)o_city.value=(city)?city:'';
    if(o_province)o_province.value=(province)?province:'';
    if(o_region)o_region.value=(region)?region:'';
    if(o_zip)o_zip.value=(zip)?zip:'';
    if(o_phone)o_phone.value=(phone)?phone:'';    
    if(o_country)o_country[(country)?country:0].selected=true;

    
}



function inquiry_checkall(o, checked, name) {
    
        while(o) {
            if (o.tagName.toLowerCase()=='table') 
                break;
            o=o.parentNode;
        } 
        
        list=o.getElementsByTagName('input')
        for(i=0;i<list.length;i++)
            if(list[i].type=='checkbox')
                if(!list[i].disabled)                  
                    if(list[i].id.indexOf(name)>0)
                        list[i].checked=checked;

}

//
// pdAddress.js
//
function countryChange(countryID, ddID, tbID, txtID, stateID) {
    var ddCountry, dd, tb, txtbox ;
    var hasItems = false ;
    var myEle ;
    var x ;
    var prov = new Array();
    var curVal;
    
    //get the controls
    ddCountry = document.getElementById(countryID);
    dd = document.getElementById(ddID);
    tb = document.getElementById(tbID);                    
    txtbox = document.getElementById(txtID);

    curVal = dd.value;
    
    //clear the dropdown
    for (var q=dd.options.length;q>=0;q--) dd.options[q]=null;
    
    //blank item for dropdown if one of countries we show state/province dd for
    var country;
    country = ddCountry.value;
    if (country=="AU"||country=="CA"||country=="GB"||country=="US") 
    {
        myEle=document.createElement("option");
        var theText=document.createTextNode("");
        myEle.appendChild(theText);
        myEle.setAttribute("value","");
        dd.appendChild(myEle);
    }
    
    //loop through provinces to see if any apply for this country
    for ( x = 0 ; x < arrProvince.length  ; x++ ) {
        prov = arrProvince[x]
        if ( prov[0] == country ) {
            myEle = document.createElement("option") ;
            myEle.setAttribute("value",prov[2]);
            var txt = document.createTextNode(prov[1]);
            myEle.appendChild(txt);
            dd.appendChild(myEle);
            hasItems = true;
            if (curVal == prov[2]) dd.value = curVal;
        }
    }

    //toggle visibility of dropdown and textbox
    if (hasItems == true)
        {
            dd.style.display = '';
            txtbox.style.display = 'none';
            txtbox.value = '';
        }
    else
        {
            txtbox.style.display = '';
            dd.style.display = 'none';
        }   
 
}

// Detect Flash
function HasFlash() {
    var hasFlash = false;
    if (navigator.plugins && navigator.mimeTypes.length) {
        var x = navigator.plugins["Shockwave Flash"];
        if (x && x.description) {
            hasFlash = true;
        }
    } else { 
        try {
            var axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.7");
        } catch (e) {
            try {
                var axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash.6");
            } catch (e) { }
            try {
                axo = new ActiveXObject("ShockwaveFlash.ShockwaveFlash");
            } catch (e) { }
        }
        if (axo != null) {
            hasFlash = true;
        }
    }
    return hasFlash;
}


// JQuery Datepicker
function dp_init(id, dateFormat, minDate, maxDate, beforeShowDayFunction, checkChanges) {
    var checkChanges = (pdChangeCheck != undefined && pdChangeCheck != null && pdChangeCheck.activeLevel > 0);
    if (checkChanges) { var $prevChange = $("#" + id).val(); }
    $("#" + id).datepicker({
        duration:''
        , showOn:'button'
        , buttonImage:'/podium/images/calendar.jpg'
        , buttonText:'Show Calendar'
        , buttonImageOnly:true
        , numberOfMonths:3
        , stepMonths:3
        , changeMonth:true
        , changeYear:true
        , showButtonPanel: true
        , yearRange:'-5:+5'
        , dateFormat: dateFormat 
        , onSelect: function() {
                        //NS 28506 - add ability to use change check logic
                        //when it's turned on.
                        if (checkChanges)
                        {
                            var $selVal = $("#" + id).val();
                            pdChangeCheck.globalChange = ($prevChange != $selVal);  
                        }
                   }
        , beforeShow: dp_show
        , minDate: minDate
        , maxDate: maxDate 
        , beforeShowDay: beforeShowDayFunction 
    });  
}

function dp_blur(input, val_id, close) {
    try {
        var $input = $(input);
        if ($input) {
            if ($input.val().length > 0) {
                var sep  = "/"
                if ($input.val().indexOf('.') > -1) {sep = "."}
                var inputVals = $input.val().split(sep);
                if (inputVals.length >= 2) { 
                    var inputMonth;
                    var inputDay;
                    var inputYear = '';
                    if (inputVals.length == 3) {inputYear = inputVals[2];}

                    if ((inputYear.length == 0) || (inputYear.length == 2)) {
                        var validator = null;
                        var dateFormat = $input.datepicker('option', 'dateFormat');
                        var newDate = new Date();
                        
                        if (val_id != null) {
                            validator = document.getElementById(val_id);
                            ValidatorEnable(validator, false);
                        }
                        
                        if (dateFormat.charAt(0) == "d") {
                            inputMonth = inputVals[1] - 1;
                            inputDay = inputVals[0];
                        } else {
                            inputMonth = inputVals[0] - 1;
                            inputDay = inputVals[1];
                        }
                        /*NS 26055 - set date before month so that in cases when the current day doesn't exist
                        //in the specified month the month doesn't get set to the month after the user specified
                        //month. Also check to see if month after setMonth is different then the user entered month 
                        //so we can fail validation instead of letting setMonth change to the next month in cases
                        where an actual invalid m/d or m/d/yy date was entered, ie 2/30.*/
                        newDate.setDate(inputDay);
                        newDate.setMonth(inputMonth);
                        //check entered month/date vs set month/date and fail validation if not
                        if (inputMonth == newDate.getMonth() && inputDay == newDate.getDate() && inputYear.length <= 2) {
                            if (inputYear.length == 0) {
                                $input.datepicker('setDate', newDate);
                                if (close == true) { $input.datepicker('hide') };
                            } else if (inputYear.length == 2) {
                                newDate.setFullYear('20' + inputVals[2]);
                                $input.datepicker('setDate', newDate);
                                if (close == true) { $input.datepicker('hide') };
                            }
                        }
                        else {
                            if (inputMonth != newDate.getMonth() || inputDay != newDate.getDate()) {
                                validator.enabled = true;
                                validator.isvalid = false;
                                var valCtrl = '#' + val_id;
                                $(valCtrl).show();
                                return 0;
                            }
                        }
                        
                        if (val_id != null) {
                             ValidatorEnable(validator, true);
                        }
                       
                    }
                }
            }
        }
    } catch(e) {
        // Do Nothing
    }
}

function dp_focus(input) {
    var id = $(input).attr("id");
    if (id) {
       $("input.datepicker").each(function(){
            if ($(this).attr("id") != id) {
                window.setTimeout("$(this).datepicker('hide')", 200);
            }
       });
    }
}

function dp_show(input, inst) {
    // Remove built-in keyboard shortcuts
    $(input).unbind("keydown");
    // Set the position to below the textbox
    dp_setPos(input, inst);
    // Check valid date
    dp_blur(input, null, false);
}

function dp_setPos(input, dp) {
    try {
        /*NS 25810 - comment out positioning
        var _inputTop = $(input).position().top;
        var _inputHeight = $(input).height() + 7;
        var _dpTop = _inputTop + _inputHeight;
        setTimeout(function(){$('#ui-datepicker-div').css({'top': _dpTop});},1)*/

        /** NS 29321 - horizontal positioning fix for IE **
        ** This functionality is close to what other browsers do automatically. **
        if the width of datepicker div and the left position of textbox is more than
        the current total browser window width then reposition the datepicker div
        by setting dp left position to input textbox left position+datepicker div width - browser
        window width . The left scroll amt is factored in as well as an additional 27 px  to compensate 
        for spacing/padding of datepicker div.*/
        if (isIE == 1) {
            var _inputLeft = $(input).position().left;
            setTimeout(function() {
                var divW = $('#ui-datepicker-div').width();
                var pWidth = $('body').width();
                var scroll = $('body').scrollLeft();
                if (_inputLeft + divW+30 > pWidth) {
                    var lftAdj = (_inputLeft + divW) - pWidth - scroll;
                    var newLeft = _inputLeft - lftAdj - 27;
                    $('#ui-datepicker-div').css({ 'left': newLeft + 'px' });
                }
            }, 1);
        }
        setTimeout(function() { $('#ui-datepicker-div').css({ 'z-index': '999999'}); }, 1)
        setTimeout(function() { $(".ui-datepicker-highlight a").attr('title', 'Class Scheduled') }, 100)
    } catch(e) {
        // Do Nothing
    }
}

function dp_keyDown(input, e) {
    var kCode = (e.which ? e.which : e.keyCode);
    var curDate = new Date();
    var cancelEvent = false;
    var $input = $(input);
    
    if ($input.datepicker("getDate") != null) {
        curDate = $input.datepicker("getDate");
    }
   
    switch(kCode) {
        case 67: // c (open)
            if (!e.ctrlKey) {
                $input.datepicker('show');
            }
            break     
        case 84: // t (today)
            $input.datepicker('setDate', new Date());
            cancelEvent = true;
            break;
        case 9: case 27: // tab / esc (close)
            $input.datepicker('hide');
            break       
        case 13:  // enter (validate and close)
            dp_blur(input, null, false);
            $input.datepicker('hide');
            cancelEvent = true;
            break       
        case 38: // up arrow (+1 day)
            $input.datepicker('setDate', new Date(curDate.setDate(curDate.getDate() + 1)));
            cancelEvent = true;
            break;
        case 40: // down arrow (-1 day)
            $input.datepicker('setDate', new Date(curDate.setDate(curDate.getDate() - 1)));
            cancelEvent = true;
            break;
        case 39: // right arrow (+1 week)
            $input.datepicker('setDate', new Date(curDate.setDate(curDate.getDate() + 7)));
            cancelEvent = true;
            break;
         case 37: // left arrow (-1 week)
            $input.datepicker('setDate', new Date(curDate.setDate(curDate.getDate() - 7)));
            cancelEvent = true;
            break;
         case 33: // page up (+1 month)
            $input.datepicker('setDate', new Date(curDate.setMonth(curDate.getMonth() + 1)));
            cancelEvent = true;
            break;
          case 34: // page down (-1 month)
            $input.datepicker('setDate', new Date(curDate.setMonth(curDate.getMonth() - 1)));
            cancelEvent = true;
            break;
    }
    
    if (cancelEvent == true) {
        e.cancelBubble = true;
	    e.returnValue = false;
    }
}

function dp_highlightDates(date, arr_dates) {
    var dtSelect = false;
    for (i = 0; i < arr_dates.length; i++) {
        if (date.getFullYear() == arr_dates[i][0] && date.getMonth() == arr_dates[i][1] - 1 && date.getDate() == arr_dates[i][2]) {
            dtSelect = true;
            break;
        }
    }
    if (dtSelect == true) {
       return [true,'ui-datepicker-highlight'];
     } else {
         return [true,''];
   }
}

//NS 26735 - needed for puspage
//used to ensure proper relative url prior to absolute url conversion.
//tinyMCE appears to base relative urls on the location of the
//paste plugin htm file instead of document_base_url so urls
//get an extra 3 ../ because of the /tiny_mce/plugins/paste/ directories.
function pasteProcRelUrl(txt) {
    //just get bad rel urls from paste word. They will
    //have 3 or 4 "../" since they are based on pasteword.html location
    //this regex will get urls after href|src that don't have http:\\,https:\\,or mailto:
    //that start with 3 or 4 ../'s.
    var repatt = /(href|src)\s*=\s*([\'"]*(?!http:|https:|mailto:))?((\.\.\/){3,4})(\w*[^\'">]*)/gi;
    var regex = new RegExp(repatt);
    var arrMatches = regex.exec(txt);
    var currUrl, findUrl, curDirs, dirArr;
    while (arrMatches != null && arrMatches != undefined) {
        currDirs = arrMatches[3]; //this will just be ../'s used //to get count
        currUrl = arrMatches[5]; //the url without ../'s
        findUrl = currDirs + currUrl;
        //alert(currDirs + '\n' + currUrl + '\n' + findUrl);
        dirArr = currDirs.match(/\.\.\//gi);
        if (dirArr != null && dirArr != undefined) {
            if (dirArr.length == 4) {
                currUrl = '../' + currUrl;
            }
        }
        findUrl = findUrl.replace(/\//gi, '\\/').replace(/\./gi, '\\.').replace(/\?/gi, '\\?');
        eval('txt=txt.replace(/' + findUrl + '/gi,"' + currUrl + '");');
        //alert(currUrl);
        arrMatches = regex.exec(txt);
    }
    //alert(txt);
    return txt;
}

// HTML Editor
function htmlEditorInit(id, toolbarType, allowFullScreen, initFunction) {
    var toolBarButtons1 = '';
    var toolBarButtons2 = '';
    var toolBarButtons3 = '';
    var toolBarButtons4 = '';
    var plugins = '';
    var validElements = '';
    var pasteProperties = '';
    var useInlineStyles = false;
    var forcePNewlines = false;
    var forceBrNewlines = true;
    var forcedRootBlock = '';
        
    switch (toolbarType) {
        case "full":
            toolBarButtons1 = 'code,|,spellchecker,print,replace,|,cut,copy,paste,pasteword,pastetext,|,undo,redo,|,table';
            toolBarButtons2 = 'bold,italic,underline,sup,sub,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,indent,outdent,numlist,bullist,|,charmap,link,unlink,anchor';
            break;
        case "limited":
            toolBarButtons1 = 'code,|,spellchecker,|,cut,copy,paste,pasteword,pastetext,|,bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull';
            break;
        case "full3line":
            toolBarButtons1 = 'code,|,spellchecker,print,replace,|,cut,copy,paste,pasteword,pastetext,|,undo,redo,|,table';
            toolBarButtons2 = 'bold,italic,underline,sup,sub,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull';
            toolBarButtons3 = 'indent,outdent,numlist,bullist,|,charmap,link,unlink,anchor';
            break;
        case "merge":
            toolBarButtons1 = 'code,|,spellchecker,|,cut,copy,paste,pasteword,pastetext,|,bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull';
            break;
        case "grading":
           toolBarButtons1 = 'code,|,spellchecker,|,bold,italic,underline,|,pastetext';
           break;
        case "pushpage":
            toolBarButtons1 = 'code,spellchecker,cut,copy,paste,pasteword,pastetext';
            toolBarButtons2 = 'bold,italic,underline,sup,sub,strikethrough,table';
            toolBarButtons3 = 'justifyleft,justifycenter,justifyright,justifyfull,numlist,bullist,charmap';
            toolBarButtons4 = 'link,unlink,anchor,formatselect';
           break;
    }
    if (allowFullScreen == true) {
        toolBarButtons1 = 'fullscreen,' + toolBarButtons1;
    }

     switch (toolbarType) {
        case "grading":
            plugins = 'fullscreen,paste,spellchecker,tabfocus,safari';
            validElements = 'b/strong,i/em,u,br,-span[*]';
            pasteProperties = 'margin,padding';
            break;
        case "pushpage":
            plugins = 'autoresize,paste,safari,spellchecker,table,tabfocus';
            validElements = '@[id|class|style|title|dir<ltr?rtl|align],'
                            + 'a[rel|rev|charset|hreflang|tabindex|accesskey|type|name|href|target|title|class],'
                            + 'b/strong,i/em,strike,u,-ol[type|compact],-ul[type|compact],-li,br,'
                            + 'img[longdesc|usemap|src|border=0|alt=|title|hspace|vspace|width|height|align],'
                            + '-sub,-sup,-blockquote,'
                            + '-table[border=0|cellspacing|cellpadding|width|frame|rules|height|align|summary|bgcolor|background|bordercolor],'
                            + '-tr[rowspan|width|height|align|valign|bgcolor|background|bordercolor],tbody,thead,tfoot,'
                            + '#td[colspan|rowspan|width|height|align|valign|bgcolor|background|bordercolor|scope],'
                            + '#th[colspan|rowspan|width|height|align|valign|scope],caption,'
                            + '-span,-code,-pre,address,-h1,-h2,-h3,-h4,-h5,-h6,hr[size|noshade],-font[face'
                            + '|size|color],dd,dl,dt,cite,abbr,acronym,del[datetime|cite],ins[datetime|cite],'
                            + 'map[name],area[shape|coords|href|alt|target],bdo,'
                            + 'button,col[align|char|charoff|span|valign|width],colgroup[align|char|charoff|span|valign|width],'
                            + 'kbd,label[for],legend,optgroup[label|disabled],option[disabled|label|selected|value],'
                            + 'q[cite],samp,select[disabled|multiple|name|size],small';
            pasteProperties = 'font-size,color,margin,padding';
            // Setting these for pushpage fixes an issue with alignment not working in certain browsers
            // In the future, may need to set these by default to fix all areas of the system
            useInlineStyles = true;
            forcePNewlines = true;
            forceBrNewlines = false;
            forcedRootBlock = 'div';
            break;
       default:
            plugins = 'media,fullscreen,paste,print,safari,searchreplace,spellchecker,table,tabfocus';
            validElements = '*[*],b/strong,i/em,script[src|type],-span[*],-font[*]';
            pasteProperties = 'font-size,color,margin,padding';
            break;
    }
    
    // NS 29774
    // We are replacing all instances of paragraph tags with div tags which are better supported within Crystal
    //  and which do not require margin and padding of 0.
    //  div/p will replace all P tags with DIV
    //  #div[*] will allow all attributes on div tags and will pad empty div tags with &nbsp;
    validElements += ',div/p,#div[*]'

    tinyMCE.init({
        mode: 'exact',
        elements: id,
        language: "en",
        plugins: plugins,
        button_tile_map: true,
        forced_root_block: forcedRootBlock,
        force_p_newlines: forcePNewlines,
        force_br_newlines: forceBrNewlines,
        remove_redundant_brs: false,
        valid_elements: validElements,
        invalid_elements: "st1:*",
        convert_fonts_to_spans: false,
        inline_styles: useInlineStyles,
        paste_retain_style_properties: pasteProperties,
        paste_auto_cleanup_on_paste: true,
        paste_preprocess: function (pl, o) {
            o.content = pasteProcRelUrl(o.content);
        },
        paste_postprocess: function (pl, o) {
            var html = o.node.innerHTML;
            html = html.replace(/\<div id="_mcePaste[^\>]*\>/gi, '<div>');
            o.node.innerHTML = html;
        },
        cleanup_callback: function (type, value) {
            var val = value;
            if ((type == 'get_from_editor') || (type == 'insert_to_editor')) {
                val = val.replace(/\<\/\*span-[^\>]*\>/gi, '');
                // Cleanup Browser Highlighter AddOn Junk
                val = val.replace(/\<div[^\>]*refHTML[^\>]*\>[^\>]*\<\/div\>/gi, '');
                val = val.replace(/\<input[^\>]*gwProxy[^\>]*\/\>/gi, '');
                val = val.replace(/\<input[^\>]*jsProxy[^\>]*\/\>/gi, '');

                //NS 28367 - Cleanup weird span-style-span tag that sometimes gets added
                //repeatedly when pasting html
                val = val.replace(/\<[^\>]*span-style-span[^\>]*\>.*?\<\/[^\>]*span-style-span[^\>]*\>/gi, '');
            }
            return val;
        },
        save_callback: function (element_id, html, body) {
            // regular expression to make sure html is not just an empty div region
            html = html.replace(/^\<div\>\&nbsp\;\<\/div\>$/gi, '');
            return html;
        },
        //NS 28096 - This is an overwrite of the FormatBlock
        //function in Podium\tinymce\tiny_mce_src.js line 10241
        //We're rewriting it to apply block formatting to the
        //selected text instead of the current block element node
        //as clients we're getting confused when entire blocks were
        //getting formatting 
        //acoording to tinyMCE docs this is the old way to override command, but addCommand
        //doesn't appear to work in chrome/safari.
        execcommand_callback: function (editor_id, elm, command, user_interface, value) {
            if (editor_id != 'pp_edittext') { return false; }
            switch (command) {
                case "FormatBlock":
                    var ed = tinyMCE.getInstanceById(editor_id), val = value;
                    var s = ed.selection, dom = ed.dom, bl, nb, b;
                    var isIE = tinymce.isIE, isGecko = tinymce.isGecko;
                    var content = s.getContent(), rBlock = ed.settings.forced_root_block;
                    var sNode = s.getNode();
                    //move this up heare to prevent error in IE when val has no value
                    val = ed.settings.forced_root_block ? (val || 'p') : val;
                    function newBlock(formats, n, val) {
                        var ret = false;
                        //alert('in newBlock:\n' + n.nodeName.toLowerCase() + '\n' + val);
                        //alert(String(formats.indexOf(n.nodeName.toLowerCase()) < 0) + ' || ' + String((n.nodeName.toLowerCase() == 'p' && val.toLowerCase() != 'p')));
                        if (formats.indexOf(n.nodeName.toLowerCase()) < 0 || (n.nodeName.toLowerCase() == 'p' && val.toLowerCase() != 'p')) {
                            ret = true;
                        }
                        //alert(ret);
                        return ret;
                    }
                    //s.getRng(true);
                    //if there's selected content and the content is not in one of the set
                    //block format nodes or node is p and val is not p(we can have h1/h2 nodes
                    //in a p tag) then add a rootblock(div in pp) around it to make new block
                    if (content.length > 0 && newBlock(ed.settings.theme_advanced_blockformats, sNode, val)) {
                        //alert('**\n'+sNode.nodeName.toLowerCase);
                        //selection.SetNode doesn't work in firefox for some reason so use this instead
                        s.setContent('<' + rBlock + ' id="__tmp_' + rBlock + '">' + content + '</' + rBlock + '>');
                        s.select(ed.getDoc().getElementById('__tmp_' + rBlock), 1);
                        //s.setNode(ed.getDoc().getElementById('__tmp_' + rBlock));
                        dom.setAttrib('__tmp_' + rBlock, 'id', null);
                        //alert(rBlock + '||\n ' + content + '\n\n\n' + s.getContent()); //Node().innerHTML
                    }

                    function isBlock(n) {
                        return /^(P|DIV|H[1-6]|ADDRESS|BLOCKQUOTE|PRE)$/.test(n.nodeName);
                    };

                    bl = dom.getParent(s.getNode(), function (n) {
                        return isBlock(n);
                    });

                    // IE has an issue where it removes the parent div if you change format on the paragrah in <div><p>Content</p></div>
                    // FF and Opera doesn't change parent DIV elements if you switch format
                    if (bl) {
                        if ((isIE && isBlock(bl.parentNode)) || bl.nodeName == 'DIV') {
                            // Rename block element
                            nb = ed.dom.create(val);
                            tinymce.each(dom.getAttribs(bl), function (v) {
                                dom.setAttrib(nb, v.nodeName, dom.getAttrib(bl, v.nodeName));
                            });

                            b = s.getBookmark();
                            //alert('bef replace\n'+nb+'\n\n'+bl.innerHTML);
                            dom.replace(nb, bl, 1);
                            //alert('aft replace');
                            s.moveToBookmark(b);
                            ed.nodeChanged();
                            return true;
                        }
                    }

                    /*if (val.indexOf('<') == -1)
                    val = '<' + val + '>';

                    if (tinymce.isGecko)
                    val = val.replace(/<(div|blockquote|code|dt|dd|dl|samp)>/gi, '$1');*/

                    ed.getDoc().execCommand('FormatBlock', false, val);
                    //return true;
            }
            return false;
        },

        urlconverter_callback: function (url, node, on_save) {
            // Fix for IE replacing anchor tags with full url.  Used for detail pages and backlinks in pushpage2010
            if ((url.indexOf('/podium/default.aspx') >= 0) && ((url.indexOf('#dp:') >= 0) || (url.indexOf('#bl') >= 0)) ) {
                return '#' + url.split('#')[1];
            } else {
                return url;
            }
        },

        //NS 26735 - make all relative urls absolute
        //Pushpage content can't have relative urls.
        convert_urls: true,
        relative_urls: false,
        remove_script_host: false,
        document_base_url: location.protocol + '//' + location.host + '/podium/',
        theme: "advanced",
        theme_advanced_buttons1: toolBarButtons1,
        theme_advanced_buttons2: toolBarButtons2,
        theme_advanced_buttons3: toolBarButtons3,
        theme_advanced_buttons4: toolBarButtons4,
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        theme_advanced_statusbar_location: "none",
        theme_advanced_resizing: false,
        oninit: initFunction,
        spellchecker_rpc_url: "/podium/SpellChecker.aspx",
        spellchecker_languages: "+English=en",
        theme_advanced_blockformats: "div,h1,h2",
        setup: function (ed) {
            ed.onEvent.add(function (ed, e) {
                htmlEditorStoreBookmark(ed);
            });
            if (id == 'pp_edittext') {
                ed.onInit.add(function (ed) {
                    // BTS 09/20/2010 - NS27890 - scrollbars causing an issue in firefox
                    $('#pp_edittext_ifr').attr('scrolling', 'no');
                    //NS 26913 - force toolbar elements to have white background
                    $(".mceToolbar").css("background-color", "#ffffff");
                    ed.focus();
                });
                ed.onMouseDown.add(function (ed, o) { ppHideImageMenu(); });
                ed.onMouseUp.add(function (ed, o) { ppInitImageEdit(); });
            }
        },
        class_filter: function (cls, rule) { return false; }
    });

}    

function htmlEditorSave(id) {
    var editor = tinyMCE.get(id);
    if (editor) {
        editor.save();
        fInitFormChange();
    }
}

function htmlEditorRemove(id) {
    var editor = tinyMCE.get(id);
    editor.remove();
}

function htmlEditorInsertContent(id, content) {
    var editor = tinyMCE.get(id);
    htmlEditorReStoreBookmark(editor);    
    editor.execCommand('mceInsertContent', false, content);
    htmlEditorStoreBookmark(editor);    
}

var htmlEditorBookmark = false;
function htmlEditorStoreBookmark(editor) {
    htmlEditorBookmark = htmlEditorBookmark = editor.selection.getBookmark();
}

function htmlEditorReStoreBookmark(editor) {
    editor.selection.moveToBookmark(htmlEditorBookmark);
}


// File Manager
var fmAllowMulti = true;
function fmInitSelection(allowMulti) {
    fmAllowMulti = allowMulti;

    $('.fm_item').attr('checked', false);
    var select_array = fmGetSelectArray();
    if (select_array.length > 0) {
        for (var s = 0, lenS = select_array.length; s < lenS; ++s) {
            $('#fm_item_' + select_array[s]).attr('checked', true);
        }
    }
    fmUpdateStatus();

    $('.fm_item').click(function () {
        var $chkbox = $(this);
        var $fm_select = $("#fm_select");
        var checked = $chkbox.is(':checked');
        var itemId = $chkbox.val();

        if (fmAllowMulti == false) {
            $fm_select.val('');
            $('.fm_item').attr('checked', false);
            $chkbox.attr('checked', checked);
        }

        var select_array = fmGetSelectArray();
        var index = -1;
        if (select_array.length > 0) {
            for (var s = 0, lenS = select_array.length; s < lenS; ++s) {
                if (select_array[s] == itemId) {
                    index = s;
                    break;
                }
            }
        }
        if (!checked && index > -1) {
            select_array.splice(index, 1);
        } else if (checked) {
            select_array[select_array.length] = itemId;
        }
        $fm_select.val(select_array.toString());
        fmUpdateStatus();
    });
}

function fmGetSelectArray() {
    var $fm_select = $("#fm_select");
    var select_value = $fm_select.val();
    select_array = new Array();
    if (select_value.length > 0) {
        select_array = select_value.split(",");
    }
    return select_array;
}

function fmClearSelect() {
   var $fm_select = $("#fm_select");
   $fm_select.val('');
   fmInitSelection(fmAllowMulti);
}

function fmUpdateStatus() {
    var $fm_footer = $("#fm_footer");
    var select_array = fmGetSelectArray();
    $fm_footer.html(select_array.length + ' file(s) selected');
    if (select_array.length > 0) {
        $fm_footer.append('&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onClick="fmClearSelect()" class="link">Clear All</a>');
    }
}

// Check for changes before navigating away from page
function pdChangeCheckCreate(activeLevel, hasChange) {
    // Internal reference to the class
    // to avoid the "this" pointer issue
    // when dealing with events.
    var _this = this;

    // Set by code to determine if change has been made
    this.globalChange = hasChange;

    // Activate Level 0 = off, 1 = auto, 2 = manual
    this.activeLevel = activeLevel;
    
    // Determines whether or not a form is being saved.
    this.saving;

    // Message to display.
    this.navigateAwayMessage = "Changes have been made which will be lost if you leave this page.";
    
    //init arry to track original values of form items 
    this.formValues = new Array;
    
    //hidden form field to post hasChanges to server
    if(this.activeLevel>0){
        this.formChangeField = document.createElement('input');
        this.formChangeField.name='formChangeField';
        this.formChangeField.type='hidden';
        this.formChangeField.value=hasChange;
        $(theform).append(this.formChangeField);
    }

    // Determines whether check needs to be run
    this.runCheck = function() {
        $(_this.formValues).each(function(k){if(_this.formValues[k]){if(_this.formValues[k].changed&&_this.formChangeField){_this.formChangeField.value='true';}}});
        return (_this.activeLevel != '0' && !_this.saving && _this.globalChange)
    }
    
    // Alert used when closing window or using back button (not used by IE)
    this.checkForChanges = function() {
        if (!document.all && _this.runCheck()) {
            //if(document.all && event) {
            //     event.returnValue = _this.navigateAwayMessage;
            //} else {
                return _this.navigateAwayMessage;
            //}
        }
    }
    
    // Alert used when using postback and/or pdL
    this.confirmNav = function() {
        var continueNav = true;
        if (_this.runCheck()) {
            continueNav = ConfirmClick(_this.navigateAwayMessage + '\n\nPress OK to leave this page and lose any changes. Press Cancel to remain on this page.');
            if (continueNav) {
                _this.globalChange = false;
            }
            else { if(_this.formChangeField){_this.formChangeField.value=false}; }
        }
        return continueNav;
    }
    
    //add changeevent function to form inputs
    this.pdChangeCheckBind = function () {
        if (_this.activeLevel == 1) {
            $("input").each(function (index, domEle) {if(domEle.type!='hidden'){_this.setChange(index, domEle)}});
            var i=_this.formValues.length;
            $("select").each(function (index, domEle) { _this.setChange(i+index, domEle)});
        }
    }
    
    this.setChange = function(index, domEle){
        var value;
        switch (domEle.type){
            case 'radio' : value = domEle.checked; break;
            case 'checkbox' : value = domEle.checked; break;
            default : value = domEle.value;
        }
        _this.formValues[index]=new FormItem(value);
        $(domEle).change(function() {
            _this.globalChange=true;
            var item = _this.formValues[index];
            item.changed=(item.value==domEle.value)?false:true;
        });
    }
    
}

//pdChangeCheck object
var pdChangeCheck;

function pdChangeCheckInit(activeLevel, hasChange) {
    pdChangeCheck = new pdChangeCheckCreate(activeLevel, hasChange);
    pdChangeCheck.pdChangeCheckBind();
}

function FormItem(value){
    this.changed = false;
    this.value=value;
}

// Register the event.
window.onbeforeunload = function() {
   return pdChangeCheck.checkForChanges();
}


// FTPimage Cleanup
function pdFtpImageClean(url) {
    $('TEXTAREA').each(function(){
        var val = $(this).val();
        var regEx = new RegExp('http://' + url + '/ftpimages/','g');
        val = val.replace(regEx, '/ftpimages/');
        regEx = new RegExp('https://' + url + '/ftpimages/','g');
        val = val.replace(regEx, '/ftpimages/');
        $(this).val(val);
    });
}

// Portal tabs
function initPortalTabs() {
    var $tabs = $('#thPrtTabs TD');
    var $pRow = $($tabs[0]).parent();
    var $parent = $('#thPrtTabsWrap');
    var $lPg = $('#lPg');
    
    if ($parent.length > 0 && $pRow.length > 0) {
        var maxWidth;
        if ($lPg.css('width')) {
            maxWidth = parseInt($lPg.css('width'));
        } else {
            maxWidth = $lPg.width();
        }
        
        var maxEnd = $parent.offset().left + maxWidth;
        var maxEndPadding = 50;
        
        if ($('#thMngPortal').length > 0) {maxEndPadding += 100;}
        if ($('#thAddPortal').length > 0) {maxEndPadding += 100;}
        maxEnd = maxEnd - maxEndPadding;
        
        var ddMenuStarted = false;
        var firstTabTop = 0;
        
        $tabs.each(function(){
            var $tab = $(this);
            //$tab.css({'float':'left','display':'block','position':'relative'});
            var end = $tab.offset().left + $tab.width();
            var top = $tab.offset().top;
            
            if (firstTabTop == 0) firstTabTop = top;
            
            if (end > maxEnd || top > firstTabTop || ddMenuStarted) { 
                if (!ddMenuStarted) { 
                    // set this flag so all tabs from this point on are placed in the dropdown
                    ddMenuStarted = true;
                    $pRow.append('<td id="thPrtTabMore" class="tab" onmouseover="this.className=\'tabo\';" onmouseout="this.className=\'tab\';" style="cursor:pointer;padding:0px 4px 0px 4px;white-space:nowrap;">more <img src="/ftpimages/999/podium/style1/icons/dd_icon.gif" /></td>');
                    $('#thPrtTabs').after('<div id="thPrtTabMoreDD" style="position:absolute;display:none;border:1px solid #c1c1c1;background-color:#fff;padding:10px 20px 10px 20px;"></div>');
                    $('#thPrtTabMoreDD').append($('<table id="thPrtTabMoreTbl" border="0" cellspacing="0" cellpadding="0">'));
                }
               
                $tab.attr('onmouseover','').attr('onmouseout','').attr('style','').css({'padding-bottom':'10px','cursor':'pointer'});
                            
                if ($tab.hasClass('tabs')) {
                    $tab.html('<b>' + $tab.html() + '</b>');
                    $tab.attr('class','').addClass('maintext');
                    $('#thPrtTabMore').attr('onmouseover','').attr('onmouseout','').removeClass('tab').addClass('tabs');
                } else {
                    $tab.attr('class','').addClass('link');
                }
                
                var $row = $('<tr>');
                $('#thPrtTabMoreTbl').append($row);
                $row.append($tab);
            } 
        });
    }
    
    if ($tabs.length == 0) {
        $('#thPrtEditWrap').hide();
        $('#thMngPortal').css({'margin-top':'0px'});
    }
    
    $('#thPrtTabMore').click(function() {
         pdMenuShowDrop('thPrtTabMore', 'thPrtTabMoreDD', 'thPrtTabMore', 'left', '', 6, null);
    });
    
    var $AddPortal = $('#thAddPortal');
    if ($AddPortal.length > 0 && $pRow.length > 0) {
       $pRow.append('<td id="thPrtTabAdd" class="maintext" style="padding:0px 0px 0px 10px;"></td>');
       $('#thPrtTabAdd').append($AddPortal);
    }
}


// pd3Menu tabs
function initPD3Nav(id) {
    var $parent = $('#' + id);
    
    if ($parent.length > 0) {
        var $items = $parent.find('.pd3NavItem');
        var parentEnd = $parent.offset().left + $parent.width();
        var parentTop = $parent.offset().top + 10;
        var i = 0;
        var parentEndPadding = 120;
        var ddMenuStarted = false;
        var $moreButton = $('<div id="' + id + 'more" class="pd3NavMoreBtn pd3ColorTxt pd3LargeTxt"><div id="' + id + 'morelbl" style="display:inline-block;max-width:100px;_width:100px;white-space: nowrap;overflow:hidden;text-overflow:ellipsis;">More</div> <img src="/ftpimages/999/podium/style1/icons/arrow_small_down.png" /></div>');

        parentEnd = parentEnd - parentEndPadding;

        $('#' + id + 'more').remove();
        $('#' + id + 'moredd').remove();
        openDrop = '';
        openDropButton = '';

        $items.each(function () {
            var $item = $(this);
            var end = $item.offset().left + $item.width();
            var top = $item.offset().top;
            if (end > parentEnd || top > parentTop || ddMenuStarted) {
                if (i == 0) {
                    // set this flag so all tabs from this point on are placed in the dropdown
                    ddMenuStarted = true;
                    $item.after($moreButton);
                    $parent.before('<div id="' + id + 'moredd" class="pd3NavMoreDD" style="display:none;"></div>');
                }

                $item.css({ 'margin-bottom': '10px', 'float': 'none' });

                if ($item.prev().hasClass('pd3NavDivider')) {
                    $item.prev().remove();
                }

                $('#' + id + 'moredd').append($item);

                if ($item.hasClass('pd3NavItemOn')) {
                    var label = $item.html();
                    $('#' + id + 'morelbl').html(label).attr('title', label);
                }

                i += 1;
            }
        });
    }

    $moreButton.click(function () {
        pdMenuShowDrop(id + 'more', id + 'moredd', id + 'more', 'left', '', 1, null);
    });

}




// Page Editing
var pageEditOrigColWidth = null;

function pageEditInit() {
     $('.pgTbl div.pgCol').sortable({
        opacity: 0.6, 
        items: '.chSortable',
        handle: '.chSortableHandle', 
        cancel: 'A',
        tolerance:  'pointer', 
        connectWith: $('.pgTbl div.pgCol'), 
        cursor: 'move',
        placeholder: 'sortplaceholder',
        update: pageEditSortUpdate,
        start: function(event, ui) {pageEditSortStart(ui);},
        stop: function(event, ui) {pageEditSortStop(ui);}
   });
   
   $(".pgTbl .chSortableHandle").css({"cursor":"move"});
    
   pageEditHelpText(false);
}

function pageEditSortUpdate() {
    pageEditHelpText(false);
    
    var sSort = '';
	$(".pgTbl div.pgCol").each(function(){
		var arrSort = $(this).sortable('toArray');
		var col = this.id.replace('pgCol_', '');
		if (col.length > 0) {
		    if (sSort.length > 0) sSort += '|';
		    sSort += col + ':' + arrSort.toString().replace(/chS_cb/gi,'');
		}
	});
	$('#thPrtOrder').val(sSort);
	if (window.prtUpdateSortCallback) prtUpdateSortCallback();
}

function pageEditHelpText(showAll) {
    $('.pgTbl div.pgCol').each(function() {
        var $this = $(this);
        var $col = $($this.parents('TD')[0]);
        if (showAll || $this.html() == '' || $this.html() == '<!-- -->') {
            $this.css({'height': $col.height(),'min-height':'50px'});
            $col.css({'border':'1px dashed #c1c1c1'});
        } else {
            $this.css({'height': '','min-height':''});
            $col.css({'border':''});
        }
    });
}

function pageEditSortStart(ui) {
    var $item = $(ui.item);
    var $col = $($item.parents('DIV.pgCol')[0]);
    pageEditOrigColWidth = $col.width();
    
    pageEditHelpText(true);
}

function pageEditSortStop(ui) {
    var $item = $(ui.item);
    var $chInner = $($item.find('DIV.chInner')[0]);
    var $col = $($item.parents('DIV.pgCol')[0]);
    
    if (pageEditOrigColWidth != null) {
        var widthDiff = pageEditOrigColWidth - $col.width();
        if (widthDiff > 0) {
           $chInner.find('select, div, table, td').each(function() {
                var $this = $(this);
                var attrWidth = pageEditGetAttr($this, 'width');
                if (attrWidth != '100%' && $this.width() > $chInner.width()) {
                    var newWidth = $this.width() - widthDiff;
                    if ($this.attr('height') || ($this.attr('style') && $this.attr('style').indexOf('height') > -1)) {
                        pageEditSetSize($this, newWidth);
                    } else {
                        $this.css('width', newWidth);
                    }
                }
            })
            $chInner.find('img').each(function() {
                var $this = $(this);
                var attrWidth = pageEditGetAttr($this, 'width');
                if (attrWidth != '100%' && $this.width() > $chInner.width()) {
                    var newWidth = $this.width() - widthDiff;
                    pageEditSetSize($this, newWidth);
                    if ($this.attr('name')) {
                        var name = $this.attr('name');
                        if (name.indexOf('ctlGallery') > -1) {
                            var id = name.replace('myimage', '');
                            pageEditGalleryFix(id, newWidth)
                        }
                    }
                }
            })    
             $chInner.find('embed, object').each(function() {
                var $this = $(this);
                var attrWidth = pageEditGetAttr($this, 'width');
                if (attrWidth != '100%' && $this.width() > $chInner.width()) {
                    var newWidth = $this.width() - widthDiff;
                    $this.attr('width', newWidth);
                }
            })     
        }
    }
        
    pageEditOrigColWidth = null;
    
    pageEditHelpText(false);
}

function pageEditGetAttr(obj, attr) {
    var attrValue = '';
    if (obj.attr(attr)) {
        attrValue = obj.attr(attr);
    } else if (obj.css(attr)) {
        attrValue = obj.css(attr);
    }
    return attrValue;
}

function pageEditSetSize(obj, width) {
    var $obj = $(obj);
    var newW = parseInt(width);
    var newH;
    
    var origW = $obj.width();
    var origH = $obj.height();
   
    var perW = newW / origW;
    newH = Math.floor(origH * perW);	
    if (newW > 0 && newH > 0) {
        var tagName = $obj.get(0).tagName.toLowerCase();
        if (tagName == 'img' || tagName == 'embed' || tagName == 'object') {
            $obj.attr("width", newW);
            $obj.attr("height", newH);
            obj.css({'height':'','width':''});
        } else {
            $obj.css("width", newW);
            $obj.css("height", newH);
        }
    }
    
}

function pageEditGalleryFix(id, width) {
    var arrID = id + 'Images';
    var arr = eval(arrID);
    if (arr) {
        var $dummyDIV = $('<div>');
        $('BODY').append($dummyDIV);
        for (var s = 0, lenS = arr.length + 1; s < lenS; ++s) {
            var imageString = arr[s];
            var $img = $(imageString);
            $dummyDIV.html('');
            $dummyDIV.append($img);
            pageEditSetSize($img, width);
            arr[s] = $dummyDIV.html();
        }
        $dummyDIV.remove();
    }
}


	var methods = {
		init : function ( options ) {
			if (options) $.extend(settings, options);
			
			$(document).bind('click.pdLogin', function(e) {
		        var $clicked = $(e.target);
		        if ((! $clicked.hasClass("pdLogin")) && (! $clicked.hasClass("pdLoginController")) && (! $clicked.parents().hasClass("pdLogin")) && (! $clicked.parents().hasClass("pdLoginController"))) {
                   methods.hide();
                }
		    });
		
			return this.each(function(){
				var $this = $(this);
				var userLoggedIn = false;
				
				if (window.pdGlobal && pdGlobal.userLoggedIn) {
				    userLoggedIn = true;
				}
								
				if (settings.autoLabel) {
				    if (userLoggedIn) {
				        $this.html(settings.secureLabel);
				    } else {
				        $this.html(settings.publicLabel);
				    }
				}
				
				$this.addClass('pdLoginController');
				if (userLoggedIn && window.pdCustLoginFunc != null) {
				     $this.bind('click.pdLogin', pdCustLoginFunc);
				} else {
				    $this.bind('click.pdLogin', methods.show);
				    
				    if (window.location.hash == '#pdl') {
				        $(document).ready(function(){$this.click();});
				    }
				}
				
			});
		},
		
		destroy : function ( options ) {
			return this.each(function(){
				var $this = $(this);
				$this.removeClass('pdLoginController');
				$this.unbind('click.pdLogin');
				$(document).unbind('click.pdLogin');
				$('#pdLogin').remove();
			});
		},
		
		show : function ( ) {
		    var $this = $(this);
		               
            if (settings.sslDestination == 'self') {
		        window.location.hash = '#pdl';
		    }
			
		    if ($('#pdLogin').length == 0) {
			    $('#Form1').append($('<div id="pdLogin" class="pdLogin" style="position:absolute;"></div>'));
		    }
		    var $formDiv = $('#pdLogin');
						
		    if ($formDiv.html().length == 0 && window.pdCustLoginFunc != null) {
		        // console.log('pdLoginFormGet');
			    pdCustLoginFunc(settings.forceSSL.toString() + ':' + settings.sslDestination);
				
			    function contentWait(x) {
		            runner();
		            function runner() {
		                // console.log(x);
		                --x;
                        if ($formDiv.html().length > 0) {
		                    x = 0;
		                }
		                if (x <= 0) {
		                    initForm();
		                } else {
                            window.setTimeout(runner, 100);
                        }
		            }
    			   
		        }
		        contentWait(30);
		    } else {
		        initForm();
		    }
            								
			function initForm() {
			    // console.log('setPosition');
			    $formDiv.show();
			    var controllerPos = $this.offset();
			    var controllerWidth = $this.width();
			    var controllerHeight = $this.height();
                var formWidth = $formDiv.width();
                var left = controllerPos.left;
			    var top = (controllerPos.top + controllerHeight) + settings.offsetTop;
			    if (formWidth && formWidth > 0) {
			        if (settings.align == 'right') {
			            left = controllerPos.left - formWidth;
			        } else if (settings.align == 'center') {
			            left = (controllerPos.left + Math.floor(controllerWidth / 2)) - Math.floor(formWidth / 2);
			        }
			    }
    			
			    $formDiv.css({
				    'left' : left + 'px',
				    'top' : top + 'px'
			    });
    			
			    $('.pdLoginSignOutBtn').val(settings.signOutButtonLabel);
			    $('.pdLoginSignInBtn').val(settings.signInButtonLabel);
			}
			
			$('.pdLoginController').unbind('click.pdLogin');
			$('.pdLoginController').bind('click.pdLogin', methods.hide);
		},
		
		hide : function ( ) {
			var $this = $(this);
			$('#pdLogin').hide();
			$('.pdLoginController').unbind('click.pdLogin');
			$('.pdLoginController').bind('click.pdLogin', methods.show);
		}
	};




// Scrolling Div
function scrollDivImages() {
    //Get our elements for faster access and set overlay width
    var div = $('div.sc_menu'),
                 ul = $('ul.sc_menu'),
                 // unordered list's left margin
                 ulPadding = 0;

    //Get menu width
    var divWidth = div.width();

    //Remove scrollbars
    div.css({overflow: 'hidden'});

    //Find last image container
    var lastLi = ul.find('li:last');

    //When user move mouse over menu
    div.mousemove(function(e){

      //As images are loaded ul width increases,
      //so we recalculate it each time
      var ulWidth = lastLi[0].offsetLeft + lastLi.outerWidth() + ulPadding;

      var left = (e.pageX - div.offset().left) * (ulWidth-divWidth) / divWidth;
      div.scrollLeft(left);
    });
}


// Media

var pdNoFlash = '<div class="maintext">This content requires the latest version of the Adobe Flash Player.</div><a href="http://www.adobe.com/go/getflashplayer" target="_blank"><img src="https://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" border="0" alt="Get Adobe Flash player" /></a>';

function centerThumbs(parentTag, parentClass) {
    $(parentTag + '.' + parentClass + ' img').each(function (i, img) {
        var $img = $(img);
        var $parent = $(img).parents(parentTag + ':eq(0)');
        var imgWidth = $img.width();
        var parentWidth = $parent.width();
        if (imgWidth > parentWidth) {
            $img.css({
                position: "relative",
                left: (parentWidth - imgWidth) / 2
            });
        }
    });
}

function buildAudioFlashScript(containerId, albumId, width, height) {
    var $container = $('#' + containerId);
    if (HasFlash() == false) {
        initMediaGallery('audio', containerId, albumId, width, height);
    } else {
        var schoolId = 0;
        var currentUrl = 'http://' + window.location.host;
        var playerPath = '/podium/swf/pdXSPF.swf';
        var fv = false;
        
        if (window.pdGlobal) {
            schoolId = pdGlobal.schoolId;
        }
        
        var xmlPath = currentUrl + '/podium/tools/galleryxml.aspx?pk=' + albumId;
        if (schoolId != 0) {
            xmlPath += '&sid=' + schoolId;
        }
        if (width == 600 && height == 153) {
            fv = true;
            xmlPath += '&lv=1';
        }
        
        var $embed = $('<embed>');
        $embed.attr('width', width).attr('height', height).attr('wmode', 'transparent');
        $embed.attr('flashvars', 'playlist_url=' + encodeURIComponent(xmlPath) + '&amp;autoplay=' + fv.toString());
        $embed.attr('quality', 'high')
            .attr('allowfullscreen', 'true')
            .attr('scale', 'exactfit')
            .attr('menu', 'false')
            .attr('bgcolor', '#ffffff')
            .attr('name', containerId + 'aspm')
            .attr('id',  containerId + 'aspm')
            .attr('src', playerPath)
            .attr('type', 'application/x-shockwave-flash');
        
        $container.empty();
        $container.append($embed);    
        
    }
}

function buildSlideShowProScript(contentType, containerId, channelId, albumId, width, height, channelParams) {
    var $container = $('#' + containerId);
    
    if (HasFlash() == false) {
        if (contentType == "video" && window.bcPlayerData == null) {
            $container.html(pdNoFlash);
        } else {
            initMediaGallery(contentType, containerId, albumId, width, height);
        }
    } else {
        var schoolId = 0;
        var currentUrl = 'http://' + window.location.host;
        var playerPath = '/podium/swf/pdSlideShow_197.swf';
        var fv = false;
        
        if (window.pdGlobal) {
            schoolId = pdGlobal.schoolId;
        }
        
        var xmlPath = currentUrl + '/podium/tools/galleryxml.aspx?pk=' + albumId;
        if (schoolId != 0) {
            xmlPath += '&sid=' + schoolId;
        }
        if (width == 999 && height == 999) {
            fv = true;
            xmlPath += '&fv=true&lv=1';
        } else {
            xmlPath += '&fv=false&lv=0';
        }
        
        var globalParam = '';
        if (!fv) {
            globalParam = 'navButtonsAppearance:All Visible;';
        }
        
        var ssp_SchoolParam = '';
        var ssp_ChannelParam = '';
        if (window.sspSchoolParam) ssp_SchoolParam = sspSchoolParam;
        if (eval('window.sspChannelParam_' + channelId)) ssp_ChannelParam = eval('sspChannelParam_' + channelId);
        
        if (channelParams && channelParams.length > 0) {
            ssp_ChannelParam += channelParams;
        }
        
        var $embed = $('<embed>');
        if (fv) {
            $embed.attr('width', '100%').attr('height', '100%').attr('wmode', 'window');
        } else {
            $embed.attr('width', width).attr('height', height).attr('wmode', 'transparent');
        }
        $embed.attr('flashvars', 'xmlfile=' + encodeURIComponent(xmlPath) + '&globalParams=' + globalParam + '&schoolParams=' + ssp_SchoolParam + '&channelParams=' + ssp_ChannelParam + '&fullView=' + fv.toString());
        $embed.attr('quality', 'best')
            .attr('allowfullscreen', 'true')
            .attr('scale', 'exactfit')
            .attr('menu', 'false')
            .attr('bgcolor', '#ffffff')
            .attr('name', containerId + 'ssm')
            .attr('id',  containerId + 'ssm')
            .attr('src', playerPath)
            .attr('type', 'application/x-shockwave-flash');
        
        $container.empty();
        $container.append($embed);    
    }
}

function buildBCVideoScript(containerId, videoId, width, height, autoStart) {
    var $container = $('#' + containerId);
    if (window.bcPlayerData == null || bcPlayerData.playerId.length == 0) {
        $container.html('<div class="msg" style="padding:10px;">Missing Player Data</div>');
    } else {
        var $object = $('<object>');
        $object.attr('id', containerId + '_vid');
        $object.addClass('BrightcoveExperience');
        $object.attr('width', width).attr('height', height).attr('wmode', 'transparent');

        var params = {};
        params.playerID = bcPlayerData.playerId;
        params.playerKey = bcPlayerData.playerKey;
        params.autoStart = autoStart;
        params.bgcolor = "#ffffff";
        params.width = width;
        params.height = height;
        params.isVid = "true";
        params.isUI = "true";
        params.dynamicStreaming = "true";
        params.videoSmoothing = "true";
        if (window.location.protocol == 'https:') {
            params.secureConnections = "true";
        }
        params.wmode = "transparent";

        for (var i in params) {
            var $param = $('<param>');
            $param.attr('name', i).attr('value', params[i]);
            $object.append($param)
        }
        
        var $param = $('<param>');
        $param.attr('name', '@videoPlayer').attr('value', videoId);
        $object.append($param)
        
        $container.empty();
        $container.append($object);
        
        brightcove.createExperiences();
    }
}

function initMediaGallery(contentType, containerId, albumId, width, height) {
    var $container = $('#' + containerId);
    var schoolId = 0;
    if (window.pdGlobal) {
        schoolId = pdGlobal.schoolId;
    }
     
    $.ajax({
        url: '/podium/tools/galleryjson.aspx',
        dataType: 'json',
        data: {sid: schoolId,pk: albumId},
        error: function(XMLHttpRequest, textStatus, errorThrown) {$container.html(errorThrown)},
        success: function(data) {
                switch (contentType) {
                   case 'audio': 
                        buildAudioGalleryContent(containerId, data, width, height);
                        break;
                   case 'video':
                        buildBCVideoGalleryContent(containerId, data, width, height);
                        break;
                   case 'photo': 
                        buildImageGalleryContent(containerId, data, width, height);
                        break;
                 }
            }
    });
}

function buildBCVideoGalleryContent(containerId, albumData, width, height) {
    var $container = $('#' + containerId);
    
    if ($container.length > 0 && albumData.files.length > 0) {
        $container.empty();
        
        var videoContainerId = containerId + '_vid';
        var $videoContainer = $('<div id="' + videoContainerId + '"></div>');
        $videoContainer.css({
            'width':width,
            'height':height
            });
        $container.append($videoContainer);
        
        buildBCVideoScript(videoContainerId, albumData.files[0].externalId, width, height, false);
        
        if (albumData.files.length > 1) {
            var thumbContainerId = containerId + '_thumb';
            var $thumbContainer = $('<div id="' + thumbContainerId + '" class="vidSlider"></div>');
            $thumbContainer.css({
                'width':width,
                'height':38
                });
            $container.append($thumbContainer);
            
            var $prevButton = $('<a href="#" class="buttons prev">&nbsp;</a>');
            $prevButton.css({
                'height':34
                });
            $thumbContainer.append($prevButton);
            
            var $viewport = $('<div class="viewport"></div>');
            $viewport.css({
                'height':34
                });
            $thumbContainer.append($viewport);
            
            var $overview = $('<ul class="overview"></ul>');
            $viewport.append($overview);
            
            for (var i = 0; i < albumData.files.length; i++) {
                var video = albumData.files[i];
                
                var $thumbLI = $('<li></li');
                $thumbLI.css({
                    'height':34
                    });
                $overview.append($thumbLI);
                
                var $thumbIMG = $('<img src="' + video.thumbnail + '" id="' + containerId + '_' + video.externalId + '" title="' + video.title + '" width="' + video.thumbnailWidth + '" height="' + video.thumbnailHeight + '" />');
                $thumbIMG.css({
                    'cursor':'pointer'
                    });
                $thumbLI.append($thumbIMG);
                
                $thumbIMG.bind('click', function() {
                        var videoid = $(this).attr('id').replace(containerId + '_', '');
                        buildBCVideoScript(videoContainerId, videoid, width, height, false);
                    });
            }
            
            var $paddingLI = $('<li></li');
            $paddingLI.css({
                'height':34,
                'width':50,
                'border':'none'
                });
            $overview.append($paddingLI);
            
            var $nextButton = $('<a href="#" class="buttons next">&nbsp;</a>');
            $nextButton.css({
                'height':34
                });
            $thumbContainer.append($nextButton);
            
            $(document).ready(function(){$('#' + thumbContainerId).tinycarousel({duration:300,display:2});});
        }
    }
}


function buildAudioGalleryContent(containerId, albumData, width, height) {
    var $container = $('#' + containerId);
    var fv = false;
      
    if (width == 600 && height == 153) {
        fv = true;
    }
    
    if ($container.length > 0 && albumData.files.length > 0) {
        $container.empty();
        
        $container.css({
            'width':width,
            'height':height
            });
        
        if (!HasFlash() && jQuery.browser.mozilla) {
            $container.html(pdNoFlash);
        } else {
            var $listDiv = $('<div></div>');
            $listDiv.css({
                'float':'left',
                'width':width,
                'height':height,
                'overflow':'auto'
                })
                .addClass('pd3Grad2')
                .addClass('pd3AudItem');
            $container.append($listDiv);
            
            for (var i = 0; i < albumData.files.length; i++) {
                var audio = albumData.files[i];
                
                var $itemDiv = $('<div></div>');
                $itemDiv.css({
                    'padding':5
                    });
                $listDiv.append($itemDiv);
                
                 var $titleDiv  = $('<div></div>');
                $titleDiv.html(audio.title);
                $itemDiv.append($titleDiv); 
                
                var $btnDiv  = $('<div></div>');
                $btnDiv.addClass('pd3AudButtonsWrap')
                    .append('<div class="pd3AudButton"><a id="' + containerId + '_play_' + i + '" href="#"><img src="/ftpimages/999/podium/style1/icons/play_button.png" style="vertical-align:middle;" border="0" /><span style="vertical-align:middle;font-weight:normal;"> Play</span></a>'
                            + '<a id="' + containerId + '_pause_' + i + '" href="#"><img src="/ftpimages/999/podium/style1/icons/pause_button.png" style="vertical-align:middle;" border="0" /><span style="vertical-align:middle;font-weight:normal;"> Pause</span></a></div>')
                    .append('<div class="pd3Divider2"><!-- --></div>')
                    .append('<div class="pd3AudButton"><a id="' + containerId + '_stop_' + i + '" href="#"><img src="/ftpimages/999/podium/style1/icons/stop_button.png" style="vertical-align:middle;" border="0" /><span style="vertical-align:middle;font-weight:normal;"> Stop</span></a></div>');
                $itemDiv.append($btnDiv);
                
                if (width > 300) {
                     $btnDiv.css({
                         'float':'right',
                         'width':120
                        });
                     $titleDiv.css({
                        'float':'left',
                        'width': width - 170
                        })
                }
                
                $itemDiv.append('<div id="' + containerId + '_audplay_' + i + '"></div>');
                $itemDiv.append('<div style="clear:both"><!-- --></div>');
                
               $('#' + containerId + '_audplay_' + i).jPlayer({
                        ready: function () {
                                 var id = this.element.attr('id');
                                 var idSplit = id.split('_');
                                 var index = idSplit[idSplit.length - 1];
                                 this.setFile(albumData.files[index].url);
                               },
                        swfPath : '/ftpimages/999/podium/libs/jquery-jplayer/1.1.1/'
                    })
                    .jPlayer('cssId', 'play', containerId + '_play_' + i)
                    .jPlayer('cssId', 'pause', containerId + '_pause_' + i)
                    .jPlayer('cssId', 'stop', containerId + '_stop_' + i);
      
            }
        }
    
        $container.append('<div style="clear:both"><!-- --></div>');
    }
}

var galleriaThemeLoaded = false;
function buildImageGalleryContent(containerId, albumData, width, height) {
    var $container = $('#' + containerId);
    var fv = false;
      
    if (width == 999 && height == 999) {
        fv = true;
    }
    
    if ($container.length > 0 && albumData.files.length > 0) {
        $container.empty();
        
        if (!galleriaThemeLoaded) {
            var themeURL;
            if (fv) {
                themeURL = '/ftpimages/999/podium/libs/jquery-galleria/1.2.pre/themes/fullscreen/galleria.fullscreen.js';
                if($.browser.msie){
                    // IE has an issue loading the theme from cache so we add a random number to load new version
                    var theUniqueID = Math.floor(Math.random() * 1000000) * ((new Date()).getTime() % 1000);
                    themeURL += '?i=' + theUniqueID;
                }
            } else {
                themeURL = '/ftpimages/999/podium/libs/jquery-galleria/1.2.pre/themes/classic/galleria.classic.js';
            }
            Galleria.loadTheme(themeURL);
            galleriaThemeLoaded = true;
        }
                
        $container.css({
                'width':width,
                'height':height
                });
                
        for (var i = 0; i < albumData.files.length; i++) {
            var photo = albumData.files[i];
            var largeUrl;
            var thumbUrl;
            
            if (photo.zoom && photo.zoom.length > 0) {
                largeUrl = photo.zoom
            } else {
                largeUrl = photo.url
            }
            
            if (photo.thumbnail && photo.thumbnail.length > 0) {
                thumbUrl = photo.thumbnail
            } else {
                thumbUrl = photo.url
            }
            
            var $link = $('<a></a>');
            $link.attr('href', largeUrl);
            $container.append($link);
            
            var $img = $('<img>');
            $img.attr('src', thumbUrl).attr('alt', photo.caption).attr('title', photo.title);
            $link.append($img);
        }
        
        $container.galleria({'autoplay': fv, 'max_scale_ratio': 1, 'image_crop': false, 'transition': 'fade', 'width': width, 'height': height});       
    }
}

//bookstore release
function fcAddTableRowJQAdd(tblId,trClone,delTd,cntTd,hidVal,addLnkId,maxRows,cntStrt){
    //tblId - the id of the table
    //trClone - the row to be cloned (first,last,eq(x),..)allow to be specified for case when there are headers/footers
    //delTd - the td (first,last,eq(x),..) to have a delete row inserted. If there's no delTd then we won't add a delete td
     //cntTd - the td (first,last,eq(x),..) where counter is. If there's no cntTd then we won't add a counter
    //hidVal - value to be inserted into hidden inputs - usfull if we're keeping track of ids with hidden inputs
    //addLnkId - id of the a tag that's used as the add row link 
    //maxRows - max number of rows to add
    //cntStrt - number to start count at when renumbering

    //clone row
    var newRow = $('table#' + tblId + ' tr:' + trClone + '').clone();
    
    //add incremented counter if td specified
    if (cntTd != undefined && cntTd != null && cntTd.length > 0) {
         var newTrCnt = $('table#' + tblId + ' tr').length + 1;
         //alert(newTrCnt);
         $('td:' + cntTd + '', newRow).html(String(newTrCnt));

         //set new Ids/Names for any inputs
         $(':input', newRow).each(function () {
             var currId = $(this).attr('id');
             var currName = $(this).attr('name');
             $(this).attr('id', currId + '_' + newTrCnt);
             $(this).attr('name', currId + '_' + newTrCnt);
         });
    }
    //clear all inputs
    $(':input', newRow).val('');
    if (hidVal != undefined && hidVal != null && hidVal.length > 0) { $(':hidden',newRow).val(hidVal);} 
    $(':checkbox', newRow).attr('checked', 'false');
    $(':radio', newRow).attr('checked', 'false');
    $('select', newRow).attr('selectedIndex', '0');

    //add delete row
    if (delTd != undefined && delTd != null && delTd.length > 0) {
        //if we have max rows then add delete that will hide add lnk at max, otherwise add a genereic delete
        if (maxRows != undefined && maxRows != null && maxRows > 0) {
            //hide add if passed in
            if (addLnkId != undefined && addLnkId != null && addLnkId.length > 0) {
                if ($('table#' + tblId + ' tr').length + 1 == maxRows) {
                    $('a#' + addLnkId + '').hide();
                }
            }
            var delLnk = '<a href="javascript:void(0);" onclick="fcDelRow(\'' + tblId + '\',\'' + cntTd + '\',$(this),' + maxRows + ',\'' + addLnkId + '\',1,true);" class="link">Delete</a>';
	}
        else {
            var delLnk = '<a href="javascript:void(0);" onclick="$(this).parent().parent().remove();" class="link">Delete</a>';
        }
        $('td:' + delTd + '', newRow).html(delLnk);
    }

    //insert new row
    $('table#' + tblId + ' tr:last').after(newRow);

    //renumber rows if cntTd is specified
    RenumberRows(cntTd, tblId, true, cntStrt);
}

//this version of the function gets a row passed into it
function fcAddTableRowJQEdit(tblId, newRowStr, delTd,addLnkId,maxRows,numExisting) {
    //tblId - the id of the table
    //cntTd - the td (first,last,eq(x),..) where counter is. If there's no cntTd then we won't add a counter
    //delTd - the td (first,last,eq(x),..) to have a delete row inserted. If there's no delTd then we won't add a delete td
    //newRowStr - the new row string
    //addLnkId - id of the a tag that's used as the add row link 
    //maxRows - max number of rows to add
    //numExisting - total number of items (rows) existing

    var newTrCnt = $('table#' + tblId + ' tr').length + 1;
    if (newTrCnt >= maxRows) {
        //hide add if passed in
        $('a#' + addLnkId + '').hide(); 
    }
    if (newTrCnt <= maxRows) {
        var newRow = $(newRowStr);
   
        //set new Ids/Names for any inputs
        $(':input', newRow).each(function () {
            var currId = $(this).attr('id');
            var currName = $(this).attr('name');
            $(this).attr('id', currId + newTrCnt);
            $(this).attr('name', currId + newTrCnt);
        });

        var start = 1;
        if (numExisting > 0) { start = numExisting + 1; }

        //delete row
        if (delTd != undefined && delTd != null && delTd.length > 0) {
            //if we have max rows then add delete that will hide add lnk at max, otherwise add a genereic delete
            if (maxRows != undefined && maxRows != null && maxRows > 0) {
                var delLnk = '<a href="javascript:void(0);" onclick="fcDelRow(\'' + tblId + '\',\'\',$(this),' + maxRows + ',\''+addLnkId + '\',' + start + ',false);" class="link">Delete</a>';
            }
            else {
                var delLnk = '<a href="javascript:void(0);" onclick="$(this).parent().parent().remove();" class="link">Delete</a>';
            }
            $('td:' + delTd + '', newRow).html(delLnk);
        }

        //insert new row
        newRow.appendTo('table#' + tblId);

        //renumber rows if cntTd is specified
        //RenumberRows(cntTd, tblId, false, start);
    }

 }

 function fcDelRow(tblId, cntTd, curEl, maxRows, addLnkId, Strt, hideDelOnOne) {
     if ($('tr', curEl.parent().parent().parent()).length - 1 < maxRows) {
         $('a#' + addLnkId + '').show();
     }
     curEl.parent().parent().remove();

     if (cntTd != undefined && cntTd != null && cntTd.length > 0) {
         //renumber rows
         RenumberRows(cntTd, tblId, hideDelOnOne, Strt);
     }
 }

 function RenumberRows(cntTd, tblId, hideDelOnOne,start) {
     //cntTd - the td (first,last,eq(x),..) where counter is. If there's no cntTd then we won't add a counter
     //tblId - the id of the table
     if (start == undefined || start == null || start < 1) {
         start = 1;
     }

     if (cntTd != undefined && cntTd != null && cntTd.length > 0) {
         $('table#' + tblId + ' tr').each(function (index, element) {
             //rem delete link if we're down to 1 row
             if (hideDelOnOne && $('table#' + tblId + ' tr').length < 2) {
                 $('a:contains(Delete)', element).hide('');
             }
             else {
                 $('a:contains(Delete)', element).show('');
             }

             $('td:' + cntTd + '', element).html(String(index + start));
         });
     }
 }


 function setPd3DropdownButtonValue(buttonId, dropdownId, defaultText) {
     var $button = $('#' + buttonId);
     var $dropdown = $('#' + dropdownId);

     var buttonValue = '';

     $dropdown.find('select').each(function () {
         var $selectedOption = $(this).find('option:selected');
         if ($selectedOption.length > 0) {
             if (buttonValue.length > 0) {
                 buttonValue += ' : ';
             }
             buttonValue += $selectedOption.text();
         }
     });

     $dropdown.find('A.selected').each(function () {
         if (buttonValue.length > 0) {
             buttonValue += ' : ';
         }
         buttonValue += $(this).html();
     });

     if (buttonValue.length == 0) {
         buttonValue = defaultText;
     }
     
     $button.html(buttonValue);
 }