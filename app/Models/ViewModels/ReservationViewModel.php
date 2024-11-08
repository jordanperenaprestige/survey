<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Str;
use App\Models\Company;
use App\Models\SiteBuilding;
use App\Models\SiteBuildingLevel;
use App\Models\SiteBuildingRoom;

class ReservationViewModel extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'reservations';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Append additiona info to the return data
     *
     * @var string
     */
	public $appends = [
        'building_name',
        'building_floor_name',
        'building_room_name',

    ];

    // public function getSiteDetails()
    // {   
    //     return $this->hasMany('App\Models\SiteMeta', 'site_id', 'id');
    // }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getBuildingNameAttribute() 
    {
        return SiteBuilding::find($this->site_building_id)->name;
    }

    public function getBuildingFloorNameAttribute() 
    {
        return SiteBuildingLevel::find($this->site_building_level_id)->name;
    }

    public function getBuildingRoomNameAttribute() 
    {
        return SiteBuildingRoom::find($this->site_building_room_id)->name;
    }
}
