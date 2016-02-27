<?php namespace Model;

class UsersTrackLogs extends Models {

	protected $fillable = ['stats_id', 'url', 'method', 'data','ajax'];

	public function stats()
	{
		return $this->belongsTo('\Model\UsersStatistics', 'stats_id');
	}

	public function usageByUnit($unit_ids)
	{
        $data =  \DB::table('users_track_logs')
            ->select(\DB::raw('units.id, units.name, units.identifier, COUNT(DISTINCT session_id) as session_count, COUNT(*) as pageview_count'))
            ->join('units', 'units.id', '=', 'user_usage.unit_id')
            ->whereIn('unit_id', $unit_ids)
            ->groupBy('unit_id')
            ->get();

        return $data;  
	}
}