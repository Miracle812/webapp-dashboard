<?php namespace Sections\Accountants;

class Invoices extends AccountantsSection {

    public function getIndex()
    {
        $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('Invoices', false));
        $this->layout->content = \View::make($this->regView('index'), compact('breadcrumbs'));
    }

    public function datatable(){

        $invoices = \DB::table('xero_invoices')->join('xero_contacts', 'xero_invoices.xero_contact_id', '=', 'xero_contacts.id')
            ->select([
                'xero_invoices.InvoiceNumber',
                'xero_invoices.Reference',
                'xero_contacts.Name',
                'xero_invoices.Status',
                'xero_invoices.Total',
                'xero_invoices.DueDate',
                'xero_invoices.CurrencyCode'
            ]);

        return \Datatables::of($invoices)
            ->edit_column('DueDate', function ($invoice) {
                return \Carbon::parse($invoice->DueDate)->format('d-m-Y');
            })
            ->edit_column('Total', function ($invoice) {
                return $invoice->Total . $invoice->CurrencyCode;
            })
            ->edit_column('InvoiceNumber', function ($invoice) {
                return $invoice->InvoiceNumber?:'<span class="uk-text-muted uk-text-small">N/A</span>';
            })
            ->edit_column('Reference', function ($invoice) {
                return $invoice->Reference?:'<span class="uk-text-muted uk-text-small">N/A</span>';
            })
            ->edit_column('Status', function ($invoice) {
                $overdue = ((($invoice->Status) == 'AUTHORISED') && (\Carbon::parse($invoice->DueDate) < \Carbon::now()));
                $atxt = '';
                switch($invoice->Status){
                    case 'AUTHORISED' :
                        if($overdue){
                            $atxt = '(overdue)';
                            $class = 'uk-badge-danger';
                        }else{
                            $class = 'uk-badge-success';
                        }

                        break;
                    case 'PAID' : $class = 'uk-badge-success'; break;
                    default : $class = '';
                }
                return '<span class="uk-badge '.$class.'">'.$invoice->Status.' '.$atxt.'</span>';
            })
            ->remove_column('xero_contact_id')
            ->remove_column('CurrencyCode')
            ->make();
    }
}