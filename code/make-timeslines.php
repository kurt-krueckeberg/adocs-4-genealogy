<?php
declare(strict_types=1);

function goto_regex(\SplFileObject $file, string $regex) : void
{ 
   foreach($file as $line) {
            
       if (preg_match( $regex, $line) === 1)   
           break;
   }
}

class TimelineCreator {

   private \SplFileObject $ofile;
   private string $module;

   public function __construct(string $outfile, string $header, string $module)
   {      
      $this->ofile = new \SplFileObject($outfile, "w");

      $this->ofile->fwrite($header . "\n");

      $this->module = $module;
   }

   public function __invoke(string $input_folder, string $fname)
   {
      $file = new \SplFileObject($input_folder . $fname, "r");
      
      $line = $file->fgets();

      $line =  rtrim($line); // remove newline and whitespace from right.

      $header = substr($line,2); // skip over "= ".
     
      $xref = "== xref:" . $this->module . ":" . $fname . '[' . $header . ']' . "\n";

      $this->ofile->fwrite($xref);
      
      goto_regex($file, "@^== Family Relationship@");
      
      while (!$file->eof()) { // foreach would call rewind()!
      
         $line = $file->fgets();
          
         // We exit when a line starts with "== "
         $rc = preg_match("/^== /", $line);  
              
         if ($rc === 1) 
             break;

          // Convert any "xref:petzen-" to be "xref:petzen:petzen-"
         $line = preg_replace('@(xref):(petzen-)@', "$1:petzen:$2", $line);

         $this->ofile->fwrite($line);
      }
   }
}

$config = \yaml_parse_file('./config.yml');

foreach ($config['timelines'] as $timeline) {
    
        $creator = new TimelineCreator($config['output_folder'] . '/' . $timeline['output_file'], $timeline['page_header'], $timeline['module']);
        
        $input_folder = $config['input_folder'] . '/modules/' . $timeline['module'] . '/pages/';
        
        foreach($timeline['input_files'] as $file) {
         
           $creator($input_folder, $file);  
        }
}
