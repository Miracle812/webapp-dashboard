<?php namespace Modules;

class Billing extends Modules
{
    public $layout;
    protected $options = [];

    public function __construct()
    {
        $this->activateUserSection();
        $this->user = \Auth::user();
        \View::share('sectionName', 'Billing');
        $this->section->breadcrumbs->addCrumb('billing', 'Billing');
    }

    public function getIndex()
    {
        $this->layout = \View::make($this->layout);
        $breadcrumbs = $this->section->breadcrumbs->addLast($this->setAction('Clients list', false));
        $this->layout->content = \View::make($this->regView('index'), compact('breadcrumbs'));
    }

    public function getInvoices($id)
    {
        $contact = \Model\XeroContacts::find($id);
        $this->layout = \View::make($this->layout);
        $breadcrumbs = $this->section->breadcrumbs->addLast($this->setAction('Invoices list', false));
        $this->layout->content = \View::make($this->regView('invoices'), compact('breadcrumbs','contact'));
    }

    public function getCreate()
    {
        $breadcrumbs = $this->section->breadcrumbs->addLast($this->setAction('create'));

        $view = ($ajax = \Request::ajax()) ? 'modal.create' : 'create';
        $view = \View::make($this->regView($view), compact('breadcrumbs'));
        if ($ajax)
            return $view;
        else
            $this->layout->content = $view;
    }

    public function postCreate()
    {
        $user = $this->user;
        $input = \Input::all();
        $rules = [
            'title'=>'required',
            'message'=>'required',
            'category_id'=>'required',
            'user_name'=>'required',
            'user_email'=>'required|email',
        ];

        $validator = \Validator::make($input, $rules);
        if(!$validator -> fails()) {
            $new = new \Model\SupportTickets();
            $new = $new::create($input+['user_id'=>$user->id]);
            if($new->id) {
                $new->ident = $new -> ident();
                $new->update();
                \Services\FilesUploader::updateAfterCreate(['support_tickets', \Auth::user()->id, $user->unit()->id, $new->id]);
                return \Response::json(['type' => 'success', 'msg' => \Lang::get('/common/messages.create_success')]);
            }
            else
                return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.create_fail')]);
        }
        else
        {
            $errMsg = $this->ajaxErrors($validator->messages()->toArray(),[]);
            return \Response::json(['type'=>'error', 'msg'=>\Lang::get('/common/messages.create_fail'), 'errors' => $errMsg]);
        }
    }

    public function getContactsDatatable()
    {

        if(\Auth::user()->hasRole('admin')) {
            $contacts = \Model\XeroContacts::with('invoices')->get();
        }else {
            $contacts = \Model\XeroContacts::with('invoices')->get();
        }
        if($contacts->count()) {
            foreach ($contacts as $contact) {
                $invoices = $contact->invoices;
                $options[] = [
                    $contact->Name,
                    '<a href="/billing/invoices/'.$contact->id.'">'.$contact->Name.'</a>',
                    ($contact->clients->count() ? implode(',',$contact->clients->lists('name')):'<span class="text-danger">-- Not assigned --</span>'),
                    $invoices->sum('AmountDue'),
                    $invoices->sum('AmountPaid'),
                    $invoices->sum('AmountCredited'),
                    (implode(',',array_unique($invoices->lists('CurrencyCode'))) ? : 'N/A'),
                    '<a href="/billing/invoices/'.$contact->id.'"><div class="text-center">'.\HTML::ownNumStatus($invoices->count()).'</div></a>',
                ];
            };
            if($options)
                return \Response::json(['aaData' => $options]);
            else
                return \Response::json(['aaData' => []]);
        }
        else
            return \Response::json(['aaData' => []]);
    }

    public function getInvoicesDatatable($id)
    {

        $contact = \Model\XeroContacts::with('invoices')->find($id);

        if($contact) {
            $invoices = $contact->invoices;
            foreach ($invoices as $invoice) {
                $options[] = [
                    $invoice->DueDate,
                    \Carbon::parse($invoice->DueDate)->format('d/m/Y'),
                    $invoice->InvoiceNumber,
                    $invoice->Reference,
                    $invoice->Total,
                    $invoice->AmountDue,
                    $invoice->AmountPaid,
                    $invoice->AmountCredited,
                    $invoice->CurrencyCode,
                    $invoice->Status,
                ];
            };
            if($options)
                return \Response::json(['aaData' => $options]);
            else
                return \Response::json(['aaData' => []]);
        }
        else
            return \Response::json(['aaData' => []]);
    }

    public function getDisplay($id)
    {
        $ticket = \Model\SupportTickets::find($id);
        $replies = $ticket -> replies;
        $breadcrumbs = $this->section->breadcrumbs->addLast($this->setAction('display'));
        $view = \View::make($this->regView('display'), compact('breadcrumbs','replies','ticket'));
        $this->layout = \View::make($this->layout);
        $this->layout->content = $view;
    }

    public function postReply($id)
    {
        $ticket = \Model\SupportTickets::find($id);
        $rules = [
            'message' => 'required',
            'status'  => 'required',
        ];
        $validator = \Validator::make(\Input::all(), $rules);
        if (!$validator -> fails()){
            $user = \Auth::user();
            $reply = \Model\SupportReplies::create([
                'ticket_id'=>$ticket->id,
                'user_id'=>\Auth::user()->id,
                'message'=>\Input::get('message'),
                'user_name'=>$user->fullname(),
            ]);
            $ticket->update(['status'=>\Input::get('status')]);
            \Services\FilesUploader::updateAfterCreate(['support_replies', $user->id, (($unit = $user->unit())?$unit->id:null), $reply->id]);
            return \Response::json(['type' => 'success', 'msg' => 'Reply has been sent']);
        }
        else
            return \Response::json(['type' => 'error', 'msg' => 'Fail! Reply hasn\'t been sent', 'errors' => $this->ajaxErrors($validator -> messages() -> toArray(), [])]);
    }

    public function getAssigning()
    {
        $contacts = \Model\XeroContacts::get();
        $clients = \Model\Headquarters::get();
        $breadcrumbs = $this->section->breadcrumbs->addLast($this->setAction('Assigning Clients',false));
        $view = \View::make($this->regView('assigning'), compact('breadcrumbs','contacts','clients'));
        $this->layout = \View::make($this->layout);
        $this->layout->content = $view;
    }

    public function postAssigning($id)
    {
        $contact = \Model\XeroContacts::find($id);
        $client = \Input::get('client') ? : 0;
        if($client > 0) {
            $contact->clients()->sync([$client]);
        } else {
            $contact->clients()->detach();
        }
        return \Response::json(['type' => 'success']);

    }

}

