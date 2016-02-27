<?php namespace Model;

class SupportCategories extends Models {

    public function members()
    {
        return $this->belongsToMany('\User', 'support_assigned_categories','category_id','user_id');
    }
}