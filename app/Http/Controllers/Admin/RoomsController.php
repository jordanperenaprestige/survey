<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\RoomsControllerInterface;
use Illuminate\Http\Request;
use App\Http\Requests\RoomRequest;
use App\Models\SiteBuildingRoom;
use App\Models\SiteMap;
use App\Models\ViewModels\AdminViewModel;
use App\Models\ViewModels\SiteBuildingRoomViewModel;

class RoomsController extends AppBaseController implements RoomsControllerInterface
{
    /********************************************
    * 			BUILDING FLOORS MANAGEMENT	 	*
    ********************************************/
    public function __construct()
    {
        $this->module_id = 13; 
        $this->module_name = 'Sites Management';
    }

    public function list(Request $request)
    {
        try
        {
            $site_id = session()->get('site_id');
           
            $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();

            $buildings = SiteBuildingRoomViewModel::when(request('search'), function($query){
                return $query->where('site_building_levels.name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('site_buildings.name', 'LIKE', '%' . request('search') . '%')
                             ->orWhere('site_buildings.descriptions', 'LIKE', '%' . request('search') . '%');
            })
            ->leftJoin('site_buildings', 'site_building_rooms.site_building_id', '=', 'site_buildings.id')
            //->leftJoin('site_building_levels', 'site_building_rooms.site_building_level_id', '=', 'site_building_levels.id')
            ->where('site_building_rooms.site_id', $site_id)
            ->select('site_building_rooms.*')
            ->latest()
            ->paginate(request('perPage'));
            return $this->responsePaginate($buildings, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function details($id)
    {
        try
        {
            $building_room = SiteBuildingRoomViewModel::find($id);
            return $this->response($building_room, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e)
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function store(RoomRequest $request)
    {
        try
    	{
            $site_id = session()->get('site_id');
            $data = [
                'site_id' => $site_id,
                'site_building_id' => $request->site_building_id,
                'site_building_level_id' => $request->site_building_level_id,
                'name' => $request->name,
                'active' => 1
            ];

            $building = SiteBuildingRoom::create($data);

            return $this->response($building, 'Successfully Created!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function update(RoomRequest $request)
    {
        try
    	{
            $building_room = SiteBuildingRoom::find($request->id);
            $data = [
                'site_building_id' => $request->site_building_id,
                'site_building_level_id' => $request->site_building_level_id,
                'name' => $request->name,
                'active' => ($request->active == 'false') ? 0 : 1,
            ];

            $building_room->update($data);

            return $this->response($building_room, 'Successfully Created!', 200);            
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function delete($id)
    {
        try
    	{
            $building_room = SiteBuildingRoom::find($id);
            $building_room->delete();
            return $this->response($building_room, 'Successfully Deleted!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getRooms($id)
    {
        try
    	{
            $building_rooms = SiteBuildingRoom::where('site_building_room_id', $id)->get();
            return $this->response($building_rooms, 'Successfully Retreived!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getBuildingLevelRoomByIds(Request $request)
    {
        try 
    	{  
            $filters = json_decode($request->filters);
            $levels = SiteBuildingRoom::whereIn('site_building_level_id', $filters->site_building_level_room_ids)->get();
            return $this->response($levels, 'Successfully Deleted!', 200);
        }
        catch (\Exception $e) 
        {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

}
