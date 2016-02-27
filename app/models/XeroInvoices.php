<?php namespace Model;

class XeroInvoices extends Models
{
    protected $fillable = ['xero_contact_id','Date','DueDate','Status','LineAmountTypes','SubTotal','TotalTax','Total','UpdatedDateUTC','CurrencyCode','FullyPaidOnDate','Type','InvoiceID','InvoiceNumber','Reference','AmountDue','AmountPaid','AmountCredited'];

    public function contact()
    {
        return $this->belongsTo('\Model\XeroContacts', 'xero_contact_id');
    }
}