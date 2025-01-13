<?php
declare(strict_types=1);
namespace Timeline;

class HtmlBuilder implements HtmlBuilderInterface {
    
    private BuildStrategyInterface $strategy;
    
    private string $page_header;
    
    function __construct(string $ofname, BuildStrategyInterface $strat_in) //string $header)
    {
        $this->ofile = new \SplFileObject($ofname, "w");
                
        $this->strategy = $strat_in;
    }
    
    function create_html(string $fname_yml)
    {
        $content = $this->strategy->build_html($fname_yml);

        $this->ofile->fwrite($content);    
    }
}
