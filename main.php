<?php
// Handle AJAX request for voter ID validation
if (isset($_POST['check_voter_id'])) {
    $voter_id = $_POST['check_voter_id'];

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "votingsystem";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if voter ID exists
    $sql = "SELECT voter_id FROM voters WHERE voter_id = '$voter_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "exists"; // Voter ID exists
    } else {
        echo "available"; // Voter ID is available
    }

    $conn->close();
    exit; // Exit the script to prevent additional output
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['check_voter_id'])) {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "votingsystem";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve form data
    $name = $_POST['name'];
    $voter_id = $_POST['voter_id'];
    $department = $_POST['department'];
    $year = $_POST['year'];
    $password = $_POST['password'];

    // Insert data into the database
    $sql = "INSERT INTO voters (voter_id, name, department, year, password) 
            VALUES ('$voter_id', '$name', '$department', '$year', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration successful!');</script>";
    } else {
        echo "<script>alert('Error: Duplicate Voter ID or other issue.');</script>";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>VCW Union Election Voting</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f5f5f5;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow-x: hidden;
        }

        .header {
            background-color: green;
            color: white;
            width: 100%;
            text-align: center;
            padding: 20px;
        }
        .header img {
            width: 100px;
            height: 100px;
        }

        .header h1 {
            font-size: 24px;
            color: white;
            text-align: center;
        }

        .header p {
            color: white;
            text-align: center;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 500px;
            margin-top: 80px;
            align-items: flex-start;
            margin-top: 70px;

        }

        .form-group input {
            width: 100%;
            padding: 10px 40px 10px 10px; /* Space for the icon */
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-group .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 18px;
            color: #666;
        }

        .form-group label {
            flex: 1;
            font-weight: bold;
            text-align: left;
        }

        .form-group input,
        .form-group select {
            flex: 2;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-left:-2%;
            background-color: #fff;
        }

        .form-group .year-container {
            flex: 2;
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .form-group select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

.submit-btn {
    padding: 10px;
    background-color: green;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    width: 10%;
    text-align: center;
	margin-top: 1%;
}


        .submit-btn:hover {
            background-color: rgb(1, 89, 1);
        }

        .header-table {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .header-table td {
            padding: 0 10px;
        }

        .header p {
            margin: 5px 0 0 0;
        }
        
.toggle-password {
    position: absolute;
    top: 50%; /* Center align the eye icon vertically */
    right: 10px; /* Position the eye icon to the right */
    transform: translateY(-50%);
    cursor: pointer;
}
.error-message {
    color: red;
    font-size: 14px;
    margin-top: 15%; 
	margin-left: 47%;/* Spacing below the input field */
    display: none; /* Initially hidden */
    text-align: left; /* Aligns with input field */
    position: absolute; /* Positions it relative to the parent container */
}


        .form-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            position: relative;
        }


    </style>
    <script>
        function toggleYearOptions() {
            const department = document.getElementById("department").value;
            const yearSelect = document.getElementById("year-select");

            // Reset the year dropdown
            yearSelect.innerHTML = '';

            // Add the "Select Year" option
            const selectOption = document.createElement("option");
            selectOption.value = "";
            selectOption.textContent = "Select Year";
            yearSelect.appendChild(selectOption);

            // Add UG and PG groups
            const ugGroup = document.createElement("optgroup");
            ugGroup.label = "UG";
            const pgGroup = document.createElement("optgroup");
            pgGroup.label = "PG";

            // Check if the department allows PG options
            const allowsPG = ["English", "Commerce", "History"].includes(department);

            if (allowsPG) {
                // Both UG and PG options for these departments
                const ugOption1 = document.createElement("option");
                ugOption1.value = "I";
                ugOption1.textContent = "I (UG)";
                ugGroup.appendChild(ugOption1);

                const ugOption2 = document.createElement("option");
                ugOption2.value = "II";
                ugOption2.textContent = "II (UG)";
                ugGroup.appendChild(ugOption2);

                const ugOption3 = document.createElement("option");
                ugOption3.value = "III";
                ugOption3.textContent = "III (UG)";
                ugGroup.appendChild(ugOption3);

                const pgOption1 = document.createElement("option");
                pgOption1.value = "I (PG)";
                pgOption1.textContent = "I (PG)";
                pgGroup.appendChild(pgOption1);

                const pgOption2 = document.createElement("option");
                pgOption2.value = "II (PG)";
                pgOption2.textContent = "II (PG)";
                pgGroup.appendChild(pgOption2);
            } else if (department) {
                // UG options for other departments
                const ugOption1 = document.createElement("option");
                ugOption1.value = "I";
                ugOption1.textContent = "I (UG)";
                ugGroup.appendChild(ugOption1);

                const ugOption2 = document.createElement("option");
                ugOption2.value = "II";
                ugOption2.textContent = "II (UG)";
                ugGroup.appendChild(ugOption2);

                const ugOption3 = document.createElement("option");
                ugOption3.value = "III";
                ugOption3.textContent = "III (UG)";
                ugGroup.appendChild(ugOption3);
            }

            // Append the groups to the select dropdown
            yearSelect.appendChild(ugGroup);
            if (allowsPG) {
                yearSelect.appendChild(pgGroup); // Only append PG group if applicable
            }
        }

      
    function checkVoterID() {
        const voterIDInput = document.getElementById('voter_id');
        const voterID = voterIDInput.value;

        if (voterID) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "", true); // The "" refers to the same PHP page.
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    if (xhr.responseText === "exists") {
                        alert("Voter ID already exists! Please use a different ID.");
                        voterIDInput.value = ""; // Clear the input if the ID exists.
                    }
                }
            };
            xhr.send("check_voter_id=" + voterID); // Send the voter ID to the server.
        }
    }
    
</script>

</head>

<body>
<div class="header">
        <table class="header-table">
            <tr>
                <td><img src="leftlogo.jpeg" alt="College Logo1"></td>
                <td>
                    <h1>VELLALAR COLLEGE FOR WOMEN</h1>
                    <p>College with Potential for Excellence | An ISO 9001:2015 Certified Institution Re-accredited with
                        'A+' Grade (CYCLE IV) by NAAC & Affiliated with Bharathiyar University, Coimbatore</p>
                </td>
                <td><img src="rightlogo.jpeg" alt="College Logo2"></td>
            </tr>
        </table>
    </div>

    <div class="container">
        <form method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>
            </div>
            <div class="form-group">
                <label for="department">Department</label>
                <select id="department" name="department" onchange="toggleYearOptions()" required>
                    <option value="">Select Department</option>
                    <option value="Botany">Botany</option>
                    <option value="Zoology">Zoology</option>
                    <option value="Physics">Physics</option>
                    <option value="Chemistry">Chemistry</option>
                    <option value="Computer Science">Computer Science</option>
                    <option value="Nutrition & Dietetics">Nutrition & Dietetics</option>
                    <option value="History">History</option>
                    <option value="English">English</option>
                    <option value="Commerce">Commerce</option>
                    <option value="Mathematics">Mathematics</option>
                </select>
            </div>
            <div class="form-group">
                <label for="year">Year</label>
                <select id="year-select" name="year" required>
                    <option value="">Select Year</option>
                </select>
            </div>
            <div class="form-group">
                <label for="voter_id">Voter ID </label>
                <input type="text" id="voter_id" name="voter_id" placeholder="22UCS001" oninput="checkVoterID()" required>
                </div>
       
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <i class="fas fa-eye toggle-password" id="togglePassword1"></i>
        </div>

        <div class="form-group">
    <label for="confirmPassword">Confirm Password</label>
    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" required>
    <div class="error-message" id="errorMessage">Passwords do not match!</div>
    <i class="fas fa-eye toggle-password" id="togglePassword2"></i>
</div>

</div>

    <script>
        // Toggling password visibility
document.querySelectorAll('.toggle-password').forEach(toggle => {
    toggle.addEventListener('click', function () {
        const input = this.parentElement.querySelector('input'); // Correctly select the input field
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
});

// Password matching validation
const passwordField = document.getElementById('password');
const confirmPasswordField = document.getElementById('confirmPassword');
const errorMessage = document.getElementById('errorMessage');

confirmPasswordField.addEventListener('input', () => {
    if (confirmPasswordField.value !== passwordField.value) {
        errorMessage.style.display = 'block'; // Show the error message
    } else {
        errorMessage.style.display = 'none'; // Hide the error message
    }
});

    </script>
            <button type="submit" class="submit-btn">Submit</button>
        </form>
    </div>
</body>

</html>
 