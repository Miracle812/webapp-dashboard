<?php namespace Sections\Accountants;

class Contacts extends AccountantsSection {

    public function getIndex()
    {
        $breadcrumbs = $this->breadcrumbs->addLast($this->setAction('Invoices', false));
        $this->layout->content = \View::make($this->regView('index'), compact('breadcrumbs'));
    }

    public function datatable(){

        $invoices = \DB::table('xero_contacts')->join('xero_contacts_addresses', 'xero_contacts.id', '=', 'xero_contacts_addresses.xero_contact_id')
            ->select([
                'xero_contacts.Name',
                'xero_contacts_addresses.City',
                'xero_contacts.DefaultCurrency',
                'xero_contacts.ContactStatus'
            ]);

        return \Datatables::of($invoices)
            ->make();
    }
}