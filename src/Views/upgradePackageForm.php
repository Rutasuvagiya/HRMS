<div class="health-container">
    <?php if (!empty($records)): ?>
    <h2>Upgrade Your Package</h2>
    <form method="POST" action="upgradeMyPackage">
        <label>Select New Package:</label>
       
        <select name="package" class="form-dropdown">
            <?php foreach ($records as $package): ?>
                <option value="<?php echo htmlspecialchars($package['id']); ?>">
                    <?php echo htmlspecialchars($package['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Upgrade</button>
    </form>
<?php else: ?>
    <p>No packages available for upgrade.</p>
<?php endif; ?>
</div>
