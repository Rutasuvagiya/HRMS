
        <div class="card">
            <h2>Packages</h2>
            <table id="records-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Validity</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php foreach ($package as $record) : ?>
                <tr>
                    <td><?= $record['name'] ?></td>
                    <td><?= $record['price'] ?></td>
                    <td><?= $record['validity'] ?> days</td>
                    
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <div class="card">
        <h2>Patient Health Records</h2>
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
                    
                </tr>
                <?php endforeach; ?>
                
                <!-- Additional rows can be added here -->
            </tbody>
        </table>
        </div>
        
    </div>
</body>
</html>
