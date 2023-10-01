
jQuery(document).ready(function(){
 console.log("Plugin js added");
 
});	


  var myResponse;
  var myResponse2="{\"Status\":\"OK\",\"Method\":\"GAME-LINES\",\"GameLines\":[{\"Away\":{\"Rotation\":6110,\"Name\":\"Tasmania (W)\"},\"Home\":{\"Rotation\":6111,\"Name\":\"South Australia (W)\"},\"Draw\":{\"Rotation\":6112},\"Lines\":[{\"IsAlternative\":false,\"HideTitle\":false,\"PeriodName\":\"Game\",\"PeriodDescription\":\"Game\",\"Away\":{\"MoneyLine\":{\"Price\":-175,\"Decimal\":\"1.57\",\"Fractional\":{\"Numerator\":4,\"Denominator\":7}}},\"Home\":{\"MoneyLine\":{\"Price\":135,\"Decimal\":\"2.35\",\"Fractional\":{\"Numerator\":27,\"Denominator\":20}}},\"HasAlternativeOptionLines\":false}],\"MarketsCount\":0,\"MatchupDateTime\":\"2023-02-24T22:05:00\",\"SportName\":\"Cricket\",\"LeagueName\":\"Australia NCL Women\",\"IsFeature\":false,\"SportType\":\"Other Sports\",\"SportSubType\":\"Cricket\"}]}";
 

sGames.addEventListener("change", function() {
    var valueFromJS = this.value;

    // Add loading spinner
   /* var spinner = document.createElement("div");
    spinner.innerHTML = "<p>Loading this...</p>";
    document.body.appendChild(spinner);*/

   
 // Add loading spinner
 if (document.getElementById("single_game").checked) {    // Hide dropdowns 
  var spinnerContainer = document.querySelector('.spinner_s-container');
  spinnerContainer.style.display = 'flex';
}


  
    jQuery.post(
      myAjax.ajaxurl,
      {
        action: "update_my_variable",
        value: valueFromJS
      },
      function(response) {

        // Remove loading spinner
       // document.body.removeChild(spinner);

        // Remove loading spinner
      spinnerContainer.style.display = 'none';

        // Store the response in a variable for later use
        myResponse = JSON.parse(response);
        //myResponse = JSON.stringify(myResponse.trim());
  
        
        //console.log(myResponse) ;
       // console.log(myResponse2) ;
            // Update $j_parts based on the selected value
            
              

            var nba_datap = JSON.parse(myResponse); 
		

			//ddl2.options.length = 0;
            var ddl2 = document.getElementById("ddl2");

            ddl2.options.length = 0;
			for (i = 0; i < nba_datap.GameLines.length; i++) {

				var dateMatch= nba_datap.GameLines[i].MatchupDateTime;
				var date_to_convert =  new Date(dateMatch);
				var date_options = {month: "numeric", day: "numeric" };
				var converted_date = date_to_convert.toLocaleDateString("en-US", date_options);

               // var homeName = nba_datap.GameLines[i].Home.Name;
               // var awayName = nba_datap.GameLines[i].Away.Name;
				
			
                createOption(ddl2, nba_datap.GameLines[i].Home.Name + " vs " + nba_datap.GameLines[i].Away.Name + " - " + converted_date, nba_datap.GameLines[i].Home.Name + " vs " + nba_datap.GameLines[i].Away.Name + " " + converted_date);
		
                
                //console.log("The Value of data " + homeName + awayName);   
			}

			 
            function createOption(sGames, text, value) {
                var opt = document.createElement("option");
                    opt.value = value;
                    opt.text = text;
                    sGames.options.add(opt);
        };	

            
            
          }
        
      
    );
  });
  
