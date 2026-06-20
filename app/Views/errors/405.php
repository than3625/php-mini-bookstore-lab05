<?php ob_start(); ?>
<h1>405 - Method Not Allowed</h1>
<p>Sorry, the HTTP method used for this request is not allowed for this URL.</p>
<a href="/" class="btn primary">Back to dashboard</a>
<?php
$content = ob_get_clean();
$title = 'Method Not Allowed';
require __DIR__ . '/../layout.php';
?>
