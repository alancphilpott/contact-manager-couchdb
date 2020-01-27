<?php
$autoloader = join(DIRECTORY_SEPARATOR, [__DIR__, 'vendor', 'autoload.php']);
require $autoloader;

//Import necessary classes.
use PHPOnCouch\CouchClient;
use PHPOnCouch\Exceptions;

//Create client access to the database.
$client = new CouchClient('http://localhost:5984', 'contactsapp');

error_reporting(E_ERROR);
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
                    <a class="nav-link" href="index.php">View Contacts
                        <span class="sr-only">(current)</span>
                    </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="#">Create Contact
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
        <h1 class="mt-5">Create Contact</h1>
    </div>
</div>
<div class="container">
    <div class="row">
        <form id="createContactFrm" role="form" action="createContact.php" method="post">
            <h4 class="mt-5">Please Enter All Fields</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" class="form-control" name="firstName" placeholder="Enter First Name">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" class="form-control" name="lastName" placeholder="Enter Last Name">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Home Number</label>
                        <input type="text" class="form-control" name="homeNum" placeholder="Enter Home Number">
                    </div>
                </div>
                <div class="col-md-6">
                    <label>Mobile Number</label>
                    <input type="text" class="form-control" name="mobileNum" placeholder="Enter Mobile Number">
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Enter Email Address">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success btn-send" name="createContactBtn">
                Create Contact
            </button>
            <?php
            if (isset($_POST["createContactBtn"])) {
                $new_doc = new stdClass();
                $new_doc->firstName = $_POST["firstName"];
                $new_doc->lastName = $_POST["lastName"];
                $new_doc->number->home = $_POST["homeNum"];
                $new_doc->number->mobile = $_POST["mobileNum"];
                $new_doc->email = $_POST["email"];
                try {
                    $response = $client->storeDoc($new_doc);
                } catch (Exception $e) {
                    echo "<p class='alert'>ERROR: " . $e->getMessage() . " (" . $e->getCode() . ")<br></p>";
                }
                echo "<div class=\"alert alert-success\"><strong>Document Successfully Created</strong><BR>ID = " . $response->id . "<BR>Revision = " . $response->rev . "</div>";
            }
            ?>
        </form>

    </div>
</div>

<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>