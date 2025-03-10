
    <div class="main-content">
        <div class="container">
        <h1>Package List</h1>
        <a href="/addPackage" class="linkButton">Add New Package</a>
        <table id="records-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Validity</th>
                    
                </tr>
            </thead>
            <tbody>
                <!-- Sample data rows -->
                <?php foreach ($records as $record) : ?>
                <tr>
                    <td><?= $record['name'] ?></td>
                    <td><?= $record['price'] ?></td>
                    <td><?= $record['validity'] ?> days</td>
                    
                </tr>
                <?php endforeach; ?>
                
                <!-- Additional rows can be added here -->
            </tbody>
        </table>

    
    </div>
    </div>
    </div>
   
