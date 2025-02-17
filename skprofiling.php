<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$database = "skdb"; 

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $mname = trim($_POST['mname']);
    $suffix = trim($_POST['suffix']);
    $purok = trim($_POST['purok']);
    $gender = trim($_POST['gender']);
    $age = trim($_POST['age']);
    $birthday = trim($_POST['birthday']);
    $email = trim($_POST['email']);
    $contact = trim($_POST['contact']);
    $religion = trim($_POST['religion']);
    $hobbies = trim($_POST['hobbies']);
    $talent = isset($_POST['talent']) ? trim($_POST['talent']) : "";// Fixed undefined index issue
    $guardian = trim($_POST['guardian']);
    $guardian_contact = trim($_POST['guardian_contact']);
    
    // Retrieve the missing variables
    $civil_status = isset($_POST['civil_status']) ? trim($_POST['civil_status']) : "";
    $youth_group = isset($_POST['youth_group']) ? trim($_POST['youth_group']) : "";
    $education = isset($_POST['education']) ? trim($_POST['education']) : "";

    if (empty($fname) || empty($lname) || empty($mname) || empty($suffix) || empty($purok) || empty($gender) || empty($age) || empty($birthday) || empty($email) || empty($contact)) {
        $error = "ALL REQUIRED FIELDS MUST BE FILLED OUT.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "INVALID EMAIL FORMAT.";
    } elseif (!ctype_digit($contact) || strlen($contact) < 10 || strlen($contact) > 12) {
        $error = "INVALID CONTACT NUMBER (10-12 DIGITS REQUIRED).";
    } else {
        $sql = "INSERT INTO sk_profiling (fname, lname, mname, suffix, purok, gender, age, birthday, email, contact, religion, hobbies, talent, guardian, guardian_contact) 
                VALUES ('$fname', '$lname', '$mname', '$suffix', '$purok', '$gender', '$age', '$birthday', '$email', '$contact', '$religion', '$hobbies', '$talent', '$guardian', '$guardian_contact')";

        if ($conn->query($sql) === TRUE) {
            $last_id = $conn->insert_id; // Get last inserted user ID
            
            // Insert into sk_demographics
            $sql_demographics = "INSERT INTO sk_demographics (user_id, civil_status, youth_group, education, religion) 
                                 VALUES ('$last_id', '$civil_status', '$youth_group', '$education', '$religion')";
            
            if ($conn->query($sql_demographics) === TRUE) {
                $success = "FORM SUBMITTED SUCCESSFULLY!";
            } else {
                $error = "ERROR IN DEMOGRAPHIC INSERTION: " . $conn->error;
            }
        } else {
            $error = "ERROR: " . $conn->error;
        }
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SK Profiling Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #ececec;
            margin: 0; 
            padding: 0;
        }

        .container {
            background: linear-gradient(to right, #007bff, #ff0000);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            width: 80%;
            max-width: 900px;
            color: white;
            text-align: center;
            margin: 80px auto 0; /* Push below navbar */
            margin-bottom: 30px;
        }
        h2 {
            text-transform: uppercase;
            font-weight: bold;
        }
        .form-control {
            background: white;
            color: black;
            border: none;
            /* text-transform: uppercase; */
        }
        .form-control::placeholder {
            color: gray;
        }
        .btn-submit {   
            border: none;
            /* text-transform: uppercase; */
            font-weight: bold;
            width: 100%;
            padding: 10px;
            cursor: pointer;
            transition: 0.3s;
            background-color: gray; /* Default disabled color */
        }
        .btn-submit.active {
            background-color: blue; /* Active state color */
        }
        .error {
            color: yellow;
            font-weight: bold;
        }
        .success {
            color: lightgreen;
            font-weight: bold;
        }

        select {
            background: rgba(255, 255, 255, 0.2); /* Default transparent */
            color: black;
            border: none;
            text-transform: uppercase;
            transition: background 0.3s ease;
        }

        select option {
            background: white;
            color: black   ;
        }

        select:focus, select.selected {
            background: white; /* Stay white after selection */
        }



        /* General Navbar Styling  HERE */
        .navbar {
            background: linear-gradient(to right, #007bff, #ff0000); /* Blue to Red Gradient */
            padding: 10px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        /* Navbar Brand */
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: white !important;
        }

        /* Navbar Links */
        .navbar-nav .nav-link {
            color: white !important;
            font-size: 1.1rem;
            font-weight: 500;
            padding: 10px 15px;
            transition: 0.3s ease-in-out;
        }

        /* Active Link */
        .navbar-nav .nav-link.active {
            background-color: rgba(255, 255, 255, 0.3);
            border-radius: 5px;
        }

        /* Hover Effect */
        .navbar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
        }

        /* Mobile Menu */
        .navbar-toggler {
            border: none;
            outline: none;
        }

        .navbar-toggler-icon {
            filter: invert(1); /* Makes the icon white */
        }

    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="sklogo.png" alt="Logo" width="60" height="60" class="me-2">
                SK Profiling
            </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="skview.php">SK Profiles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">About</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4 p-4 bg-light rounded">
    <h2 class="text-center">SK PROFILING FORM</h2>

    <!-- SHOW ERROR OR SUCCESFULL -->
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?> 

    <form method="POST" id="profilingForm">
        <h4>Basic Information</h4>
        <div class="row">
            <div class="col-md-4"> <input type="text" name="fname" class="form-control mb-2" placeholder="FIRST NAME" required> </div>
            <div class="col-md-3"> <input type="text" name="lname" class="form-control mb-2" placeholder="LAST NAME" required> </div>
            <div class="col-md-3"> <input type="text" name="mname" class="form-control mb-2" placeholder="MIDDLE NAME"></div>
            <div class="col-md-2"> <input type="text" name="suffix" class="form-control mb-2" placeholder="SUFFIX"> </div>
        </div>

        <div class="row">
            <div class="col-md-6"> <input type="text" name="email" class="form-control mb-2" placeholder="EMAIL"></div>
            <div class="col-md-6"> <input type="text" name="contact" class="form-control mb-2" placeholder="CONTACT"> </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <select name="purok" class="form-control mb-2">
                    <option value="">SELECT PUROK</option>
                    <option value="1">PUROK 1</option>
                    <option value="2">PUROK 2</option>
                    <option value="3">PUROK 3</option>
                    <option value="4">PUROK 4</option>
                    <option value="5">PUROK 5</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="gender" class="form-control mb-2">
                    <option value="">SELECT GENDER</option>
                    <option value="Male">MALE</option>
                    <option value="Female">FEMALE</option>
                </select>
            </div>
            <div class="col-md-2"><input type="number" name="age" class="form-control mb-2" placeholder="AGE" required></div>
            <div class="col-md-4">
                <input type="date" name="birthday" placeholder="Date of Birth" class="form-control mb-3" required>
            </div>
        </div>

        <h4>Demographic Characteristics</h4>

        <select name="civil_status" class="form-control mb-2">
            <option value="">Select Civil Status</option>
            <option value="Single">Single</option>
            <option value="Married">Married</option>
            <option value="Widowed">Widowed</option>
            <option value="Divorced">Divorced</option>
            <option value="Separated">Separated</option>
            <option value="Annulled">Annulled</option>
            <option value="Live-in">Live-in</option>
        </select>

        <select name="youth_group" class="form-control mb-2">
            <option value="">Select Youth Age Group</option>
            <option value="15-17">Child Youth (15-17 yrs old)</option>
            <option value="18-24">Core Youth (18-24 yrs old)</option>
            <option value="25-30">Young Adult (25-30 yrs old)</option>
        </select>

        <select name="education" class="form-control mb-2">
            <option value="">Select Educational Background</option>
            <option value="Elementary Level">Elementary Level</option>
            <option value="Elementary Graduate">Elementary Graduate</option>
            <option value="Highschool Level">Highschool Level</option>
            <option value="Highschool Graduate">Highschool Graduate</option>
            <option value="Vocational Graduate">Vocational Graduate</option>
            <option value="College Level">College Level</option>
            <option value="College Graduate">College Graduate</option>
            <option value="Masters Level">Masters Level</option>
            <option value="Masters Graduate">Masters Graduate</option>
            <option value="Doctorate Level">Doctorate Level</option>
            <option value="Doctorate Graduate">Doctorate Graduate</option>
        </select>
        
        <!-- RELIGION -->
        <select name="religion" class="form-control mb-2">
            <option value="">Select Religion</option>
            <option value="Christianity">Christianity</option>
            <option value="Islam">Islam</option>
            <option value="Buddhism">Buddhism</option>
            <option value="Hinduism">Hinduism</option>
            <option value="Other">Other</option>
        </select>
        
        <!-- HOBBIES AND RELIGION -->
        <div class="row"> 
            <div class="col-md-6"> <input type="text" name="hobbies" class="form-control mb-2" placeholder="HOBBIES" required> </div>
            <div class="col-md-6"> <input type="text" name="talent" class="form-control mb-2" placeholder="TALENTS" required> </div>
        </div>

        <h4>Guardian Details</h4>
        <input type="text" name="guardian" class="form-control mb-2" placeholder="Guardian Name">
        <input type="text" name="guardian_contact" class="form-control mb-2" placeholder="Guardian Contact">
        
        <button type="submit" class="btn btn-primary w-100" id="submitBtn">Submit</button>
    </form>
</div>

<!-- Success Modal -->
<?php if ($success): ?>  
    <div id="successModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Success</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Form Submitted Successfully!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.getElementById("profilingForm").addEventListener("input", function() {
        let allFilled = [...this.elements].every(input => input.value.trim() !== "" || input.type === "submit");
        document.getElementById("submitBtn").classList.toggle("active", allFilled);
        document.getElementById("submitBtn").disabled = !allFilled;
    });

    //THIS IS FOR THE SELECT PART FOR ITS BACKGROUND
    document.querySelectorAll("select").forEach(select => {
        select.addEventListener("change", function() {
            this.classList.add("selected"); // Make background white after selection
        });
    });

    // THIS JS IS FOR MODAL POP UP
    document.addEventListener("DOMContentLoaded", function() {
    var successMessage = "<?php echo $success; ?>";
    if (successMessage.trim() !== "") {
        console.log("Showing success modal...");  // Debugging
        var successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();
    }
});


</script>
</body>


</html>
