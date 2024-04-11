<!DOCTYPE html>
<html>
  <head>
    <title>Display Table</title>
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
        text-align: center;
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

      .button-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 20px;
        margin-bottom: 20px;
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
    
    <h1>Display the Tuples in Team table</h1>
    <form method="GET" action="displayTable.php"> <!--refresh page when submitted-->
    <input type="hidden" id="displayTeamTupleRequest" name="displayTeamTupleRequest">
    <input type="submit" value="Submit" name="displayTeamTuples"></p>
    </form>

    <h1>Display the Tuples in PlayerInfo table</h1>
    <form method="GET" action="displayTable.php"> <!--refresh page when submitted-->
    <input type="hidden" id="displayPlayerInfoTupleRequest" name="displayPlayerInfoTupleRequest">
    <input type="submit" value="Submit" name="displayPlayerInfoTuples"></p>
    </form>

    <h1>Display the Tuples in TitleWin table</h1>
    <form method="GET" action="displayTable.php"> <!--refresh page when submitted-->
    <input type="hidden" id="displayTitleWinTupleRequest" name="displayTitleWinTupleRequest">
    <input type="submit" value="Submit" name="displayTitleWinTuples"></p>
    </form>

    <h1>Display the Tuples in PlayerRole table</h1>
    <form method="GET" action="displayTable.php"> <!--refresh page when submitted-->
    <input type="hidden" id="displayPlayerRoleTupleRequest" name="displayPlayerRoleTupleRequest">
    <input type="submit" value="Submit" name="displayPlayerRoleTuples"></p>
    </form>



    <hr />
    <h1>Projection</h1>
    <div class="button-container">
    <button onclick="location.href='handleProjection.php'">Projection</button>
    </div>

    <h1>Selection</h1>
    <div class="button-container">
    <button onclick="location.href='handleSelection.php'">Selection</button>
    </div>
    <hr />

    <h2>Reset the entire Database</h2>
    <form method="POST" action="displayTable.php">
    <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
    <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
    <p><input type="submit" value="Reset" name="reset"></p>
    </form>
    <hr />

</form>

    <div id="output"></div>
</form>

<?php
// helper function
include("init.php")
?>

</body>
</html>
