<?php
header("X-Sendfile: $file_name");
header("Content-type: application/octet-stream");
header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');

?>