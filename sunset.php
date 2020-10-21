<html>

<head>
    <script src="https://cdn.syncfusion.com/ej2/dist/ej2.min.js"></script>
    <link href="https://cdn.syncfusion.com/ej2/material.css" rel="stylesheet">
</head>

<style>

.container {
  position: relative;
  text-align: : center;
}

.center{
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 50%;
}

#imageText {
  z-index: 100;
  position: absolute;
  color: white;
  font-size: 12px;
  font-weight: bold;
  left: 400px;
  top: 465px;
}


</style>



<body>
<div style= text-align:center>

    <input id="datepicker"  type="date" name="Date: " value="" min="2016-01-01" max="">
    <input type="submit" onclick="Submit()" value="Show Sunset">
    </div>

<p> Testing my Calender input </p>





<!--THIS IS PHP CODE, IT READS ALL OF THE PICTURES FROM THE PRICTURES FOLDER
AND WRITES THEM TO A TEXT FILE -->
<?php

    $directory = "/var/www/html/pictures";
    $images = glob($directory . "/*.jpg");

    $myfile = fopen("sunset.txt", "w") or die ("Unable to open file!");

    foreach($images as $image)
    {
      //echo $image;
      $test = substr($image, 23);
      fwrite($myfile, $test.PHP_EOL);

    }
    fclose($myfile);
    //echo "<p> Helo </p>";
 ?>






<!-- THIS CREATES THE CALENDER WITH THE INPUT AND IT HAS A MIN AND MAX RANGE -->
 <script type="text/javascript">

    /*GET THE MIN AND MAX FOR THE CALENDER*/
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();
   if(dd<10){
          dd='0'+dd
      }
      if(mm<10){
          mm='0'+mm
      }

      today = yyyy+'-'+mm+'-'+dd;
      document.getElementById("datepicker").setAttribute("max", today);
      document.getElementById("datepicker").setAttribute("value", today);

 </script>







<!--USER INPUT AND THIS FINDS THE PICTURE BASED ON THE USER input
IF THERE IS NO SUNSET PICTURE ON THAT DATE,
WE FIND THE CLOSEST PICTURE TO THAT DATE-->
<script type="text/javascript">
  /*GET PICTURE WITH THE USER input
  IF THERE IS NO SUNSET ON THAT DATE, GET THE closest SUNSET PICTURE TO THAT DATE*/

  function Submit(){
    var inputdate = document.getElementById("datepicker").value;
    var min = new Date('2016-01-01');
    var max = new Date();
    var test = inputdate.replace(/-/g,'/');         /*replce '-' with '/' because for some reason if we have a '-', it doesnt find the current dates
                                                    it finds yesterdays date for some reason, '/' fixes it*/
    var inputcheck = new Date(test);

    min.setDate(min.getDate());

    var substrings = inputdate.split("-");
    var Inputmm = parseInt(substrings[1]);
    var Inputdd = parseInt(substrings[2]);
    var Inputyyyy = parseInt(substrings[0]);

    if(isNaN(inputdate) && (inputcheck.getTime() >= min.getTime()) && (inputcheck.getTime() <= max.getTime()))
    {
      searchByDate(Inputmm, Inputdd, Inputyyyy, inputdate);
    }
    else {
      alert("Invalid input");
    }


  }

  /*SEARCH FOR A SUNSET PICTURE BY THE USER INPUT DATE*/
  function searchByDate(mm, dd, yyyy, inputdate)
  {
    var found = 0;

    var i = 0;
    /*THIS CHECKS IF THERE IS A SUNSET PICTURE FOR THE DATE THE USER ENTERED
    IF THERE IS A SUNSET PCITURE THEN WE DISPLAY*/
    while(lines[i] != null)
    {
      var year = parseInt(lines[i].substring(0,4));
      var month = parseInt(lines[i].substring(4,6));
      var day = parseInt(lines[i].substring(6,8));

      if(year == yyyy && month == mm && day == dd)
      {
        found = 1;
        //alert("Found closest date: " + lines[i]);
        current = "/pictures/" + lines[i];

        //update the picture text
        document.getElementById("imageText").innerHTML = "Sunset on: " + month + "/" + day + "/" + year;
        image = document.getElementById('image');
        image.src = current;
        //alert(current);

      }
      i = i + 1;
  }
  /*IF THERE WAS NO SUNSET PICTURE FOUND FOR THE INPUT DATE THEN WE NEED TO FIND THE
  CLOSEST SUNSET PICTURE TO THAT DATE*/
  if(found == 0)
  {
    var futureDate = new Date(inputdate);
    var pastDate = new Date(inputdate);
    futureDate.setDate(futureDate.getDate() + 1)
    pastDate.setDate(pastDate.getDate() + 1)


    var isFutureSunset = 0;
    var isPastSunset = 0;


    while(isPastSunset == 0 && isFutureSunset == 0)
    {
      futureDate.setDate(futureDate.getDate() + 1);
      pastDate.setDate(pastDate.getDate() - 1);

      var futureDay = futureDate.getDate();
      var futureMonth = futureDate.getMonth() + 1;
      var futureYear = futureDate.getFullYear();

      var pastDay = pastDate.getDate();
      var pastMonth = pastDate.getMonth() + 1;
      var pastYear = pastDate.getFullYear();

      /*compare both future and past dates with all of the files*/
      var i = 0;
      while(lines[i] != null)
      {
        var year = parseInt(lines[i].substring(0,4));
        var month = parseInt(lines[i].substring(4,6));
        var day = parseInt(lines[i].substring(6,8));

        if(year == futureYear && month == futureMonth && day == futureDay)
        {
          //alert("found future sunset at: " + futureMonth + "/" + futureDay + "/" + futureYear)
          isFutureSunset = 1;
          currentFuture = "/pictures/" + lines[i];

        }
        if(year == pastYear && month == pastMonth && day == pastDay)
        {
          //alert("found past sunset at: " + pastMonth + "/" + pastDay + "/" + pastYear)
          isPastSunset = 1;
          currentPast = "/pictures/" + lines[i];

        }

        /*COMPARE BOTH FUTURE DATE AND PAST DATE*/
        i++;
      }

    }

    /*IF IS PASTSUNSET WAS FOUND THEN DISPLAY THAT PCITURE
    IF FUTURESUNSET WAS FOUND THEN DISPLAY THAT PICTURES
    IF BOTH WERE FOUND THAT ARE THE SAME DAYS FROM THE USER DATE INPUT
    THEN DISPLAY THE PAST PICTURE*/

    if(isFutureSunset == 1 && isPastSunset == 1)
    {
      document.getElementById("imageText").innerHTML = "Sunset not found on " + mm + "/" + dd + "/" + yyyy + ", Closest Sunset on: " + pastMonth + "/" + pastDay + "/" + pastYear;
      image = document.getElementById('image');
      image.src = currentPast;
    }
    else if(isPastSunset == 1)
    {
      document.getElementById("imageText").innerHTML = "Sunset not found on " + mm + "/" + dd + "/" + yyyy + ", Closest Sunset on: " + pastMonth + "/" + pastDay + "/" + pastYear;
      image = document.getElementById('image');
      image.src = currentPast;
    }
    else if(isFutureSunset == 1)
    {
      document.getElementById("imageText").innerHTML = "Sunset not found on " + mm + "/" + dd + "/" + yyyy + ", Closest Sunset on: " + futureMonth + "/" + futureDay + "/" + futureYear;
      image = document.getElementById('image');
      image.src = currentFuture;
    }
  }


}


</script>








<!--THIS RUNS ONLOAD HENCE WHY THERE IS NO FUNCTION FOR THE CODE BELOW
THIS CREATES AN ARRAY OF ALL OF THE TEXT IN THE SUNSET.textarea
THEN WE FIND THE LASTEST SUNSET PICTURE AND WE DISPLAY IT-->
<script type="text/javascript">

/*THIS READS ALL OF THE SUNSET PICTURES FROM THE SUNSET.TXT
AND STORES IT IN AN ARRAY*/
//function getLastestSunset(){
  var txtFile = new XMLHttpRequest();
  txtFile.open("GET", "sunset.txt", true);
  txtFile.onreadystatechange = function()
  {
    if (txtFile.readyState === 4) {  // document is ready to parse.
      if (txtFile.status === 200) {  // file is found
        allText = txtFile.responseText;
        lines = txtFile.responseText.split("\n");
        //alert(allText);
      }
    }

  getCurrentPicture(lines);

  }
  txtFile.send(null);
//}


/*FUNCTION THAT FINDS THE RECENT SUNSET*/
function getCurrentPicture(lines)
{
  //alert(lines[0]);
  var today = new Date();
  //alert("in current: " + today);
  var dd = parseInt(String(today.getDate()).padStart(2, '0'));
  var mm = parseInt(String(today.getMonth() + 1).padStart(2, '0')); //January is 0!
  var yyyy = today.getFullYear();
  var current;


  /*FIND THE MOST RECENT PICTURE*/
  var found = 0;
  while(found == 0)
  {
    //alert(yyyy);
    /*LOOP THROUGH THE FILE*/
    var i = 0;
    while(lines[i] != null)
    {
      var year = parseInt(lines[i].substring(0,4));
      var month = parseInt(lines[i].substring(4,6));
      var day = parseInt(lines[i].substring(6,8));

      if(year == yyyy && month == mm && day == dd)
      {
        found = 1;
        //alert("Found closest date: " + lines[i]);
        current = "/pictures/" + lines[i];

        //update the picture text
        document.getElementById("imageText").innerHTML = "Lastest Sunset: " + month + "/" + day + "/" + year;

        //alert(current);
      }


      i++;
    }

    /*GO ONE DAY IN THE PAST*/
    dd = dd - 1;
    if(dd == 0)
    {

      mm = mm - 1;
      if(mm == 0)
      {
        mm = 12;
        yyyy = yyyy - 1;
      }


      if(mm == 04 || mm == 06 || mm == 09 || mm == 11)
      {
        dd = 30;
      }
      else if(mm == 02)
      {
        dd = 28;
        //check for leap year??
      }
      else {
        dd = 31;
      }

    }

  }
  image = document.getElementById('image');
  image.src = current;

}

</script>



<div class="container">
<img id="image" src=" " width="650px" height="500px" class="center"/>
<p id="imageText"></p>
</div>



</body>

</html>
