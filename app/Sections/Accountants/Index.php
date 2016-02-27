<?php namespace Sections\Accountants;
use Illuminate\Support\Collection;
class Index extends AccountantsSection {

    public function getIndex()
    {
        $invoices  = \Model\XeroInvoices::all();
        $chartDays = $this->getGraphData($invoices)['days'];
        $chartQuarters = $this->getGraphData($invoices)['quarters'];
        $chartMonths = $this->getGraphData($invoices)['months'];
        $invs['incoming'] = $this->getInvoices($invoices,'incoming',6);
        $invs['overdue'] = $this->getInvoices($invoices,'overdue',6);
        $calendar = $this->getCalendarInvoices($invoices);
        $paidInvoices = $invoices->filter(function($item){
            return ($item->Status == 'PAID');
        })->sortByDesc('FullyPaidOnDate')->take(20);
        $topboxes  = ['week','month','paid','overdue'];
        foreach($topboxes as $key => $topbox){
            $topboxes[$topbox] = $this->topBox($topbox,$invoices);
            unset($topboxes[$key]);
        }

        $topboxes['completed'] = (($topboxes['paid']['sum'] / ($topboxes['paid']['sum'] + $topboxes['overdue']['sum']))*100);

        $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('Clients list', false));
        $this->layout->content = \View::make($this->regView('index'), compact('breadcrumbs','topboxes','chartDays','chartMonths','chartQuarters','invs','calendar','paidInvoices'));
    }

    public function getCalendarInvoices($collection)
    {
        $groupedInvoices =  $collection->groupBy(function($date) {
            return \Carbon::parse($date->DueDate)->format('Y-m-d');
        });
        $data=[];
        foreach($groupedInvoices as $date => $items){
            $items = Collection::make($items);
            foreach ($items as $item){
                $data[] = ['date'=>$date, 'title'=>$item->contact->Name.' - '.$item->Total.$item->CurrencyCode, 'url'=> 'javascript:void(0)', 'timeStart'=>'', 'timeEnd'=>''];
            }
        }
        return $data;
    }

    public function getInvoices($collection,$type,$limit)
    {
        return $collection->filter(function($item)use($type){
            $item->DateStatus = $this->getDateStatus($item, 'DueDate');
            switch ($type){
                case 'incoming':
                    return ((\Carbon::parse($item->DueDate) > \Carbon::now())&&($item->Status == 'AUTHORISED'));
                    break;
                case 'overdue':
                    return ((($item->Status) == 'AUTHORISED') && (\Carbon::parse($item->DueDate) < \Carbon::now()));
                    break;
            }
        })->take($limit)->sortBy('DueDate');
    }

    public function getDateStatus($item,$column)
    {
        $now = \Carbon::now();
        $date = \Carbon::parse($item->$column);
        $diff = $now -> diffInDays($date);

        if($diff>=8){ return 1; }
        elseif($diff>=3 && $diff<=7){ return 2; }
        else{ return 3; }
    }


    public function getGraphData($invoices)
    {
        $startOfYear = \Carbon::now()->startOfYear();
        $invoices = $invoices->filter(function($item)use($startOfYear){
            return $item->where('Status','PAID')->where('DueDate','>',$startOfYear);
        });

        $daysArray = $this->getDaysNums($startOfYear, \Carbon::now(), 'Y-m-d');
        $groupedInvoices =  $invoices->groupBy(function($date) {
            return \Carbon::parse($date->FullyPaidOnDate)->format('Y-m-d');
        });

        $sumq = $sumd = $summ = $dataOut = [];
        $dataOut['quarters'] = array_fill_keys(range(1,4), 0);
        $dataOut['months'] = array_fill_keys(range(1,12), 0);
        foreach($daysArray as $key => $date)
        {
            $cdate = \Carbon::parse($date);

            $sumd = ($cdate->format('d') == '01') ? [] : $sumd;
            if(isset($q) && $cdate->quarter == $q){$sumq = $sumq;}else{$q=$cdate->quarter;$sumq=[];}
            if(isset($m) && $cdate->month == $m){$summ = $summ;}else{$m=$cdate->month;$summ=[];}

            if(isset($groupedInvoices[$date]))
            {
                $items = Collection::make($groupedInvoices[$date]);
                $summ[] = $sumq[] = $sumd[] = $items->sum('AmountPaid');
            }
            $dataOut['days'][] = ['date'=>$date, 'value'=>array_sum($sumd)];
            $dataOut['quarters'][$cdate->quarter] = array_sum($sumq);
            $dataOut['months'][$m] = array_sum($summ);
        }
        return $dataOut;
    }

    public function topBox($target,$invoices)
    {
        $now = \Carbon::now(\Auth::user()->timezone);
        $startYear = clone $now;
        $subMonth  = clone $now;
        $subWeek = clone $now;
        $subWeek->subWeeks(1)->startOfDay();
        $subMonth->subMonths(1)->startOfDay();
        $startYear->startOfYear();

        $invoices = $invoices->filter(function($item)use($target,$now,$subWeek,$subMonth,$startYear){
            switch($target){
                case'week': //payments over week
                    return (
                        (\Carbon::parse($item->FullyPaidOnDate) >= $subWeek) &&
                        (floatval($item->AmountPaid) > 0) &&
                        ($item->Status == 'PAID')
                    ) ? true : false;
                    break;
                case'month': //payments over month
                    $subMonth = clone $now;
                    $subMonth->subMonths(1)->startOfDay();
                    return (
                        (\Carbon::parse($item->FullyPaidOnDate) >= $subMonth) &&
                        (floatval($item->AmountPaid) > 0) &&
                        ($item->Status == 'PAID')
                    ) ? true : false;
                    break;
                case'paid':  //all payments
                    return (
                        ($item->Status = 'PAID') &&
                        (\Carbon::parse($item->DueDate) > $startYear)
                    ) ? true : false;
                    break;
                case'overdue': //overdue from month
                    return (
                        ($item->Status = 'AUTHORISED')  &&
                        (\Carbon::parse($item->DueDate) < $now)  &&
                        (\Carbon::parse($item->DueDate) > $startYear)  &&
                        (($item->AmountCredited == 0) && ($item->AmountPaid == 0))
                    ) ? true : false;
                    break;
            }
        });

        $data = [];
        switch($target){
            case'week':
                $invs = clone $invoices;
                $daysArray = $this->getDaysNums($subWeek, $now);
                $groups =  $invs->groupBy(function($date) {
                    return \Carbon::parse($date->FullyPaidOnDate)->format('ymd');
                });

                foreach ($daysArray as $day) {
                    $data[$day] = (isset($groups[$day])) ? Collection::make($groups[$day])->sum('AmountPaid') : 0;
                }
                break;
            case'month':
                $invs = clone $invoices;
                $daysArray = $this->getDaysNums($subMonth, $now);
                $groups =  $invs->groupBy(function($date) {
                    return \Carbon::parse($date->FullyPaidOnDate)->format('ymd');
                });
                foreach ($daysArray as $day) {
                    $data[$day] = (isset($groups[$day])) ? Collection::make($groups[$day])->sum('AmountPaid') : 0;
                }
                break;
            case'paid':
                $invs = clone $invoices;
                $data = ['items'=>$invs->count(), 'sum'=>($invs->sum('AmountPaid')+$invs->sum('AmountCredited'))];
                break;
            case'overdue':
                $invs = clone $invoices;
                $data = ['items'=>$invs->count(), 'sum'=>$invs->sum('Total')];
                break;
        }
        return $data;

    }

    public function getDaysNums(\Carbon $from, \Carbon $to, $format = 'ymd')
    {
        $days = [];
        $diff = $from->diffInDays($to);
        for ($i = 1; ($i) <= ($diff); $i++) {
            $days[] = $from->addDays(1)->format($format);
        }
        return $days;
    }
}
