<?php
$autoloader = join(DIRECTORY_SEPARATOR, [__DIR__, 'vendor', 'autoload.php']);
require $autoloader;

//Import necessary classes.
use PHPOnCouch\CouchClient;
use PHPOnCouch\Exceptions;

//Create client access to the database.
$client = new CouchClient('http://localhost:5984', 'contactsapp');
?>

<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Contacts App - CouchDB</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
    <div class="container">
        <a class="navbar-brand" href="#">ContactsApp</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"
                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">View Contacts
                        <span class="sr-only">(current)</span>
                    </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="createContact.php">Create Contact
                        <span class="sr-only">(current)</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Page Content -->
<div class="col-lg-12 text-center">
    <div class="jumbotron">
        <h1 class="mt-5">List of All Contacts</h1>
    </div>
</div>
<div class="container">
    <div class="row">
        <?php
        //Create a table displaying all contacts from each document.
        ?>
        <table class="table table-hover">
            <thead class="thead-dark">
                <tr class="d-lg-table-row">
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>

            <?php
            if (isset($_POST["updateContactBtn"])) {
                //Get The Document
                try {
                    $doc = $client->getDoc($_POST["idToUpdate"]);
                } catch (Exception $e) {
                    echo "ERROR: " . $e->getMessage() . " (" . $e->getCode() . ")<br>\n";
                }
                //Make changes to document.
                $doc->firstName = $_POST["firstName"];
                $doc->lastName = $_POST["lastName"];
                $doc->number->home = $_POST["homeNum"];
                $doc->number->mobile = $_POST["mobileNum"];
                $doc->email = $_POST["email"];

                //Update the document on CouchDB server.
                try {
                    $response = $client->storeDoc($doc);
                } catch (Exception $e) {
                    echo "ERROR: " . $e->getMessage() . " (" . $e->getCode() . ")<br>\n";
                }
                echo "<div class=\"alert alert-success\"><strong>Document Successfully Updated</strong><BR>ID = " . $response->id . "<BR>Revision = " . $response->rev . "</div>";
            }

            if(isset($_POST["deleteContactBtn"])){
                //Retrieve the document.
                try {
                    $doc = $client->getDoc($_POST["docID"]);
                } catch (Exception $e) {
                    echo "ERROR: ".$e->getMessage()." (".$e->getCode().")<br>\n";
                }
                //Permanently remove the document.
                try {
                    $client->deleteDoc($doc);
                } catch (Exception $e) {
                    echo "ERROR: ".$e->getMessage()." (".$e->getCode().")<br>\n";
                }
                echo "<div class=\"alert alert-success\"><strong>Document " . $_POST["docID"] . " Successfully Deleted</strong></div>";
            }

            $doc = $client->getView("Contacts", "allContacts");
            foreach ($doc->rows as $row) {
                echo "<tr>";
                echo "<td>" . $row->value->firstName . "</td>";
                echo "<td>" . $row->value->lastName . "</td>";
                echo "<td>Home: " . $row->value->number->home . "<BR>Mobile: " . $row->value->number->mobile . "</td>";
                echo "<td>" . $row->value->email . "</td>";
                echo "<td>
                        <form method='post' action='updateContact.php'>
                            <input type='hidden' name='docID' value='$row->id'/>
                            <button type='submit' class='btn btn-send btn-primary' name='updateContactBtn'>Update</button>
                        </form>
                      </td>";
                echo "<td>
                        <form method='post' action='index.php'>
                            <input type='hidden' name='docID' value='$row->id'/>
                            <button type='submit' class='btn btn-send btn-danger' name='deleteContactBtn'>Delete</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</div>

<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>
