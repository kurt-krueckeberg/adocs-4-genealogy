<?php
declare(strict_types=1);
namespace Timeline;

interface BuildStrategyInterface {
       
    function build_html(string $fname_yml) : string;
}
