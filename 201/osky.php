/** console.php **/
#!/usr/bin/env php
<?php
require_once __dir__ . '/vendor/autoload.php';


use Symfony\Component\Console\Application;
use Osky\SearchCommand;

$app = new Application('Console App', 'v1.0.0');
$app -> add(new SearchCommand());

$app -> run();