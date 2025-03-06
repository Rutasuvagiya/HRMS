
    <div class="health-container">
        <h1>Personal Health Records</h1>
        <?php if (!empty($generalMessage)): ?>
            <p class="general-error"><?= $generalMessage ?></p>
        <?php endif; ?>
        <form action="submitHealthRecord" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="id" name="id" value="<?= ($input['id']??'') ?>">
            <label for="patient_name">Patient Name:</label>
            <span class="error"><?= $error['patient_name'] ?? '' ?></span>
            <input type="text" id="patient_name" name="patient_name" value="<?= htmlspecialchars($input['patient_name']??'') ?>" required>

            <label for="age">Age:</label>
            <span class="error"><?= $error['age'] ?? '' ?></span>
            <input type="text" id="age" name="age" value="<?= htmlspecialchars($input['age']??'') ?>" required>

            <label for="gender">Gender:</label>
            <span class="error"><?= $error['gender'] ?? '' ?></span>
            <select name="gender" class="form-dropdown">

                <option value="Male" <?= (isset($input['age']) && $input['gender'] =='Male')?'selected':''; ?> >Male</option>
                <option value="Female" <?= (isset($input['age']) && $input['gender'] =='Female')?'selected':''; ?>>Female</option>
                <option value="Other" <?= (isset($input['age']) && $input['gender'] =='Other')?'selected':''; ?>>Other</option>
            </select>

            <label for="allergies">Allergies:</label>
            <span class="error"><?= $error['allergies'] ?? '' ?></span>
            <textarea id="allergies" name="allergies"><?= htmlspecialchars($input['allergies']??'') ?></textarea>

            <label for="medications">Current Medications:</label>
            <span class="error"><?= $error['medications'] ?? '' ?></span>
            <textarea id="medications" name="medications"><?= htmlspecialchars($input['medications']??'') ?></textarea>

            <label>Attachment (PDF/JPG/PNG/GIF): <input type="file" name="attachment" ></label><br>
            <span class="error"><?= $error['attachment'] ?? '' ?></span>
            <button type="submit" name="addRecord">Save</button>
        </form>
    </div>


     