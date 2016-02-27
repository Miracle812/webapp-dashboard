<?php namespace Model;

class UsersStatistics extends Models {

    protected $fillable = ['user_id','session_id','action','ip','agent','role'];

    public function user()
    {
        return $this->belongsTo('\User', 'user_id');
    }

    public function tracks()
    {
        return $this->hasMany('\Model\UsersTrackLogs', 'stats_id');
    }

    public function lastTrack()
    {
        return $this->tracks()->whereRaw('id IN (SELECT max(id) FROM users_track_logs GROUP BY stats_id)')->first();
    }

    public function avPagesPerVisit(){
        dd($this->tracks()->whereRaw('id IN (SELECT *,count(id) FROM users_track_logs GROUP BY stats_id)')->get()->toArray());
    }

    public function durationSpentTimePage(){

    }
}
