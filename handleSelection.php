<!DOCTYPE html>
<html>
  <head>
    <title>Selection</title>
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
    margin-top: 10px;
    margin-bottom: 10px;
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

    </style>
    <script>
      function goBack() {
        window.location.href = "displayTable.php";
      }
    </script>
  </head>
  <body>
  <button id="back-button" onclick="goBack()">Back</button>


<?php
// Check if form has been submitted
if(isset($_POST['selection_button'])) {
   // Get the checkbox value
   $checkbox_value = $_POST['attributes'];
   // Store the checkbox value in a variable or array
   // For example, if you want to store in an array:
   $selected_checkbox = array();
   foreach($checkbox_value as $value) {
      $selected_checkbox[] = $value;
   }
}
$checkbox_values = array('PlayerID', 'BMI', 'TeamId', 'PlayerName');
?>

<form method="POST">
<h1>Select attributes in PlayerInfo Table:</h1><br>
   <?php foreach($checkbox_values as $value) { ?>
      <input type="checkbox" name="attributes[]" value="<?php echo $value; ?>" 
      <?php if(isset($selected_checkbox) && in_array($value, $selected_checkbox)) echo 'checked'; ?>>
      <?php echo $value; ?><br>
   <?php } ?>
   <input type="submit" name="selection_button" value="Submit">

   <?php
  if(isset($_POST["attributes"])){
    $attributes_selected = $_POST['attributes'];
    echo "<br />Player's <br />";
    foreach($attributes_selected as $attr){
        if($attr == 'BMI'){
            echo "BMI to be <input type='text' name='GLEOp' placeholder='e.g. >, <, ='><input type='text' name='BMInum' placeholder='e.g. 1'> <br />";
        }else if($attr == 'PlayerID'){
            echo "ID as <input type='text' name='selectionPID' placeholder='e.g. 1'><br />";
        }else if($attr == 'PlayerName'){
            echo "Name as <input type='text' name='selectionPN' placeholder='e.g. Neymar'><br />";
        }else if($attr == 'TeamId'){
            echo "In team <input type='text' name='selectionTID' placeholder='e.g. 1'><br />";
        }
    }
    echo "<input type='submit' name='selection_result' value='Submit'>";
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

