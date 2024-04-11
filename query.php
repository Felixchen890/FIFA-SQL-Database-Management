<!DOCTYPE html>
<html>
  <head>
    <title>Manipulate Table</title>
    <style>
      body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
        background-image: url('football.png');
        background-repeat: no-repeat;
        background-size: cover;
        color: white;
      }
      
      h1 {
        text-align: center;
        margin-top: 50px;
      }
      
      form {
        text-align: center;
        margin-top: 50px;
      }
      
      input[type=text] {
        padding: 10px;
        border: none;
        border-radius: 3px;
        margin-right: 10px;
      }
      
      button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
      }
      
      table {
        border-collapse: collapse;
        margin: auto;
      }
      
      th, td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
      }
      
      th {
        background-color: #4CAF50;
        color: white;
      }

      #back-button {
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
      }

      input[type=submit] {
        background-color: #4CAF50; /* Green */
        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 10px;
      }

      input[type="text"] {
        padding: 10px;
        border: 2px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
        font-family: Arial, sans-serif;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
      }

      div {
        font-size: 20px;
        font-family: Georgia, serif;
        color: white;
        line-height: 1.5;
        text-align: center;
      }

      .select-wrapper {
        position: relative;
        display: inline-block;
        font-size: 16px;
        line-height: 1.3;
        width: 400px;
        height: 40px;
        background: #fff;
        border: 2px solid #ccc;
        border-radius: 4px;
        overflow: hidden;
      }

      .select-wrapper select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        cursor: pointer;
        display: block;
        width: 100%;
        height: 100%;
        padding: 10px;
        font-size: 16px;
        line-height: 1.3;
        border: none;
        background: transparent;
      }

      .select-wrapper select:focus {
        outline: none;
      }

      .select-wrapper::after {
        content: '\25BC';
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        font-size: 24px;
        line-height: 1;
        color: #999;
      }


    </style>
    <script>
      function displayTable() {
        // get the input value
        var inputValue = document.getElementById("input").value;

        // create the table HTML
        var tableHTML = "<table>";
        tableHTML += "<tr><th>Value</th><th>Number</th></tr>";
        for (var i = 1; i <= 10; i++) {
          tableHTML += "<tr><td>" + inputValue + "</td><td>" + i + "</td></tr>";
        }
        tableHTML += "</table>";

        // display the table
        document.getElementById("output").innerHTML = tableHTML;
      }

      function goBack() {
        window.location.href = "index.php";
      }
    </script>
  </head>
  <body>
    <button id="back-button" onclick="goBack()">Back</button>
    
    <h2>Select players such that BMI greater than certain value from PlayerInfo table</h2>
<form method="GET" action="query.php"> <!--refresh page when submitted-->
    <input type="hidden" id="selectPlayerRequest" name="selectPlayerRequest">
    <div>
    BMI: <input type="text" name="BMINo" placeholder='e.g. 1'> <br /><br />
    </div>
    <input type="submit" value="Select" name="selectSubmit"></p>
</form>

<hr />

<h2>Project Player name and target attribute from PlayerInfo table</h2>
<form method="GET" action="query.php"> <!--refresh page when submitted-->
    <input type="hidden" id="projectPlayerAttributeRequest" name="projectPlayerAttributeRequest">
    <div>
    Attributes: 
    </div>
    <div class = "select-wrapper">
    <select name="attribute" id="attribute">
            <option value="0" selected="selected">--Please choose an attribute--</option>
            <option value="playerID">PlayerID</option>
            <option value="BMI">BMI</option>
            <option value="teamID">Team ID</option>
        </select>
        <br><br>
    </div>
    <br /><br />
    <input type="submit" value="Project" name="projectSubmit"></p>
</form>

<hr />

<h2>Join Team & TitleWin</h2>
    <form method="GET" action="query.php">
        <input type="hidden" id="joinRequest" name="joinRequest">
        <div>
        Find the Title Won by Team Name: <input type="text" name="teamName" placeholder='e.g. Barcelona'> <br /><br />
        </div>
        <input type="submit" value="Filter" name="submitJoinQuery">
    </form>

<hr />

<h2>Aggregation with Group By Team ID</h2>
        <form method="GET" action="query.php">
            <input type="hidden" id="aggregationWithGroupByRequest" name="aggregationWithGroupByRequest">
            <div>
            Find the Average BMI of Team ID: <input type="text" name="teamIDAggregation" placeholder='e.g. 1'> <br /><br />
            </div>
            <input type="submit" value="Filter" name="submitAggregationWithGroupByQuery">
        </form>

<hr />

<h2>Nested Aggregation with Group By Team Name</h2>
  <form method="GET" action="query.php">
      <input type="hidden" id="aggregationNestedRequest" name="aggregationNestedRequest">
      <div>
      Find the Average BMI group by team name that won at least one title <br /><br />
      </div>
      <input type="submit" value="Filter" name="submitAggregationNestedQuery">
  </form>

<hr />

<h2>Count the Tuples in Team table</h2>
<form method="GET" action="query.php"> <!--refresh page when submitted-->
    <input type="hidden" id="countTupleRequest" name="countTupleRequest">
    <input type="submit" value="Submit" name="countTuples"></p>
</form>

<hr />

<h2>Teams' average BMI grouped by name of Team having lowest BMI of group is higher than 13 (Aggregation with HAVING)</h2>
<form method="GET" action="query.php"> <!--refresh page when submitted-->
    <input type="hidden" id="groupByBMIRequest" name="groupByBMIRequest">
    <input type="submit" value="Submit" name="groupByBMI"></p>
</form>

<hr />

<h2>Division query: find a team that has won all title in a single year</h2>
<h4>Note: if there is no title for the given year, then all teams will be displayed</h4>
        <form method="GET" action="query.php">
            <input type="hidden" id="divisionRequest" name="divisionRequest">
            <div>
            Find the team that won all titles of a single year: <input type="text" name="year" placeholder='e.g. 2018'> <br /><br />
            </div>
            <input type="submit" value="Submit" name="division">
        </form>

<hr />

<form>

    <div id="output"></div>
</form>

<?php
// helper function
include("init.php")
?>

</body>
</html>