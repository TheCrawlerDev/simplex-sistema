<?php
set_time_limit(0);
function shell($comm){
	$output = shell_exec($comm);
	return "<pre>$output</pre>";
}
// $output = shell_exec('cat lighthouse-sites/report.json');
// echo "<pre>$output</pre>";
// echo exec('lighthouse --help');
// $output = shell_exec('lighthouse https://airhorner.com/');
// echo "<pre>$output</pre>";
echo shell("lighthouse https://www.zoom.com.br/ --output json --output-path ./report.json
");
?>