#!/usr/bin/env php
<?php
declare(strict_types=1);
use Timeline\{HtmlBuilder};

include "vendor/autoload.php";

$dir = '/home/kurt/adocs-4-genealogy/r/p/';

$timeline_input = $dir . "timeline_input.txt";
        
$builder = new HtmlBuilder($dir . "timeline.adoc", new \Timeline\BlueTimelineBuildStrategy);

try {
    
 $builder->create_html("src/timeline-input.yml");
 
} catch (\Exception $e) {
    
 echo $e->getMessage();
 
}
