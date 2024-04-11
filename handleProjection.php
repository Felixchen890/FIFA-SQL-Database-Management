<!DOCTYPE html>
<html>
  <head>
    <title>Projection</title>
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
    margin-left: 10px;
    margin-right: 10px;
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

    .radio-group {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .radio-group label {
      display: flex;
      align-items: center;
      margin-bottom: 10px;
      cursor: pointer;
      color: white;
    }

    .radio-group input[type="radio"] {
      appearance: none;
      -webkit-appearance: none;
      width: 20px;
      height: 20px;
      border: 2px solid #ccc;
      border-radius: 50%;
      margin-right: 10px;
      cursor: pointer;
    }

    .radio-group input[type="radio"]:checked {
      background-color: #0066cc;
      border-color: #0066cc;
    }

    .radio-group .radio-label {
      font-size: 16px;
      color: #333;
      color: white;
    }

    </style>
    <script>
      function goBack() {
        window.location.href = "displayTable.php";
      }
    </script>
  </head>
  <body>
  <button id="back-button" onclick="goBack()">Back</button>

<!-- Define the form with the table and attribute checkboxes and submit button -->
<form method="post" action="handleProjection.php">
  <h1>Select table:</h1><br>
  <div class="radio-group">
  <label>
    <input type="radio" name="table" value="Team" <?php if (isset($_POST['table']) && $_POST['table'] == 'Team') echo 'checked="checked"'; ?>>
    <span class="radio-label">Team</span>
  </label>
  <label>
    <input type="radio" name="table" value="PlayerInfo" <?php if (isset($_POST['table']) && $_POST['table'] == 'PlayerInfo') echo 'checked="checked"'; ?>>
    <span class="radio-label">PlayerInfo</span>
  </label>
  <label>
    <input type="radio" name="table" value="PlayerRole" <?php if (isset($_POST['table']) && $_POST['table'] == 'PlayerRole') echo 'checked="checked"'; ?>>
    <span class="radio-label">PlayerRole</span>
  </label>
  <label>
    <input type="radio" name="table" value="TitleWin" <?php if (isset($_POST['table']) && $_POST['table'] == 'TitleWin') echo 'checked="checked"'; ?>>
    <span class="radio-label">TitleWin</span>
  </label>
</div>
  <br>
  <input type="submit" name="projection_button" value="Submit">
  <?php
    // Define the table attribute arrays
    $table_attributes = array(
      'Team' => array('TeamId', 'TeamName'),
      'PlayerInfo' => array('PlayerID', 'BMI', 'TeamId', 'name'),
      'PlayerRole' => array('name', 'role'),
      'TitleWin' => array('titleName', 'year', 'TeamId')
    );
    
    // Loop through the attributes for the selected table and display checkboxes
    if(isset($_POST['table'])) {
      $table = $_POST['table'];
      $attributes = $table_attributes[$table];

      echo "<h1>You have selected: $table</h1><br>";
      echo "<h1>Select attributes:</h1><br>";
      
      foreach($attributes as $attribute) {
        echo "<input type='checkbox' name='attributes[]' value='$attribute'>$attribute<br>";
      }
      echo "<input type='submit' name='projection_result' value='Submit'>";
    }
  ?>
</form>


<form>
    <div id="output"></div>
</form>

<?php
include("init.php")
?>

</body>
</html>

