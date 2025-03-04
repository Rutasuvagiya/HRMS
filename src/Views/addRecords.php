<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Health Records</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="health-container">
        <h1>Personal Health Records</h1>
        <form action="submit_health_record.php" method="POST">
            <label for="patientName">Patient Name:</label>
            <input type="text" id="patientName" name="patientName" required>

            <label for="dob">Age:</label>
            <input type="text" id="age" name="age" required>

            <label for="gender">Gender:</label>
            <input type="radio" name="gender" value="Male" > Male
            <input type="radio" name="gender" value="Female" > Female
            <input type="radio" name="gender" value="Other" > Other

            <label for="allergies">Allergies:</label>
            <textarea id="allergies" name="allergies"></textarea>

            <label for="medications">Current Medications:</label>
            <textarea id="medications" name="medications"></textarea>

            <label for="emergency-contact">Emergency Contact:</label>
            <input type="text" id="emergencyContact" name="emergencyContact" required>

     