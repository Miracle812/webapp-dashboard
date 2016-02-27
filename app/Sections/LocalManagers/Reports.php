<?php namespace Sections\LocalManagers;

class Reports extends LocalManagersSection {

    public function __construct(){
        parent::__construct();
        $this -> breadcrumbs -> addCrumb('reports', 'Reports');
    }

    public function getIndex(){
        $staff = \Model\Staffs::where('unit_id','=',\Auth::user()->unit()->id)->get();
        $groups = ['pods'=>'Pods','probes'=>'Probes'];
        $breadcrumbs = $this -> breadcrumbs -> addLast( $this -> setAction('creator') );
        return \View::make($this->regView('index'), compact('staff','breadcrumbs','groups'));
    }

    public function postCreate()
    {
        $formData = \Input::get();
        $unit = $this->auth_user->unit();
        $reportsService = new \Services\ReportsGenerator();
        $reportsService -> form_data = $formData;
        $report = [$reportsService -> getReport()];
        $selectedReports = [$reportsService -> getSelectedReports()];
        $report=$report[0];
        $view =  \View::make($this->regView('report'), compact('report','unit','selectedReports'));
        $view = $view->render();
        $pdf = \App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($view);
        $pdf -> setOrientation('landscape')
             -> setOption('footer-right', 'Page: [page] / [toPage]')
             -> setOption('print-media-type', true)
             -> setOption('no-background', true);
        $pdf -> output();
        return $pdf->download('navitas-report.pdf');
    }
}