<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Records Listing</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Health Records</h1>
        <div class="search-bar">
            <input type="search" id="search" placeholder="Search records..." aria-label="Search health records">
        </div>
        <table id="records-table">
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Date of Birth</th>
                    <th>Blood Type</th>
                    <th>Allergies</th>
                    <th>Medications</th>
                    <th>Emergency Contact</th>
                </tr>
            </thead>
            <tbody>
                <!-- Sample data rows -->
                <tr>
                    <td>John Doe</td>
                    <td>01/15/1985</td>
                    <td>O+</td>
                    <td>Peanuts</td>
                    <td>Ibuprofen</td>
                    <td>Jane Doe</td>
                </tr>
                <tr>
                    <td>Mary Smith</td>
                    <td>03/22/1990</td>
                    <td>A-</td>
                    <td>None</td>
                    <td>None</td>
                    <td>John Smith</td>
                </tr>
                <!-- Additional rows can be added here -->
            </tbody>
        </table>
    </div>
    <script src="script.js"></scri
