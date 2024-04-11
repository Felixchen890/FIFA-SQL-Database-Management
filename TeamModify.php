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

      div {
        font-size: 20px;
        font-family: Georgia, serif;
        color: white;
        line-height: 1.5;
        text-align: center;
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
        window.location.href = "choice.php";
      }
    </script>
  </head>
  <body>
    <button id="back-button" onclick="goBack()">Back</button>
    
    <h2>Insert New Teams into Team table</h2>
    <form method="POST" action="TeamModify.php"> <!--refresh page when submitted-->
    <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
    <div>
    Team Id: <input type="text" name="teamNo" placeholder="e.g. 5"> <br /><br />
    Team Name: <input type="text" name="teamName" placeholder="e.g. Barcelona"> <br /><br />
    </div>

    <input type="submit" value="Insert" name="insertSubmit"></p>
    </form>

<hr />

    <h2>Delete existing Teams from Team table</h2>
    <form method="POST" action="TeamModify.php"> <!--refresh page when submitted-->
    <input type="hidden" id="deleteQueryRequest" name="deleteQueryRequest">
    <div>
    Team Id: <input type="text" name="teamNo" placeholder="e.g. 5"> <br /><br />
    <input type="submit" value="Delete" name="deleteSubmit"></p>
    </div>
    </form>

<hr />

    <h2>Update Name in Team table</h2>
    <p>The values are case sensitive and if you enter in the wrong case, the update statement will not do anything.</p>

    <form method="POST" action="TeamModify.php"> <!--refresh page when submitted-->
    <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
    <div>
    ID of Team: <input type="text" name="targetID" placeholder="e.g. 5"> <br /><br />
    New Name: <input type="text" name="newName" placeholder="e.g. Barcelona"> <br /><br />
    </div>

    <input type="submit" value="Update" name="updateSubmit"></p>
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