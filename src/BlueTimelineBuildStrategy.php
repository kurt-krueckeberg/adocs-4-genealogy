<?php
declare(strict_types=1);
namespace Timeline;

class BlueTimelineBuildStrategy implements BuildStrategyInterface  {
    
 private static $content_start =<<< START
++++
<div class="tl-wrapper">
  <div class="center-line">
      <a href="#" class="scroll-icon"><i class="fas fa-caret-up"></i></a>
  </div>
START;

 private static $content_end =<<< END
</div>
++++
END;

 private static $section_start =<<< SECTIONBEGIN
  <div class="row row-%d">
    <section>
      <i class="icon %s"></i>
      <div class="details">
        <span class="title">%s</span>
        <span>%s</span>
      </div>
      <p>%s</p>
      <div class="bottom">
SECTIONBEGIN;

 private static $section_end =<<< SECTIONEND
        <i></i>
      </div>
    </section>
  </div>
SECTIONEND;

  /*
    Maps the 'icon: name' to fontawesome class name for <i class="icon ...."<</i>
   */

  private static  $iconMap = [
'male' => 'fa-solid fa-mars',
'female' => 'fa-solid fa-venus',
'residence' => 'fa-solid fa-house',
'flag' => 'fa-solid fa-flag',
'war' => 'fa-solid fa-person-military-rifle',
'landmark' => 'fa-solid fa-landmark-dome',
'cross' => 'fa-solid fa-cross',
'ship' => 'fa-solid fa-ship',
'marriage' => 'fa-solid fa-ring',
'calendar' => 'fa-solid fa-calendar-days'];
  
  function __construct()
  {              
     $this->is_left = true; 
  }
    
  private function create_section(array $input) : string
  {  
     $row_id = $this->is_left ? 1 : 2;
            
     $this->is_left = !$this->is_left;  

     $icon_class = self::$iconMap[$input['icon']];
         
     $section_start = sprintf(self::$section_start,
             $row_id,
               $icon_class,
             $input["title"],           
             $input['date'],
             $input['content']);

     $section_citation = "\n++++\n" . $input["citation"] . "\n++++\n";

     //-- $section_end = sprintf(self::$section_end, " filler ");

     $section_end = self::$section_end;

     return $section_start . $section_citation . $section_end;
   }
    
    function build_html(string $fname_yml) : string
    {
       $yml_array = \yaml_parse_file($fname_yml);
       
       $content = $yml_array['page_start'] ."\n";  
    
       $sections = self::$content_start . "\n"; 
       
       foreach($yml_array['timeline'] as $item)  {
         
          $sections .= $this->create_section($item) . "\n";
       }
       
       $content .= $sections;
       
       $content .= self::$content_end;
       
       return $content;
    }
}
