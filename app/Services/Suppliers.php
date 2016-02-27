<?php namespace Services;

class Suppliers extends \BaseController {

    public static $htmlTagAttr = [
        'data-original-title' => ["<button type='button' class='close pull-right m-l' data-dismiss='popover'>×</button>"],
        'title' => [''],
        'class' => ['btn', 'btn-rounded', 'btn-sm', 'btn-icon', 'btn-primary','m-l' ],
        'data-placement' => ['left'],
        'data-html' => ['true'],
        'data-toggle' => ['popover'],
        'data-content' => [],
    ];

    public function getPopoverBuilder($parameters)
    {
        $startOuterBegin = '<a';
        $endOuter = '</a>';
        $startOuterEnd = '>';
        $html = $startOuterBegin.' ';
        if($htmlAttr = self::$htmlTagAttr){
            $htmlAttr = array_merge($htmlAttr,$parameters);
            foreach($htmlAttr as $key => $values){
                if($key=='data-original-title') $values[] = $htmlAttr['popover-title'][0];
                $html .= $key.'="'.implode(" ", $values).'" ';
            }
        }
        $html .= $startOuterEnd.implode('', $htmlAttr['popover-button']).$endOuter;
        return $html;
    }
}