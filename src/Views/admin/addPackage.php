
    <div class="main-content">
    <div class="package-container">
        <h2>Add New Package</h2>
        <br/>
        <?php if (!empty($generalMessage)) : ?>
            <p class="general-error"><?= $generalMessage ?></p>
        <?php endif; ?>
        <form action="/savePackage" method="POST">
            <label for="name">Name:</label>
            <span class="error"><?= $error['name'] ?? '' ?></span>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
            

            <label for="price">Price:</label>
            <span class="error"><?= $error['price'] ?? '' ?></span>
            <input type="text" id="price" name="price" value="<?= htmlspecialchars($_POST['price'] ?? '') ?>" required>
            

            <label for="validity">Validity(In Days):</label>
            <span class="error"><?= $error['validity'] ?? '' ?></span>
            <input type="text" id="validity" name="validity" value="<?= htmlspecialchars($_POST['validity'] ?? '') ?>" required>
            
            <button type="submit" name="save">Save</button>
        </form>
    </div>
    </div>
</body>
</html>
