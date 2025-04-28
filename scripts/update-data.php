<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use Zhineng\RegionChina\Build\CodeToNameHandler;
use Zhineng\RegionChina\Build\RelationshipHandler;
use Zhineng\RegionChina\Build\TopLevelHandler;

$doc = new \DOMDocument;
$doc->loadHTMLFile(__DIR__.'/../resources/raw.html', \LIBXML_NOERROR);

/** @var \Zhineng\Region\Build\RegionHandler */
$handlers = [
    $codeToNameHandler = new CodeToNameHandler,
    $topLevelHandler = new TopLevelHandler,
    $relationshipHandler = new RelationshipHandler,
];

$count = 0;

$rows = $doc->getElementsByTagName('tr');

foreach ($rows as $row) {
    $columns = $row->getElementsByTagName('td');

    $columnCode = $columns->item(1);
    $columnName = $columns->item(2);

    if (! $columnCode instanceof \DOMElement) {
        continue;
    }

    if (! $columnName instanceof \DOMElement) {
        continue;
    }

    $code = mb_trim($columnCode->textContent);

    if (! is_numeric($code)) {
        continue;
    }

    $name = rtrim(mb_trim($columnName->textContent), '*');

    $count++;

    foreach ($handlers as $handler) {
        $handler->handle($code, $name);
    }
}

printf("- Export code to name map\n");
$codeToNameHandler->export(__DIR__.'/../resources/code-to-name.php');

printf("- Export top level list\n");
$topLevelHandler->export(__DIR__.'/../resources/top-levels.php');

printf("- Export relationship map\n");
$relationshipHandler->export(__DIR__.'/../resources/relationships.php');

printf("- Processed %d regions.\n", $count);
