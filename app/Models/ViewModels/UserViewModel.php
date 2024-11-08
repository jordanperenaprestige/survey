<?php

namespace App\Models\ViewModels;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\UserRole;
use App\Models\Permission;
use App\Models\SiteBuilding;
use App\Models\SiteBuildingLevel;
use App\Models\UserBrand;
use App\Models\UserSite;
use App\Models\UserBuilding;
use App\Models\UserLevel;
use App\Models\UserRoom;
use App\Models\UserScreen;

class UserViewModel extends Model
{
    use SoftDeletes;
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'salt',
        'remember_token',
        'login_attempt',
        'is_blocked',
        'is_active',
        'activation_token',
        'remember_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'users';

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
        'details',
        'roles',
        'permissions',
        'company',
        'supervisor',
        'brands',
        'sites',
        'buildings',
        'levels',
        'rooms',
        'screens',
        'profile_image',
    ];

    public function getUserDetails()
    {   
        return $this->hasMany('App\Models\UserMeta', 'user_id', 'id');
    }

    public function getRoles()
    {
        return $this->hasMany('App\Models\UserRole', 'user_id', 'id');
    }

    public function getPermissions()
    {
        $role_ids = $this->getRoles()->pluck('role_id')->toArray();
        return Permission::whereIn('role_id', $role_ids)->where('active', 1)->whereIn('modules.role',['Portal'])->whereNull('modules.deleted_at')
                        ->selectRaw('modules.id, modules.parent_id, modules.name, modules.link, modules.class_name, max(permissions.can_view) AS can_view, max(permissions.can_add) AS can_add, max(permissions.can_edit) AS can_edit, max(permissions.can_delete) AS can_delete')
                        ->leftJoin('modules', 'permissions.module_id', '=', 'modules.id')
                        ->groupBy('permissions.module_id');
    }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getDetailsAttribute() 
    {
        return $this->getUserDetails()->pluck('meta_value','meta_key')->toArray();
    }

    public function getRolesAttribute() 
    { 
        $role_ids = $this->getRoles()->pluck('role_id')->toArray();
        return RoleViewModel::whereIn('id', $role_ids)->get();
    }

    public function getPermissionsAttribute() 
    {
        $permissions_group = [];
        $permissions_parent = $this->getPermissions()->whereNull('modules.parent_id')->get();
      
        foreach($permissions_parent as $index => $permission) {
            $permissions_group[$permission->id] = $permission;
            $permissions_group[$permission->id]['sub_permissions'] = $this->getPermissions()->where('modules.parent_id', $permission->id)->get();
        }

        return $permissions_group;
    }

    public function getCompanyAttribute() 
    {
        $company = CompanyViewModel::find($this->company_id);
        if($company)
            return $company;
    }

    public function getSupervisorAttribute() 
    {
        $supervisor = AdminViewModel::find($this->supervisor_id);
        if($supervisor)
            return $supervisor;
    }

    public function getBrandsAttribute() 
    {
        $brand_ids = UserBrand::where('user_id', $this->id)->get()->pluck('brand_id');
        $brands = BrandViewModel::whereIn('id', $brand_ids)->get();
        if($brands)
            return $brands;
    }

    public function getSitesAttribute() 
    {
        $site_ids = UserSite::where('user_id', $this->id)->get()->pluck('site_id');
        $sites = SiteViewModel::where('deleted_at', Null)->whereIn('id', $site_ids)->get();
        if($sites)
        return $sites;
    }
    public function getBuildingsAttribute() 
    {
        $building_ids = UserBuilding::where('user_id', $this->id)->get()->pluck('building_id');
        $buildings = SiteBuildingViewModel::where('deleted_at', Null)->whereIn('id', $building_ids)->get();
        if($buildings)
        return $buildings;
    }
    
    public function getLevelsAttribute() 
    {
        $level_ids = UserLevel::where('user_id', $this->id)->get()->pluck('level_id');
        $levels = SiteBuildingLevelViewModel::where('deleted_at', Null)->whereIn('id', $level_ids)->get();
        if($levels)
        return $levels;
    }
    public function getRoomsAttribute() 
    {
        $room_ids = UserRoom::where('user_id', $this->id)->get()->pluck('room_id');
        $rooms = SiteBuildingRoomViewModel::where('deleted_at', Null)->whereIn('id', $room_ids)->get();
        if($rooms)
        return $rooms;
    }
    public function getScreensAttribute() 
    {
        $screen_ids = UserScreen::where('user_id', $this->id)->get()->pluck('site_screen_id');
        $screens = SiteScreenViewModel::whereIn('id', $screen_ids)->get();
        if($screens)
            return $screens;
    }

    public function getProfileImageAttribute() 
    {
        $profile_image = $this->getUserDetails()->where('meta_key', 'profile_image')->pluck('meta_value')->toArray();
        if(count($profile_image) > 0)
            return asset($profile_image[0]);
    }

}
