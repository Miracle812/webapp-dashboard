<?php namespace Model;

class XeroAssignedContacts extends Models
{
    protected $fillable = ['client_id','xero_contact_id'];

    public function client()
    {
        return $this->belongsToMany('\Model\Headquarters', 'client_id');
    }

    public function contact()
    {
        return $this->belongsToMany('\Model\XeroContacts', 'xero_contact_id');
    }
}