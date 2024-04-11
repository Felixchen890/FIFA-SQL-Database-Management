<!--Test Oracle file for UBC CPSC304 2018 Winter Term 1
  Created by Jiemin Zhang
  Modified by Simona Radu
  Modified by Jessica Wong (2018-06-22)
  This file shows the very basics of how to execute PHP commands
  on Oracle.
  Specifically, it will drop a table, create a table, insert values
  update values, and then query for values

  IF YOU HAVE A TABLE CALLED "demoTable" IT WILL BE DESTROYED
  The script assumes you already have a server set up
  All OCI commands are commands to the Oracle libraries
  To get the file to work, you must place it somewhere where your
  Apache server can run it, and you must rename it to have a ".php"
  extension.  You must also change the username and password on the
  OCILogon below to be your ORACLE username and password -->

  <html>
<head>
    <title>CPSC 304 PHP/Oracle Demonstration</title>
</head>

<body>

<?php

// error_reporting(E_ALL);
// ini_set('display_errors', '1');
//this tells the system that it's no longer just parsing html; it's now parsing PHP

$success = True; //keep track of errors so it redirects the page only if there are no errors
$db_conn = NULL; // edit the login credentials in connectToDB()
$show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())
$chosen = NULL;

function debugAlertMessage($message) {
    global $show_debug_alert_messages;

    if ($show_debug_alert_messages) {
        echo "<script type='text/javascript'>alert('" . $message . "');</script>";
    }
}

function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
    //echo "<br>running ".$cmdstr."<br>";
    global $db_conn, $success;

    $statement = OCIParse($db_conn, $cmdstr);
    //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
        echo htmlentities($e['message']);
        $success = False;
        echo "<h2>Please try enter again.</h2>";
        echo "<h2>Be aware of duplication or invalid format!</h2>";
    }

    $r = OCIExecute($statement, OCI_DEFAULT);
    if (!$r) {
        echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
        $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
        echo htmlentities($e['message']);
        echo "<h2>Please try enter again.</h2>";
        echo "<h2>Be aware of duplication or invalid format!</h2>";
        $success = False;
    } 
    return $statement;
}

function executeBoundSQL($cmdstr, $list) {
    /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
In this case you don't need to create the statement several times. Bound variables cause a statement to only be
parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
See the sample code below for how this function is used */

    global $db_conn, $success;
    $statement = OCIParse($db_conn, $cmdstr);

    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn);
        echo htmlentities($e['message']);
        echo "<h2>Please try enter again.</h2>";
        echo "<h2>Be aware of duplication or invalid format!</h2>";
        $success = False;
    }

    foreach ($list as $tuple) {
        foreach ($tuple as $bind => $val) {
            //echo $val;
            //echo "<br>".$bind."<br>";
            OCIBindByName($statement, $bind, $val);
            unset ($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
        }

        $r = OCIExecute($statement, OCI_DEFAULT);
        if (!$r) {
            echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
            $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
            echo htmlentities($e['message']);
            echo "<br>";
            echo "<h2>Please try enter again.</h2>";
            echo "<h2>Be aware of duplication or invalid format!</h2>";
            $success = False;
        } 
    }
}

function printResult($result) { //prints results from a select statement
    echo "<br>Retrieved data from table demoTable:<br>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row["ID"] . "</td><td>" . $row["NAME"] . "</td></tr>"; //or just use "echo $row[0]"
    }

    echo "</table>";
}

function connectToDB() {
    global $db_conn;

    // Your username is ora_(CWL_ID) and the password is a(student number). For example,
    // ora_platypus is the username and a12345678 is the password.
    $db_conn = OCILogon("ora_xry2002", "a85873248", "dbhost.students.cs.ubc.ca:1522/stu");

    if ($db_conn) {
        debugAlertMessage("Database is Connected");
        return true;
    } else {
        debugAlertMessage("Cannot connect to Database");
        $e = OCI_Error(); // For OCILogon errors pass no handle
        echo htmlentities($e['message']);
        return false;
    }
}

function disconnectFromDB() {
    global $db_conn;

    debugAlertMessage("Disconnect from Database");
    OCILogoff($db_conn);
}

// Define a function to sanitize user input
function sanitize($input) {
    $input = trim($input); // Remove leading and trailing whitespace
    $input = stripslashes($input); // Remove backslashes
    $input = str_replace('\\', '', $input);
    $input = str_replace('//', '', $input);
    $input = str_replace('/', '', $input);
    $input = str_replace(';', '', $input);
    // $input = str_replace('-', '', $input); // might turn -15 into 15, so remove this row 
    $input = str_replace(',', '', $input);
    $input = str_replace('[', '', $input);
    $input = str_replace(']', '', $input);
    $input = str_replace('{', '', $input);
    $input = str_replace('}', '', $input);
    $input = str_replace('`', '', $input);
    $input = str_replace('~', '', $input);
    return $input;
}

function handleUpdateRequest() {
    global $db_conn;

    $team_id = sanitize($_POST['targetID']);
    $new_name = sanitize($_POST['newName']);

    // you need the wrap the old name and new name values with single quotations
    executePlainSQL("UPDATE Team SET TeamName='" . $new_name . "' WHERE TeamId= '". $team_id ."'");
    $result = executePlainSQL("SELECT * FROM Team");
    displayTeam($result);
    OCICommit($db_conn);
}

function handleUpdatePIRequest() {
    global $db_conn;

    $player_id = sanitize($_POST['pID']);
    $new_pname = sanitize($_POST['newPlayerInfoName']);

    // you need the wrap the old name and new name values with single quotations
    executePlainSQL("UPDATE PlayerInfo SET name='" . $new_pname . "' WHERE PlayerID= '". $player_id ."'");
    $result = executePlainSQL("SELECT * FROM PlayerInfo");
    displayPlayerInfo($result);
    OCICommit($db_conn);
}

function handleResetRequest() {
    global $db_conn;
    // Drop old table
    // executePlainSQL("DROP TABLE demoTable");
    executePlainSQL("DROP TABLE PlayerInfo");
    executePlainSQL("DROP TABLE PlayerRole");
    executePlainSQL("DROP TABLE TitleWin");
    executePlainSQL("DROP TABLE Team");
    //executePlainSQL("DROP TABLE Sponsorship");

    // Create new table
    $font_size = "50px";
    echo "<p style='font-size: $font_size; text-align: center;'>Reset Successful!</p>";
    // executePlainSQL("CREATE TABLE demoTable (id int PRIMARY KEY, name char(30))");

    // Team table
    executePlainSQL("CREATE TABLE Team(
        TeamId int PRIMARY KEY,
        TeamName char(50)
        )");

    // TitleWin table
    executePlainSQL("CREATE TABLE TitleWin(
        titleName char(50),
        year int,
        TeamId int NOT NULL,
        PRIMARY KEY (titleName, year),
        FOREIGN KEY(TeamId) REFERENCES Team(TeamId) ON DELETE CASCADE
        )");

    // PlayerRole table
    executePlainSQL("CREATE TABLE PlayerRole(
        name char(50) PRIMARY KEY,
        role char(50)
        )");

    // PlayerInfo table
    executePlainSQL("CREATE TABLE PlayerInfo(
        PlayerID int PRIMARY KEY,
        BMI int,
        TeamId int,
        name char(50),
        FOREIGN KEY(TeamId) REFERENCES Team(TeamId) ON DELETE CASCADE
        )");

    // Sponsorship table
    // executePlainSQL("CREATE TABLE Sponsorship(
    //     sponsorID int PRIMARY KEY,
    //     amount int
    //     )");

    executePlainSQL("INSERT INTO Team VALUES (1, 'Real Madrid')");
    executePlainSQL("INSERT INTO Team VALUES (2, 'Barcelona')");
    executePlainSQL("INSERT INTO Team VALUES (3, 'Manchester United')");
    executePlainSQL("INSERT INTO Team VALUES (4, 'Brazil')");
    executePlainSQL("INSERT INTO Team VALUES (5, 'Spain')");
    executePlainSQL("INSERT INTO Team VALUES (6, 'Japan')");
    
    executePlainSQL("INSERT INTO PlayerInfo VALUES (1, 15, 1, 'Karim Benzema')");
    executePlainSQL("INSERT INTO PlayerInfo VALUES (2, 16, 1, 'Vini Jr.')");
    executePlainSQL("INSERT INTO PlayerInfo VALUES (3, 21, 1, 'Luka Modric')");
    executePlainSQL("INSERT INTO PlayerInfo VALUES (4, 18, 1, 'Toni Kroos')");
    executePlainSQL("INSERT INTO PlayerInfo VALUES (5, 19, 1, 'Thibaut Courtois')");

    executePlainSQL("INSERT INTO PlayerInfo VALUES (6, 20, 2, 'Robert Lewandowski')");
    executePlainSQL("INSERT INTO PlayerInfo VALUES (7, 19, 2, 'Raphinha')");
    executePlainSQL("INSERT INTO PlayerInfo VALUES (8, 18, 2, 'Pedri')");
    executePlainSQL("INSERT INTO PlayerInfo VALUES (9, 17, 2, 'Gavi')");
    executePlainSQL("INSERT INTO PlayerInfo VALUES (10, 13, 2, 'Marc-Andre ter Stegen')");

    executePlainSQL("INSERT INTO PlayerInfo VALUES (11, 15, 3, 'Marcus Rashford')");
    executePlainSQL("INSERT INTO PlayerInfo VALUES (12, 11, 3, 'Antony')");
    executePlainSQL("INSERT INTO PlayerInfo VALUES (13, 13, 3, 'Casemiro')");
    executePlainSQL("INSERT INTO PlayerInfo VALUES (14, 12, 3, 'Marcel Sabitzer')");
    executePlainSQL("INSERT INTO PlayerInfo VALUES (15, 11, 3, 'David de Gea')");

    executePlainSQL("INSERT INTO PlayerInfo VALUES (16, 12, 4, 'Neymar')");
    executePlainSQL("INSERT INTO PlayerInfo VALUES (17, 13, 4, 'Richarlison')");
    executePlainSQL("INSERT INTO PlayerInfo VALUES (18, 14, 4, 'Lucas Paqueta')");
    executePlainSQL("INSERT INTO PlayerInfo VALUES (19, 21, 4, 'Bruno Guimaraes')");
    executePlainSQL("INSERT INTO PlayerInfo VALUES (20, 16, 4, 'Alisson Becker')");

    executePlainSQL("INSERT INTO PlayerInfo VALUES (21, 15, 5, 'Alvaro Morata')");
    executePlainSQL("INSERT INTO PlayerInfo VALUES (22, 14, 5, 'Ansu Fati')");
    executePlainSQL("INSERT INTO PlayerInfo VALUES (23, 23, 5, 'Gavi')");
    executePlainSQL("INSERT INTO PlayerInfo VALUES (24, 21, 5, 'Pedri')");
    executePlainSQL("INSERT INTO PlayerInfo VALUES (25, 19, 5, 'Unai Simon')");

    executePlainSQL("INSERT INTO PlayerRole VALUES ('Karim Benzema', 'Forward')");
    executePlainSQL("INSERT INTO PlayerRole VALUES ('Vini Jr.', 'Forward')");
    executePlainSQL("INSERT INTO PlayerRole VALUES ('Luka Modric', 'Midfielder')");
    executePlainSQL("INSERT INTO PlayerRole VALUES ('Toni Kroos', 'Midfielder')");
    executePlainSQL("INSERT INTO PlayerRole VALUES ('Thibaut Courtois', 'GoalKeeper')");

    executePlainSQL("INSERT INTO PlayerRole VALUES ('Robert Lewandowski', 'Forward')");
    executePlainSQL("INSERT INTO PlayerRole VALUES ('Raphinha', 'Forward')");
    executePlainSQL("INSERT INTO PlayerRole VALUES ('Pedri', 'Midfielder')");
    executePlainSQL("INSERT INTO PlayerRole VALUES ('Gavi', 'Midfielder')");
    executePlainSQL("INSERT INTO PlayerRole VALUES ('Marc-Andre ter Stegen', 'GoalKeeper')");

    executePlainSQL("INSERT INTO PlayerRole VALUES ('Marcus Rashford', 'Forward')");
    executePlainSQL("INSERT INTO PlayerRole VALUES ('Antony', 'Forward')");
    executePlainSQL("INSERT INTO PlayerRole VALUES ('Casemiro', 'Midfielder')");
    executePlainSQL("INSERT INTO PlayerRole VALUES ('Marcel Sabitzer', 'Midfielder')");
    executePlainSQL("INSERT INTO PlayerRole VALUES ('David de Gea', 'GoalKeeper')");

    executePlainSQL("INSERT INTO PlayerRole VALUES ('Neymar', 'Forward')");
    executePlainSQL("INSERT INTO PlayerRole VALUES ('Richarlison', 'Forward')");
    executePlainSQL("INSERT INTO PlayerRole VALUES ('Lucas Paqueta', 'Midfielder')");
    executePlainSQL("INSERT INTO PlayerRole VALUES ('Bruno Guimaraes', 'Midfielder')");
    executePlainSQL("INSERT INTO PlayerRole VALUES ('Alisson Becker', 'GoalKeeper')");

    executePlainSQL("INSERT INTO PlayerRole VALUES ('Alvaro Morata', 'Forward')");
    executePlainSQL("INSERT INTO PlayerRole VALUES ('Ansu Fati', 'Forward')");
    executePlainSQL("INSERT INTO PlayerRole VALUES ('Unai Simon', 'GoalKeeper')");

    executePlainSQL("INSERT INTO TitleWin VALUES ('UEFA Champions League', 2018, 1)");
    executePlainSQL("INSERT INTO TitleWin VALUES ('UEFA Champions League', 2017, 1)");
    executePlainSQL("INSERT INTO TitleWin VALUES ('UEFA Champions League', 2022, 1)");
    executePlainSQL("INSERT INTO TitleWin VALUES ('Laliga', 1961, 1)");

    executePlainSQL("INSERT INTO TitleWin VALUES ('UEFA Champions League', 2015, 2)");
    executePlainSQL("INSERT INTO TitleWin VALUES ('Laliga', 1959, 2)");

    executePlainSQL("INSERT INTO TitleWin VALUES ('UEFA Champions League', 2008, 3)");
    executePlainSQL("INSERT INTO TitleWin VALUES ('Premier League', 2002, 3)");

    executePlainSQL("INSERT INTO TitleWin VALUES ('World Cup Champion', 2002, 4)");
    executePlainSQL("INSERT INTO TitleWin VALUES ('World Cup Champion', 1994, 4)");

    executePlainSQL("INSERT INTO TitleWin VALUES ('World Cup Champion', 2010, 5)");
    executePlainSQL("INSERT INTO TitleWin VALUES ('European Championship', 2012, 5)");

    OCICommit($db_conn);
}

function handleInsertRequest() {
    global $db_conn;

    //Getting the values from user and insert data into the table
    $tuple = array (
        ":bind1" => sanitize($_POST['teamNo']),
        ":bind2" => sanitize($_POST['teamName'])
    );

    $alltuples = array (
        $tuple
    );

    executeBoundSQL("insert into Team values (:bind1, :bind2)", $alltuples);
    $font_size = "50px";
    if ($success) {
        echo "<p style='font-size: $font_size; text-align: center;'>Insert Successful!</p>";
    }
    $result = executePlainSQL("SELECT * FROM Team");
    displayTeam($result);
    OCICommit($db_conn);
}

function handleInsertPIRequest() {
    global $db_conn;

    //Getting the values from user and insert data into the table
    $tuple = array (
        ":bind1" => sanitize($_POST['playerNo']),
        ":bind2" => sanitize($_POST['BMI']),
        ":bind3" => sanitize($_POST['teamID']),
        ":bind4" => sanitize($_POST['playerName'])
    );

    $alltuples = array (
        $tuple
    );

    executeBoundSQL("insert into PlayerInfo values (:bind1, :bind2, :bind3, :bind4)", $alltuples);
    $font_size = "50px";
    if ($success) {
        echo "<p style='font-size: $font_size; text-align: center;'>Insert Successful!</p>";
    }
    $result = executePlainSQL("SELECT * FROM PlayerInfo");
    displayPlayerInfo($result);
    OCICommit($db_conn);
}

function handleDeleteRequest() {
    global $db_conn;

    //Getting the values from user and insert data into the table
    $tuple = array (
        ":bind1" => sanitize($_POST['teamNo'])
    );

    $alltuples = array (
        $tuple
    );

    executeBoundSQL("DELETE FROM Team WHERE TeamId = (:bind1)", $alltuples);
    $font_size = "50px";
    if ($success) {
        echo "<p style='font-size: $font_size; text-align: center;'>Delete Successful!</p>";
    }
    $result = executePlainSQL("SELECT * FROM Team");
    displayTeam($result);
    OCICommit($db_conn); // used for saving changes in the database
}

function handleDeletePIRequest() {
    global $db_conn;

    //Getting the values from user and insert data into the table
    $tuple = array (
        ":bind1" => sanitize($_POST['playerID'])
    );

    $alltuples = array (
        $tuple
    );

    executeBoundSQL("DELETE FROM PlayerInfo WHERE PlayerID = (:bind1)", $alltuples);
    $font_size = "50px";
    if ($success) {
        echo "<p style='font-size: $font_size; text-align: center;'>Delete Successful!</p>";
    }
    $result = executePlainSQL("SELECT * FROM PlayerInfo");
    displayPlayerInfo($result);
    OCICommit($db_conn); // used for saving changes in the database
}

function handleCountRequest() {
    global $db_conn;

    $result = executePlainSQL("SELECT Count(*) FROM Team");

    if (($row = oci_fetch_row($result)) != false) {
        $font_size = "50px";
        echo "<p style='font-size: $font_size; text-align: center;'>The number of tuples in team Table: $row[0]</p>";
    }
}


function handleDisplayTeamTuplesRequest() {
    global $db_conn;

    $result = executePlainSQL("SELECT * FROM Team");
    displayTeam($result);
}

function handleDisplayPlayerInfoTuplesRequest() {
    global $db_conn;

    $result = executePlainSQL("SELECT * FROM PlayerInfo");
    displayPlayerInfo($result);
}

function handleDisplayTitleWinRequest() {
    global $db_conn;
    $result = executePlainSQL("SELECT * FROM TitleWin");
    displayTitleWin($result);
}

function handleDisplayPlayerRoleRequest() {
    global $db_conn;
    $result = executePlainSQL("SELECT * FROM PlayerRole");
    displayPlayerRole($result);
}

function handleSelectionPlayer() {
    global $db_conn;

    $BMI = sanitize($_GET['BMINo']);

    $result = executePlainSQL("SELECT * FROM PlayerInfo WHERE BMI > $BMI");
    displayPlayerInfo($result);
}

function handleProjectionPlayer() {
    global $db_conn;

    if (!empty($_GET['attribute'])) {
        $font_size = "50px";
        echo "<p style='font-size: $font_size; text-align: center;'>Projection Result</p>";
        $attribute = sanitize($_GET['attribute']);
        $playerID = "PlayerID";
        $result = executePlainSQL("SELECT name, ". $attribute ." FROM PlayerInfo");
        displayPlayerInfoColumn($result, $attribute);
    }
}


function handleJoinRequest() {
    global $db_conn;
    $team_name = sanitize($_GET['teamName']);
    $font_size = "50px";
    echo "<p style='font-size: $font_size; text-align: center;'>Showing titles won by: $team_name</p>";
    $result = executePlainSQL("SELECT * FROM Team t, TitleWin tw WHERE t.TeamId = tw.TeamId AND t.TeamName = '$team_name'");

    //printPlayerResult($result);
    
    // only querying data from the tables and not modifying them. 
    //     no need to call OCICommit($db_conn) after a join query.
    displayJoinResult($result);

    //displayTeam($result)
}

// display join result table
function displayJoinResult($result) {
    echo "<table>";
    echo "<tr><th>TeamId</th><th>TeamName</th><th>titleName</th><th>year</th>";

    while ($row = oci_fetch_array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] .  "</td></tr>";
    }

    echo "</table>";
}

function handleGroupByRequest() {
    global $db_conn;
    $team_ID_GroupBy = sanitize($_GET['teamIDAggregation']);

    $font_size = "50px";
    echo "<p style='font-size: $font_size; text-align: center;'>Selected team ID: $team_ID_GroupBy</p>";

    // ? select all into result and choose what to display in the display helper function ?
    // group by WHO's TeamId ?  Team OR PlayerInfo ?
    $result = executePlainSQL("SELECT pi.TeamId, AVG(pi.BMI) FROM Team t, PlayerInfo pi WHERE pi.BMI IS NOT NULL AND t.TeamId = pi.TeamId AND pi.TeamId = '$team_ID_GroupBy' GROUP BY pi.TeamId");

    // displayJoinResult($result);
    // display helper function:
    displayGroupByResult($result);
}

function handleNestedAggregationRequest() {
    global $db_conn;
    $font_size = "50px";
    echo "<p style='font-size: $font_size; text-align: center;'>Result</p>";
    $result = executePlainSQL("SELECT DISTINCT t1.TeamName, AVG(BMI) FROM Team t1, PlayerInfo pi WHERE t1.TeamId = pi.TeamId AND t1.TeamId IN (SELECT t2.TeamId FROM Team t2, TitleWin ti WHERE t2.TeamId = ti.TeamId) GROUP BY t1.TeamName");
    displayNestedAggregationResult($result);
}

function displayDivisionResult($result) {
    echo "<table>";
    echo "<tr><th>Team Name</th>";
    while ($row = oci_fetch_array($result, OCI_BOTH)) { 
        echo "<tr><td>" . $row[0] . "</td></tr>";
    }
    echo "</table>";
}

// function for query C: display average BMI for all teams, group by team number
function handleGroupByAVGBMIRequest() {
    global $db_conn;

    $font_size = "50px";

    // execute the query B on note
    $result = executePlainSQL("SELECT pi.TeamId, AVG(pi.BMI) FROM PlayerInfo pi, PlayerRole pr WHERE pi.BMI IS NOT NULL AND pi.name = pr.name GROUP BY pi.TeamId HAVING MIN(pi.BMI) > 13");

    // displayJoinResult($result);
    // display helper function:
    displayGroupByResult($result);
}

// function for query D: find teams that win all titles in 2018
function handleFindTitleKillerRequest() {
    // display team name
    global $db_conn;
    $year = sanitize($_GET['year']);

    // execute query
    $result = executePlainSQL("SELECT DISTINCT t.TeamName 
    FROM Team t 
    WHERE NOT EXISTS ((SELECT ti.titleName 
                        FROM TitleWin ti 
                        WHERE ti.year = '$year') 
                        MINUS 
                        (SELECT ti2.titleName 
                        FROM TitleWin ti2
                        WHERE t.TeamId = ti2.TeamId AND ti2.year = '$year'))");

    // displayJoinResult($result);
    // display helper function:
    displayDivisionResult($result);
}

function displayNestedAggregationResult($result) {
    echo "<table>";
    echo "<tr><th>Team Name</th><th>Average BMI</th>";
    while ($row = oci_fetch_array($result, OCI_BOTH)) { 
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] .  "</td></tr>";
    }
    echo "</table>";
}

function displayGroupByResult($result) {
    echo "<table>";
    echo "<tr><th>TeamId</th><th>Average BMI</th>";

    while ($row = oci_fetch_array($result, OCI_BOTH)) { // number of columns shown: 4
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] .  "</td></tr>";
    }

    echo "</table>";
}


// display team table
function displayTeam($result) {
    $font_size = "50px";
    echo "<p style='font-size: $font_size; text-align: center;'>Team Result</p>";
    echo "<table>";
    echo "<tr><th>Team ID</th><th>Team Name</th></tr>";

    while ($row = oci_fetch_array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
    }

    echo "</table>";
}

// display playerInfo table 
function displayPlayerInfo($result) {
    $font_size = "50px";
    echo "<p style='font-size: $font_size; text-align: center;'>Player Info Result</p>";
    echo "<table>";
    echo "<tr><th>Player ID</th><th>BMI</th><th>Team ID</th><th>Player Name</th></tr>";

    while ($row = oci_fetch_array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td></tr>";
    }

    echo "</table>";
}

// display TitleWin table
function displayTitleWin($result) {
    $font_size = "50px";
    echo "<p style='font-size: $font_size; text-align: center;'>Title Result</p>";
    echo "<table>";
    echo "<tr><th>Title Name</th><th>Year</th><th>Team ID</th></tr>";

    while ($row = oci_fetch_array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>";
    }

    echo "</table>";
}

// display PlayerRole table
function displayPlayerRole($result) {
    $font_size = "50px";
    echo "<p style='font-size: $font_size; text-align: center;'>Player Role Result</p>";
    echo "<table>";
    echo "<tr><th>Player Name</th><th>Role</th></tr>";

    while ($row = oci_fetch_array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
    }

    echo "</table>";
}

function displayPlayerInfoColumn($result, $attribute) { 
    echo "<table>";
    echo "<tr><th>Player Name</th><th>$attribute</th></tr>";

    while (($row = oci_fetch_row($result)) != false) {
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
    }

    echo "</table>";
}

function handleInsertTitleRequest() {
    global $db_conn;
    $TitleName = sanitize($_POST['titleNameIn']);
    $Year = sanitize($_POST['titleYearIn']);
    $TeamID = sanitize($_POST['teamNoIn']);
    executePlainSQL("INSERT INTO TitleWin VALUES ('$TitleName', '$Year', '$TeamID')");
    $result = executePlainSQL("SELECT * FROM TitleWin");
    displayTitleWin($result);
    OCICommit($db_conn); 
}

function handleDeleteTitleRequest() {
    global $db_conn;
    $TitleName = sanitize($_POST['titleNameDe']);
    $Year = sanitize($_POST['titleYearDe']);
    executePlainSQL("DELETE FROM TitleWin WHERE titleName = '$TitleName' AND year = '$Year'");
    $result = executePlainSQL("SELECT * FROM TitleWin");
    displayTitleWin($result);
    OCICommit($db_conn); 
}

function handleUpdateTitleRequest() {
    global $db_conn;
    $TitleName = sanitize($_POST['titleNameUp']);
    $Year = sanitize($_POST['titleYearUp']);
    $TeamID = sanitize($_POST['teamIdUp']);
    executePlainSQL("UPDATE TitleWin SET teamId = '$TeamID' WHERE titleName = '$TitleName' AND year = '$Year'");
    $result = executePlainSQL("SELECT * FROM TitleWin");
    displayTitleWin($result);
    OCICommit($db_conn); 
}

function handleInsertRoleRequest() {
    global $db_conn;
    $PlayerName = sanitize($_POST['playerNameRoleIn']);
    $Role = sanitize($_POST['Role']);
    executePlainSQL("INSERT INTO PlayerRole VALUES ('$PlayerName', '$Role')");
    $result = executePlainSQL("SELECT * FROM PlayerRole");
    displayPlayerRole($result);
    OCICommit($db_conn); 
}

function handleDeleteRoleRequest() {
    global $db_conn;
    $PlayerName = sanitize($_POST['playerNameRoleDe']);
    executePlainSQL("DELETE FROM PlayerRole WHERE name = '$PlayerName'");
    $result = executePlainSQL("SELECT * FROM PlayerRole");
    displayPlayerRole($result);
    OCICommit($db_conn); 
}

function handleUpdateRoleRequest() {
    global $db_conn;
    $PlayerName = sanitize($_POST['targetPlayerName']);
    $newRole = sanitize($_POST['newRoleName']);
    executePlainSQL("UPDATE PlayerRole SET role = '$newRole' WHERE name = '$PlayerName'");
    $result = executePlainSQL("SELECT * FROM PlayerRole");
    displayPlayerRole($result);
    OCICommit($db_conn); 
}

// HANDLE ALL POST ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handlePOSTRequest() {
    if (connectToDB()) {
        if (array_key_exists('resetTablesRequest', $_POST)) {
            handleResetRequest();
        } else if (array_key_exists('updateQueryRequest', $_POST)) {
            handleUpdateRequest();
        } else if (array_key_exists('insertQueryRequest', $_POST)) {
            handleInsertRequest();
        } else if (array_key_exists('deleteQueryRequest', $_POST)) {
            handleDeleteRequest();
        } else if (array_key_exists('insertTitleRequest', $_POST)) {
            handleInsertTitleRequest();
        } else if (array_key_exists('deleteTitleRequest', $_POST)) {
            handleDeleteTitleRequest();
        } else if (array_key_exists('updateTitleRequest', $_POST)) {
            handleUpdateTitleRequest();
        } else if (array_key_exists('insertPIQueryRequest', $_POST)) {
            handleInsertPIRequest();
        } else if (array_key_exists('updatePIQueryRequest', $_POST)) {
            handleUpdatePIRequest();
        } else if (array_key_exists('deletePIQueryRequest', $_POST)) {
            handleDeletePIRequest();
        } else if (array_key_exists('insertRoleRequest', $_POST)) {
            handleInsertRoleRequest();
        } else if (array_key_exists('deleteRoleRequest', $_POST)) {
            handleDeleteRoleRequest();
        } else if (array_key_exists('updateRoleRequest', $_POST)) {
            handleUpdateRoleRequest();
        }

        disconnectFromDB();
    }
}

// HANDLE ALL GET ROUTES
// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
function handleGETRequest() {
    // echo "<p>GET</p>";
    if (connectToDB()) {
        if (array_key_exists('countTuples', $_GET)) {
            handleCountRequest();
        } else if (array_key_exists('displayTeamTuples', $_GET)) {
            handleDisplayTeamTuplesRequest();
        } else if (array_key_exists('displayPlayerInfoTuples',$_GET)) {
            handleDisplayPlayerInfoTuplesRequest();
        } else if (array_key_exists('selectPlayerRequest',$_GET)) {
            handleSelectionPlayer();
        } else if (array_key_exists('projectPlayerAttributeRequest',$_GET)) { 
            handleProjectionPlayer();
        } else if (array_key_exists('joinRequest', $_GET)) {
            handleJoinRequest();
        } else if (array_key_exists('aggregationWithGroupByRequest', $_GET)) { 
            handleGroupByRequest(); // **************
        } else if (array_key_exists('displayTitleWinTupleRequest', $_GET)) {
            handleDisplayTitleWinRequest();
        } else if (array_key_exists('displayPlayerRoleTupleRequest', $_GET)) {
            handleDisplayPlayerRoleRequest();
        } else if (array_key_exists('aggregationNestedRequest', $_GET)) {
            handleNestedAggregationRequest();
        } else if (array_key_exists('groupByBMIRequest', $_GET)){
            handleGroupByAVGBMIRequest();
        } else if (array_key_exists('divisionRequest', $_GET)) {
            handleFindTitleKillerRequest();
        }

        disconnectFromDB();
    }
}

if (isset($_POST['reset']) || 
isset($_POST['updateSubmit']) || 
isset($_POST['insertSubmit']) ||
isset($_POST['deleteSubmit']) 
) { 
    handlePOSTRequest();

} else if (isset($_GET['countTupleRequest']) ||
isset($_GET['displayTeamTupleRequest']) ||
isset($_GET['displayPlayerInfoTupleRequest']) ||
// isset($_GET['displayPlayerInfoAttributeRequest']) ||
isset($_GET['selectSubmit']) ||
isset($_GET['projectSubmit']) ||
isset($_GET['submitJoinQuery']) || 
isset($_GET['submitAggregationWithGroupByQuery']) ||
isset($_GET['displayTitleWinTupleRequest']) ||
isset($_GET['displayPlayerRoleTupleRequest']) ||
isset($_GET['aggregationNestedRequest']) ||
isset($_GET['groupByBMIRequest']) ||
isset($_GET['divisionRequest'])) {
    handleGETRequest(); 
}



   // handle projection 
  // Check if the form was submitted
  if(isset($_POST['projection_button'])) {
    // Check if a table was selected
    if(isset($_POST['table'])) {
      $table = $_POST['table'];
      
      // Check if any checkboxes were selected for attributes
      if(!empty($_POST['attributes'])) {
        // 
      } else {
        // No attributes were selected, display an error message
        echo "<div><h2>Please select at least one attribute</h2></div><br>";
      }
    } else {
      // No table was selected, display an error message
      echo "<div><h2>Please select a table</h2></div><br>";
    }
  }

  if(isset($_POST['projection_result'])) {
    if (connectToDB()) {
        $table = $_POST['table'];
        $selected_attributes = implode(",", $_POST['attributes']);
        $query = "SELECT $selected_attributes FROM $table";
        $result = executePlainSQL($query);
        $attributes_array = explode(",", $selected_attributes);

        $font_size = "50px";
        echo "<p style='font-size: $font_size; text-align: center;'>Projection Result</p>";

        echo "<table>";
        echo "<tr>";
        foreach ($attributes_array as $attribute) {
            echo "<th>$attribute</th>";
        }
        echo "</tr>";

        while ($row = oci_fetch_array($result, OCI_ASSOC)) {
            echo "<tr>";
            foreach($row as $val) {
                echo "<td>" . $val . "</td>";
            }
            echo "</tr>";
        }

        echo "</table>";
        disconnectFromDB();
    }
  }

  // handle Selection 
  // Check at least one check box was submitted
  if(isset($_POST['selection_result'])) {
    if (connectToDB()) {
        $selected_attributes = implode(", ", $_POST['attributes']);
        $attributes = $_POST['attributes'];
        $query = "SELECT * FROM PlayerInfo WHERE ";
        foreach($attributes as $a){
            if($a == 'BMI'){
                $curMod = sanitize($_POST['GLEOp']);
                $curVal = sanitize($_POST['BMInum']);
                $query .= " BMI " . $curMod . " " . $curVal . " AND ";
            }else if($a == 'PlayerID'){
                $curVal = sanitize($_POST['selectionPID']);
                $query .= " PlayerID = " . $curVal . " AND "; 
            }else if($a == 'PlayerName'){
                $curVal = sanitize($_POST['selectionPN']);
                $query .= " name = '$curVal' AND ";
            }else if($a == 'TeamId'){
                $curVal = sanitize($_POST['selectionTID']);
                $query .= " TeamId = " . $curVal . " AND ";
            }
        }
        $query = substr($query, 0, -4);
        //
        $result = executePlainSQL($query);
        displayPlayerInfo($result);
        disconnectFromDB();
    }
  }
?>
</body>
</html>