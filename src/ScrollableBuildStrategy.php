<?php
declare(strict_types=1);
namespace Timeline;

class ScrollableBuildStrategy implements BuildStrategyInterface  {
    
    static private $article_html = [ 'nav_insert' => "++++
<div class='timeline'>
  <nav class='timeline__nav'>
    <ul>\n",

   'milestones_insert' =>
    "<ul>
  </nav>
  <div class='timeline__section'>
     <div class='wrapper'>\n",

    'article_end' =>
     "</div>
  </div>
</div>
<script type='text/javascript' src='http://jquerypost.com/cdn/jquery-1.12.4.min.js'></script>
<script type='text/javascript'>
      $(() => {
  let stickyTop = 0,
  scrollTarget = false;

  let timeline = $('.timeline__nav'),
  items = $('li', timeline),
  milestones = $('.timeline__section .milestone'),
  offsetTop = parseInt(timeline.css('top'));

  const TIMELINE_VALUES = {
    start: 190,
    step: 30 };


  $(window).resize(function () {
    timeline.removeClass('fixed');

    stickyTop = timeline.offset().top - offsetTop;

    $(window).trigger('scroll');
  }).trigger('resize');

  $(window).scroll(function () {
    if ($(window).scrollTop() > stickyTop) {
      timeline.addClass('fixed');
    } else {
      timeline.removeClass('fixed');
    }
  }).trigger('scroll');

  items.find('span').click(function () {
    let li = $(this).parent(),
    index = li.index(),
    milestone = milestones.eq(index);

    if (!li.hasClass('active') && milestone.length) {
      scrollTarget = index;

      let scrollTargetTop = milestone.offset().top - 80;

      $('html, body').animate({ scrollTop: scrollTargetTop }, {
        duration: 400,
        complete: function complete() {
          scrollTarget = false;
        } });

    }
  });
  $(window).scroll(function () {
    let viewLine = $(window).scrollTop() + $(window).height() / 3,
    active = -1;

    if (scrollTarget === false) {
      milestones.each(function () {
        if ($(this).offset().top - viewLine > 0) {
          return false;
        }

        active++;
      });
    } else {
      active = scrollTarget;
    }

    timeline.css('top', -1 * active * TIMELINE_VALUES.step + TIMELINE_VALUES.start + 'px');

    items.filter('.active').removeClass('active');

    items.eq(active != -1 ? active : 0).addClass('active');
  }).trigger('scroll');
});\n
</script>
++++"];

    private string $nav_rows;

    private string $milestones;
    
    function __construct()
    {              
       $this->milestones = $this->nav_rows = '';
    }
    
    private function add_year_milestones(int $year, array $milestones) : void
    {
        $this->nav_rows .= "<li><span>$year</span><li>\n";

        $this->milestones .= "<h2 class='milestone'>$year</h2>\n";

        foreach($milestones as $milestone) {

            $this->milestones .= "  <p>$milestone</p>\n";
        }
    } 

    private function build_content() : string
    {
        $content = self::$article_html['nav_insert'];

        $content .= $this->nav_rows;  

        $content .= self::$article_html['milestones_insert'];  

        $content .= $this->milestones;

        $content .= self::$article_html['article_end'];  

        return $content;
    } 
    
    function build_html(string $fname_yml) : string
    {
       $yml_array = \yaml_parse_file($fname_yml);
    
       foreach($yml_array['timeline'] as $item)  {
         
          $this->add_year_milestones($item['year'], $item['milestones']);
       }
       
       $content = $yml_array['page_start'] ."\n";  

       $content .= $this->build_content(); 

       return $content;
    }
}
