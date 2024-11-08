<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use App\Models\SitePoint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\ViewModels\AdminViewModel;
use App\Models\ViewModels\SiteBuildingRoomViewModel;
use App\Models\ViewModels\QuestionnaireSurveyViewModel;
use App\Models\QuestionnaireAnswer;
use App\Models\QuestionnaireSurvey;
use App\Models\SendSMS;
use Illuminate\Support\Facades\DB;
use DateTime;
use DateInterval;
use Carbon\Carbon;

class DashboardController extends AppBaseController
{
    /************************************
     * 	    DASHBOARD MANAGEMENT		*
     ************************************/
    public function index()
    {
        return view('admin.dashboard');
    }
    public function update($id)
    {
        session()->forget('room_id');
        session()->put('room_id', $id);
        return view('admin.dashboard_room');
    }

    public function getRoomSurvey()
    {
        $id = session()->get('room_id');
        $room = SiteBuildingRoomViewModel::find($id);
        $question_answers = QuestionnaireAnswer::orderBy('questionnaire_id', 'asc')->get();

        $data = array();
        foreach ($question_answers as $answer) {

            $questionnaire_survey = QuestionnaireSurvey::where('questionnaire_id', $answer->questionnaire_id)
                ->where('questionnaire_answer_id', $answer->id)
                ->where('site_id', $room->site_id)
                ->where('site_building_id', $room->site_building_id)
                ->where('site_building_level_id', $room->site_building_level_id)
                ->where('site_building_room_id', $room->id)
                ->orderBy('id', 'desc')
                ->limit(1)
                ->get();


            if (count($questionnaire_survey) > 0) {
                foreach ($questionnaire_survey as $key => $survey) {
                    $data[] = [
                        'questionnaire_answer_id' => $answer->id,
                        'questionnaire_id' => $answer->questionnaire_id,
                        'questionnaire_name' => $answer->answer,
                        'site_id' => $room->site_id,
                        'site_building_id' => $room->site_building_id,
                        'site_building_level_id' => $room->site_building_level_id,
                        'site_building_room_id' => $room->id,
                        'site_name' => $room->site_name,
                        'site_building_name' => $room->building_name,
                        'site_building_floor_name' => $room->building_floor_name,
                        'site_building_room_name' => $room->name,
                        'survey_id' => $survey['id'],
                        'survey_remarks' => $survey['remarks'],
                        'survey_status' => $survey['status'],
                        'pending' => ($survey['remarks'] == 'Pending') ? 'bg-gradient-danger' : 'btn-outline-danger',
                        'done' => ($survey['remarks'] == 'Pending') ? 'btn-outline-success' : 'bg-gradient-success',
                    ];
                }
            } else {
                $data[] = [
                    'questionnaire_answer_id' => $answer->id,
                    'questionnaire_id' => $answer->questionnaire_id,
                    'questionnaire_name' => $answer->answer,
                    'site_id' => $room->site_id,
                    'site_building_id' => $room->site_building_id,
                    'site_building_level_id' => $room->site_building_level_id,
                    'site_building_room_id' => $room->id,
                    'site_name' => $room->site_name,
                    'site_building_name' => $room->building_name,
                    'site_building_floor_name' => $room->building_floor_name,
                    'site_building_room_name' => $room->name,
                    'survey_id' => 0,
                    'pending' => 'btn-outline-danger',
                    'done' => 'btn-outline-success',
                ];
            }
        }

        return $this->response($data, 'Successfully Retreived!', 200);
    }

    public function storeUpdate(Request $request)
    {
        $user = AdminViewModel::find(Auth::user()->id);
    
        if ($request->concern) {
            // pending_survey_answer_room
            foreach (explode(",", $request->concern) as $v) {
                $survey = explode("_", $v);
                $pending_done =  $survey[0];
                $survey_id = $survey[1];
                $answer_id = $survey[2];
                $room_id = $survey[3];
                $questionnaire_id = $survey[4];
                $site_id = $survey[5];
                $site_building_id = $survey[6];
                $site_building_level_id = $survey[7];
//Pending_12_10_4_23_12_13_3,pending_11_11_4_23_12_13_3{
   //done_12_10_4_23_12_13_3,   done_11_11_4_23_12_13_3
echo '0';
                if ($pending_done == 'pending') {
                    $questionnaire_survey = QuestionnaireSurvey::where('questionnaire_answer_id', $answer_id)
                        ->where('site_building_room_id', $room_id)
                        ->where('Remarks', 'Pending')
                        ->orderBy('id', 'desc')
                        ->limit(1)
                        ->get();

                    if (count($questionnaire_survey) == 0) {
                        $data = [
                            'user_id' =>   $user->client_id,
                            'questionnaire_id' => $questionnaire_id,
                            'questionnaire_answer_id' => $answer_id,
                            'site_id' => $site_id,
                            'site_building_id' => $site_building_id,
                            'site_building_level_id' => $site_building_level_id,
                            'site_building_room_id' => $room_id,
                            'remarks' => 'Pending',
                            'status' => 1,
                            'active' => 1,
                        ];

                        QuestionnaireSurvey::create($data);
                    }
                } else { 
                    $data = [
                        'questionnaire_answer_id' => $answer_id,
                        'site_building_room_id' => $room_id,
                        'site_id' => $site_id,
                        'site_building_id' => $site_building_id,
                        'site_building_level_id' => $site_building_level_id,

                    ];


                    $questionnaire_surveyz = QuestionnaireSurvey::where('questionnaire_answer_id', $answer_id)
                        ->where('site_building_room_id', $room_id)
                        ->where('site_id', $site_id)
                        ->where('site_building_id', $site_building_id)
                        ->where('site_building_level_id', $site_building_level_id)
                        ->where('Remarks', 'Pending')
                        ->orderBy('id', 'desc')
                        ->limit(1)
                        ->get();

                    if (count($questionnaire_surveyz) != 0) {

                        // $data = [
                        //     'Remarks' => 'Done',
                        // ];

                        // $questionnaire_surveyz->update($data);
                        // $affected = DB::update(
                        //     'update questionnaire_surveys set remarks = "Done" where questionnaire_answer_id = "'.$answer_id.'",
                        //     ['Done']
                        // );
                        // DB::unprepared('update questionnaire_surveys set remarks = "Done" where id  = "'.$questionnaire_surveyz->id.'");
                        ////  echo '>>>>>>>>'; echo $survey_id;

                        DB::unprepared(
                            '
                            update 
                                questionnaire_surveys set
                                    user_id = "' . $user->client_id . '",
                                    remarks = "Done", 
                                    status = "2", 
                                    updated_at = "' . date("Y-m-d H:i:s") . '"
                            where
                            questionnaire_id = "' . $questionnaire_id . '" and
                            questionnaire_answer_id = "' . $answer_id . '" and
                            site_id = "' . $site_id . '" and
                            site_building_id = "' . $site_building_id . '" and
                            site_building_level_id = "' . $site_building_level_id . '" and
                            site_building_room_id = "' . $room_id . '"'

                        );
                    }
                }
            }
        }

        return $this->response('', 'Successfully Retreived!', 200);
    }

    public function getAverageTimeByDaily(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");

            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('avg(TIMESTAMPDIFF(minute, created_at,updated_at)) AS minutes')
                ->where('created_at', '>=', date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59')
                ->where('site_building_room_id', $id)
                ->where('remarks', 'Done')
                ->groupBy('site_building_id')
                ->groupBy('questionnaire_answer_id')
                ->get();
            $sum = 0;
            if (count($logs) > 0) {
                foreach ($logs as $k => $v) {
                    $sum += $v->minutes;
                }
                $avg_time = number_format($sum / count($logs), 2);
            } else {
                $avg_time = 0;
            }

            // echo '>>>>>>>>>>>>>>'.$avg_time;
            return $this->response($avg_time, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
    public function getTotalSMSByDaily(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");

            $logs = SendSMS::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->where('created_at', '>=', date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59')
                ->where('site_building_room_id', $id)
                ->get()
                ->count();
            return $this->response($logs, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getTrendReportByDaily(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");

            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
                ->where('site_building_room_id', $id)
                ->where('created_at', '>=', date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59')
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('day(created_at)'))
                ->orderBy('created_at', 'ASC')
                ->get();
            $created_at = array();
            $data_day = array();
            $per_day = [];
            $per_building = [];
            foreach ($logs as $index => $log_created_at) {
                $day = date("m/d", strtotime($log_created_at->created_at));
                $created_at[] = $day;
            }

            foreach ($logs as $index => $log) {
                $day = date("m/d", strtotime($log->created_at));
                foreach ($created_at as $v) {
                    $data_day[$v] = ($v == $day) ? $log->total_survey : '';
                }
                $per_day[] = [
                    'day' => $day,
                    'total_survey' => $log->total_survey,
                    'reports' => $log->total_survey,
                    'building_name' => $log->building_name,
                    'building_color' => $log->building_color,
                    'data' => $data_day,
                ];
                $per_building[] = [
                    'building_name' => $log->building_name . ',color' . $log->building_color,
                    'reports' => $log->total_survey,
                ];
            }
            $per_day['legend'] = $this->sortWeek($per_building);
            return $this->response($per_day, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
    public function getTrendIncidentByDaily(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");

            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
                ->where('site_building_room_id', $id)
                ->where('created_at', '>=', date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59')
                ->where('remarks', 'Done')
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('day(created_at)'))
                ->orderBy('created_at', 'ASC')
                ->get();
            $created_at = array();
            $data_day = array();
            $per_day = [];
            $per_building = [];
            foreach ($logs as $index => $log_created_at) {
                $day = date("m/d", strtotime($log_created_at->created_at));
                $created_at[] = $day;
            }

            foreach ($logs as $index => $log) {
                $day = date("m/d", strtotime($log->created_at));
                foreach ($created_at as $v) {
                    $data_day[$v] = ($v == $day) ? $log->total_survey : '';
                }
                $per_day[] = [
                    'day' => $day,
                    'total_survey' => $log->total_survey,
                    'reports' => $log->total_survey,
                    'building_name' => $log->building_name,
                    'building_color' => $log->building_color,
                    'data' => $data_day,
                ];
                $per_building[] = [
                    'building_name' => $log->building_name . ',color' . $log->building_color,
                    'reports' => $log->total_survey,
                ];
            }
            $per_day['legend'] = $this->sortWeek($per_building);
            return $this->response($per_day, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getTrendReportByDailyAll(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");

            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
                ->where('site_building_room_id', $id)
                ->where('created_at', '>=', date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59')
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('day(created_at)'))
                ->orderBy('created_at', 'ASC')
                ->get();
            $created_at = array();
            $data_day = array();
            $per_day = [];
            $per_building = [];

            foreach ($this->createDateRangeArray($request->start_date, $request->end_date) as $vDateRange) {
                $day = date("m/d", strtotime($vDateRange));
                $created_at[] = $day;
            }


            foreach ($logs as $index => $log) {
                $day = date("m/d", strtotime($log->created_at));
                foreach ($created_at as $v) {
                    $data_day[$v] = ($v == $day) ? $log->total_survey : '';
                }
                $per_day[] = [
                    'day' => $day,
                    'total_survey' => $log->total_survey,
                    'reports' => $log->total_survey,
                    'building_name' => $log->building_name,
                    'building_color' => $log->building_color,
                    'data' => $data_day,
                ];
                $per_building[] = [
                    'building_name' => $log->building_name . ',color' . $log->building_color,
                    'reports' => $log->total_survey,
                ];
            }
            $per_day['legend'] = $this->sortWeek($per_building);

            return $this->response($per_day, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
    public function getTrendIncidentByDailyAll(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");

            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
                ->where('site_building_room_id', $id)
                ->where('created_at', '>=', date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59')
                ->where('remarks', 'Done')
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('day(created_at)'))
                ->orderBy('created_at', 'ASC')
                ->get();
            $created_at = array();
            $data_day = array();
            $per_day = [];
            $per_building = [];
            foreach ($this->createDateRangeArray($request->start_date, $request->end_date) as $vDateRange) {
                $day = date("m/d", strtotime($vDateRange));
                $created_at[] = $day;
            }



            foreach ($logs as $index => $log) {
                $day = date("m/d", strtotime($log->created_at));
                foreach ($created_at as $v) {
                    $data_day[$v] = ($v == $day) ? $log->total_survey : '';
                }
                $per_day[] = [
                    'day' => $day,
                    'total_survey' => $log->total_survey,
                    'reports' => $log->total_survey,
                    'building_name' => $log->building_name,
                    'building_color' => $log->building_color,
                    'data' => $data_day,
                ];
                $per_building[] = [
                    'building_name' => $log->building_name . ',color' . $log->building_color,
                    'reports' => $log->total_survey,
                ];
            }
            $per_day['legend'] = $this->sortWeek($per_building);
            return $this->response($per_day, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getAverageTimeByDay(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");

            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('avg(TIMESTAMPDIFF(minute, created_at,updated_at)) AS minutes')
                ->where('updated_at', '>=', date('Y-m-d', strtotime($request->day)) . ' 00:00:00')
                ->where('updated_at', '<=', date('Y-m-d', strtotime($request->day)) . ' 23:59:59')
                ->where('site_building_room_id', $id)
                ->where('remarks', 'Done')
                ->groupBy('site_building_id')
                ->groupBy('questionnaire_answer_id')
                ->get();
            $sum = 0;
            if (count($logs) > 0) {
                foreach ($logs as $k => $v) {
                    $sum += $v->minutes;
                }
                $avg_time = number_format($sum / count($logs), 2);
            } else {
                $avg_time = 0;
            }

            return $this->response($avg_time, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
    public function getTotalSMSByDay(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");

            $logs = SendSMS::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->where('created_at', '>=', date('Y-m-d', strtotime($request->day)) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($request->day)) . ' 23:59:59')
                ->where('site_building_room_id', $id)
                ->get()
                ->count();

            return $this->response($logs, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
    public function getTrendReportByDay(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");

            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
                ->where('site_building_room_id', $id)
                ->where('created_at', '>=', date('Y-m-d', strtotime($request->day)) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($request->day)) . ' 23:59:59')
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('hour(created_at)'))
                ->get();

            $per_hour = [];
            $per_building = [];
            foreach ($logs as $index => $log) {
                $hour = date("H", strtotime($log->created_at));
                $per_hour[] = [
                    'building_name' => $log->building_name,
                    'building_color' => $log->building_color,
                    'twentyfour' => ($hour == '00') ? $log->total_survey : '',
                    'one' => ($hour == '01') ? $log->total_survey : '',
                    'two' => ($hour == '02') ? $log->total_survey : '',
                    'three' => ($hour == '03') ? $log->total_survey : '',
                    'four' => ($hour == '04') ? $log->total_survey : '',
                    'five' => ($hour == '05') ? $log->total_survey : '',
                    'six' => ($hour == '06') ? $log->total_survey : '',
                    'seven' => ($hour == '07') ? $log->total_survey : '',
                    'eight' => ($hour == '08') ? $log->total_survey : '',
                    'nine' => ($hour == '09') ? $log->total_survey : '',
                    'ten' => ($hour == '10') ? $log->total_survey : '',
                    'eleven' => ($hour == '11') ? $log->total_survey : '',
                    'twelve' => ($hour == '12') ? $log->total_survey : '',
                    'thirteen' => ($hour == '13') ? $log->total_survey : '',
                    'forteen' => ($hour == '14') ? $log->total_survey : '',
                    'fifteen' => ($hour == '15') ? $log->total_survey : '',
                    'sixteen' => ($hour == '16') ? $log->total_survey : '',
                    'seventeen' => ($hour == '17') ? $log->total_survey : '',
                    'eighteen' => ($hour == '18') ? $log->total_survey : '',
                    'nineteen' => ($hour == '19') ? $log->total_survey : '',
                    'twenty' => ($hour == '20') ? $log->total_survey : '',
                    'twentyone' => ($hour == '21') ? $log->total_survey : '',
                    'twentytwo' => ($hour == '22') ? $log->total_survey : '',
                    'twentythree' => ($hour == '23') ? $log->total_survey : '',
                    'reports' => $log->total_survey,
                ];
                $per_building[] = [
                    'building_name' => $log->building_name . ',color' . $log->building_color,
                    'reports' => $log->total_survey,
                ];
            }
            $per_hour['legend'] = $this->sortWeek($per_building);

            return $this->response($per_hour, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getTrendIncidentByDay(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");

            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
                ->where('updated_at', '>=', date('Y-m-d', strtotime($request->day)) . ' 00:00:00')
                ->where('updated_at', '<=', date('Y-m-d', strtotime($request->day)) . ' 23:59:59')
                ->where('site_building_room_id', $id)
                ->where('remarks', 'Done')
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('hour(created_at)'))
                ->orderBy('created_at', 'ASC')
                ->get();

            $per_hour = [];
            $per_building = [];
            foreach ($logs as $index => $log) {
                $hour = date("H", strtotime($log->created_at));
                $per_hour[] = [
                    'building_name' => $log->building_name,
                    'building_color' => $log->building_color,
                    'twentyfour' => ($hour == '00') ? $log->total_survey : '',
                    'one' => ($hour == '01') ? $log->total_survey : '',
                    'two' => ($hour == '02') ? $log->total_survey : '',
                    'three' => ($hour == '03') ? $log->total_survey : '',
                    'four' => ($hour == '04') ? $log->total_survey : '',
                    'five' => ($hour == '05') ? $log->total_survey : '',
                    'six' => ($hour == '06') ? $log->total_survey : '',
                    'seven' => ($hour == '07') ? $log->total_survey : '',
                    'eight' => ($hour == '08') ? $log->total_survey : '',
                    'nine' => ($hour == '09') ? $log->total_survey : '',
                    'ten' => ($hour == '10') ? $log->total_survey : '',
                    'eleven' => ($hour == '11') ? $log->total_survey : '',
                    'twelve' => ($hour == '12') ? $log->total_survey : '',
                    'thirteen' => ($hour == '13') ? $log->total_survey : '',
                    'forteen' => ($hour == '14') ? $log->total_survey : '',
                    'fifteen' => ($hour == '15') ? $log->total_survey : '',
                    'sixteen' => ($hour == '16') ? $log->total_survey : '',
                    'seventeen' => ($hour == '17') ? $log->total_survey : '',
                    'eighteen' => ($hour == '18') ? $log->total_survey : '',
                    'nineteen' => ($hour == '19') ? $log->total_survey : '',
                    'twenty' => ($hour == '20') ? $log->total_survey : '',
                    'twentyone' => ($hour == '21') ? $log->total_survey : '',
                    'twentytwo' => ($hour == '22') ? $log->total_survey : '',
                    'twentythree' => ($hour == '23') ? $log->total_survey : '',
                    'reports' => $log->total_survey,
                ];
                $per_building[] = [
                    'building_name' => $log->building_name . ',color' . $log->building_color,
                    'reports' => $log->total_survey,
                ];
            }
            $per_hour['legend'] = $this->sortWeek($per_building);
            return $this->response($per_hour, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getAverageTimeByWeek(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");
            $date = Carbon::parse($request->week);
            if ($request->by == 2) {
                $start_date = $date->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
                $end_date = $date->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');
            } else {
                if ($request->customize == 'week') {
                    $start_date = $date->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
                    $end_date = $date->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');
                } else {
                    $start_date = $request->start_date;
                    $end_date = $request->end_date;
                }
            }
            // $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
            //     return $query->where('site_id', $site_id);
            // })
            //     ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
            //     ->where('created_at', '>=', date('Y-m-d', strtotime($date->startOfWeek()->format('Y-m-d'))) . ' 00:00:00')
            //     ->where('created_at', '<=', date('Y-m-d', strtotime($date->endOfWeek()->format('Y-m-d'))) . ' 23:59:59')
            //     ->where('site_building_room_id', $id)
            //     ->where('remarks', 'Done')
            //     ->groupBy('site_building_id')
            //     ->groupBy(QuestionnaireSurveyViewModel::raw('hour(created_at)'))
            //     ->orderBy('created_at', 'ASC')
            //     ->get();

            // $current_year = date("Y");

            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('avg(TIMESTAMPDIFF(minute, created_at,updated_at)) AS minutes')
                ->where('created_at', '>=', date('Y-m-d', strtotime($start_date)) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($end_date)) . ' 23:59:59')
                ->where('site_building_room_id', $id)
                ->where('remarks', 'Done')
                ->groupBy('site_building_id')
                ->groupBy('questionnaire_answer_id')
                ->get();
            $sum = 0;
            if (count($logs) > 0) {
                foreach ($logs as $k => $v) {
                    $sum += $v->minutes;
                }
                $avg_time = number_format($sum / count($logs), 2);
            } else {
                $avg_time = 0;
            }

            return $this->response($avg_time, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getTotalSMSByWeek(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");
            $date = Carbon::parse($request->week);
            if ($request->by == 2) {
                $start_date = $date->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
                $end_date = $date->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');
            } else {
                if ($request->customize == 'week') {
                    $start_date = $date->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
                    $end_date = $date->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');
                } else {
                    $start_date = $request->start_date;
                    $end_date = $request->end_date;
                }
            }

            $logs = SendSMS::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->where('created_at', '>=', date('Y-m-d', strtotime($start_date)) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($end_date)) . ' 23:59:59')
                ->where('site_building_room_id', $id)
                ->get()
                ->count();

            return $this->response($logs, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getTrendReportByWeek(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $date = Carbon::parse($request->week);
            if ($request->by == 2) {
                $start_date = $date->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
                $end_date = $date->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');
            } else {
                if ($request->customize == 'week') {
                    $start_date = $date->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
                    $end_date = $date->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');
                } else {
                    $start_date = $request->start_date;
                    $end_date = $request->end_date;
                }
            }

            $current_year = date("Y");

            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
                ->where('created_at', '>=', date('Y-m-d', strtotime($start_date)) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($end_date)) . ' 23:59:59')
                ->where('site_building_room_id', $id)
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('day(created_at)'))
                ->orderBy('created_at', 'ASC')
                ->get();

            $per_day = [];
            $per_building = [];
            foreach ($logs as $index => $log) {
                $day = date("D", strtotime($log->created_at));
                $per_day[] = [
                    'building_name' => $log->building_name,
                    'building_color' => $log->building_color,
                    'mon' => ($day == 'Mon') ? $log->total_survey : '',
                    'tue' => ($day == 'Tue') ? $log->total_survey : '',
                    'wed' => ($day == 'Wed') ? $log->total_survey : '',
                    'thu' => ($day == 'Thu') ? $log->total_survey : '',
                    'fri' => ($day == 'Fri') ? $log->total_survey : '',
                    'sat' => ($day == 'Sat') ? $log->total_survey : '',
                    'sun' => ($day == 'Sun') ? $log->total_survey : '',
                    'reports' => $log->total_survey,
                ];
                $per_building[] = [
                    'building_name' => $log->building_name . ',color' . $log->building_color,
                    'reports' => $log->total_survey,
                ];
            }
            $per_day['legend'] = $this->sortWeek($per_building);

            return $this->response($per_day, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getTrendIncidentByWeek(Request $request)
    {

        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $date = Carbon::parse($request->week);
            $current_year = date("Y");
            if ($request->by == 2) {
                $start_date = $date->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
                $end_date = $date->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');
            } else {
                if ($request->customize == 'week') {
                    $start_date = $date->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
                    $end_date = $date->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');
                } else {
                    $start_date = $request->start_date;
                    $end_date = $request->end_date;
                }
            }

            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
                ->where('created_at', '>=', date('Y-m-d', strtotime($start_date)) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($end_date)) . ' 23:59:59')
                ->where('site_building_room_id', $id)
                ->where('remarks', 'Done')
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('day(created_at)'))
                ->orderBy('created_at', 'ASC')
                ->get();

            $per_day = [];
            $per_building = [];
            foreach ($logs as $index => $log) {
                $day = date("D", strtotime($log->created_at));
                $per_day[] = [
                    'building_name' => $log->building_name,
                    'building_color' => $log->building_color,
                    'sun' => ($day == 'Sun') ? $log->total_survey : '',
                    'mon' => ($day == 'Mon') ? $log->total_survey : '',
                    'tue' => ($day == 'Tue') ? $log->total_survey : '',
                    'wed' => ($day == 'Wed') ? $log->total_survey : '',
                    'thu' => ($day == 'Thu') ? $log->total_survey : '',
                    'fri' => ($day == 'Fri') ? $log->total_survey : '',
                    'sat' => ($day == 'Sat') ? $log->total_survey : '',
                    'reports' => $log->total_survey,
                ];
                $per_building[] = [
                    'building_name' => $log->building_name . ',color' . $log->building_color,
                    'reports' => $log->total_survey,
                ];
            }
            $per_day['legend'] = $this->sortWeek($per_building);
            return $this->response($per_day, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
    public function getDonutReportByDay(Request $request)
    {
        try {
            $percentage = $this->getPercentage($request);
            return $this->response($percentage, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getDonutReportByDayAnswer(Request $request)
    {
        try {
            $percentage = $this->getPercentageAnswer($request);
            return $this->response($percentage, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getDonutReportByDaily(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);

            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;
            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, count(*) as tenant_survey')
                ->where('site_building_room_id', $id)
                ->where('created_at', '>=', date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59')
                ->where('remarks', 'Done')
                ->groupBy('questionnaire_id')
                ->orderBy('questionnaire_id', 'ASC')
                ->get();

            $total = $logs->sum('tenant_survey');

            $percentage = [];
            foreach ($logs as $index => $log) {
                $percentage[] = [
                    'questionnaire' => $log->questionnaire,
                    'questionnaire_color' => $log->questionnaire_color,
                    'questionnaire_answer' => $log->questionnaire_answer,
                    'tenant_survey' => $log->tenant_survey,

                    'percentage_share' => round(($log->tenant_survey / $total) * 100, 2)
                ];
            }

            //return $percentage;
            return $this->response($percentage, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getDonutReportByDailyAnswer(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);

            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;
            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, count(*) as tenant_survey')
                ->where('site_building_room_id', $id)
                ->where('created_at', '>=', date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59')
                ->where('remarks', 'Done')
                ->groupBy('questionnaire_id')
                ->groupBy('questionnaire_answer_id')
                ->orderBy('questionnaire_id', 'ASC')
                ->get();

            $total = $logs->sum('tenant_survey');

            $percentage = [];
            foreach ($logs as $index => $log) {
                $percentage[] = [
                    'questionnaire' => $log->questionnaire,
                    'questionnaire_answer' => $log->questionnaire_answer,
                    'tenant_survey' => $log->tenant_survey,

                    'percentage_share' => round(($log->tenant_survey / $total) * 100, 2)
                ];
            }

            //return $percentage;
            return $this->response($percentage, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }


    public function getAverageTimeByMonth(Request $request)
    {
        try {
            $id = session()->get('room_id');

            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");

            if ($request->by == 3) {
                $start_date  = date('Y-m-d', strtotime($request->month)) . ' 00:00:00';
                $end_date = date('Y-m-t', strtotime($request->month)) . ' 23:59:59';
            } else {
                if ($request->customized == 'month') {
                    //$start_date  = date('Y-m-d', strtotime($request->month)) . ' 00:00:00';
                    //$end_date = date('Y-m-t', strtotime($request->month)) . ' 23:59:59';
                    $start_date  = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
                    $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';
                } else {
                    $start_date = $request->start_date;
                    $end_date = $request->end_date;
                }
            }

            // $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
            //     return $query->where('site_id', $site_id);
            // })
            //     ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
            //     ->whereBetween('created_at', [$start_date, $end_date])
            //     ->where('site_building_room_id', $id)
            //     ->where('remarks', 'Done')
            //     ->groupBy('site_building_id')
            //     ->groupBy(QuestionnaireSurveyViewModel::raw('week(created_at)'))
            //     ->orderBy('created_at', 'ASC')
            //     ->get();

            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('avg(TIMESTAMPDIFF(minute, created_at,updated_at)) AS minutes')
                ->whereBetween('created_at', [$start_date, $end_date])
                ->where('site_building_room_id', $id)
                ->where('remarks', 'Done')
                ->groupBy('site_building_id')
                ->groupBy('questionnaire_answer_id')
                ->get();
            $sum = 0;
            if (count($logs) > 0) {
                foreach ($logs as $k => $v) {
                    $sum += $v->minutes;
                }
                $avg_time = number_format($sum / count($logs), 2);
            } else {
                $avg_time = 0;
            }

            return $this->response($avg_time, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
    public function getTotalSMSByMonth(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");

            if ($request->by == 3) {
                $start_date  = date('Y-m-d', strtotime($request->month)) . ' 00:00:00';
                $end_date = date('Y-m-t', strtotime($request->month)) . ' 23:59:59';
            } else {
                if ($request->customized == 'month') {
                    //$start_date  = date('Y-m-d', strtotime($request->month)) . ' 00:00:00';
                    //$end_date = date('Y-m-t', strtotime($request->month)) . ' 23:59:59';
                    $start_date  = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
                    $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';
                } else {
                    $start_date = $request->start_date;
                    $end_date = $request->end_date;
                }
            }

            $logs = SendSMS::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->whereBetween('created_at', [$start_date, $end_date])
                ->where('site_building_room_id', $id)
                ->get()
                ->count();
            return $this->response($logs, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getTrendReportByMonth(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");

            if ($request->by == 3) {
                $start_date  = date('Y-m-d', strtotime($request->month)) . ' 00:00:00';
                $end_date = date('Y-m-t', strtotime($request->month)) . ' 23:59:59';
            } else {
                if ($request->customized == 'month') {
                    //$start_date  = date('Y-m-d', strtotime($request->month)) . ' 00:00:00';
                    //$end_date = date('Y-m-t', strtotime($request->month)) . ' 23:59:59';
                    $start_date  = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
                    $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';
                } else {
                    $start_date = $request->start_date;
                    $end_date = $request->end_date;
                }
            }
            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
                ->whereBetween('created_at', [$start_date, $end_date])
                ->where('site_building_room_id', $id)
                ->groupBy('site_building_id')
                // ->groupBy('jordan')
                ->groupBy(QuestionnaireSurveyViewModel::raw('week(created_at)'))
                ->get();
            $total_per_month = [];
            $per_month = [];
            $per_range = [];
            $month = explode("-", $request->month);
            $monthz = $month[1];
            $year = $month[0];
            $lastDayOfWeek = '6'; //1 (for monday) to 7 (for sunday)
            $weeks = $this->getWeeksInMonth($year, $monthz, $lastDayOfWeek);
            $per_building = [];
            foreach ($logs as $index => $log) {
                $day = date("d", strtotime($log->created_at));
                $set = $index + 1;
                $bar = array();

                foreach ($weeks as $weekNumber => $week) {
                    $monday = substr($week[0], 8);
                    $sunday = substr($week[1], 8);
                    $bar[] = ($day >= $monday && $day <= $sunday) ? $log->total_survey : '';
                }
                $per_month[] = [
                    'building_name' => $log->building_name,
                    'building_color' => $log->building_color,
                    'bar' => $bar,
                    'reports' => $log->total_survey,
                ];
                $per_building[] = [
                    'building_name' => $log->building_name . ',color' . $log->building_color,
                    'reports' => $log->total_survey,
                ];
            }

            $per_month['legend'] = ($this->sortWeek($per_building)) ? $this->sortWeek($per_building) : '<div></div>';

            foreach ($weeks as $weekNumber => $week) {
                $monday = substr($week[0], 8);
                $sunday = substr($week[1], 8);

                $per_range[] = [
                    str_replace('-', '/', substr($week[0], 5)) . '-' . str_replace('-', '/', substr($week[1], 5)), //str_replace('-', '/', substr($weeks[$index + 1][0], 5)) . ' - ' . str_replace('-', '/', substr($weeks[$index + 1][1], 5)),
                ];
            }
            $total_per_month[] = $per_month;
            $total_per_month[] = $per_range;
            return $this->response($total_per_month, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getTrendIncidentByMonth(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");

            if ($request->by == 3) {
                $start_date  = date('Y-m-d', strtotime($request->month)) . ' 00:00:00';
                $end_date = date('Y-m-t', strtotime($request->month)) . ' 23:59:59';
            } else {
                if ($request->customized == 'month') {
                    //$start_date  = date('Y-m-d', strtotime($request->month)) . ' 00:00:00';
                    //$end_date = date('Y-m-t', strtotime($request->month)) . ' 23:59:59';
                    $start_date  = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
                    $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';
                } else {
                    $start_date = $request->start_date;
                    $end_date = $request->end_date;
                }
            }
            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
                ->whereBetween('created_at', [$start_date, $end_date])
                ->where('site_building_room_id', $id)
                ->where('remarks', 'Done')
                ->groupBy('site_building_id')
                // ->groupBy('jordan')
                ->groupBy(QuestionnaireSurveyViewModel::raw('week(created_at)'))
                ->get();
            $total_per_month = [];
            $per_month = [];
            $per_range = [];
            $month = explode("-", $request->month);
            $monthz = $month[1];
            $year = $month[0];
            $lastDayOfWeek = '6'; //1 (for monday) to 7 (for sunday)
            $weeks = $this->getWeeksInMonth($year, $monthz, $lastDayOfWeek);
            $per_building = [];
            foreach ($logs as $index => $log) {
                $day = date("d", strtotime($log->created_at));
                $set = $index + 1;
                $bar = array();

                foreach ($weeks as $weekNumber => $week) {
                    $monday = substr($week[0], 8);
                    $sunday = substr($week[1], 8);
                    $bar[] = ($day >= $monday && $day <= $sunday) ? $log->total_survey : '';
                }
                $per_month[] = [
                    'building_name' => $log->building_name,
                    'building_color' => $log->building_color,
                    'bar' => $bar,
                    'reports' => $log->total_survey,
                ];
                $per_building[] = [
                    'building_name' => $log->building_name . ',color' . $log->building_color,
                    'reports' => $log->total_survey,
                ];
            }

            $per_month['legend'] = ($this->sortWeek($per_building)) ? $this->sortWeek($per_building) : '<div></div>';

            foreach ($weeks as $weekNumber => $week) {
                $monday = substr($week[0], 8);
                $sunday = substr($week[1], 8);

                $per_range[] = [
                    str_replace('-', '/', substr($week[0], 5)) . '-' . str_replace('-', '/', substr($week[1], 5)), //str_replace('-', '/', substr($weeks[$index + 1][0], 5)) . ' - ' . str_replace('-', '/', substr($weeks[$index + 1][1], 5)),
                ];
            }
            $total_per_month[] = $per_month;
            $total_per_month[] = $per_range;
            return $this->response($total_per_month, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
    public function getAverageTimeByYear(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");
            if ($request->by == 4) {
                $start_date  = $request->year . '-01-01 00:00:00';
                $end_date = $request->year . '-12-31 23:59:59';
            } else {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
            }
            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('avg(TIMESTAMPDIFF(minute, created_at,updated_at)) AS minutes')
                ->whereBetween('created_at', [$start_date, $end_date])
                ->where('site_building_room_id', $id)
                ->where('remarks', 'Done')
                ->groupBy('site_building_id')
                ->groupBy('questionnaire_answer_id')
                ->get();
            $sum = 0;
            //print_r($sum);
            //print_r(count($logs));
            if (count($logs) > 0) {
                foreach ($logs as $k => $v) {
                    $sum += $v->minutes;
                }
                $avg_time = number_format($sum / count($logs), 2);
            } else {
                $avg_time = 0;
            }

            return $this->response($avg_time, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function gerTotalSMSByYear(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");
            if ($request->by == 4) {
                $start_date  = $request->year . '-01-01 00:00:00';
                $end_date = $request->year . '-12-31 23:59:59';
            } else {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
            }
            $logs = SendSMS::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->whereBetween('created_at', [$start_date, $end_date])
                ->where('site_building_room_id', $id)
                ->get()
                ->count();
            return $this->response($logs, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getTrendReportByYear(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");

            if ($request->by == 4) {
                $start_date  = $request->year . '-01-01 00:00:00';
                $end_date = $request->year . '-12-31 23:59:59';
            } else {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
            }
            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
                ->whereBetween('created_at', [$start_date, $end_date])
                ->where('site_building_room_id', $id)
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('month(created_at)'))
                ->orderBy('created_at', 'ASC')
                ->get();

            $per_month = [];
            $per_building = [];
            foreach ($logs as $index => $log) {

                $month = date("m", strtotime($log->created_at));

                $per_month[] = [
                    'building_name' => $log->building_name,
                    'building_color' => $log->building_color,
                    'jan' => ($month == '01') ? $log->total_survey : '',
                    'feb' => ($month == '02') ? $log->total_survey : '',
                    'mar' => ($month == '03') ? $log->total_survey : '',
                    'apr' => ($month == '04') ? $log->total_survey : '',
                    'may' => ($month == '05') ? $log->total_survey : '',
                    'jun' => ($month == '06') ? $log->total_survey : '',
                    'jul' => ($month == '07') ? $log->total_survey : '',
                    'aug' => ($month == '08') ? $log->total_survey : '',
                    'sep' => ($month == '09') ? $log->total_survey : '',
                    'oct' => ($month == '10') ? $log->total_survey : '',
                    'nov' => ($month == '11') ? $log->total_survey : '',
                    'dec' => ($month == '12') ? $log->total_survey : '',
                    'reports' => $log->total_survey,
                ];
                $per_building[] = [
                    'building_name' => $log->building_name . ',color' . $log->building_color,
                    'reports' => $log->total_survey,
                ];
            }
            $per_month['legend'] = $this->sortWeek($per_building);
            return $this->response($per_month, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
    public function getTrendIncidentByYear(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");
            if ($request->by == 4) {
                $start_date  = $request->year . '-01-01 00:00:00';
                $end_date = $request->year . '-12-31 23:59:59';
            } else {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
            }
            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
                ->whereBetween('created_at', [$start_date, $end_date])
                ->where('site_building_room_id', $id)
                ->where('remarks', 'Done')
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('month(created_at)'))
                ->orderBy('created_at', 'ASC')
                ->get();

            $per_month = [];
            $per_building = [];
            foreach ($logs as $index => $log) {

                $month = date("m", strtotime($log->created_at));

                $per_month[] = [
                    'building_name' => $log->building_name,
                    'building_color' => $log->building_color,
                    'jan' => ($month == '01') ? $log->total_survey : '',
                    'feb' => ($month == '02') ? $log->total_survey : '',
                    'mar' => ($month == '03') ? $log->total_survey : '',
                    'apr' => ($month == '04') ? $log->total_survey : '',
                    'may' => ($month == '05') ? $log->total_survey : '',
                    'jun' => ($month == '06') ? $log->total_survey : '',
                    'jul' => ($month == '07') ? $log->total_survey : '',
                    'aug' => ($month == '08') ? $log->total_survey : '',
                    'sep' => ($month == '09') ? $log->total_survey : '',
                    'oct' => ($month == '10') ? $log->total_survey : '',
                    'nov' => ($month == '11') ? $log->total_survey : '',
                    'dec' => ($month == '12') ? $log->total_survey : '',
                    'reports' => $log->total_survey,
                ];
                $per_building[] = [
                    'building_name' => $log->building_name . ',color' . $log->building_color,
                    'reports' => $log->total_survey,
                ];
            }
            $per_month['legend'] = $this->sortWeek($per_building);
            return $this->response($per_month, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getAverageTimeByYears(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");

            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('avg(TIMESTAMPDIFF(minute, created_at,updated_at)) AS minutes')
                ->where('created_at', '>=', date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59')
                ->where('site_building_room_id', $id)
                ->where('remarks', 'Done')
                ->groupBy('site_building_id')
                ->groupBy('questionnaire_answer_id')
                ->orderBy('created_at', 'ASC')
                ->get();
            $sum = 0;
            if (count($logs) > 0) {
                foreach ($logs as $k => $v) {
                    $sum += $v->minutes;
                }
                $avg_time = number_format($sum / count($logs), 2);
            } else {
                $avg_time = 0;
            }
            return $this->response($avg_time, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
    public function getTotalSMSByYear(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");
            if ($request->by == 4) {
                $start_date  = $request->year . '-01-01 00:00:00';
                $end_date = $request->year . '-12-31 23:59:59';
            } else {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
            }
            $logs = SendSMS::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->whereBetween('created_at', [$start_date, $end_date])
                ->get()
                ->count();
            return $this->response($logs, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
    public function getTotalSMSByYears(Request $request)
    {
        try {
            //$id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");

            $logs = SendSMS::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->where('created_at', '>=', date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59')
                //->where('site_building_room_id', $id)
                ->orderBy('created_at', 'ASC')
                ->get()
                ->count();
            return $this->response($logs, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
    public function getTrendReportByYears(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");

            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
                ->where('created_at', '>=', date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59')
                ->where('site_building_room_id', $id)
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('year(created_at)'))
                ->orderBy('created_at', 'ASC')
                ->get();
            $created_at = array();
            $data_year  = array();
            $per_year = [];
            $per_building = [];

            foreach ($this->createDateRangeArray($request->start_date, $request->end_date, 'year') as $vDateRange) {
                $created_at[] = $vDateRange;
            }

            foreach ($logs as $index => $log) {
                $year = date("Y", strtotime($log->created_at));
                foreach ($created_at as $v) {
                    $data_year[$v] = ($v == $year) ? $log->total_survey : '';
                }
                $per_year[] = [
                    'year' => $year,
                    'total_survey' => $log->total_survey,
                    'reports' => $log->total_survey,
                    'building_name' => $log->building_name,
                    'building_color' => $log->building_color,
                    'data' => $data_year,
                ];
                $per_building[] = [
                    'building_name' => $log->building_name . ',color' . $log->building_color,
                    'reports' => $log->total_survey,
                ];
            }
            $per_year['legend'] = $this->sortWeek($per_building);

            return $this->response($per_year, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
    public function getTrendIncidentByYears(Request $request)
    {
        try {
            $id = session()->get('room_id');
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");

            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
                ->where('site_building_room_id', $id)
                ->where('created_at', '>=', date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59')
                ->where('remarks', 'Done')
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('year(created_at)'))
                ->orderBy('created_at', 'ASC')
                ->get();
            $created_at = array();
            $data_year  = array();
            $per_year = [];
            $per_building = [];

            foreach ($this->createDateRangeArray($request->start_date, $request->end_date, 'year') as $vDateRange) {
                $created_at[] = $vDateRange;
            }

            foreach ($logs as $index => $log) {
                $year = date("Y", strtotime($log->created_at));
                foreach ($created_at as $v) {
                    $data_year[$v] = ($v == $year) ? $log->total_survey : '';
                }
                $per_year[] = [
                    'year' => $year,
                    'total_survey' => $log->total_survey,
                    'reports' => $log->total_survey,
                    'building_name' => $log->building_name,
                    'building_color' => $log->building_color,
                    'data' => $data_year,
                ];
                $per_building[] = [
                    'building_name' => $log->building_name . ',color' . $log->building_color,
                    'reports' => $log->total_survey,
                ];
            }
            $per_year['legend'] = $this->sortWeek($per_building);
            return $this->response($per_year, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }


    public function getPercentage($request)
    {
        $id = session()->get('room_id');
        $site_id = '';
        $filters = json_decode($request->filters);

        if ($request->day) {
            $start_date  = date('Y-m-d', strtotime($request->day)) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime($request->day)) . ' 23:59:59';
        } else if ($request->week) {
            $date = Carbon::parse($request->week);
            if ($request->by == 2) {
                $start_date = $date->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
                $end_date = $date->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');
            } else {
                if ($request->customize == 'week') {
                    $start_date = $date->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
                    $end_date = $date->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');
                } else {
                    $start_date = $request->start_date . ' 00:00:00';
                    $end_date = $request->end_date . ' 23:59:59';
                    //$start_date  = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
                    //$end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';
                }
            }
        } else if ($request->month) {
            if ($request->by == 3) {
                $start_date  = date('Y-m-d', strtotime($request->month)) . ' 00:00:00';
                $end_date = date('Y-m-t', strtotime($request->month)) . ' 23:59:59';
            } else {
                if ($request->customized == 'month') {
                    $start_date  = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
                    $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';
                } else {
                    $start_date = $request->start_date;
                    $end_date = $request->end_date;
                }
            }
        } else if ($request->year) {
            if ($request->by == 4) {
                $start_date  = $request->year . '-01-01 00:00:00';
                $end_date = $request->year . '-12-31 23:59:59';
            } else {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
            }
        } else if ($request->start_date && $request->start_date) {
            $start_date = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';
        } else {
            $start_date = date("Y-m-d", strtotime("-1 months"));
            $end_date = date("Y-m-d");
        }


        if ($filters)
            $site_id = $filters->site_id;
        if ($request->site_id)
            $site_id = $request->site_id;

        ///echo $start_date .'---'. $end_date;

        $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
            return $query->where('site_id', $site_id);
        })
            ->selectRaw('questionnaire_surveys.*, count(*) as tenant_survey')
            ->whereBetween('created_at', [$start_date, $end_date])
            ->where('site_building_room_id', $id)
            ->where('remarks', 'Done')
            ->groupBy('questionnaire_id')
            //->groupBy('jordan')
            ->orderBy('questionnaire_id', 'ASC')
            ->get();

        $total = $logs->sum('tenant_survey');

        $percentage = [];
        foreach ($logs as $index => $log) {
            $percentage[] = [

                'questionnaire' => $log->questionnaire,
                'questionnaire_color' => $log->questionnaire_color,
                'questionnaire_answer' => $log->questionnaire_answer,
                'tenant_survey' => $log->tenant_survey,

                'percentage_share' => round(($log->tenant_survey / $total) * 100, 2)
            ];
        }

        return $percentage;
    }

    public function getPercentageAnswer($request)
    {
        $id = session()->get('room_id');
        $site_id = '';
        $filters = json_decode($request->filters);

        if ($request->day) {
            $start_date  = date('Y-m-d', strtotime($request->day)) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime($request->day)) . ' 23:59:59';
        } else if ($request->week) {
            $date = Carbon::parse($request->week);
            if ($request->by == 2) {
                $start_date = $date->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
                $end_date = $date->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');
            } else {
                if ($request->customize == 'week') {
                    $start_date = $date->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
                    $end_date = $date->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');
                } else {
                    $start_date = $request->start_date . ' 00:00:00';
                    $end_date = $request->end_date . ' 23:59:59';
                }
            }
        } else if ($request->month) {
            if ($request->by == 3) {
                $start_date  = date('Y-m-d', strtotime($request->month)) . ' 00:00:00';
                $end_date = date('Y-m-t', strtotime($request->month)) . ' 23:59:59';
            } else {
                if ($request->customized == 'month') {
                    $start_date  = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
                    $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';
                } else {
                    $start_date = $request->start_date;
                    $end_date = $request->end_date;
                }
            }
        } else if ($request->year) {
            if ($request->by == 4) {
                $start_date  = $request->year . '-01-01 00:00:00';
                $end_date = $request->year . '-12-31 23:59:59';
            } else {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
            }
        } else if ($request->start_date && $request->start_date) {
            $start_date = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';
        } else {
            $start_date = date("Y-m-d", strtotime("-1 months"));
            $end_date = date("Y-m-d");
        }



        if ($filters)
            $site_id = $filters->site_id;
        if ($request->site_id)
            $site_id = $request->site_id;



        $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
            return $query->where('site_id', $site_id);
        })
            ->selectRaw('questionnaire_surveys.*, count(*) as tenant_survey')
            ->where('site_building_room_id', $id)
            ->whereBetween('created_at', [$start_date, $end_date])
            ->where('remarks', 'Done')
            ->groupBy('questionnaire_id')
            ->groupBy('questionnaire_answer_id')
            ->orderBy('questionnaire_id', 'ASC')
            ->get();

        $total = $logs->sum('tenant_survey');

        $percentage = [];
        foreach ($logs as $index => $log) {
            $percentage[] = [

                'questionnaire' => $log->questionnaire,
                'questionnaire_color' => $log->questionnaire_color,
                'questionnaire_answer' => $log->questionnaire_answer,
                'tenant_survey' => $log->tenant_survey,

                'percentage_share' => round(($log->tenant_survey / $total) * 100, 2)
            ];
        }

        return $percentage;
    }

    public function getSurveyFirstLast()
    {
        try {
            $id = session()->get('room_id');
            $currentDateTime = new DateTime('now');
            $currentDate = $currentDateTime->format('Y-m-d');
            $start_date  = date('Y-m-d', strtotime($currentDate)) . ' 00:00:00';
            $end_date = date('Y-m-d', strtotime($currentDate)) . ' 23:59:59';

            $logs = QuestionnaireSurvey::where('site_building_room_id', $id)
                ->whereBetween('updated_at', [$start_date, $end_date])
                ->get()->count();

            return $this->response($logs, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
    public function getWeeksInMonth($year, $month, $lastDayOfWeek)
    {
        $aWeeksOfMonth = [];
        $date = new DateTime("{$year}-{$month}-01");

        $iDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $aOneWeek = [$date->format('Y-m-d')];
        $weekNumber = 1;
        for ($i = 1; $i <= $iDaysInMonth; $i++) {
            if ($lastDayOfWeek == $date->format('N') || $i == $iDaysInMonth) {
                $aOneWeek[]      = $date->format('Y-m-d');
                $aWeeksOfMonth[$weekNumber++] = $aOneWeek;
                $date->add(new DateInterval('P1D'));
                // $date->add(CarbonInterval::days(1));
                $aOneWeek = [$date->format('Y-m-d')];
                $i++;
            }
            $date->add(new DateInterval('P1D'));
        }
        return $aWeeksOfMonth;
    }

    function createDateRangeArray($strDateFrom, $strDateTo, $filterBy = 'daily')
    {
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.

        // could test validity of dates here but I'm already doing
        // that in the main script

        $aryRange = [];

        $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
        $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));
        if ($filterBy == 'year') {
            if ($iDateTo >= $iDateFrom) {
                array_push($aryRange, date('Y', $iDateFrom)); // first entry
                while ($iDateFrom < $iDateTo) {
                    $iDateFrom += 86400; // add 24 hours
                    array_push($aryRange, date('Y', $iDateFrom));
                }
            }
            return array_unique($aryRange);
        } else {
            if ($iDateTo >= $iDateFrom) {
                array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
                while ($iDateFrom < $iDateTo) {
                    $iDateFrom += 86400; // add 24 hours
                    array_push($aryRange, date('Y-m-d', $iDateFrom));
                }
            }
            return $aryRange;
        }
    }
    public function sortWeek($per_building)
    {
        $buildings = [];
        $sort_buildings = [];

        foreach ($per_building as $building) {
            $buildings[array_shift($building)][] = $building;
        }

        foreach ($buildings as $k => $vBuilding) {
            $building_color = explode(',color', $k);
            $a = [];
            foreach ($vBuilding as $k => $v) {
                $a[] = $vBuilding[$k]['reports'];
            }

            $sort_buildings[] = '<div style=" height: 50px;display:inline;font-size: 0.500em;color: #FF0000;padding-right: 10px;padding-left: 10px;"><di style="background: #4679BD; display: inline;
            background-color: ' . $building_color[1] . ';
            padding-top: .1px;
            padding-right: 35px;
            padding-bottom: .1px;
            
        "></div>' . $building_color[0] . ' (Reports(s)' . array_sum($a) . ')</di>';
        }
        return implode($sort_buildings);
    }
    public function getFirstLastSurvey()
    {
        try {
            $id = session()->get('room_id');
            $total_count = QuestionnaireSurvey::select('created_at')
                ->where('site_building_room_id', $id)
                ->get()->count();

            $first = $questionnaire_survey = QuestionnaireSurvey::select('created_at')
                ->where('site_building_room_id', $id)
                ->orderBy('id', 'asc')
                ->limit(1)
                ->get();
            $last = $questionnaire_survey = QuestionnaireSurvey::select('created_at')
                ->where('site_building_room_id', $id)
                ->orderBy('id', 'desc')
                ->limit(1)
                ->get();

            $first_last = array();
            if ($total_count == 0) {
                $first_last[] =  date('Y-m-d');
                $first_last[] = date('Y-m-d');
            } else {
                $first_last[] =  date('Y-m-d', strtotime($first[0]->created_at));
                $first_last[] = date('Y-m-d', strtotime($last[0]->created_at));
            }


            return $this->response($first_last, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }
}
