<!DOCTYPE HTML>
<html>
    <head>
        <title>NBA Database</title>
    </head>

    <body>
        <h2>Reset</h2>
        <p>If you wish to reset the table press on the reset button. If this is the first time you're running this page, you MUST use reset</p>

        <form method="POST" action="teamWinTitle.php">
            <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
            <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
            <p><input type="submit" value="Reset" name="reset"></p>
        </form>

        <hr />

        <h2>Insert A New TeamWinTitle Tuple</h2>
        <form method="POST" action="teamWinTitle.php"> <!--refresh page when submitted-->
            <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
            Title Name: <input type="text" name="titleName"> <br /><br />
            Title Year: <input type="text" name="titleYear"> <br /><br />
            Conference/Division: <input type="text" name="conference"> <br /><br />
            Team ID: <input type="text" name="teamID"> <br /><br />
            <input type="submit" value="Insert" name="insertSubmit"></p>
        </form>

        <hr />


        <h2>Delete an Existing TeamWinTitle Tuple</h2>
        <form method="POST" action="teamWinTitle.php"> <!--refresh page when submitted-->
            <input type="hidden" id="deleteQueryRequest" name="deleteQueryRequest">
            Title Name: <input type="text" name="titleName"> <br /><br />
            Title Year: <input type="text" name="titleYear"> <br /><br />
            Conference: <input type="text" name="conference"> <br /><br />
            <input type="submit" value="Delete" name="deleteSubmit"></p>
        </form>

        <hr />

        <h2>Count the Tuples in TeamWinTitle</h2>
        <form method="GET" action="teamWinTitle.php"> <!--refresh page when submitted-->
            <input type="hidden" id="countTupleRequest" name="countTupleRequest">
            <input type="submit" name="countTuples"></p>
        </form>

        <hr />
        <h2>Division Query</h2>
        <p>This is a hardcoded query in which it queries the IDs of all team that won all the distinct titles in history</p>
        <p>You could choose one extra attributes to display from the dropdown list</p>
        <form method="POST" action="teamWinTitle.php">
            <input type="hidden" id="divisionQueryRequest" name="divisionQueryRequest">
            Attributes:
            <select name="attribute" id="attribute">
                <option value="" selected="selected">--Select an attribute--</option>
                <option value="tName" name="tName">Team Name</option>
                <option value="city" name="city">City</option>
                <option value="states" name="states">States</option>
            </select>
            <br><br>
            <input type="submit" name="divisionQuery"></p>
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

        function handleResetRequest() {
            global $db_conn;
            // Drop old table
            executePlainSQL("DROP TABLE TeamWinTitle");

            // Create new table
            echo "<br> creating new table <br>";
            executePlainSQL("
                create table TeamWinTitle (
                titleName char(40), 
                titleYear int, 
                conference char(15),
                teamID int, 
                primary key (titleName, titleYear, conference),
                foreign key (teamID) references Team
            )
            ");
            OCICommit($db_conn);
        }

        function handleInsertRequest() {
            global $db_conn;

            $title_name = $_POST['titleName'];
            $title_year = $_POST['titleYear'];
            $conference = $_POST['conference'];
            $team_id = $_POST['teamID'];

            // executeBoundSQL("insert into TeamWinTitle values (:bind1, :bind2, :bind3)", $alltuples);
            executePlainSQL("insert into TeamWinTitle values ('" . $title_name . "', " . $title_year . ", '" . $conference . "', ". $team_id . ")");
            OCICommit($db_conn);
        }

        function handleDeleteRequest() {
			global $db_conn;

            //Getting the values from user and insert data into the table
			// $tuple = array (
            //     ":bind1" => $_POST['titleName'],
            //     ":bind2" => $_POST['titleYear']
            // );

			// $alltuples = array (
            //     $tuple
            // );
            // executeBoundSQL("DELETE FROM TeamWinTitle WHERE titleName = (:bind1) AND titleYear = (:bind2)", $alltuples);
            $title_name = $_POST['titleName'];
            $title_year = $_POST['titleYear'];
            $conference = $_POST['conference'];

            executePlainSQL("DELETE FROM TeamWinTitle WHERE titleName = '" . $title_name . "' AND titleYear = " . $title_year . " AND conference = '" . $conference . "'");
            OCICommit($db_conn);
		}

        function printDivisionResult($result) {
            echo "<br>Retrieved data from table Team:<br>";
            echo "<table>";

            while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td></tr>";
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

        function handleCountRequest() {
            global $db_conn;

            $result = executePlainSQL("SELECT Count(*) FROM TeamWinTitle");

            if (($row = oci_fetch_row($result)) != false) {
                echo "<br> The number of tuples in TeamWinTitle: " . $row[0] . "<br>";
            }
        }

        function handleDivisionRequest() {
            global $db_conn;

            $extra_attribute = $_POST['attribute'];
            echo $extra_attribute;

            $result = executePlainSQL("SELECT t.teamid, t." . $extra_attribute . " FROM team t WHERE NOT EXISTS ((SELECT DISTINCT w.titlename FROM teamwintitle w) MINUS (SELECT DISTINCT w.titlename FROM teamwintitle w WHERE t.teamid = w.teamid))");

            printDivisionResult($result);
        }


        function handlePOSTRequest() {
            if (connectToDB()) {
                if (array_key_exists('resetTablesRequest', $_POST)) {
                    handleResetRequest();
                } else if (array_key_exists('insertQueryRequest', $_POST)) {
                    handleInsertRequest();
                } else if (array_key_exists('deleteQueryRequest', $_POST)) {
                    handleDeleteRequest();
                } else if (array_key_exists('divisionQueryRequest', $_POST)) {
                    handleDivisionRequest();
                }

                disconnectFromDB();
            }
        }

        function handleGETRequest() {
            if (connectToDB()) {
                if (array_key_exists('countTuples', $_GET)) {
                    handleCountRequest();
                }

                disconnectFromDB();
            }
        }

        if (isset($_POST['reset']) || isset($_POST['updateSubmit']) || isset($_POST['insertSubmit']) || isset($_POST['deleteSubmit']) || isset($_POST['divisionQuery'])) {
            handlePOSTRequest();
        } else if (isset($_GET['countTupleRequest'])) {
            handleGETRequest();
        }
		
		?>
    </body>
</html>