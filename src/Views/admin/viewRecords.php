

    <div class="main-content">
        <div class="container">
        <h1>Patient Health Records</h1>
        
        <table id="records-table">
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Allergies</th>
                    <th>Medications</th>
                    <th>Attachments</th>
                    <th>Created Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <!-- Sample data rows -->
                <?php foreach ($records as $record) : ?>
                <tr>
                    <td><?= $record['patient_name'] ?></td>
                    <td><?= $record['age'] ?></td>
                    <td><?= $record['gender'] ?></td>
                    <td><?= $record['allergies'] ?></td>
                    <td><?= $record['medications'] ?></td>
                    <td><?php if (!empty($record['attachment'])) : ?>
                <a href="<?= htmlspecialchars($record['attachment']) ?>" target="_blank">View</a>
                        <?php else : ?>
                No Attachment
                        <?php endif; ?></td>
                    <td><?= date('Y-m-d H:i:s', strtotime($record['created_at']));?></td>
                    <td><button onClick="viewHistory(<?= $record['id']; ?>)">View History</button></td>
                </tr>
                <?php endforeach; ?>
                
                <!-- Additional rows can be added here -->
            </tbody>
        </table>

        <!-- Overlay -->
    <div id="overlay" class="overlay" onclick="closePopup()"></div>

    <!-- Popup -->
    <div id="popup" class="popup">
        <div id="popup-content"></div>
        <button onclick="closePopup()">Close</button>
    </div>
    </div>
    </div>
    </div>
    <script>
        // Function to fetch and display record history in popup
        function viewHistory(recordId) {
            // Create an XMLHttpRequest object
            var xhttp = new XMLHttpRequest();
            // Define a callback function
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Insert response text into the popup
                    document.getElementById("popup-content").innerHTML = this.responseText;
                    // Display the popup and overlay
                    document.getElementById("popup").style.display = "block";
                    document.getElementById("overlay").style.display = "block";
                }
            };
            // Send an AJAX request
            xhttp.open("GET", "getLog?id=" + recordId, true);
            xhttp.send();
        }

        // Function to close the popup
        function closePopup() {
            document.getElementById("popup").style.display = "none";
            document.getElementById("overlay").style.display = "none";
        }
    </script>
