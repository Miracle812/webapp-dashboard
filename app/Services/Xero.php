<?php namespace Services;
use \Daursu\Xero\Models\Address;
use \Daursu\Xero\Models\Contact;
use \Daursu\Xero\Models\Invoice;
use \Daursu\Xero\Models\Account; ;

class Xero
{
    public static function cronTask()
    {
        $contacts = Contact::get();
        $invoices = Invoice::get();
        foreach($contacts as $contact)
        {
            $contactArr = $contact->toArray();
            $item = \Model\XeroContacts::where('ContactID',$contact->ContactID)->first();
            if(!$item){
                $item = \Model\XeroContacts::create($contactArr);
            }
            else{
                $item->update($contactArr);
            }

            if($item) {


                if (isset($contactArr['Addresses'])) {
                    foreach ($contactArr['Addresses'] as $key => $addresses) {
                        if (count($addresses)) {
                            foreach ($addresses as $address) {
                                $adres = \Model\XeroContactsAddresses::where('AddressType', $address['AddressType'])->where('xero_contact_id', $item->id)->first();
                                if (!$adres) {
                                    $adres = \Model\XeroContactsAddresses::create($address + ['xero_contact_id' => $item->id]);
                                } else {
                                    $adres->update($address);
                                }
                            }
                        }
                    }
                }
                $invoices->each(function($invItem)use($item){
                    $invoiceArr = $invItem->toArray();
                    $invoiceCont = isset($invoiceArr['Contact']['ContactID']) ? $invoiceArr['Contact']['ContactID'] : NULL;
                    if($invoiceCont &&  ($invoiceCont == $item->ContactID)){
                        $invoice = \Model\XeroInvoices::where('InvoiceID', $invItem->InvoiceID)->first();
                        if (!$invoice) {
                            $invoice = \Model\XeroInvoices::create($invoiceArr + ['xero_contact_id' => $item->id]);
                        } else {
                            $invoice->update($invoiceArr);
                        }
                    }
                });
            }
        }
        Invoice::get();
    }
}