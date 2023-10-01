<?php
/*
Plugin Name: Rev affiliate Widget 
Description: Show affiliate links

*/

// Rev affiliate and load the widget
add_action( 'wp_ajax_nopriv_update_my_variable', 'update_my_variable' );
add_action( 'wp_ajax_update_my_variable', 'update_my_variable' );
function update_my_variable() {
  //session_start();
  $value = $_POST['value'];
  //$_SESSION['my_variable'] = $value;
  

  global $j_parts;
		//$json_nba_n = get_option('json_nba_n');
		//echo "json_nba_n= " .$json_nba_n;

		$seprator = "*";
		$j_parts = explode($seprator, $value, 2);
		$j_parts[0] = trim(str_replace(" ", "%20", $j_parts[0]));
		$j_parts[1] = str_replace(" ", "%20", $j_parts[1]);

		//$json_nba = file_get_contents('https://www.betus.com.pa/api/feed/sportsbook/listgamelines/byName/'.$j_parts[1].'/'.$j_parts[0]);
		
		$json_nba_url = 'https://www.betus.com.pa/api/feed/sportsbook/listgamelines/byName/'.$j_parts[1].'/'.$j_parts[0];
		
		$trans_key_nba = 'odds_' . md5($json_nba_url);
		if (false === ($json_nba = get_transient($trans_key_nba))) {

			$json_nba = file_get_contents($json_nba_url);
			set_transient($trans_key_nba, $json_nba, 300);
		}
		
		echo $json_nba;
  wp_die();
}


add_action( 'wp_enqueue_scripts', 'my_enqueue_scripts' );
function my_enqueue_scripts() {
	wp_enqueue_script( 'my-script', plugin_dir_url( __FILE__ ) . 'js/custom-script.js', array( 'jquery' ), '1.0', true );
	wp_localize_script( 'my-script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
}


function rev_load_widget() {
    register_widget( 'revmaster_widget' );
}
add_action( 'widgets_init', 'rev_load_widget' );

// The widget Class
class revmaster_widget extends WP_Widget {

  function __construct() {
    parent::__construct(

      // Base ID 
      'revmaster_widget',

      // Widget name 
      __('Rev affiliate Widget ', 'rev_widget_domain'),

      // Widget description
      array( 'description' => __( 'Show Affiliates Details in a Widget', 'rev_widget_domain' ), )
    );
  }
  
  
  // Display the widget
	public function widget( $args, $instance ) {

		//extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );
		
		// WordPress core before_widget hook
		echo $before_widget;

		// Display the widget
		
		//echo "Title testing";
		//header('Content-type:application/json;charset=utf-8');

	//$atts = array_change_key_case( (array) $atts, CASE_LOWER );
	
	$atts['sport'] = 'football';
	$atts['league'] = 'nfl';
	
	/*
	$atts['team1'] = 'new-york-giants';
	$atts['team2'] = 'denver-broncos';*/
	
	$json_schedule_t = 'https://www.betus.com.pa/api/feed/sportsbook/listschedule';

		$trans_key_schedule = 'odds_' . md5($json_schedule_t);
		if (false === ($schedule_api = get_transient($trans_key_schedule))) {

			$schedule_api = file_get_contents($json_schedule_t);
			set_transient($trans_key_schedule, $schedule_api, 300);
		}
	
/*
		global $j_parts;
		$json_nba_n = get_option('json_nba_n');
		
		//echo "json_nba_n= " .$json_nba_n;

		$seprator = "*";
		$j_parts = explode($seprator, $json_nba_n, 2);
		$j_parts[0] = trim(str_replace(" ", "%20", $j_parts[0]));
		$j_parts[1] = str_replace(" ", "%20", $j_parts[1]);

		$json_nba = file_get_contents('https://www.betus.com.pa/api/feed/sportsbook/listgamelines/byName/'.$j_parts[1].'/'.$j_parts[0]);
		*/
	
	$gamebox = '<div class="widget-sections"><div class="btsgm-preview-section" id="prvSection">';
	
	$gamebox .= '<h2>Preview</h2>';
	$gamebox .= '<label class="themeSwitch" for="toggleInput">
				<input class="toggle" id="toggleInput" type="checkbox" checked onclick="frameValues();">
				<span class="themeToggle round"></span>
				</label>';
	$gamebox .= '<div class="orient"><a class="landscape active" id="landMode" onclick="changePort();"></a>
				<a class="portrait" onclick="changeLand();"></a></div>';
	$gamebox .= '<div class="dark-light-box" id="lightDark"> <div class="spinner_p" style="display: none;"></div>	';
	$gamebox .= '<h5>Select the options and click Preview to the preview the iframe</h5><iframe id="affFrame"></iframe></div>
	
				 <svg display="none" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="768" height="800" viewBox="0 0 768 800"><defs><g id="icon-close"><path class="path1" d="M31.708 25.708c-0-0-0-0-0-0l-9.708-9.708 9.708-9.708c0-0 0-0 0-0 0.105-0.105 0.18-0.227 0.229-0.357 0.133-0.356 0.057-0.771-0.229-1.057l-4.586-4.586c-0.286-0.286-0.702-0.361-1.057-0.229-0.13 0.048-0.252 0.124-0.357 0.228 0 0-0 0-0 0l-9.708 9.708-9.708-9.708c-0-0-0-0-0-0-0.105-0.104-0.227-0.18-0.357-0.228-0.356-0.133-0.771-0.057-1.057 0.229l-4.586 4.586c-0.286 0.286-0.361 0.702-0.229 1.057 0.049 0.13 0.124 0.252 0.229 0.357 0 0 0 0 0 0l9.708 9.708-9.708 9.708c-0 0-0 0-0 0-0.104 0.105-0.18 0.227-0.229 0.357-0.133 0.355-0.057 0.771 0.229 1.057l4.586 4.586c0.286 0.286 0.702 0.361 1.057 0.229 0.13-0.049 0.252-0.124 0.357-0.229 0-0 0-0 0-0l9.708-9.708 9.708 9.708c0 0 0 0 0 0 0.105 0.105 0.227 0.18 0.357 0.229 0.356 0.133 0.771 0.057 1.057-0.229l4.586-4.586c0.286-0.286 0.362-0.702 0.229-1.057-0.049-0.13-0.124-0.252-0.229-0.357z"></path></g></defs></svg>
				  <div class="wrapper pop-wrap">
					<button class="modal-toggle">Preview as pop-up</button>
				  </div>
				  
				  <div class="prePop">
					<div class="modal-overlay modal-toggle"></div>
					<div class="modal-wrapper modal-transition" id="viewPop">
					  <div class="modal-header">
						<button class="modal-close modal-toggle"><svg class="icon-close icon" viewBox="0 0 32 32"><use xlink:href="#icon-close"></use></svg></button>
						<h2 class="modal-heading">Preview</h2>
					  </div>
					  
					  <div class="modal-body">
						<div class="modal-content">
						  <iframe width="100%" height="100%" id="popaffFrame"></iframe>

						</div>
					  </div>
					</div>
				  </div>	
	';
	$gamebox .= '<div class="paste-title"><h5 style="display:inline;">COPY/PASTE CODE </h5></div> 
					<div class="revFrame" id="CopyFrame"></div>
					<button id="copyBtn" onclick="copyIframe(\'CopyFrame\');">Copy</button>
				</div>';	
	$gamebox .='<div class="betus-gamelines-league" id="betus-league">
					<div class="rev-select">
						<form method="post" id="revForm">
							<div class="affLinks" id="widgetSec">
								<h2>ODDS WIDGET SETTING</h2>
								<label class="labelAff">Your affiliate link
								<input type="text" id="linkAff" placeholder="https://record.revmasters.com" rel="nofollow" onchange=frameValues();>
								<span class="ico"></span>
								<span id="affError" style=color:#ff0000;></span>
								</label>
							</div>						
							<div class="embed_section" id="embed_sections">
								<h4>Select an embeddable type:<span style="color:#FC9901;text-transform:capitalize;" id="chooseEmbed"></span></h4>
								<label for="single_game" class="labelRadio">Single game
								<input type="radio" id="single_game" name="rev_sport" class="sEmbed" value="single" onclick="yesnoCheck(); getRadioValue(); configureDropDownLists(this,document.getElementById(\'tempSize\')); frameValues(); " >
								<span class="checkRadio" id="chkRadio"></span></label>
								<label for="league" class="labelRadio">League
								<input type="radio" id="league" name="rev_sport" class="sEmbed" value="league" onclick="javascript:yesnoCheck(); getRadioValue(); sportSelectValue(); configureDropDownLists(this,document.getElementById(\'tempSize\')); frameValues();">
								<span class="checkRadio" id="chkRadio"></span></label>
								<!--
								<label for="video" class="labelRadio">Video
								<input type="radio" id="video" name="rev_sport" class="sEmbed" value="video" onclick="javascript:yesnoCheck(); getRadioValue(); configureDropDownLists(this,document.getElementById(\'tempSize\')); frameValues();">
								<span class="checkRadio" id="chkRadio"></span></label>
								-->
								<span id="checkRadioError"></span>
							</div>
			
							<div class="sport_section" id="sport_sections">
								<h4>Select a Sport/Game: <span style="color:#FC9901;text-transform:capitalize;" id="headlineGame"></span></h4>
								<select id="sGames" name="leGames" onchange="ddList(this,document.getElementById(\'ddl2\')); sportSelectValue(); frameValues(); " onclick="sortTeams();">
									<option value="">Select a sport</option>
									  
								</select><br>

								<div class="spinner_s-container">  
								<p style="display:inline-block;">Loading Games...</p>
								</div>

								
								<select id="ddl2" onchange="sportSelectValue(); frameValues();"  >
								<option value="">Select a Game</option>
								</select><br>
								
								<div id="gameEnddiv">
									<h4 class="game-end-title"> When game ends? </h4>
									<select id="gameEnd" onchange="frameValues();" >
										<option value="">Select a option</option>
										  <option value="game">Show next game</option>  
										  <option value="banner">Show Banner</option>	  
									</select><br>
								</div>	
								
							</div>
								
							<div class="temp_section" id="temp_sections">
								<h4>Select a Template Size: <span id="tmpSize" style=color:#FC9901;></span></h4>
								<select id="tempSize" name="" onclick="javascript:changeSize(); frameValues();">
								<option value="">Select a Template</option>  
								</select><br>
								<div id="cDimension">
									<input type="text" id="cWidth" placeholder="Width" onchange="frameValues();"> x
									<input type="text" id="cHeight" placeholder="Height" onchange="frameValues();">
								</div>	
								<button class="clearBtn"  id="btnClearData"">Clear </button> 
								<button class="submitBtn">Preview</button>
							</div>
								
						</form>
					</div>
				</div>
			</div>';	

	$gameboxt = '<script> 

	/*********************************************Rev affiliat  */
		

	jQuery(document).ready(function(){
		
		

		//var json_text = document.getElementsByTagName("pre")[0].innerHTML;
		var games = JSON.parse(' . $schedule_api . '); 

		
		const daySelect = document.getElementById("sGames");
		let group;


		games.Schedule.forEach( function(x, i) {
			if(x.PeriodType == "Game") {
				var opt_text=x.LeagueName ;
				var opt_value=x.LeagueName + "*" +  x.SportName ;
				var opt_extra= x.SportName;

				
				const emptyArray = {};
				const Skey = opt_text ;
				const Svalue = opt_extra ;
				emptyArray[Skey] = Svalue;

				Object.entries(emptyArray).forEach(([key, value]) => {
					if (value !== group) {
					 group = value;
					 const optGroup = document.createElement("optgroup");
					 optGroup.label = group;
					 daySelect.appendChild(optGroup);
				   }
				  

				  
				});
				  //const [opt_text, opt_value] = key.split("*");
				 
				  daySelect.options[daySelect.options.length] = new Option(opt_text, opt_value);

			}	

		});	


	});	
	
		function yesnoCheck() {
			
			if (document.getElementById("single_game").checked) {    // Hide dropdowns 
				document.getElementById("ddl2").style.display = "block";
				document.getElementById("gameEnddiv").style.display = "block";
				// document.getElementById("betus-teamData").style.display = "block";
			}
			else{ 
				document.getElementById("ddl2").style.display = "none";			
				document.getElementById("gameEnddiv").style.display = "none";

				
				// document.getElementById("betus-teamData").style.display = "none";
			}
		}

		function getRadioValue() {             // Checkboxes titles
			var radio = document.getElementsByName("rev_sport");
			for(i = 0; i < radio.length; i++) {
				if(radio[i].checked)
					document.getElementById("chooseEmbed").innerHTML = " " + " " + radio[i].value ;
			}
		}
		
		document.getElementById("tempSize").addEventListener("change", changeSize);   // Change template size
		function changeSize() {
			var tempVal= jQuery("#tempSize").val();
			document.getElementById("tmpSize").innerHTML = tempVal;
			
			
				
		}
	
		function sportSelectValue(){  					// Dropdown titles
			var selectSport = document.getElementById("sGames");
			var value = selectSport.options[selectSport.selectedIndex].value;
		
			var sportSelectTeam = document.getElementById("ddl2");
			var valueTeam = sportSelectTeam.options[sportSelectTeam.selectedIndex].value;
		
			if(document.getElementById("single_game").checked){
				document.getElementById("headlineGame").innerHTML = "<span class=\"gameCap\">" + value + "</span>" +" > " + valueTeam ;
			}
			if(document.getElementById("league").checked){
				document.getElementById("headlineGame").innerHTML = "<span class=\"gameCap\">" + value + "</span>";
			}	
		};
		
		$("#btnClearData").click(function () { // Clear form 
			$("#revForm")[0].reset();
			return false;
		});
		
		function frameValues(){
			
			var radio = document.getElementsByName("rev_sport");
			if(document.getElementById("single_game").checked){
				var revUrl = new URL("https://widgets.revmasters.com/widgets/?afflink=https://record.revmasters.com/_test&etype=league&sport=nhl&team-away=team_name&team-home=team_name&theme=dark&game-end=game");
			}
			else{
				var revUrl = new URL("https://widgets.revmasters.com/widgets/?afflink=https://record.revmasters.com/_test&etype=league&sport=nhl&theme=dark");
			}
			var search_params = revUrl.searchParams;
			
			// change the search property of the main url
			revUrl.search = search_params.toString();

			// new value set
			
			if (document.getElementById("toggleInput").checked == true) {    // Theme toggle 
				toggleValue = "light";
				search_params.set("theme", toggleValue);
			}
			else {
				var toggleValued = "dark";	
				search_params.set("theme", toggleValued);
			}
			
			var radio = document.getElementsByName("rev_sport");
				for(i = 0; i < radio.length; i++) {
					if(radio[i].checked)
						var radioOutput = radio[i].value ;
						}
			search_params.set("etype", radioOutput);
					
			var selectSport = document.getElementById("sGames");
			var valueOfGame = selectSport.options[selectSport.selectedIndex].value;	

			
			//var str = "Argentina Liga A + Basketball";
            var select_p_v = valueOfGame.split("*");

//console.log("The value of selcted sport line 437" + select_p_v[0]);			
			search_params.set("sport", select_p_v[0]);

			
			linkAff= jQuery("#linkAff").val();
			search_params.set("afflink", linkAff);
			
			endGame= jQuery("#gameEnd").val();
			search_params.set("game-end", endGame);
		
			tempName = jQuery("#tempSize").val();	
			temps = tempName.split("x");
			// search_params.set("size", temps[0]);	
			
			
			if(document.getElementById("single_game").checked){
				teamName = jQuery("#ddl2").val();	
				teams = teamName.split(" vs ");
				
				var last = teamName;
				
				var teamRemove= teams[1];
				var lastIndex = teamRemove.lastIndexOf(" ");
				teamRemove = teamRemove.substring(0, lastIndex);

				var lastWord = last.split(" ").pop();
			
				search_params.set("team-away", teams[0]);
				search_params.set("team-home", teamRemove);
				search_params.set("matchDate", lastWord);				
			}
			
			// the new url string
			var new_url = revUrl.toString();
				
			var clearUrl="";
			
			jQuery("#lightDark h5").hide();

			jQuery(document).ready(function(){
				jQuery(".submitBtn").click(function(e) {
					e.preventDefault();	
					
						if(tempName == "Custom"){
							customWidth = jQuery("#cWidth").val();
							customHeight = jQuery("#cHeight").val();
					
							//jQuery("iframe").height(customHeight + "px");
							//jQuery("iframe").width(customWidth + "px");
							
							$("iframe")[0].setAttribute("width", customWidth );
							$("iframe")[0].setAttribute("height", customHeight );
						}		
						else{
					
							//jQuery("iframe").height(temps[1]  + "px");
							$("iframe")[0].setAttribute("width", temps[0] );
							$("iframe")[0].setAttribute("height", temps[1] );

							//jQuery("iframe").width(temps[0]  + "px");
				
							}
					//console.log(new_url);
					var urlTosee =jQuery("#affFrame").attr("src", new_url);
					var urlTopop =jQuery("#popaffFrame").attr("src", new_url);
				})
				});
				
			jQuery(document).ready(function(){
				jQuery(".clearBtn").click(function(e) {
					e.preventDefault();
					jQuery("iframe").attr("src", clearUrl);
					jQuery("#CopyFrame").empty()

				})
				});
				
			jQuery(document).ready(function(){
				jQuery("#ddl2").change(function(e) {
					e.preventDefault();
					jQuery("iframe").attr("src", clearUrl);
				})
				
			jQuery("#sGames").change(function(e) {
					e.preventDefault();
					jQuery("iframe").attr("src", clearUrl);
					
				})
			jQuery("#tempSize").change(function(e) {
					e.preventDefault();
					jQuery("iframe").attr("src", clearUrl);
					jQuery("#cWidth").val("");
					jQuery("#cHeight").val("");
				})	
				
				if ($("#CopyFrame:contains(\'</iframe>\')").length > 0) {

				jQuery("#toggleInput").change(function(e) {
					e.preventDefault();
					jQuery("iframe").attr("src", new_url);
				})
				}	
				
				});
				
				jQuery(document).ready(function(){
					jQuery(".submitBtn").click(function(e) {
					e.preventDefault();	
				
				
				var affValid =jQuery("#linkAff").val(); 
				
				var embedSingle =jQuery("#single_game").val();
				var embedLeague =jQuery("#league").val();
				var embedVideo =jQuery("#video").val();
				
				var gamesValid =jQuery("#sGames").val();
				
				var tempValid =jQuery("#tempSize").val();
				
				customWidth = jQuery("#cWidth").val();
				customHeight = jQuery("#cHeight").val();
				
				const verify = ["https://www.betus.com.pa/", "https://record.revmasters.com/"];

				
					if(affValid.startsWith("https://www.betus.com.pa/") == false && affValid.startsWith(verify[1]) == false){
					document.getElementById("affError").innerHTML = "Affiliate link is incorrect. Please enter your full affiliate link, or contact your Affiliate Manager with any issues.";
					e.preventDefault();	
					}
					else if(embedSingle == "" ){
					document.getElementById("checkRadioError").innerHTML = "Please select an embeddable type! ";
					console.log(embedSingle);
					e.preventDefault();	
					}
					else if(gamesValid == ""){
					document.getElementById("sGames").style.border = "1px solid red";
					e.preventDefault();	
					}		
				
					else if (tempValid == ""){
					document.getElementById("tempSize").style.border = "1px solid red";
					e.preventDefault();	
					}
					
					else if (tempName == "Custom" && customWidth !== "" || customHeight !== ""){
					
					var frameHTML = "<iframe src=\"" + new_url + "\" width=\"" + customWidth + "\" height=\"" + customHeight + "\"></iframe>";
							jQuery("div.revFrame").text(frameHTML);
							var escaped = jQuery("<div>").text(frameHTML).html();
					}					
					
					else{
					var frameHTML = "<iframe src=\"" + new_url + "\" width=\"" + temps[0] + "\" height=\"" + temps[1] + "\"></iframe>";
							jQuery("div.revFrame").text(frameHTML);
							var escaped = jQuery("<div>").text(frameHTML).html();


							setTimeout(function() {
								jQuery(".spinner_p").hide();
							}, 2500); // hide spinner after 2 seconds


					}
					
					
					$("#sGames").change(function() {
						document.getElementById("sGames").style.border = "0px";
						document.getElementById("sGames").style.borderBottom = "2px solid #FC9901";

					});
					$("#tempSize").change(function() {
						document.getElementById("tempSize").style.border = "0px";
						document.getElementById("tempSize").style.borderBottom = "2px solid #FC9901";
					});				
			
		})
				});
				if ($("#CopyFrame:contains(\'</iframe>\')").length > 0) {
				$("#toggleInput").change(function() {
					var frameHTML = "<iframe src=\"" + new_url + "\" width=\"" + temps[0] + "\" height=\"" + temps[1] + "\"></iframe>";
							jQuery("div.revFrame").text(frameHTML);
							var escaped = jQuery("<div>").text(frameHTML).html();
					var urlTosee =jQuery("#affFrame").attr("src", new_url);		
					});		
				}
				//console.log(new_url);
				
			
			jQuery(document).ready(function(){
				tempName = jQuery("#tempSize").val();	
				temps = tempName.split("x");
				
				if(tempName == "Custom") {
					
					document.getElementById("viewPop").style.width = jQuery("#cWidth").val() + "px";
					document.getElementById("affFrame").style.width = jQuery("#cWidth").val() + "px";
					document.getElementById("popaffFrame").style.width = jQuery("#cWidth").val() + "px";
					
					document.getElementById("viewPop").style.height = jQuery("#cHeight").val() + "px";
					document.getElementById("affFrame").style.height = jQuery("#cHeight").val() + "px";
					document.getElementById("popaffFrame").style.height = jQuery("#cHeight").val() + "px";

				} else {
				
					document.getElementById("viewPop").style.width = temps[0] + "px";
					document.getElementById("affFrame").style.width = temps[0] + "px";
					document.getElementById("popaffFrame").style.width = temps[0] + "px";
					document.getElementById("viewPop").style.height = temps[1] + "px";
					document.getElementById("affFrame").style.height = temps[1] + "px";
					document.getElementById("popaffFrame").style.height = temps[1] + "px";
				}
	});
	
			jQuery(document).ready(function(){
				
				tempVal = jQuery("#tempSize").val();
				if(tempVal == "Custom"){
				
				$("#cDimension").show();
				console.log("Custom size selected");
			}
			else{
				$("#cDimension").hide();
			}

	});
	


	}
	frameValues();
	sGames.addEventListener("change", function() {
		frameValues();
	
	});
	


	function copyIframe(id)
	{
		var copyrange = document.createRange();
			copyrange.selectNode(document.getElementById(id));
			window.getSelection().removeAllRanges();
			window.getSelection().addRange(copyrange);
			document.execCommand("copy");
			window.getSelection().removeAllRanges();
	}	
	
	jQuery(document).ready(function(){
	jQuery(".ico").click(function(e) {
		e.preventDefault();
		jQuery("#linkAff").val("https://record.revmasters.com/");
		
		

	})
	});
	jQuery(document).ready(function(){	
	jQuery("#linkAff").click(function(e) {
		e.preventDefault();
		jQuery(".ico").hide();

	})
	});
	
	jQuery(".modal-toggle").on("click", function(e) {
		e.preventDefault();
		jQuery(".prePop").toggleClass("is-visible");
	});	

	
			
	</script>';
	
	$gameboxt .= '<script> 	  

  

	function configureDropDownLists(single_game,tempSize) {
    
		var singGameTxt = ["300x250", "970x160", "1040x160", "Custom"];
		
		var sLeagueTxt = ["300x600", "325x508", "970x770", "Custom"];
	
		var sVideoTxt = ["300x250", "Custom"];

		switch (single_game.value) {
			case "single":
				tempSize.options.length = 1;
				for (i = 0; i < singGameTxt.length; i++) {
					createOption(tempSize, singGameTxt[i], singGameTxt[i]);
				}
				break;
			case "league":
				tempSize.options.length = 1; 
			for (i = 0; i < sLeagueTxt.length; i++) {
				createOption(tempSize, sLeagueTxt[i], sLeagueTxt[i]);
				}
				break;
			case "video":
				  tempSize.options.length = 1; 
				createOption(tempSize, sVideoTxt, sVideoTxt);
			
				break;	
				default:
					tempSize.options.length = 1;
				break;
		}

}

    function createOption(tempSize, text, value) {
        var opt = document.createElement("option");
			opt.value = value;
			opt.text = text;
			tempSize.options.add(opt);
}

	function changeLand(){

		var formDiv = document.getElementById("betus-league");
			formDiv.style.width="100%";
			//formDiv.style.margin="30px 0px 0px 0px";
		
		var prvDiv = document.getElementById("prvSection");
			formDiv.style.width="100%";
			prvDiv.style.background="#fff";
			prvDiv.style.display="block";
			prvDiv.style.margin="0 auto";
		
		var land= document.getElementById("landMode");
			//land.style.background="#000";

		
		var affLinks= document.getElementById("widgetSec");
			affLinks.style.width="31.5%";
			affLinks.style.float="left";
			affLinks.style.marginRight="20px";
			affLinks.style.minHeight="350px";


		/*var chkradio= document.getElementById("chkRadio");
		chkradio.style.backgroundColor="#fff";*/

		
		var embed=document.getElementById("embed_sections");
			embed.style.width="31.5%";
			embed.style.float="left";
			embed.style.marginRight="20px";
			embed.style.minHeight="350px";
			embed.style.background="#eee";

		var sportSection=document.getElementById("sport_sections");
			sportSection.style.width="31.5%";
			sportSection.style.float="left";
			sportSection.style.background="#eee";
		var tempSection=document.getElementById("temp_sections");
			tempSection.style.width="31.5%";
			tempSection.style.float="left";
			tempSection.style.background="#eee";


}

	function changePort(){
		var formDiv = document.getElementById("betus-league");
			formDiv.style.width="33%";
		
		var prvDiv = document.getElementById("prvSection");
			prvDiv.style.background="#eee";
			prvDiv.style.display="inline-block";
		
		
		var affLinks= document.getElementById("widgetSec");
			affLinks.style.width="inherit";
			affLinks.style.float="none";
			affLinks.style.marginRight="0px";
			affLinks.style.minHeight="inherit";

		
		
		var embed=document.getElementById("embed_sections");
			embed.style.width="inherit";
			embed.style.float="none";
			embed.style.marginRight="0px";
			embed.style.minHeight="inherit";
			embed.style.background="inherit";
			
		var sportSection=document.getElementById("sport_sections");
			sportSection.style.width="inherit";
			sportSection.style.float="none";
			sportSection.style.background="inherit";
			
		var tempSection=document.getElementById("temp_sections");
			tempSection.style.width="inherit";
			tempSection.style.float="none";
			tempSection.style.background="inherit";
	}
	
	jQuery(function() {                       
	  jQuery(".landscape").click(function() {  
		jQuery(this).addClass("active"); 
		jQuery(".portrait").removeClass("active"); 
	  });
});

	jQuery(function() {                       
	  jQuery(".portrait").click(function() {  
		jQuery(this).addClass("active");      
		jQuery(".landscape").removeClass("active"); 
	  });
});



	function sortTeams() {
		var abc = jQuery("ddl2").val();
	jQuery("#ddl2").each(function() {
		
		// Keep track of the selected option.
		var selectedValue = $(this).val();
		
		// Sort all the options by text.
		jQuery(this).html($("option", $(this)).sort(function(a, b) {
		return a.value.toUpperCase() == b.value.toUpperCase() ? 0 : a.value.toUpperCase() < b.value.toUpperCase() ? -1 : 1
			
		}));
		
		// Select one option.
		jQuery(this).val(selectedValue);
		})
}	

	</script>';
	

	echo $gamebox;
	echo $gameboxt;
	//echo $gamebox1;
	

	//echo $json1;
	
	//print_r($json);

	//$results = json_decode($json); 

	// WordPress core after_widget hook (always include )
		echo $after_widget;

	}
  
	
}
//Register the widget
function my_register_custom_widget() {
	register_widget( 'revmaster_widget' );
}
add_action( 'widgets_init', 'my_register_custom_widget' );
?>
