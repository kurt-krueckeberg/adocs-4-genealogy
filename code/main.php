<?php
declare(strict_types=1);
include_once 'config.php';

function goto_regex(\SplFileObject $file, string $regex) : void
{ 
   foreach($file as $line) {
            
       if (preg_match( $regex, $line) === 1)   
           break;
   }
}

class TimelineCreator {

   private \SplFileObject $ofile;

   public function __construct(string $outfile, string $header)
   {      
      $this->ofile = new \SplFileObject($outfile, "w");

      $this->ofile->fwrite($header . "\n");
   }

   public function __invoke(string $fname)
   {
      $file = new \SplFileObject($fname, "r");
      
      $header = $file->fgets();

      $this->ofile->fwrite("=" . $header);
      
      goto_regex($file, "@^== Family Relationship@");
      
      while (!$file->eof()) { // foreach would call rewind()!
      
         $line = $file->fgets();
          
         // We exit when a line starts with "== "
         $rc = preg_match("/^== /", $line); 
      
         if ($rc === 1) 
             break;
      
          $this->ofile->fwrite($line);
      }
   }
}

foreach ($config['timelines'] as $timeline) {
    
        $creator = new TimelineCreator($config['output_folder'] . $timeline['output_file'], $timeline['page_header']);
        
        $input_folder = $timeline['input_folder'];
        
        foreach($timeline['input_files'] as $file) {
         
           $creator($input_folder . $file);  
        }
}
