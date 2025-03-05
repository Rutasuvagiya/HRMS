

<div class="health-container">
<h1>My Package</h1>
<?php if (!empty($generalMessage)): ?>
    <p class="general-error"><?= $generalMessage ?></p>
<?php endif; ?>
<p>Package Name: <?= $records['name']?></p>
<p>Price: &#8377;<?= $records['price']?></p>
<p>Start Date: <?= date('d-m-Y', strtotime($records['start_date']))?></p>
<p>End Date: <?= date('d-m-Y', strtotime($records['end_date']))?></p>
</div>
       
   
   