$.ajaxSetup ({   
        cache: false  
    });   

function updateTeams(){
	if(document.getElementById("week").value=="--"){
		document.getElementById("teamsForWeek").style.display="none";
		document.getElementById("teamsForWeek").innerHTML="";
		document.getElementById("winsInput").style.display="none";
		document.getElementById("winsInput").innerHTML="";
	
		document.getElementById("playersForTeams").style.display="none";
		document.getElementById("playersForTeams").innerHTML="";
	}else{	
		document.getElementById("teamsForWeek").style.display="block";
		$('#teamsForWeek').load("./ajax.php?function=getSchedule&week="+document.getElementById("week").value);		
	}
	updatePlayers();
}

function updatePlayers(){
	//check to make sure the match element can be read, if it can't we can't do the next line...
	if(document.getElementById("match")){
	if(document.getElementById("match").value=="--"){
		document.getElementById("winsInput").style.display="none";
		document.getElementById("winsInput").innerHTML="";
	
		document.getElementById("playersForTeams").style.display="none";
		document.getElementById("playersForTeams").innerHTML="";
	}else{	
		document.getElementById("winsInput").style.display="block";
		$('#winsInput').load("./ajax.php?function=getWinsForTeams&match="+document.getElementById("match").value);
	

		document.getElementById("playersForTeams").style.display="block";
		$('#playersForTeams').load("./ajax.php?function=getPlayersForTeams&match="+document.getElementById("match").value, updatePlayersCallback);
		
	}
	}


}

function updatePlayersCallback(){
	BEDL.form.repeatingFields.init();	
}




function updateAverages(){
	if(document.getElementById("match").value=="--"){
		document.getElementById("averages").style.display="none";
		document.getElementById("averages").innerHTML="";
		
	}else{	
		document.getElementById("averages").style.display="block";
		$('#averages').load("./ajax.php?function=getAverages&match="+document.getElementById("match").value);
	}	
}

function md5LoginPassword(){

document.getElementById("password").value = calcMD5(document.getElementById("password").value)


}

/* When the user clicks on the link, 
toggle between hiding and showing the dropdown content */
function showSeasonMenuList(e) {
	var offsets = $('#season').offset();
	var vtop = offsets.top;
	var vleft = offsets.left;
	vtop = vtop + ($('#season').outerHeight());
	$("#seasonDropdown").toggleClass("show");
	$("#seasonDropdown").offset({top: vtop,left: vleft});
	e.preventDefault();
}

/* When the user clicks on the link, 
toggle between hiding and showing the dropdown content */
function showDocumentsMenuList(e) {
	var offsets = $('#documents').offset();
	var vtop = offsets.top;
	var vleft = offsets.left;
	vtop = vtop + ($('#documents').outerHeight());
	$("#documentsDropdown").toggleClass("show");
	$("#documentsDropdown").offset({top: vtop,left: vleft});
	e.preventDefault();
}


$(document).ready(function() {
   $('#season').click(function(e) {showSeasonMenuList(e);});
   $('#documents').click(function(e) {showDocumentsMenuList(e);});
	
	$(document).click(function(event) {
	var target = event.target.id;
		
  if (target!="season") {

    var dropdown = document.getElementById("seasonDropdown");
    if (dropdown.classList.contains('show')) {
        dropdown.classList.remove('show');
      }
  
/*  var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }*/
  } 
 
  if (target!="documents") {

    var dropdown = document.getElementById("documentsDropdown");
   
      if (dropdown.classList.contains('show')) {
        dropdown.classList.remove('show');
      }
    
  } 
  
  
  
  
});

});

