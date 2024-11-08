<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\AnswersControllerInterface;
use Illuminate\Http\Request;
//use App\Http\Requests\BuildingRequest;

use App\Models\QuestionnaireAnswer;
use App\Models\ViewModels\AdminViewModel;
use App\Models\ViewModels\QuestionnaireViewModel;

class AnswersController extends AppBaseController implements AnswersControllerInterface
{
    /********************************************
    * 		QUESTIONNAIRE ANSWER MANAGEMENT	 	*
    ********************************************/
    public function __construct()
    {
        $this->module_id = 81;
        $this->module_name = 'Survey Room Management';
    }

    public function index($id)
    {  
        session()->forget('questionnaire_id');
        session()->put('questionnaire_id', $id); 
        $questionnaire_details = QuestionnaireViewModel::find($id);  
        return view('admin.questionnaire_details', compact("questionnaire_details"));
    }

    public function list(Request $request)
    { 
        try
        {
            $questionnaire_id = session()->get('questionnaire_id');
            
            $answers = QuestionnaireAnswer::when(request('search'), function($query){
                return $query->where('answer', 'LIKE', '%' . request('search') . '%');
            })
            ->where('questionnaire_id', $questionnaire_id)
            ->latest()
            ->paginate(request('perPage')); 
            return $this->responsePaginate($answers, 'Successfully Retreived!', 200);
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

    // public function details($id)
    // {
    //     try
    //     {
    //         $building = SiteBuilding::find($id);
    //         return $this->response($building, 'Successfully Retreived!', 200);
    //     }
    //     catch (\Exception $e)
    //     {
    //         return response([
    //             'message' => $e->getMessage(),
    //             'status' => false,
    //             'status_code' => 422,
    //         ], 422);
    //     }
    // }

    // public function store(BuildingRequest $request)
    // {
    //     try
    // 	{
    //         $site_id = session()->get('site_id');
    //         $data = [
    //             'site_id' => $site_id,
    //             'name' => $request->name,
    //             'descriptions' => $request->descriptions,
    //             'active' => 1
    //         ];

    //         $building = SiteBuilding::create($data);

    //         return $this->response($building, 'Successfully Created!', 200);
    //     }
    //     catch (\Exception $e) 
    //     {
    //         return response([
    //             'message' => $e->getMessage(),
    //             'status' => false,
    //             'status_code' => 422,
    //         ], 422);
    //     }
    // }

    // public function update(BuildingRequest $request)
    // {
    //     try
    // 	{
    //         $building = SiteBuilding::find($request->id);

    //         $data = [
    //             'name' => $request->name,
    //             'descriptions' => $request->descriptions,
    //             'active' => ($request->active == 'false') ? 0 : 1,
    //         ];

    //         $building->update($data);

    //         return $this->response($building, 'Successfully Modified!', 200);
    //     }
    //     catch (\Exception $e) 
    //     {
    //         return response([
    //             'message' => $e->getMessage(),
    //             'status' => false,
    //             'status_code' => 422,
    //         ], 422);
    //     }
    // }

    // public function delete($id)
    // {
    //     try
    // 	{
    //         $building = SiteBuilding::find($id);
    //         $building->delete();
    //         return $this->response($building, 'Successfully Deleted!', 200);
    //     }
    //     catch (\Exception $e) 
    //     {
    //         return response([
    //             'message' => $e->getMessage(),
    //             'status' => false,
    //             'status_code' => 422,
    //         ], 422);
    //     }
    // }

    // public function getAll()
    // {
    //     try
    // 	{
    //         $site_id = session()->get('site_id');
    //         $buildings = SiteBuilding::where('site_id', $site_id)->get();
    //         return $this->response($buildings, 'Successfully Deleted!', 200);
    //     }
    //     catch (\Exception $e) 
    //     {
    //         return response([
    //             'message' => $e->getMessage(),
    //             'status' => false,
    //             'status_code' => 422,
    //         ], 422);
    //     }
    // }

    // public function getBuildings($id)
    // {
    //     try
    // 	{
    //         $buildings = SiteBuilding::where('site_id', $id)->get();
    //         return $this->response($buildings, 'Successfully Deleted!', 200);
    //     }
    //     catch (\Exception $e) 
    //     {
    //         return response([
    //             'message' => $e->getMessage(),
    //             'status' => false,
    //             'status_code' => 422,
    //         ], 422);
    //     }
    // }
    
}
