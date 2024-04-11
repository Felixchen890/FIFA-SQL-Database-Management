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
  <!DOCTYPE HTML>
  <html>
    <head>
        <title>NBA Database</title>
        <script src="script.js"></script>
    </head>

    <body>
        <h2>Reset</h2>
        <p>If you wish to reset the table press on the reset button. If this is the first time you're running this page, you MUST use reset</p>

        <form method="POST" action="hello.php">
            <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
            <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
            <p><input type="submit" value="Reset" name="reset"></p>
        </form>

        <hr />

        <h2>Insert Player</h2>
        <form method="POST" action="hello.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">

            Player ID: <input type="text" name="playerID"> <br /><br />
            First Name: <input type="text" name="firstName"> <br /><br />
            Last Name: <input type="text" name="lastName"> <br /><br />
            Jersey Number: <input type="text" name="jerseyNumber"> <br /><br />
            

            <input type="submit" value="Insert" name="insertSubmit"></p>
        </form>

        <hr />

        <h2>Update Jersey Number in Player Table</h2>
        <p>The values are case sensitive and if you enter in the wrong case, the update statement will not do anything.</p>

        <form method="POST" action="hello.php"> <!--refresh page when submitted-->
            <input type="hidden" id="updateQueryRequest" name="updateQueryRequest">
            Player ID: <input type="number" name="playerID"> <br /><br />
            New Number: <input type="number" name="newNumber"> <br /><br />
            <input type="submit" value="Update" name="updateSubmit"></p>
        </form>

        <hr />

        <h2>Selection Query</h2>
        <form method="POST" action="hello.php">
            <input type="hidden" id="selectTableAttributeRequest" name="selectTableAttributeRequest">
            Tables: <select name="table" id="table" onchange="tableDroplistChange()">
                <option value="0" selected="selected">--Please choose a table--</option>
                <option value="team" name="team">Team</option>
                <option value="player" name="player">Player</option>
            </select>
            <br><br>
            Attributes: <select name="attribute" id="attribute">
                <option value="" selected="selected">--Select an attribute--</option>
            </select>
            <br><br>
            <input type="submit" name="submitSelectionQuery">
        </form>

        <hr />

        <h2>Aggregation with Group By</h2>
        <form method="POST" action="hello.php">
            <input type="hidden" id="aggregationWithGroupByRequest" name="aggregationWithGroupByRequest">
            Tables:
            <select name="aggregationWithGroupByTable" id="aggregationWithGroupByTable" onchange="aggregationWithGroupByChange()">
                <option value="0" selected="selected">--Please choose a table--</option>
                <option value="team" name="team">Team</option>
                <option value="player" name="player">Player</option>
            </select>
            <br><br>
            Group By:
            <select name="aggregationWithGroupByAttribute" id="aggregationWithGroupByAttribute">
                <option selected="selected">--Select an attribute--</option>
            </select>
            <br><br>
            Aggregate Operator:
            <select name="aWithGroupByAggregateOperator" id="aWithGroupByAggregateOperator">
                <option selected="selected">--Select an aggregate operator--</option>
            </select>
            <br><br>
            <input type="submit" name="aggregationWithGroupByQuery">
        </form>

        <hr />

       <h2>Delete Player from Team</h2>
       <form method="POST" action="hello.php"> <!--refresh page when submitted-->
            <input type="hidden" id="deleteQueryRequest" name="deleteQueryRequest">
            Player ID: <input type="text" name="playerID"> <br /><br />

            <input type="submit" value="Delete" name="deleteSubmit"></p>
       </form>

       <hr />

       <h2>Project Player</h2>
       <p>Select from:(playerID,firstName,lastName,jerseyNumber</p>
       <form method="GET" action="hello.php"> <!--refresh page when submitted-->
            <input type="hidden" id="projectQueryRequest" name="projectQueryRequest">
            playerID: <input type="text" name="playerID"> <br /><br />
            firstName: <input type="text" name="firstName"> <br /><br />
            lastName: <input type="text" name="lastName"> <br /><br />
            jerseyNumber: <input type="text" name="jerseyNumber"> <br /><br />


            <input type="submit" value="Project" name="projectSubmit"></p>
       </form>


       <hr />

        <h2>Count the Tuples in Player</h2>
        <form method="GET" action="hello.php"> <!--refresh page when submitted-->
            <input type="hidden" id="countTupleRequest" name="countTupleRequest">
            <input type="submit" name="countTuples"></p>
        </form>


        <hr />

        <h2>Display the Tuples in Player</h2>
        <form method="GET" action="hello.php"> <!--refresh page when submitted-->
            <input type="hidden" id="displayPlayerTupleRequest" name="displayPlayerTupleRequest">
            <input type="submit" name="displayPlayerTuples"></p>
        </form>

        <hr />

        <?php
		//this tells the system that it's no longer just parsing html; it's now parsing PHP

        $success = True; //keep track of errors so it redirects the page only if there are no errors
        $db_conn = NULL; // edit the login credentials in connectToDB()
        $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())

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
            }

            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
                echo htmlentities($e['message']);
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

        function printPlayerResult($result) {
            echo "<br>Retrieved data from table Player:<br>";
            echo "<table>";
            echo "<tr><th>playerID</th><th>firstName</th><th>lastName</th><th>jerseyNumber </th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }

        function printTeamResult($result) {
            echo "<br>Retrieved data from table Team:<br>";
            echo "<table>";
            echo "<tr><th>teamID</th><th>tName</th><th>city</th><th>states </th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }



        function printSelectionQueryResult($result) {
            echo "<br>Retrieved data from table:<br>";
            echo "<table>";
            
            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td></tr>";
            }

            echo "</table";

        }

        function printAggregationWithGroupByTeam($result) {
            echo "<br>Retrieved data from table Team:<br>";
            echo "<table>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }

        function printAggregationWithGroupByPlayer($result) {
            echo "<br>Retrieved data from table Team:<br>";
            echo "<table>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>"; //or just use "echo $row[0]"
            }

            echo "</table>";
        }

        function connectToDB() {
            global $db_conn;

            // Your username is ora_(CWL_ID) and the password is a(student number). For example,
			// ora_platypus is the username and a12345678 is the password.
            $db_conn = OCILogon("ora_hjy1999", "a48389043", "dbhost.students.cs.ubc.ca:1522/stu");

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

        function handleUpdateRequest() {
            global $db_conn;

            $player_id = $_POST['playerID'];
            $new_number = $_POST['newNumber'];

            // you need the wrap the old name and new name values with single quotations
            executePlainSQL("UPDATE Player SET jerseyNumber = " . $new_number . " WHERE playerID =" . $player_id);
            OCICommit($db_conn);
        }

        function handleResetRequest() {
            global $db_conn;
            // Drop old table
            executePlainSQL("DROP TABLE Player");

            // Create new table
            echo "<br> creating new table <br>";
            executePlainSQL("create table Player (
                playerID int primary key, 
                firstName char(20) not null, 
                lastName char(20) not null, 
                jerseyNumber int not null
            )");
            OCICommit($db_conn);
        }

        function handleInsertRequest() {
            global $db_conn;

            $player_id = $_POST['playerID'];
            $first_name = $_POST['firstName'];
            $last_name = $_POST['lastName'];
            $jersey_number = $_POST['jerseyNumber'];

            executePlainSQL("INSERT INTO Player VALUES (" . $player_id . ", '" . $first_name . "', '" . $last_name . "', " . $jersey_number . ")");
            
            OCICommit($db_conn);
        }

        function handleCountRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT Count(*) FROM Player");

            if (($row = oci_fetch_row($result)) != false) {
                echo "<br> The number of tuples in Player: " . $row[0] . "<br>";
            }
        }

        function handleDisplayPlayerRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT * FROM Player");

            printPlayerResult($result);
        }

		function handleDeleteRequest() {
			global $db_conn;

            //Getting the values from user and insert data into the table
			$tuple = array (
                ":bind1" => $_POST['playerID']
            );

			$alltuples = array (
                $tuple
            );
            // hello
            executeBoundSQL("DELETE FROM Player WHERE playerID = (:bind1)", $alltuples);
            OCICommit($db_conn);
		}

        function handleProjectRequest() {
            global $db_conn;
            $playerID = $_GET['playerID'];
            $firstName = $_GET['firstName'];
            $lastName = $_GET['lastName'];
            $jerseyNumber = $_GET['jerseyNumber'];


            $list = [$playerID, $firstName, $lastName,$jerseyNumber];

            $list = implode(', ', array_filter($list));

            if (empty($list)) {
                $list = '*';
            }

            $result = executePlainSQL("SELECT  ". $list . " FROM Player");
            echo "<br>Retrieved data from table demoTable:<br>";
            echo "<table>";
            echo "<tr><th>$playerID</th><th>$firstName</th><th>$lastName</th><th>$jerseyNumber </th></tr>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] . "</td></tr>"; 
            }

            echo "</table>";
        }

        function handleSelectionQueryRequest() {
            global $db_conn;
            
            if (!empty($_POST['table'])) {
                $table_name = $_POST['table'];
                echo 'You have selected table: ' . $table_name;
                echo '<br />';
            }

            if (!empty($_POST['attribute'])) {
                $attribute_name = $_POST['attribute'];
                echo 'You have selected attribute: ' . $attribute_name;
                echo '<br />';
            }

            $result = executePlainSQL("SELECT " . $attribute_name . " FROM " . $table_name);

            if ($attribute_name == '*') {
                if ($table_name == 'team') {
                    $result = executePlainSQL("select * from team");
                    printTeamResult($result);
                }
                if ($table_name == 'player') {
                    printPlayerResult($result);
                }
            } else {
                printSelectionQueryResult($result);
            }
            OCICommit($db_conn);
        }

        function handleAggregationWithGroupByQueryRequest() {
            global $db_conn;

            if (!empty($_POST['aggregationWithGroupByTable'])) {
                $table_name = $_POST['aggregationWithGroupByTable'];
                echo 'You have selected table: ' . $table_name;
                echo '<br />';
            }
            if (!empty($_POST['aggregationWithGroupByAttribute'])) {
                $group_by = $_POST['aggregationWithGroupByAttribute'];
                echo 'You have chosen to group by: ' . $group_by;
                echo '<br />';
            }
            if (!empty($_POST['aWithGroupByAggregateOperator'])) {
                $aggregate_operator = $_POST['aWithGroupByAggregateOperator'];
                echo 'You have selected aggregate operator: ' . $aggregate_operator;
                echo '<br />';
            }

            $table_name = $_POST['aggregationWithGroupByTable'];
            $group_by = $_POST['aggregationWithGroupByAttribute'];
            $aggregate_operator = $_POST['aWithGroupByAggregateOperator'];
            echo $table_name;
            echo '<br />';
            echo $group_by;
            echo '<br />';
            echo $aggregate_operator;
            echo '<br />';

            if ($table_name == 'team') {
                $result = executePlainSQL("SELECT " . $group_by . ", " . $aggregate_operator . "(teamID) FROM " . $table_name . " GROUP BY " . $group_by);
                printAggregationWithGroupByTeam($result);
            } else if ($table_name == 'player') {
                $result = executePlainSQL("SELECT " . $group_by . ", " . $aggregate_operator . "(playerID) FROM " . $table_name . " GROUP BY " . $group_by);
                printAggregationWithGroupByPlayer($result);
            }

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
                } else if (array_key_exists('selectTableAttributeRequest', $_POST)) {
                    handleSelectionQueryRequest();
                } else if (array_key_exists('projectQueryRequest', $_POST)) {
                    handleProjectRequest();
                } else if (array_key_exists('aggregationWithGroupByRequest', $_POST)) {
                    handleAggregationWithGroupByQueryRequest();
                }

                disconnectFromDB();
            }
        }

        // HANDLE ALL GET ROUTES
	// A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
        function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('countTuples', $_GET)) {
                    handleCountRequest();
                } else if (array_key_exists('displayPlayerTuples', $_GET)) {
                    handleDisplayPlayerRequest();
                } else if (array_key_exists('projectQueryRequest', $_GET)) {
                    handleProjectRequest();
                }

                disconnectFromDB();
            }
        }

		if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['insertSubmit']) || isset($_POST['deleteSubmit']) || isset($_POST['submitSelectionQuery']) || isset($_POST['aggregationWithGroupByQuery'])) {
            handlePOSTRequest();
        } else if (isset($_GET['countTupleRequest']) || isset($_GET['displayPlayerTupleRequest']) || isset($_GET['projectQueryRequest'])) {
            handleGETRequest();
        }
		?>
	</body>
</html>
















