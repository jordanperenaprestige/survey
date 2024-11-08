<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\ClientUserControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Requests\ClientUserRequest;
use App\Http\Requests\EditClientUserRequest;

use App\Helpers\PasswordHelper;
use App\Models\ViewModels\UserViewModel;
use App\Models\ViewModels\AdminViewModel;
use App\Models\User;
use App\Models\Admin;
use App\Exports\Export;
use Storage;

use Hash;

class ClientUserController extends AppBaseController implements ClientUserControllerInterface
{
    /****************************************
     * 			CLIENT USERS MANAGEMENT		*
     ****************************************/
    public function __construct()
    {
        $this->module_id = 46;
        $this->module_name = 'User Management';
    }

    public function index()
    {
        return view('admin.client_users');
    }

    public function list(Request $request)
    {
        try {
             $this->permissions = AdminViewModel::find(Auth::user()->id)->getPermissions()->where('modules.id', $this->module_id)->first();

            $user = UserViewModel::when(request('search'), function ($query) {
                return $query->where('full_name', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('email', 'LIKE', '%' . request('search') . '%');
            })
                ->latest()
                ->paginate(request('perPage')); //echo '<pre>'; print_r($user); echo '</pre>';
            return $this->responsePaginate($user, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function store(ClientUserRequest $request)
    {
        try { 
            
            $salt = PasswordHelper::generateSalt();
            $password = PasswordHelper::generatePassword($salt, $request->password);

            /*For client*/

            $data = [
                'company_id' => $request->company['id'],
                'full_name' => $request->last_name . ', ' . $request->first_name,
                'email' => $request->email,
                'salt' => $salt,
                'password' => $password,
                'pass_int' => $request->pass_int,
                'mobile' => $request->mobile,
                'pin_int' => $request->pin_int,
                'active' => 1,
                'level' => $request->level
            ];
             //echo '<pre>'; print_r($data); echo '</pre>'; 
            $user = User::create($data);

            $meta_details = ["first_name" => $request->first_name, "last_name" => $request->last_name];
            $user->saveMeta($meta_details);
            $user->saveRoles($request->roles);

            $user->saveSites($request->sites);
            $user->saveBuildings($request->site_buildings);
            $user->saveLevels($request->site_building_levels);
            $user->saveRooms($request->site_building_level_rooms);

            /*for Admin*/
            $data_admin = [
                'client_id' => $user->id,
                'full_name' => $request->last_name . ', ' . $request->first_name,
                'email' => $request->email,
                'salt' => $salt,
                'password' => $password,
                'mobile' => $request->mobile,
                'active' => 1
            ];

            $admin_user = Admin::create($data_admin);

            $meta_details = ["first_name" => $request->first_name, "last_name" => $request->last_name];
            $admin_user->saveMeta($meta_details);
            $role[] = ($request->level == 'Supervisor') ? array('id'=> '11') : array('id'=> '12');
            $admin_user->saveRoles($role);
            // $user->saveBrands($request->brands);
            // $user->saveSites($request->sites);
            // $user->saveScreens($request->screens);

            return $this->response($user, 'Successfully Created!', 200);
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
            $user = UserViewModel::find($id);
            return $this->response($user, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function update(EditClientUserRequest $request)
    //public function update(Request $request)
    {  
        try {
            $user_admin = Admin::where('client_id',$request->id)->first();
            $password = PasswordHelper::generatePassword($user_admin->salt, $request->password);
            $data = [
                'full_name' => $request->last_name . ', ' . $request->first_name,
                'email' => $request->email,
                'active' => $request->isActive,
                'mobile' => $request->mobile
            ];

            if ($request->password)
                $data['password'] = $password;

            $user_admin->update($data);

            $meta_details = ["first_name" => $request->first_name, "last_name" => $request->last_name];
            $user_admin->saveMeta($meta_details);
            $role[] = ($request->level == 'Supervisor') ? array('id'=> '11') : array('id'=> '12');
            $user_admin->saveRoles($role);
            //$user->saveRoles($request->roles);


            $user = User::find($request->id);
            $password = PasswordHelper::generatePassword($user->salt, $request->password);
            $data = [
                'company_id' => $request->company['id'],
                //'supervisor_id' => $request->supervisor['id'],
                'full_name' => $request->last_name . ', ' . $request->first_name,
                'email' => $request->email,
                //'pass_int' => $request->password,
                'pass_int' => $request->pass_int,
                'role' => $request->roles,
                'mobile' => $request->mobile,
                'active' => $request->isActive,
                'level' => $request->level
            ];

            if ($request->password)
                $data['password'] = $password;// echo '<pre>'; print_r($data); echo '</pre>'; die();
            $user->update($data);

            $meta_details = ["first_name" => $request->first_name, "last_name" => $request->last_name];
            $user->saveMeta($meta_details);
            $user->saveRoles($request->roles);
            $user->saveSites($request->sites);
            $user->saveBuildings($request->site_buildings);
            $user->saveLevels($request->site_building_levels);
            // echo '>>>';
            // echo '<pre>';
            // print_r($request->site_building_level_rooms);
            // echo '</pre>';
            $user->saveRooms($request->site_building_level_rooms);
        
            return $this->response($user, 'Successfully Modified!', 200);
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
            $user = User::find($id);
            $user->delete();
            //echo 'delete'. $user->id;
            $admin = Admin::where('client_id', $user->id)->first();
            $admin->delete();
            //echo 'delete admin '. $admin->id;
            return $this->response(true, 'Successfully Deleted!', 200);
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

            $client_user_management = UserViewModel::get();
            $reports = [];
            foreach ($client_user_management as $user) {
                $reports[] = [
                    'full_name' => $user->full_name,
                    'email' => $user->email,
                    'status' => ($user->active == 1) ? 'Active' : 'Inactive',
                    'updated_at' => $user->updated_at,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "client_user.csv";
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
