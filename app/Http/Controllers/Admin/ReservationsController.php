<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\ReservationsControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Requests\ReservationRequest;
use Illuminate\Support\Str;

use App\Models\Site;
use App\Models\ViewModels\AdminViewModel;
use App\Models\ViewModels\ReservationViewModel;
use App\Exports\Export;
use App\Models\Reservation;
use Storage;
use URL;


class ReservationsController extends AppBaseController implements ReservationsControllerInterface
{
    /************************************
     * 		RESERVATION MANAGEMENT	 	*
     ************************************/
    public function __construct()
    {
        $this->module_id = 81;
        $this->module_name = 'Survey Room Management';
    }

    public function index()
    {
        return view('admin.reservations');
    }

    public function list(Request $request)
    {
        try {
            $sites = ReservationViewModel::when(request('search'), function ($query) {
                return $query->where('event', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('name', 'LIKE', '%' . request('search') . '%');
            })
                ->latest()
                ->paginate(request('perPage'));

            return $this->responsePaginate($sites, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function details($id)
    {
        try {
            $reservation = ReservationViewModel::find($id);
            return $this->response($reservation, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function store(ReservationRequest $request)
    {
        try {
            $data = [
                'active' => 1,
                'site_id' => $request->site_id,
                'site_building_id' => $request->site_building_id,
                'site_building_level_id' => $request->site_building_level_id,
                'site_building_room_id' => $request->site_building_room_id,
                'event' => $request->event,
                'name' => $request->name,
                'department' => $request->department,
                'position' => $request->position,
                'mobile_number' => $request->mobile_number,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'remarks' => $request->remarks,
            ];

            $reservation = Reservation::create($data);
            return $this->response($reservation, 'Successfully Created!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function update(ReservationRequest $request)
    {
        try {
            $reservation = Reservation::find($request->id);
            // $site->touch();

            $data = [
                'event' => $request->event,
                'name' => $request->name,
            ];

            $reservation->update($data);
            return $this->response($reservation, 'Successfully Modified!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function delete($id)
    {
        try {
            $site = Reservation::find($id);
            $site->delete();
            return $this->response($site, 'Successfully Deleted!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getAll()
    {
        try {
            $sites = ReservationViewModel::orderBy('name')->get();
            return $this->response($sites, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }



    public function downloadCsv()
    {
        try {

            $sites_management = ReservationViewModel::get();
            $reports = [];
            foreach ($sites_management as $site) {
                $reports[] = [
                    'name' => $site->name,
                    'description' => $site->descriptions,
                    'logo' => ($site->site_logo != "") ? URL::to("/" . $site->site_logo) : " ",
                    'banner' => ($site->site_banner != "") ? URL::to("/" . $site->site_banner) : " ",
                    'background' => ($site->site_background != "") ? URL::to("/" . $site->site_background) : " ",
                    'status' => ($site->active == 1) ? 'Active' : 'Inactive',
                    'is_default' => ($site->is_default == 1) ? 'Yes' : 'No',
                    'updated_at' => $site->updated_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "reservation.csv";
            // Store on default disk
            Excel::store(new Export($reports), $directory . $filename);

            $data = [
                'filepath' => '/storage/export/reports/' . $filename,
                'filename' => $filename
            ];

            if (Storage::exists($directory . $filename))
                return $this->response($data, 'Successfully Retreived!', 200);

            return $this->response(false, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
}
