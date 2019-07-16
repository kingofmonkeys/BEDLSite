function calculateSinglesHandicap(){
	
	player1ID=document.getElementById("player1").value;
	player2ID=document.getElementById("player2").value;
	gameType=document.getElementById("singlesGameType").value;
	
	if(player1ID=='' || player2ID==''){
		output = '<font color="red"><b>Please select 2 players</b></font>';
	}else if(player1ID==player2ID){
		output = '<font color="red"><b>Please select 2 different players</b></font>';
	}else if(gameType==''){
		output = '<font color="red"><b>Please select a game type</b></font>';
	}else{		
		player1 = players[player1ID];
		player2 = players[player2ID];
		//always put the lower rank first
		if(player1[4]>player2[4]){
			player1 = players[player2ID];
			player2 = players[player1ID];		
		}
		
		player1Handicap='None';
		player2Handicap='None';
		
		if(gameType=='01'){
			if(player1[4]==1){
				player1Handicap='501';
				if(player2[4]==1){
					player2Handicap='501';
				}else if(player2[4]==2){
					player2Handicap='426';
				}else if(player2[4]==3){
					player2Handicap='351';
				}else if(player2[4]==4){
					player2Handicap='276';
				}else if(player2[4]==5){
					player2Handicap='201';
				}				
			}else if(player1[4]==2){
				if(player2[4]==2){
					player1Handicap='401';
					player2Handicap='401';
				}else if(player2[4]==3){
					player1Handicap='426';
					player2Handicap='351';
				}else if(player2[4]==4){
					player1Handicap='426';
					player2Handicap='276';
				}else if(player2[4]==5){
					player1Handicap='426';
					player2Handicap='201';
				}
			}else if(player1[4]==3){
				if(player2[4]==3){
					player1Handicap='301';
					player2Handicap='301';
				}else if(player2[4]==4){
					player1Handicap='351';
					player2Handicap='276';
				}else if(player2[4]==5){
					player1Handicap='351';
					player2Handicap='201';
				}
			}else if(player1[4]==4){
				if(player2[4]==4){
					player1Handicap='301';
					player2Handicap='301';
				}else if(player2[4]==5){
					player1Handicap='276';
					player2Handicap='201';
				}
			}else if(player1[4]==5){
				player1Handicap='201';
				player2Handicap='201';
			}
		//this is cricket
		}else{
			if(player1[4]==1){				
				if(player2[4]==2){
					player2Handicap='2M+25P';
				}else if(player2[4]==3){
					player2Handicap='4M+25P';
				}else if(player2[4]==4){
					player2Handicap='6M+25P';
				}else if(player2[4]==5){
					player2Handicap='8M+25P';
				}				
			}else if(player1[4]==2){
				if(player2[4]==3){				
					player2Handicap='2M+25P';
				}else if(player2[4]==4){					
					player2Handicap='4M+25P';
				}else if(player2[4]==5){				
					player2Handicap='6M+25P';
				}
			}else if(player1[4]==3){
				if(player2[4]==4){					
					player2Handicap='2M+25P';
				}else if(player2[4]==5){					
					player2Handicap='4M+25P';
				}
			}else if(player1[4]==4){
				 if(player2[4]==5){					
					player2Handicap='2M+25P';
				}
			}
			
			
		}
		
		

		output = player1[2]+' '+player1[3] +' ('+player1[4]+') : <b>'+player1Handicap+'</b> vs '+player2[2]+' '+player2[3]+' ('+player2[4]+') : <b>'+player2Handicap+'</b>';
	
	}
	
		
	document.getElementById("singlesHandicapResults").innerHTML = output;
};




function calculateDoublesHandicap(){
	
	team1player1ID=document.getElementById("team1player1").value;
	team1player2ID=document.getElementById("team1player2").value;
	
	team2player1ID=document.getElementById("team2player1").value;
	team2player2ID=document.getElementById("team2player2").value;
	
	gameType=document.getElementById("doublesGameType").value;
	
	if(team1player1ID=='' || team1player2ID=='' || team2player1ID=='' || team2player2ID==''){
		output = '<font color="red"><b>Please select 4 players</b></font>';
	}else if(team1player1ID==team1player2ID || team1player1ID==team2player1ID || team1player1ID==team2player2ID || team1player2ID==team2player1ID || team1player2ID==team2player2ID || team2player1ID==team2player2ID){
		output = '<font color="red"><b>Please select 4 different players</b></font>';
	}else if(gameType==''){
		output = '<font color="red"><b>Please select a game type</b></font>';
	}else{	
		//validation passed	
		team1player1 = players[team1player1ID];
		team1player2 = players[team1player2ID];
		
		team2player1 = players[team2player1ID];
		team2player2 = players[team2player2ID];
		
		team1rank = parseInt(team1player1[4])+parseInt(team1player2[4]);
		team2rank = parseInt(team2player1[4])+parseInt(team2player2[4]);
		
		//always put the lower rank team first
		if(team1rank>team2rank){
			team2player1 = players[team1player1ID];
			team2player2 = players[team1player2ID];
		
			team1player1 = players[team2player1ID];
			team1player2 = players[team2player2ID];	
		}
		
		team1rank = parseInt(team1player1[4])+parseInt(team1player2[4]);
		team2rank = parseInt(team2player1[4])+parseInt(team2player2[4]);
		
		team1Handicap='None';
		team2Handicap='None';
		
		if(gameType=='01'){
			if(team1rank==2){
				team1Handicap='501';
				if(team2rank==2){
					team2Handicap='501';
				}else if(team2rank==3){
					team2Handicap='501';
				}else if(team2rank==4){
					team2Handicap='426';
				}else if(team2rank==5){
					team2Handicap='426';
				}else if(team2rank==6){
					team2Handicap='351';
				}else if(team2rank==7){
					team2Handicap='351';
				}else if(team2rank==8){
					team2Handicap='276';
				}else if(team2rank==9){
					team2Handicap='276';
				}else if(team2rank==10){
					team2Handicap='201';
				}				
			}else if(team1rank==3){
				team1Handicap='501';
				if(team2rank==3){
					team2Handicap='501';
				}else if(team2rank==4){
					team2Handicap='501';
				}else if(team2rank==5){
					team2Handicap='426';
				}else if(team2rank==6){
					team2Handicap='426';
				}else if(team2rank==7){
					team2Handicap='351';
				}else if(team2rank==8){
					team2Handicap='351';
				}else if(team2rank==9){
					team2Handicap='276';
				}else if(team2rank==10){
					team2Handicap='276';
				}				
			}else if(team1rank==4){
				team1Handicap='501';
				if(team2rank==4){
					team2Handicap='501';
				}else if(team2rank==5){
					team2Handicap='501';
				}else if(team2rank==6){
					team2Handicap='426';
				}else if(team2rank==7){
					team2Handicap='426';
				}else if(team2rank==8){
					team2Handicap='351';
				}else if(team2rank==9){
					team2Handicap='351';
				}else if(team2rank==10){
					team2Handicap='276';
				}			
			}else if(team1rank==5){
				team1Handicap='501';
				if(team2rank==5){
					team2Handicap='501';
				}else if(team2rank==6){
					team2Handicap='501';
				}else if(team2rank==7){
					team2Handicap='426';
				}else if(team2rank==8){
					team2Handicap='426';
				}else if(team2rank==9){
					team2Handicap='351';
				}else if(team2rank==10){
					team2Handicap='351';
				}		
			}else if(team1rank==6){
				team1Handicap='501';
				if(team2rank==6){
					team2Handicap='501';
				}else if(team2rank==7){
					team2Handicap='501';
				}else if(team2rank==8){
					team2Handicap='426';
				}else if(team2rank==9){
					team2Handicap='426';
				}else if(team2rank==10){
					team2Handicap='351';
				}		
			}else if(team1rank==7){
				team1Handicap='501';
				if(team2rank==7){
					team2Handicap='501';
				}else if(team2rank==8){
					team2Handicap='501';
				}else if(team2rank==9){
					team2Handicap='426';
				}else if(team2rank==10){
					team2Handicap='426';
				}		
			}else if(team1rank==8){
				team1Handicap='501';
				if(team2rank==8){
					team2Handicap='501';
				}else if(team2rank==9){
					team2Handicap='501';
				}else if(team2rank==10){
					team2Handicap='426';
				}		
			}else if(team1rank==9){
				team1Handicap='501';
				if(team2rank==9){
					team2Handicap='501';
				}else if(team2rank==10){
					team2Handicap='501';
				}		
			}else if(team1rank==10){
				team1Handicap='501';				
				team2Handicap='501';					
			}
		//this is cricket
		}else{
			if(team1rank==2){				
				if(team2rank==4){
					team2Handicap='2M+25P';
				}else if(team2rank==5){
					team2Handicap='2M+25P';
				}else if(team2rank==6){
					team2Handicap='4M+25P';
				}else if(team2rank==7){
					team2Handicap='4M+25P';
				}else if(team2rank==8){
					team2Handicap='6M+25P';
				}else if(team2rank==9){
					team2Handicap='6M+25P';
				}else if(team2rank==10){
					team2Handicap='8M+25P';
				}				
			}else if(team1rank==3){
				if(team2rank==5){
					team2Handicap='2M+25P';
				}else if(team2rank==6){
					team2Handicap='2M+25P';
				}else if(team2rank==7){
					team2Handicap='4M+25P';
				}else if(team2rank==8){
					team2Handicap='4M+25P';
				}else if(team2rank==9){
					team2Handicap='6M+25P';
				}else if(team2rank==10){
					team2Handicap='6M+25P';
				}				
			}else if(team1rank==4){
				if(team2rank==6){
					team2Handicap='2M+25P';
				}else if(team2rank==7){
					team2Handicap='2M+25P';
				}else if(team2rank==8){
					team2Handicap='4M+25P';
				}else if(team2rank==9){
					team2Handicap='4M+25P';
				}else if(team2rank==10){
					team2Handicap='6M+25P';
				}			
			}else if(team1rank==5){
				if(team2rank==7){
					team2Handicap='2M+25P';
				}else if(team2rank==8){
					team2Handicap='2M+25P';
				}else if(team2rank==9){
					team2Handicap='4M+25P';
				}else if(team2rank==10){
					team2Handicap='4M+25P';
				}		
			}else if(team1rank==6){
				if(team2rank==8){
					team2Handicap='2M+25P';
				}else if(team2rank==9){
					team2Handicap='2M+25P';
				}else if(team2rank==10){
					team2Handicap='4M+25P';
				}		
			}else if(team1rank==7){
				if(team2rank==9){
					team2Handicap='2M+25P';
				}else if(team2rank==10){
					team2Handicap='2M+25P';
				}		
			}else if(team1rank==8){
				if(team2rank==10){
					team2Handicap='2M+25P';
				}		
			}
			
			
		}
		
		

		output = team1player1[2]+' '+team1player1[3] + ' and '+team1player2[2]+' '+team1player2[3] +' ('+team1rank+') : <b>'+team1Handicap+'</b> vs '+team2player1[2]+' '+team2player1[3] + ' and '+team2player2[2]+' '+team2player2[3] +' ('+team2rank+') : <b>'+team2Handicap+'</b>';
	
	}
	
		
	document.getElementById("doublesHandicapResults").innerHTML = output;
};

