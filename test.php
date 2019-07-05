
<?php

$handle = fopen("text.txt", "w");
for ($i=0; $i < 5000000; $i++) { 
	fwrite($handle, "test@gmail.com".PHP_EOL);
}
fclose($handle);
