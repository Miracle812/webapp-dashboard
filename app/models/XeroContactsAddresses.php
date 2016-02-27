<?php namespace Model;

class XeroContactsAddresses extends Models
{
    protected $fillable = ['xero_contact_id','AddressType','AddressLine1','AddressLine2','City','Region','Country','PostalCode'];
}