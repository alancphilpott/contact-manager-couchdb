<?php
$autoloader = join(DIRECTORY_SEPARATOR, [__DIR__, 'vendor', 'autoload.php']);
require $autoloader;

//Import necessary classes.
use PHPOnCouch\CouchClient;
use PHPOnCouch\Exceptions;

//Create client access to the database.
$client = new CouchClient('http://localhost:5984', 'contactsapp');

error_reporting(E_ERROR);

//Retrieve Document ID using _GET and get Document using ID
$doc_id = $_POST["docID"];
try {
    $doc = $client->getDoc($doc_id);
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . " (" . $e->getCode() . ")<br>\n";
}
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
<div class="container">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h1 class="mt-5">Update Contact</h1>
        </div>

        <form id="createContactFrm" role="form" action="index.php" method="post">
            <h4 class="mt-5">Please Update Required Fields</h4>
            <input type="hidden" name="idToUpdate" value="<?php echo $doc_id ?>"/>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" class="form-control" name="firstName" value="<?php echo $doc->firstName ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" class="form-control" name="lastName" value="<?php echo $doc->lastName ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Home Number</label>
                        <input type="text" class="form-control" name="homeNum" value="<?php echo $doc->number->home ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <label>Mobile Number</label>
                    <input type="text" class="form-control" name="mobileNum" value="<?php echo $doc->number->mobile ?>">
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" value="<?php echo $doc->email ?>">
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success btn-send" name="updateContactBtn">
                Update Contact
            </button>
        </form>

    </div>
</div>

<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>