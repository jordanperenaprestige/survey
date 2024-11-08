<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\QuestionnairesControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Http\Requests\QuestionnaireRequest;
use Illuminate\Support\Str;

use App\Models\Site;
use App\Models\ViewModels\AdminViewModel;
use App\Models\ViewModels\QuestionnaireViewModel;
use App\Exports\Export;
use App\Models\Questionnaire;
use Storage;
use URL;


class QuestionnairesController extends AppBaseController implements QuestionnairesControllerInterface
{
    /************************************
     * 		SURVEY ROOM MANAGEMENT	 	*
     ************************************/
    public function __construct()
    {
        $this->module_id = 81;
        $this->module_name = 'Survey Room Management';
    }

    public function index()
    {
        return view('admin.questionnaires');
    }

    public function list(Request $request)
    {
        try {
            $sites = QuestionnaireViewModel::when(request('search'), function ($query) {
                return $query->where('serial_number', 'LIKE', '%' . request('search') . '%')
                    ->orWhere('questions', 'LIKE', '%' . request('search') . '%');
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
            $questionnaire = QuestionnaireViewModel::find($id); 
            return $this->response($questionnaire, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function store(QuestionnaireRequest $request)
    {
        try {
            $data = [
                'active' => 1,
                'questions' => $request->question,
                'hello' =>  'dasfaf',
            ];

            $questionnaire = Questionnaire::create($data);
            $questionnaire->serial_number = 'Q-'.Str::padLeft($questionnaire->id, 5, '0');
            $questionnaire->save();
            return $this->response($questionnaire, 'Successfully Created!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function update(QuestionnaireRequest $request)
    {
        try {
            $questionnaire = Site::find($request->id);
           // $site->touch();
            
            $data = [
                'serial_number' => ($questionnaire->serial_number) ? $questionnaire->serial_number : 'Q-'.Str::padLeft($questionnaire->id, 5, '0'),
                'questions' => $request->question,
            ];

            $questionnaire->update($data);
            return $this->response($questionnaire, 'Successfully Modified!', 200);
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
            $site = Site::find($id);
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
            $sites = SiteViewModel::orderBy('name')->get();
            return $this->response($sites, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function setDefault($id)
    {
        try {
            Site::where('is_default', 1)->update(['is_default' => 0]);
            $site = Site::find($id);
            $site->update(['is_default' => 1]);
            return $this->response($site, 'Successfully Modified!', 200);
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

            $sites_management = SiteViewModel::get();
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

            $filename = "site_management.csv";
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