<?php namespace Sections\Visitors;

class Trainings extends VisitorsSection
{

    public function __construct()
    {
        parent::__construct();
        $this->breadcrumbs->addCrumb('trainings', 'Trainings');
    }

    public function getIndex()
    {
        $staff = \Model\Staffs::where('unit_id', '=', \Auth:: user()->unit()->id);
        if (!$staff->count())
            return \Redirect::to('/trainings')->withErrors([\Lang::get('/common/messages.not_exist')]);;
        $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('list'));
        return \View::make($this->regView('index'), compact('staff', 'breadcrumbs'));
    }

    public function getList($id = null)
    {
        $staff = null;
        if ($id) {
            $staff = \Model\Staffs::find($id);
            if (!$staff || !$staff->checkAccess()) {
                return $this->redirectIfNotExist();
            }
            $this->breadcrumbs->addCrumb('/trainings/list/' . $staff->id, $staff->fullname());
        }
        $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('datatable'));
        return \View::make($this->regView('list'), compact('breadcrumbs', 'id'));
    }

    public function getDatatable($id = null)
    {
        $trainings = \Model\TrainingRecords::where('unit_id', '=', $this->auth_user->unit()->id);
        if ($id)
            $trainings = $trainings->where('staff_id', $id);
        $trainings = $trainings->get();
        if (!$trainings->count())
            return \Response::json(['aaData' => []]);

        $trainings = ($filter = \Input::get('datatable')) ? \Mapic::datatableFilter($filter, $trainings) : $trainings -> take(100);
        foreach ($trainings as $row) {
            $isExpired = $row->is_expired();
            $options[] = [
                strtotime($row->created_at),
                $row->created_at(),
                $row->staff->fullname(),
                $row->name,
                $row->date_start == '0000-00-00 00:00:00' ? 'N/A' : date('Y-m-d', strtotime($row->date_start)),
                $row->date_finish == '0000-00-00 00:00:00' ? 'N/A' : date('Y-m-d', strtotime($row->date_finish)),
                $row->date_refresh == '0000-00-00 00:00:00' ? 'N/A' : date('Y-m-d', strtotime($row->date_refresh)) . ($isExpired ? \HTML::ownOuterBuilder(' (Expired) ', 'span', 'text-danger') : ''),
                \HTML::ownOuterBuilder(
                    \HTML::ownButton($row->id, 'trainings', 'details', 'fa-search', 'btn-default')
                )
            ];
        }

        if (isset($options))
            return \Response::json(['aaData' => $options]);
        else
            return \Response::json(['aaData' => []]);
    }

    public function getDetails($id)
    {
        $training = \Model\TrainingRecords::find($id);
        if (!$training || !$training->checkAccess()) {
            return $this->redirectIfNotExist();
        }
        $files = $training->files;
        $staff = $training->staff;

        $this->breadcrumbs->addCrumb('/trainings/list/' . $staff->id, $staff->fullname());
        $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('details'));
        return \View::make($this->regView('details'), compact('training', 'files', 'staff', 'breadcrumbs'));
    }

    public function getTrainings($id_staff)
    {
        $staff = \Model\Staffs::find($id_staff);
        $trainings = $staff ? $staff->training_records()->get() : [];
        $this->params = [
            'staff' => $staff ?: [],
            'trainings' => $trainings
        ];
        $this->view = 'trainings';
        return $this->showView();
        return \View::make($this->regView('list'), compact('breadcrumbs'));
    }


}