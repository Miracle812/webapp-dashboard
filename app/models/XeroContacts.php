<?php namespace Model;

class XeroContacts extends Models
{
    protected $fillable = ['ContactID','ContactStatus','UpdatedDateUTC','Name','IsSupplier','IsCustomer','DefaultCurrency'];

    public function invoices()
    {
        return $this->hasMany('\Model\XeroInvoices','xero_contact_id');
    }

    public function clients()
    {
        return $this->belongsToMany('\Model\Headquarters', 'xero_assigned_contacts','xero_contact_id','client_id');
    }

    public function client()
    {
        return $this->clients->get(1);
    }

}