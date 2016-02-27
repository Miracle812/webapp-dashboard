<?php
namespace Services;

class PdfGenerator extends \BaseController {

    public static function getPdf($target)
    {
        $self = new self();
        $data = $self -> prepareData($target);
        $pdf = $self -> generate($data);
        return $pdf;
    }
    public function prepareData($target)
    {
        $table  = $target -> getTable();
        switch ($table) {
            case  'knowledges' :
                $title   = $target -> title;
                $tType   = $target -> target_type;
                $content = ['content_one.'.$tType => $target -> content_one ,'content_two.'.$tType => $target -> content_two];
                break;

            default : $title = ''; $content = ''; break;
        }
        return [$table => ['title' => $title, 'content'=>$content]];
    }

    public function contentBuilder($data)
    {
        $html = '';
        //$html .= $this -> getHtmlTags('start');
        //$html .= $this -> getHtmlTags('content',$data);
        //$html .= $this -> getHtmlTags('end');
        return $html;
    }

    public function getHtmlTags($type, $data=null)
    {

    }

    public function generate($data)
    {
        $view = \View::make('pdf.common', compact('data'));
        $view = $view -> render();
        $pdf  = \App::make('snappy.pdf.wrapper');
        $pdf -> loadHTML($view);
        $pdf -> setOrientation('portrait')
             -> setOption('footer-right', 'Page: [page] / [toPage]');
        $pdf -> output();
        return $pdf->download('navitas.pdf');
    }
}