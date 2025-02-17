<!DOCTYPE html>
<html>
<head>
    <title>SK Profile Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 20px;
            background: #f9f9f9;
        }
        .container {
            width: 100%;
            max-width: 1400px;
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }
        h2, h3 {
            font-size: 14px;
            text-align: center;
        }
        .table-container {
            overflow-x: auto;
            max-width: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 12px;
            table-layout: auto;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: center;
            vertical-align: middle;
            white-space: nowrap;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:nth-child(odd) {
            background-color: #ffffff;
        }
        .form {
            width: 100%;
            max-width: 400px;
            margin-bottom: 10px;
            display: flex;
            gap: 5px;
        }
        .form input {
            flex: 1;
            padding: 5px;
            font-size: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form button {
            font-size: 12px;
            padding: 5px 10px;
            border: none;
            background: #007bff;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }
        .form button:hover {
            background: #0056b3;
        }

        .table-wrapper {
            max-height: 400px; /* Adjust as needed */
            overflow-y: auto;
            border: 1px solid #ddd;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            table-layout: auto;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: center;
            white-space: nowrap;
        }

        th {
            background-color: #f2f2f2;
            position: sticky;
            top: 0;
            z-index: 2;
        }

        tbody tr.odd {
            background-color: #ffffff;
        }

        tbody tr.even {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #e0e0e0;
        }

        /* Pagination Styles */
        .pagination-container {
            text-align: right;
            margin-top: 10px;
        }

        .pagination {
            display: inline-block;
        }

        .pagination a {
            color: black;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid #ddd;
            margin: 2px;
        }

        .pagination a.active {
            background-color: #4CAF50;
            color: white;
            border: 1px solid #4CAF50;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }

        .pagination a:first-child {
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
        }

        .pagination a:last-child {
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
        }


        /* Back button styles */
        .back-button {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 3em;
            width: 100px;
            background-color: #fff;
            border-radius: 3px;
            letter-spacing: 1px;
            transition: all 0.2s linear;
            cursor: pointer;
            border: none;
            box-shadow: 3px 3px 10px rgba(0,0,0,0.1);
        }

        .back-button svg {
            margin-right: 5px;
            transition: all 0.4s ease-in;
        }

        .back-button:hover svg {
            transform: translateX(-5px);
        }

        .back-button:hover {
            box-shadow: 9px 9px 33px #d1d1d1, -9px -9px 33px #ffffff;
            transform: translateY(-2px);
        }


    </style>
</head>
<body>

<h2>SK Profiling Data</h2>

<!-- Search Bar -->
<form class="form" method="POST">
     <!--CODE PARA SA BACK BUTTON-->
     <a href="skprofiling.php" class="back-button">
            <svg height="16" width="16" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 1024 1024">
                <path d="M874.690416 495.52477c0 11.2973-9.168824 20.466124-20.466124 20.466124l-604.773963 0 188.083679 188.083679c7.992021 7.992021 7.992021 20.947078 0 28.939099-4.001127 3.990894-9.240455 5.996574-14.46955 5.996574-5.239328 0-10.478655-1.995447-14.479783-5.996574l-223.00912-223.00912c-3.837398-3.837398-5.996574-9.046027-5.996574-14.46955 0-5.433756 2.159176-10.632151 5.996574-14.46955l223.019353-223.029586c7.992021-7.992021 20.957311-7.992021 28.949332 0 7.992021 8.002254 7.992021 20.957311 0 28.949332l-188.073446 188.073446 604.753497 0C865.521592 475.058646 874.690416 484.217237 874.690416 495.52477z"></path>
            </svg>
            <span>Back</span>
        </a>

    <input type="text" id="search" name="search" placeholder="Search...">
    <button type="submit">Search</button>
</form>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "skdb"; 

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Search Results
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['search'])) {
    $search = $conn->real_escape_string($_POST['search']);
    $sql = "SELECT sp.*, sd.civil_status, sd.youth_group, sd.education, sd.religion
            FROM sk_profiling sp
            LEFT JOIN sk_demographics sd ON sp.id = sd.user_id
            WHERE sp.id LIKE '%$search%' OR sp.fname LIKE '%$search%' OR sp.lname LIKE '%$search%'";

    $result = $conn->query($sql);
    echo "<div class='container'><h3>Search Results</h3><div class='table-container'><table>";
    echo "<tr>
            <th>#</th><th>First Name</th><th>Last Name</th><th>Middle Name</th><th>Suffix</th><th>Purok</th>
            <th>Gender</th><th>Age</th><th>Birthday</th><th>Email</th><th>Contact</th>
            <th>Religion</th><th>Hobbies</th><th>Talent</th><th>Guardian</th><th>Guardian Contact</th>
            <th>Civil Status</th><th>Youth Group</th><th>Education</th>
          </tr>";
    $count = 1;
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>".$count."</td><td>".$row["fname"]."</td><td>".$row["lname"]."</td><td>".$row["mname"]."</td>
                  <td>".$row["suffix"]."</td><td>".$row["purok"]."</td><td>".$row["gender"]."</td>
                  <td>".$row["age"]."</td><td>".$row["birthday"]."</td><td>".$row["email"]."</td>
                  <td>".$row["contact"]."</td><td>".$row["religion"]."</td><td>".$row["hobbies"]."</td>
                  <td>".$row["talent"]."</td><td>".$row["guardian"]."</td><td>".$row["guardian_contact"]."</td>
                  <td>".$row["civil_status"]."</td><td>".$row["youth_group"]."</td><td>".$row["education"]."</td></tr>";
            $count++;
        }
    } else {
        echo "<tr><td colspan='19'>No data found</td></tr>";
    }
    echo "</table></div></div>";
}

// Number of records per page
$limit = 10; 

// Get the current page number, default is 1
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$page = max(1, $page); // Ensure page is at least 1

// Calculate offset for SQL query
$offset = ($page - 1) * $limit;

// Count total records
$sql_count = "SELECT COUNT(*) AS total FROM sk_profiling";
$result_count = $conn->query($sql_count);
$row_count = $result_count->fetch_assoc();
$total_records = $row_count['total'];
$total_pages = ceil($total_records / $limit); // Total pages

// Fetch data with pagination
$sql_all = "SELECT sp.*, sd.civil_status, sd.youth_group, sd.education, sd.religion
            FROM sk_profiling sp
            LEFT JOIN sk_demographics sd ON sp.id = sd.user_id
            LIMIT $limit OFFSET $offset";

$result_all = $conn->query($sql_all);
$count = $offset + 1; // Row number based on pagination

echo "<div class='container'>";
echo "<h3>All Profiles</h3>";
echo "<div class='table-wrapper'>"; // Scrollable container
echo "<table>";
echo "<thead>
        <tr>
            <th>#</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Middle Name</th>
            <th>Suffix</th>
            <th>Purok</th>
            <th>Gender</th>
            <th>Age</th>
            <th>Birthday</th>
            <th>Email</th>
            <th>Contact</th>
            <th>Religion</th>
            <th>Hobbies</th>
            <th>Talent</th>
            <th>Guardian</th>
            <th>Guardian Contact</th>
            <th>Civil Status</th>
            <th>Youth Group</th>
            <th>Education</th>
        </tr>
      </thead>";
echo "<tbody>";

if ($result_all->num_rows > 0) {
    while ($row = $result_all->fetch_assoc()) {
        $row_class = ($count % 2 == 0) ? 'even' : 'odd'; // Zebra striping
        echo "<tr class='$row_class'>
                <td>".$count."</td>
                <td>".$row["fname"]."</td>
                <td>".$row["lname"]."</td>
                <td>".$row["mname"]."</td>
                <td>".$row["suffix"]."</td>
                <td>".$row["purok"]."</td>
                <td>".$row["gender"]."</td>
                <td>".$row["age"]."</td>
                <td>".$row["birthday"]."</td>
                <td>".$row["email"]."</td>
                <td>".$row["contact"]."</td>
                <td>".$row["religion"]."</td>
                <td>".$row["hobbies"]."</td>
                <td>".$row["talent"]."</td>
                <td>".$row["guardian"]."</td>
                <td>".$row["guardian_contact"]."</td>
                <td>".$row["civil_status"]."</td>
                <td>".$row["youth_group"]."</td>
                <td>".$row["education"]."</td>
              </tr>";
        $count++;
    }
} else {
    echo "<tr><td colspan='19'>No data found</td></tr>";
}

echo "</tbody>";
echo "</table></div>"; // Close table-wrapper

// Pagination
echo "<div class='pagination-container'>";
echo "<div class='pagination'>";

// Previous button
if ($page > 1) {
    echo "<a href='?page=".($page - 1)."'>&laquo; Prev</a>";
}

// Page numbers
for ($i = 1; $i <= $total_pages; $i++) {
    if ($i == $page) {
        echo "<a class='active' href='?page=$i'>$i</a>";
    } else {
        echo "<a href='?page=$i'>$i</a>";
    }
}

// Next button
if ($page < $total_pages) {
    echo "<a href='?page=".($page + 1)."'>Next &raquo;</a>";
}

echo "</div></div>"; // Close pagination-container
$conn->close();
?>

</body>
</html>
