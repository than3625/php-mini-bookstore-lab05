<?php ob_start(); ?>
<h1>Something went wrong</h1>
<p>Sorry, we could not process your request right now.</p>
<a href="/">Back to dashboard</a>
<?php
$content = ob_get_clean();
$title = 'Server Error';
require __DIR__ . '/../layout.php';
?>