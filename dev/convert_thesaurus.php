<?php
$file = file('testfile.txt');

$myfile = fopen("testfile.php", "w");

fwrite($myfile, "<?php\n\$thesaurus = [\n");

foreach ($file as $row) {
	$matches = '';

	$row = explode(',', trim($row));

	$frist_word = $row[0];

	$synonyms = array_slice($row, 1);

	$string = "	\"{$frist_word}\" => [\"". implode("\", \"", $synonyms) . "\"],\n";

	fwrite($myfile, $string);
}

unset($file);

fwrite($myfile, '];');

fclose($myfile);
