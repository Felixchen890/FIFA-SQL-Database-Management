<!DOCTYPE html>
<html>
  <head>
    <title>Select table to manipulate</title>
    <style>
      body {
        font-family: Arial, Helvetica, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-image: url('football.png');
        background-repeat: no-repeat;
        background-size: cover;
        color: white;
        font-size: 18px;
      }

      .button-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-template-rows: repeat(2, 1fr);
        gap: 10px;
      }

      button {
        width: 200px;
        height: 50px;
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        margin: 10px;
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
    <h1>Please select the table to modify</h1>
    <div class="button-container">
      <button onclick="location.href='TeamModify.php'">Team</button>
      <button onclick="location.href='TitleModify.php'">Team Title</button>
      <button onclick="location.href='PlayerInfoModify.php'">Player Information</button>
      <button onclick="location.href='PlayerRoleModify.php'">Player Role</button>
    </div>
  </body>
</html>