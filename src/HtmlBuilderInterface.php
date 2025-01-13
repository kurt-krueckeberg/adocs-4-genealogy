<?php
declare(strict_types=1);
namespace Timeline;

interface HtmlBuilderInterface {
       
    function create_html(string $fname_yml);
   
}
