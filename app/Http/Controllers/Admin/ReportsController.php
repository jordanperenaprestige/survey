<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Interfaces\ReportsControllerInterface;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Models\SiteFeedback;
use App\Models\ViewModels\AdminViewModel;
use App\Models\ViewModels\LogsViewModel;
use App\Models\ViewModels\LogsMonthlyUsageViewModel;
use App\Models\ViewModels\SiteFeedbackViewModel;
use App\Models\ViewModels\SiteScreenUptimeViewModel;
use App\Models\ViewModels\QuestionnaireSurveyViewModel;
use App\Models\Log;
use App\Models\SiteScreenUptime;
use App\Models\QuestionnaireSurvey;
use App\Models\SendSMS;
use Carbon\Carbon;

// use App\Exports\MerchantPopulationExport;
// use App\Exports\TopTenantExport;
// use App\Exports\TopKeywordsExport;
// use App\Exports\MerchantUsageExport;
// use App\Exports\MonthlyUsageExport;
// use App\Exports\YearlyUsageExport;
// use App\Exports\IsHelpfulExport;
// use App\Exports\UptimeHistoryExport;
// use App\Exports\KioskUsageExport;
use App\Exports\Export;
use Storage;
use DateTime;
use DateInterval;

class ReportsController extends AppBaseController implements ReportsControllerInterface
{
    /********************************************
     * 			REPORTS MANAGEMENT      	 	*
     ********************************************/
    public function __construct()
    {
        $this->module_id = 20;
        $this->module_name = 'Reports';
    }

    public function index()
    {
        return view('admin.report_population');
    }

    public function topTenantSearch()
    {
        return view('admin.report_tenant_search');
    }

    public function mostSearchKeywords()
    {
        return view('admin.report_top_keywords');
    }

    public function merchantUsage()
    {
        return view('admin.report_merchant_usage');
    }

    public function monthlyUsage()
    {
        return view('admin.report_monthly_usage');
    }

    public function yearlyUsage()
    {
        return view('admin.report_yearly_usage');
    }

    public function isHelpful()
    {
        return view('admin.report_is_helpful');
    }

    public function uptimeHistory()
    {
        return view('admin.report_uptime_history');
    }

    public function kioskUsage()
    {
        return view('admin.report_kiosk_usage');
    }

    // public function getPercentage($request)
    // {
    //     $site_id = '';
    //     $filters = json_decode($request->filters);

    //     if ($request->day) {
    //         $start_date  = date('Y-m-d', strtotime($request->day)) . ' 00:00:00';
    //         $end_date = date('Y-m-d', strtotime($request->day)) . ' 23:59:59';
    //     } else if ($request->week) {
    //         $date = Carbon::parse($request->week);

    //         if ($request->by == 2) {
    //             $start_date = $date->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
    //             $end_date = $date->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');
    //         } else {
    //             if ($request->customize == 'week') {
    //                 $start_date = $date->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
    //                 $end_date = $date->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');
    //             } else {
    //                 $start_date = $request->start_date;
    //                 $end_date = $request->end_date;
    //             }
    //         }
    //     } else if ($request->month) {
    //         if ($request->by == 3) {
    //             $start_date  = date('Y-m-d', strtotime($request->month)) . ' 00:00:00';
    //             $end_date = date('Y-m-t', strtotime($request->month)) . ' 23:59:59';
    //         } else {
    //             if ($request->customized == 'month') { // echo '>>>>>>>>>>'.$request->end_date;
    //                 $start_date  = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
    //                 $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';
    //             } else {
    //                 $start_date = $request->start_date;
    //                 $end_date = $request->end_date;
    //             }
    //         }
    //     } else if ($request->year) {
    //         if ($request->by == 4) {
    //             $start_date  = $request->year . '-01-01 00:00:00';
    //             $end_date = $request->year . '-12-31 23:59:59';
    //         } else {
    //             $start_date = $request->start_date;
    //             $end_date = $request->end_date;
    //         }
    //     } else if ($request->start_date && $request->start_date) {
    //         $start_date = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
    //         $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';
    //     } else {
    //         $start_date = date("Y-m-d", strtotime("-1 months"));
    //         $end_date = date("Y-m-d");
    //     }




    //     if ($filters)
    //         $site_id = $filters->site_id;
    //     if ($request->site_id)
    //         $site_id = $request->site_id;
    //     $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
    //         return $query->where('site_id', $site_id);
    //     })
    //         ->selectRaw('questionnaire_surveys.*, count(*) as tenant_survey')
    //         ->whereBetween('created_at', [$start_date, $end_date])
    //         ->where('Remarks', 'Done')
    //         ->groupBy('questionnaire_id')
    //         ->orderBy('questionnaire_id', 'ASC')
    //         ->get();

    //     $total = $logs->sum('tenant_survey');

    //     $percentage = [];
    //     foreach ($logs as $index => $log) {
    //         $percentage[] = [

    //             'questionnaire' => $log->questionnaire,
    //             'questionnaire_color' => $log->questionnaire_color,
    //             'questionnaire_answer' => $log->questionnaire_answer,
    //             'tenant_survey' => $log->tenant_survey,

    //             'percentage_share' => round(($log->tenant_survey / $total) * 100, 2)
    //         ];
    //     }

    //     return $percentage;
    // }

    // public function getPercentageAnswer($request)
    // {
    //     $site_id = '';
    //     $filters = json_decode($request->filters);

    //     if ($request->day) {
    //         $start_date  = date('Y-m-d', strtotime($request->day)) . ' 00:00:00';
    //         $end_date = date('Y-m-d', strtotime($request->day)) . ' 23:59:59';
    //     } else if ($request->week) {
    //         $date = Carbon::parse($request->week);
    //         if ($request->by == 2) {
    //             $start_date = $date->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
    //             $end_date = $date->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');
    //         } else {
    //             if ($request->customize == 'week') {
    //                 $start_date = $date->startOfWeek(Carbon::SUNDAY)->format('Y-m-d');
    //                 $end_date = $date->endOfWeek(Carbon::SATURDAY)->format('Y-m-d');
    //             } else {
    //                 $start_date = $request->start_date;
    //                 $end_date = $request->end_date;
    //             }
    //         }
    //     } else if ($request->month) {
    //         if ($request->by == 3) {
    //             $start_date  = date('Y-m-d', strtotime($request->month)) . ' 00:00:00';
    //             $end_date = date('Y-m-t', strtotime($request->month)) . ' 23:59:59';
    //         } else {
    //             if ($request->customized == 'month') {
    //                 $start_date  = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
    //                 $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';
    //             } else {
    //                 $start_date = $request->start_date;
    //                 $end_date = $request->end_date;
    //             }
    //         }
    //     } else if ($request->year) {
    //         if ($request->by == 4) {
    //             $start_date  = $request->year . '-01-01 00:00:00';
    //             $end_date = $request->year . '-12-31 23:59:59';
    //         } else {
    //             $start_date = $request->start_date;
    //             $end_date = $request->end_date;
    //         }
    //     } else if ($request->start_date && $request->start_date) {
    //         $start_date = date('Y-m-d', strtotime($request->start_date)) . ' 00:00:00';
    //         $end_date = date('Y-m-d', strtotime($request->end_date)) . ' 23:59:59';
    //     } else {
    //         $start_date = date("Y-m-d", strtotime("-1 months"));
    //         $end_date = date("Y-m-d");
    //     }



    //     if ($filters)
    //         $site_id = $filters->site_id;
    //     if ($request->site_id)
    //         $site_id = $request->site_id;

    //     $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
    //         return $query->where('site_id', $site_id);
    //     })
    //         ->selectRaw('questionnaire_surveys.*, count(*) as tenant_survey')
    //         ->whereBetween('created_at', [$start_date, $end_date])
    //         ->where('Remarks', 'Done')
    //         ->groupBy('questionnaire_id')
    //         ->groupBy('questionnaire_answer_id')
    //         ->orderBy('questionnaire_id', 'ASC')
    //         ->get();

    //     $total = $logs->sum('tenant_survey');

    //     $percentage = [];
    //     foreach ($logs as $index => $log) {
    //         $percentage[] = [

    //             'questionnaire' => $log->questionnaire,
    //             'questionnaire_color' => $log->questionnaire_color,
    //             'questionnaire_answer' => $log->questionnaire_answer,
    //             'tenant_survey' => $log->tenant_survey,

    //             'percentage_share' => round(($log->tenant_survey / $total) * 100, 2)
    //         ];
    //     }

    //     return $percentage;
    // }
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
    public function getPercentageTwo($request)
    {

        $site_id = '';
        $filters = json_decode($request->filters);

        if ($filters)
            $site_id = $filters->site_id;
        if ($request->site_id)
            $site_id = $request->site_id;
        $start_date = ($request->start_date) ? $request->start_date : date("Y-m-d", strtotime("-1 months"));;
        $end_date = ($request->end_date) ? $request->end_date : date("Y-m-d");

        $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
            return $query->where('site_id', $site_id);
        })
            ->selectRaw('questionnaire_surveys.*, count(*) as tenant_survey')
            ->whereBetween('created_at', [$start_date, $end_date])
            ->groupBy('questionnaire_answer_id')
            ->orderBy('tenant_survey', 'DESC')
            ->get();

        $total = $logs->sum('tenant_survey');

        $percentage = [];
        foreach ($logs as $index => $log) {
            $percentage[] = [


                'questionnaire_answer' => $log->questionnaire_answer,
                'tenant_survey' => $log->tenant_survey,

                'percentage_share' => round(($log->tenant_survey / $total) * 100, 2)
            ];
        }

        return $percentage;
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


            return $this->response($percentage, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getPopulationReportTwo(Request $request)
    {
        try {
            $percentage = $this->getPercentageTwo($request);
            return $this->response($percentage, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getTenantSearch(Request $request)
    {
        try {
            $site_id = '';
            $category_totals = [];

            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $totals = Log::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('logs.main_category_id, count(*) as tenant_count')
                ->whereNotNull('brand_id')
                ->groupBy('main_category_id')
                ->get();

            foreach ($totals as $index => $total) {
                $category_totals[$total->main_category_id] = $total->tenant_count;
            }

            $overall_total = $totals->sum('tenant_count');

            $logs = LogsViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->whereNotNull('brand_id')
                ->when(count($category_totals) > 0, function ($query) use ($category_totals, $overall_total) {
                    return $query->selectRaw('logs.*, count(*) as tenant_count, 
                (CASE WHEN parent_category_id = 1 THEN ROUND((count(*)/' . $category_totals[1] . ')*100, 2)
                WHEN parent_category_id = 2 THEN ROUND((count(*)/' . $category_totals[2] . ')*100, 2)
                WHEN parent_category_id = 3 THEN ROUND((count(*)/' . $category_totals[3] . ')*100, 2)
                WHEN parent_category_id = 4 THEN ROUND((count(*)/' . $category_totals[4] . ')*100, 2)
                WHEN parent_category_id = 5 THEN ROUND((count(*)/' . $category_totals[5] . ')*100, 2)
                ELSE 0 END) AS category_percentage, 
                ROUND((count(*)/' . $overall_total . ')*100, 2) as tenant_percentage');
                })
                ->groupBy('brand_id')
                ->orderBy('tenant_count', 'DESC')
                ->paginate(request('perPage'));

            return $this->responsePaginate($logs, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getSearchKeywords(Request $request)
    {
        try {
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $logs = LogsViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('logs.*, count(*) as tenant_count')
                ->whereNotNull('key_words')
                ->groupBy('key_words')
                ->orderBy('tenant_count', 'DESC')
                ->paginate(request('perPage'));

            return $this->responsePaginate($logs, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getMerchantUsage(Request $request)
    {
        try {
            $site_id = '';
            $category_totals = [];

            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $totals = Log::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('logs.parent_category_id, count(*) as tenant_count')
                ->whereNotNull('brand_id')
                ->groupBy('parent_category_id')
                ->get();

            foreach ($totals as $index => $total) {
                $category_totals[$total->parent_category_id] = $total->tenant_count;
            }

            $overall_total = $totals->sum('tenant_count');

            LogsViewModel::setSiteId($site_id);
            $logs = LogsViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->whereNotNull('brand_id')
                ->when(count($category_totals) > 0, function ($query) use ($category_totals, $overall_total) {
                    return $query->selectRaw('logs.*, count(*) as tenant_count, 
                (CASE WHEN parent_category_id = 1 THEN ROUND((count(*)/' . $category_totals[1] . ')*100, 2)
                WHEN parent_category_id = 2 THEN ROUND((count(*)/' . $category_totals[2] . ')*100, 2)
                WHEN parent_category_id = 3 THEN ROUND((count(*)/' . $category_totals[3] . ')*100, 2)
                WHEN parent_category_id = 4 THEN ROUND((count(*)/' . $category_totals[4] . ')*100, 2)
                WHEN parent_category_id = 5 THEN ROUND((count(*)/' . $category_totals[5] . ')*100, 2)
                ELSE 0 END) AS category_percentage, 
                ROUND((count(*)/' . $overall_total . ')*100, 2) as tenant_percentage');
                })
                ->groupBy('brand_id')
                ->orderBy('tenant_count', 'DESC')
                ->paginate(request('perPage'));

            return $this->responsePaginate($logs, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getMonthlyUsage(Request $request)
    {
        try {
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");

            LogsMonthlyUsageViewModel::setSiteId($site_id, $current_year);

            $logs = LogsMonthlyUsageViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->whereYear('created_at', $current_year)
                ->selectRaw('logs.*, page, count(*) as total_count')
                ->groupBy('page')
                ->orderBy('page', 'ASC')
                ->get();

            return $this->response($logs, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getTrend(Request $request)
    {
        try {
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");


            $start_date = ($request->start_date) ? $request->start_date : date("Y-m-d", strtotime("-1 months"));;
            $end_date = ($request->end_date) ? $request->end_date : date("Y-m-d");

            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->whereYear('created_at', $current_year)
                ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
                ->groupBy('site_building_id')
                ->orderBy('total_survey', 'DESC')
                ->get();

            return $this->response($logs, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
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
    public function getTotalSMSByDaily(Request $request)
    {
        try {
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
    public function getTrendReportByDaily(Request $request)
    {
        try {
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
                ->where('created_at', '>=', date('Y-m-d', strtotime($request->day)) . ' 00:00:00')
                ->where('created_at', '<=', date('Y-m-d', strtotime($request->day)) . ' 23:59:59')
                ->where('remarks', 'Done')
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
    public function getTotalSMSByWeek(Request $request)
    {
        try {
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


            // echo '<pre>'; print_r($per_day); echo '</pre>';
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
                $start_date = $request->start_date;
                $end_date = $request->end_date;
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
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;
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
                ->groupBy('site_building_id')
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
                    'bar' => $bar,
                    'reports' => $log->total_survey,
                    'building_color' => $log->building_color,
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
                    str_replace('-', '/', substr($week[0], 5)) . '-' . str_replace('-', '/', substr($week[1], 5)),
                ];
            }
            $total_per_month[] = $per_month;
            $total_per_month[] = $per_range; // echo '<pre>'; print_r($total_per_month); echo '</pre>';
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
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;
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
            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
                ->where('remarks', 'Done')
                ->whereBetween('created_at', [$start_date, $end_date])
                ->groupBy('site_building_id')
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
                    'bar' => $bar,
                    'reports' => $log->total_survey,
                    'building_color' => $log->building_color,
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
    public function getTotalSMSByYear(Request $request)
    {
        try {
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
    public function getTrendReportByYear(Request $request)
    {
        try {
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
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('month(created_at)'))
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
                ->where('remarks', 'Done')
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('month(created_at)'))
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
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function getAverageTimeByLifetime(Request $request)
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
            if ($request->by == 3) {
                $start_date  = date('Y-m-d', strtotime($request->month)) . ' 00:00:00';
                $end_date = date('Y-m-t', strtotime($request->month)) . ' 23:59:59';
            } else {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
            }

            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('avg(TIMESTAMPDIFF(minute, created_at,updated_at)) AS minutes')
                ->whereBetween('created_at', [$start_date, $end_date])

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
    public function getTotalSMSByLifetime(Request $request)
    {
        try {
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
    public function getTrendReportByLifetime(Request $request)
    {
        try {
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;
            if ($request->by == 3) {
                $start_date  = date('Y-m-d', strtotime($request->month)) . ' 00:00:00';
                $end_date = date('Y-m-t', strtotime($request->month)) . ' 23:59:59';
            } else {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
            }
            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
                ->whereBetween('created_at', [$start_date, $end_date])
                ->groupBy('site_building_id')
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
                    'bar' => $bar,
                    'reports' => $log->total_survey,
                    'building_color' => $log->building_color,
                ];
            }
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
    public function getTrendIncidentByLifetime(Request $request)
    {
        try {
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;
            if ($request->by == 3) {
                $start_date  = date('Y-m-d', strtotime($request->month)) . ' 00:00:00';
                $end_date = date('Y-m-t', strtotime($request->month)) . ' 23:59:59';
            } else {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
            }
            $logs = QuestionnaireSurveyViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('questionnaire_surveys.*, site_building_id, count(*) as total_survey')
                ->whereBetween('created_at', [$start_date, $end_date])
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
                    'bar' => $bar,
                    'reports' => $log->total_survey,
                    'building_color' => $log->building_color,
                ];
            }
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

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
    public function getTotalSMSByYears(Request $request)
    {
        try {
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
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('year(created_at)'))
                ->orderBy('created_at', 'ASC')
                ->get();
            $created_at = array();
            $data_year  = array();
            $per_year = [];


            foreach ($this->createDateRangeArray($request->start_date, $request->end_date, 'year') as $vDateRange) {
                $year = date("Y", strtotime($vDateRange));
                $created_at[] = $year;
            }
            $per_building = [];
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
                ->where('remarks', 'Done')
                ->groupBy('site_building_id')
                ->groupBy(QuestionnaireSurveyViewModel::raw('year(created_at)'))
                ->orderBy('created_at', 'ASC')
                ->get();
            $created_at = array();
            $data_year  = array();
            $per_year = [];


            foreach ($this->createDateRangeArray($request->start_date, $request->end_date, 'year') as $vDateRange) {
                $year = date("Y", strtotime($vDateRange));
                $created_at[] = $year;
            }
            $per_building = [];
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


    public function getYearlyUsage(Request $request)
    {
        try {
            $year = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $year = $filters->year;
            if ($request->year)
                $year = $request->year;

            $current_year = date("Y");

            if ($year)
                $current_year = $year;

            $total_count = Log::whereYear('created_at', $current_year)
                ->selectRaw('count(*) as total_count')
                ->groupBy(DB::raw('YEAR(created_at)'))
                ->get()->count();

            $logs = LogsMonthlyUsageViewModel::whereYear('created_at', $current_year)
                ->selectRaw('logs.*, count(*) as total_count, ROUND((count(*)/' . $total_count . '), 2) as total_average')
                ->groupBy(DB::raw('YEAR(created_at)'))
                ->get();

            return $this->response($logs, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function downloadCsvPopulation(Request $request)
    {
        try {
            $percentage = $this->getPercentage($request);

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "merchant-population.csv";

            Excel::store(new Export($percentage), $directory . $filename);

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

    public function downloadCsvTenantSearch(Request $request)
    {
        try {
            $site_id = '';
            $category_totals = [];

            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $totals = Log::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('logs.parent_category_id, count(*) as tenant_count')
                ->whereNotNull('brand_id')
                ->groupBy('parent_category_id')
                ->get();

            foreach ($totals as $index => $total) {
                $category_totals[$total->parent_category_id] = $total->tenant_count;
            }

            $overall_total = $totals->sum('tenant_count');

            $logs = LogsViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->whereNotNull('brand_id')
                ->when(count($category_totals) > 0, function ($query) use ($category_totals, $overall_total) {
                    return $query->selectRaw('logs.*, count(*) as tenant_count, 
                (CASE WHEN parent_category_id = 1 THEN ROUND((count(*)/' . $category_totals[1] . ')*100, 2)
                WHEN parent_category_id = 2 THEN ROUND((count(*)/' . $category_totals[2] . ')*100, 2)
                WHEN parent_category_id = 3 THEN ROUND((count(*)/' . $category_totals[3] . ')*100, 2)
                WHEN parent_category_id = 4 THEN ROUND((count(*)/' . $category_totals[4] . ')*100, 2)
                WHEN parent_category_id = 5 THEN ROUND((count(*)/' . $category_totals[5] . ')*100, 2)
                ELSE 0 END) AS category_percentage, 
                ROUND((count(*)/' . $overall_total . ')*100, 2) as tenant_percentage');
                })
                ->groupBy('brand_id')
                ->orderBy('tenant_count', 'DESC')
                ->get();

            $tenants = [];
            foreach ($logs as $index => $log) {
                $tenants[] = [
                    'brand_name' => $log->brand_name,
                    'main_category_name' => $log->main_category_name,
                    'tenant_count' => $log->tenant_count,
                    'category_percentage' => $log->category_percentage,
                    'tenant_percentage' => $log->tenant_percentage
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "top-tenant-search.csv";

            Excel::store(new Export($tenants), $directory . $filename);

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

    public function downloadCsvSearchKeywords(Request $request)
    {
        try {
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $logs = LogsViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('logs.*, count(*) as tenant_count')
                ->whereNotNull('key_words')
                ->groupBy('key_words')
                ->orderBy('tenant_count', 'DESC')
                ->get();

            $search_keywords = [];
            foreach ($logs as $index => $log) {
                $search_keywords[] = [
                    'word' => $log->key_words,
                    'tenant_count' => $log->tenant_count
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "top-search-keywords.csv";

            Excel::store(new Export($search_keywords), $directory . $filename);

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

    public function downloadCsvmerchantUsage(Request $request)
    {
        try {
            $site_id = '';
            $category_totals = [];

            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $totals = Log::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('logs.parent_category_id, count(*) as tenant_count')
                ->whereNotNull('brand_id')
                ->groupBy('parent_category_id')
                ->get();

            foreach ($totals as $index => $total) {
                $category_totals[$total->parent_category_id] = $total->tenant_count;
            }

            $overall_total = $totals->sum('tenant_count');

            LogsViewModel::setSiteId($site_id);
            $logs = LogsViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->whereNotNull('brand_id')
                ->when(count($category_totals) > 0, function ($query) use ($category_totals, $overall_total) {
                    return $query->selectRaw('logs.*, count(*) as tenant_count, 
                (CASE WHEN parent_category_id = 1 THEN ROUND((count(*)/' . $category_totals[1] . ')*100, 2)
                WHEN parent_category_id = 2 THEN ROUND((count(*)/' . $category_totals[2] . ')*100, 2)
                WHEN parent_category_id = 3 THEN ROUND((count(*)/' . $category_totals[3] . ')*100, 2)
                WHEN parent_category_id = 4 THEN ROUND((count(*)/' . $category_totals[4] . ')*100, 2)
                WHEN parent_category_id = 5 THEN ROUND((count(*)/' . $category_totals[5] . ')*100, 2)
                ELSE 0 END) AS category_percentage, 
                ROUND((count(*)/' . $overall_total . ')*100, 2) as tenant_percentage');
                })
                ->groupBy('brand_id')
                ->orderBy('tenant_count', 'DESC')
                ->get();

            $search_keywords = [];
            foreach ($logs as $index => $log) {
                $search_keywords[] = [
                    'brand_name' => $log->brand_name,
                    'category_name' => $log->category_name,
                    'search_count' => $log->search_count,
                    'tenant_count' => $log->tenant_count,
                    'banner_count' => $log->banner_count,
                    'total_count' => $log->total_count,
                    'category_percentage' => $log->category_percentage,
                    'tenant_percentage' => $log->tenant_percentage
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "merchant-usage.csv";

            Excel::store(new Export($search_keywords), $directory . $filename);

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

    public function downloadCsvMonthlyUsage(Request $request)
    {
        try {
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $current_year = date("Y");

            LogsMonthlyUsageViewModel::setSiteId($site_id, $current_year);

            $logs = LogsMonthlyUsageViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->whereYear('created_at', $current_year)
                ->selectRaw('logs.*, page, count(*) as total_count')
                ->groupBy('page')
                ->orderBy('page', 'ASC')
                ->get();

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $monthly_usage = [];
            foreach ($logs as $index => $log) {
                $monthly_usage[] = [
                    'page' => $log->page,
                    'jan_count' => $log->jan_count,
                    'feb_count' => $log->feb_count,
                    'mar_count' => $log->mar_count,
                    'apr_count' => $log->apr_count,
                    'may_count' => $log->may_count,
                    'jun_count' => $log->jun_count,
                    'jul_count' => $log->jul_count,
                    'aug_count' => $log->aug_count,
                    'sep_count' => $log->sep_count,
                    'oct_count' => $log->oct_count,
                    'nov_count' => $log->nov_count,
                    'dec_count' => $log->dec_count,
                    'total_count' => $log->total_count,
                    'ave_count' => $log->ave_count
                ];
            }

            $filename = "monthly-usage.csv";

            Excel::store(new Export($monthly_usage), $directory . $filename);

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

    public function downloadCsvYearlyUsage(Request $request)
    {
        try {
            $year = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $year = $filters->year;
            if ($request->year)
                $year = $request->year;

            $current_year = date("Y");

            if ($year)
                $current_year = $year;

            $total_count = Log::whereYear('created_at', $current_year)
                ->selectRaw('count(*) as total_count')
                ->groupBy(DB::raw('YEAR(created_at)'))
                ->get()->count();

            $logs = LogsMonthlyUsageViewModel::whereYear('created_at', $current_year)
                ->selectRaw('logs.*, count(*) as total_count, ROUND((count(*)/' . $total_count . '), 2) as total_average')
                ->groupBy(DB::raw('YEAR(created_at)'))
                ->get();

            $yearly_usage = [];
            foreach ($logs as $index => $log) {
                $yearly_usage[] = [
                    'site_name' => $log->site_name,
                    'total_count' => $log->total_count,
                    'total_average' => $log->total_average
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "yearly-usage.csv";

            Excel::store(new Export($yearly_usage), $directory . $filename);

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

    public function getIsHelpful(Request $request)
    {
        try {
            $total_count = SiteFeedback::get()->count();

            $is_helpful = SiteFeedback::selectRaw('helpful, count(*) as count, ROUND((count(*)/' . $total_count . ')*100, 2) as percentage')
                ->groupBy('helpful')
                ->orderBy('count', 'DESC')
                ->get();

            return $this->response($is_helpful, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }



    public function getResponseNo()
    {
        try {
            $total_count = SiteFeedback::where('helpful', 'No')->get()->count();

            $is_helpful = SiteFeedback::selectRaw('reason, count(*) as count, ROUND((count(*)/' . $total_count . ')*100, 2) as percentage')
                ->where('helpful', 'No')
                ->groupBy('reason')
                ->orderBy('count', 'DESC')
                ->get();

            return $this->response($is_helpful, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getOtherResponse()
    {
        try {
            $is_helpful = SiteFeedback::whereNotNull('reason_other')->get();
            return $this->response($is_helpful, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function downloadCsvIsHelpful(Request $request)
    {
        try {
            $logs = SiteFeedbackViewModel::get();

            $is_helpful = [];
            foreach ($logs as $log) {
                $is_helpful[] = [
                    'site_name' => $log['site_name'],
                    'helpful' => $log['helpful'],
                    'reason' => $log['reason'],
                    'reason_other' => $log['reason_other']
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }


            $filename = "is_helpful.csv";

            Excel::store(new Export($is_helpful), $directory . $filename);

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

    public function screenUptime(Request $request)
    {
        try {
            $screens_uptime = SiteScreenUptimeViewModel::get();
            return $this->response($screens_uptime, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function getUptimeHistory(Request $request)
    {
        try {
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $screens_uptime = SiteScreenUptime::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_screens.site_id', $site_id);
            })
                ->select('site_screen_uptimes.*', 'site_screens.name')
                ->join('site_screens', 'site_screen_uptimes.site_screen_id', '=', 'site_screens.id')
                ->latest()
                ->get();

            return $this->response($screens_uptime, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function downloadCsvUptimeHistory(Request $request)
    {
        try {
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $logs = SiteScreenUptime::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_screens.site_id', $site_id);
            })
                ->select('site_screen_uptimes.*', 'site_screens.name')
                ->join('site_screens', 'site_screen_uptimes.site_screen_id', '=', 'site_screens.id')
                ->latest()
                ->get();

            $uptime_history = [];
            foreach ($logs as $index => $log) {
                $uptime_history[] = [
                    'name' => $log->name,
                    'up_time_date' => $log->up_time_date,
                    'total_hours' => $log->total_hours,
                    'opening_hour' => $log->opening_hour,
                    'closing_hour' => $log->closing_hour,
                    'hours_up' => $log->hours_up,
                    'percentage_uptime' => $log->percentage_uptime,
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "uptime-history.csv";

            Excel::store(new Export($uptime_history), $directory . $filename);

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

    public function getKioskUsage(Request $request)
    {
        try {
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $total = Log::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->get()->count();

            $logs = LogsViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('logs.*, count(*) as screen_count, ROUND((count(*)/' . $total . '), 2)*100 as total_average')
                ->groupBy('site_screen_id')
                ->orderBy('screen_count', 'DESC')
                ->get();

            return $this->response($logs, 'Successfully Retreived!', 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => false,
                'status_code' => 422,
            ], 422);
        }
    }

    public function downloadCsvKioskUsage(Request $request)
    {
        try {
            $site_id = '';
            $filters = json_decode($request->filters);
            if ($filters)
                $site_id = $filters->site_id;
            if ($request->site_id)
                $site_id = $request->site_id;

            $total = Log::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->get()->count();

            $logs = LogsViewModel::when($site_id, function ($query) use ($site_id) {
                return $query->where('site_id', $site_id);
            })
                ->selectRaw('logs.*, count(*) as screen_count, ROUND((count(*)/' . $total . '), 2)*100 as total_average')
                ->groupBy('site_screen_id')
                ->orderBy('screen_count', 'DESC')
                ->get();

            $kiosk_usage = [];
            foreach ($logs as $index => $log) {
                $kiosk_usage[] = [
                    'screen_name' => $log->screen_name,
                    'screen_location' => $log->screen_location,
                    'screen_count' => $log->screen_count,
                    'total_average' => $log->total_average
                ];
            }

            $directory = 'public/export/reports/';
            $files = Storage::files($directory);
            foreach ($files as $file) {
                Storage::delete($file);
            }

            $filename = "kiosk-usage.csv";

            Excel::store(new Export($kiosk_usage), $directory . $filename);

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

    public function getQuestionnaireSurvey(Request $request)
    {
        try {
            $total_count = QuestionnaireSurvey::get()->count();

            $pending_done = QuestionnaireSurvey::selectRaw('remarks, count(*) as count, ROUND((count(*)/' . $total_count . ')*100, 2) as percentage')
                ->groupBy('remarks')
                ->orderBy('count', 'DESC')
                ->get();

            return $this->response($pending_done, 'Successfully Retreived!', 200);
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
                array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
                while ($iDateFrom < $iDateTo) {
                    $iDateFrom += 86400; // add 24 hours
                    array_push($aryRange, date('Y-m-d', $iDateFrom));
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
                //->where('site_building_room_id', $id)
                ->get()->count();

            $first = $questionnaire_survey = QuestionnaireSurvey::select('created_at')
                //->where('site_building_room_id', $id)
                ->orderBy('id', 'asc')
                ->limit(1)
                ->get();
            $last = $questionnaire_survey = QuestionnaireSurvey::select('created_at')
                //->where('site_building_room_id', $id)
                ->orderBy('id', 'desc')
                ->limit(1)
                ->get();

            //$first_last = array();
            //if ($total_count == 0) {
                //$first_last[] =  date('Y-m-d');
                //$first_last[] = date('Y-m-d');
            //} else {
                $first_last[] =  date('Y-m-d', strtotime($first[0]->created_at));
                $first_last[] = date('Y-m-d', strtotime($last[0]->created_at));
           // }


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
