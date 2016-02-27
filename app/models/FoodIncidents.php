<?php namespace Model;

class FoodIncidents extends Models {

    protected $fillable = [
        's1_i1','s1_i2','s1_i3','s1_i4','s1_i5','s1_i6','s1_s1','s1_t1',
        's2_i1','s2_i2','s2_i3','s2_i4','s2_i5','s2_i6','s2_i7','s2_i8','s2_s0','s2_s1',
        's3_i1','s3_i2','s3_i3','s3_i4','s3_i5','s3_s0','s3_s1','s3_s2','s3_t1'
    ];

    var $categories = [
        0 => 'Foreign Body',
        1 => 'Chemical Contamination',
        2 => 'Alleged Food Poisoning',
        3 => 'Quality Issue'];

    protected $section_url = '/food-incidents/';

    public function unit (){
        return $this->belongsTo('\Model\Units', 'unit_id');
    }

    public function user (){
        return $this->belongsTo('\User', 'user_id');
    }

    public function repository()
    {
        return \App::make('\Repositories\FoodIncidents', [$this]);
    }

    public function getCategories(){
        return $this->categories;
    }

    public function getCategory($id=null){
        $id = $id ? : $this -> s1_s1;
        return $this->categories[$id];
    }

    public function getUrl( $type = 'item' )
    {
        switch ($type){
            case 'item' : $url = 'details/'.$this->id; break;
            case 'section' : $url = '' ; break;
            default : $url = ''; break;
        }
        return $this -> section_url . $url ;
    }

    public function getOutstandingTaskItemTitle($details = NULL)
    {


        $details = $this -> s1_t1 ? (', ' . strtok(wordwrap($this -> s1_t1, 25, "..."), "")) : '.';

        $msg = \Lang::get('/common/general.category') . ': ' . $this->getCategory($this->s1_s1) . $details;
        return
            parent::getOutstandingTaskItemTitle($details) . '<br>' .
            $msg  ;
    }






    public function getIssueNotification($destination)
    {
        $bid = 'food-incid';
        $bid = 'moreless-'.$destination.'-'.$bid.'-'.$this->id;
        $content =
           '<a href="/food-incidents/details/'.$this->id.'">'.
            '<span class="text-danger font-bold">Food Incident Created.</span>'.
            '</a>'.
            '<span id="'.$bid.'" class="text-muted text-xs clear" style="display: none;">'.
            '<b>Category:</b> '.$this->getCategory($this->s1_s1).'<br>'.
            '<b>Food complained:</b> '.$this->s1_i1.'<br>'.
            '<b>Complaint name:</b> '.$this->s1_i3.'<br>'.
            '</span>';
        return $this->getNotificationTemplate($destination,$content,$bid);
    }
}