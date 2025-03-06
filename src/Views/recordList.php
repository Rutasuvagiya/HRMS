
    <div class="dashboard-container">

        <h1>Health Records</h1>
        <?php if (!empty($generalMessage)): ?>
            <p class="general-error"><?= $generalMessage ?></p>
        <?php endif; ?>
        <a href="/addRecord" class="linkButton">Add new Record</a><br/><br/>
        <table id="records-table">
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Allergies</th>
                    <th>Medications</th>
                    <th>Attachments</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <!-- Sample data rows -->
                <?php foreach ($records as $record): ?>
                <tr>
                    <td><?= $record['patient_name'] ?></td>
                    <td><?= $record['age'] ?></td>
                    <td><?= $record['gender'] ?></td>
                    <td><?= $record['allergies'] ?></td>
                    <td><?= $record['medications'] ?></td>
                    <td><?php if (!empty($record['attachment'])): ?>
                        <a href="<?= htmlspecialchars($record['attachment']) ?>" target="_blank">View</a>
                    <?php else: ?>
                        No Attachment
                    <?php endif; ?></td>
                    <td><a href="/editRecord?id=<?= $record['id'] ?>">Edit</a></td>
                </tr>
                <?php endforeach; ?>
                
                <!-- Additional rows can be added here -->
            </tbody>
        </table>
    </div>

