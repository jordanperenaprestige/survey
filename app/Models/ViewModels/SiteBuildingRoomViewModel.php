<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\SiteMap;
use App\Models\Site;
use App\Models\SiteBuilding;
use App\Models\SiteBuildingLevel;
use App\Models\QuestionnaireSurvey;

class SiteBuildingRoomViewModel extends Model
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
    protected $table = 'site_building_rooms';

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
        'site_name',
        'building_name',
        'building_floor_name',
        'building_floor_room_surveys',
        'building_floor_room_survey_pendings',
        'count_pending'
    ];

    public function getBuildingLevelRoomSurveyDetails()
    {
        return QuestionnaireSurvey::where('site_id', $this->site_id)
            ->where('site_building_id', $this->site_building_id)
            ->where('site_building_level_id', $this->site_building_level_id)
            ->where('site_building_room_id', $this->id)
            ->get();
    }

    /****************************************
     *           ATTRIBUTES PARTS            *
     ****************************************/
    public function getSiteNameAttribute()
    {
        return Site::find($this->site_id)->name;
    }
    public function getBuildingNameAttribute()
    {
        return SiteBuilding::find($this->site_building_id)->name;
    }

    public function getBuildingFloorNameAttribute()
    {
        return SiteBuildingLevel::find($this->site_building_level_id)->name;
    }

    public function getBuildingFloorRoomSurveysAttribute()
    {

        //return $this->getBuildingLevelRoomSurveyDetails();
        $questionnaire_answers = QuestionnaireAnswerViewModel::get();
        $surveys = array();

        foreach ($questionnaire_answers as $k => $v) {

            $questionnaire_survey = QuestionnaireSurvey::where('questionnaire_id', $v->questionnaire_id)
                ->where('questionnaire_answer_id', $v->id)
                ->where('site_id', $this->site_id)
                ->where('site_building_id', $this->site_building_id)
                ->where('site_building_level_id', $this->site_building_level_id)
                ->where('site_building_room_id', $this->id)
                //->where('remarks', 'pending')
                // ->where('status', 1)
                // ->where('status', 2)
                ->orderBy('id', 'DESC')
                ->limit(1)
                ->get();

            if (count($questionnaire_survey) == 0) {
                $surveys[] = [
                    'questionnaire_answer_id' => $v->id,
                    'questionnaire_survey_status' => 0,
                    'questionnaire_survey_id' => 0,
                    'questionnaire_button' => $v->button,
                    'questionnaire_name' => $v->answer,
                    'questionnaire_user_role' => $v->sms_recepient,
                ];
            } else {
                foreach ($questionnaire_survey as $qv) {

                    $surveys[] = [
                        'questionnaire_answer_id' => $v->id,
                        'questionnaire_survey_status' => $qv->status,
                        'questionnaire_survey_id' => $qv->id,
                        'questionnaire_button' => $v->button,
                        'questionnaire_name' => $v->answer,
                        'questionnaire_user_role' => $v->sms_recepient,
                    ];
                }
            }
        }
        return  $surveys; //$surveys;
    }
    public function getBuildingFloorRoomSurveyPendingsAttribute()
    {

        //return $this->getBuildingLevelRoomSurveyDetails();
        $questionnaire_answers = QuestionnaireAnswerViewModel::get();
        $surveys = array();

        foreach ($questionnaire_answers as $k => $v) {

            $questionnaire_survey = QuestionnaireSurvey::where('questionnaire_id', $v->questionnaire_id)
                ->where('questionnaire_answer_id', $v->id)
                ->where('site_id', $this->site_id)
                ->where('site_building_id', $this->site_building_id)
                ->where('site_building_level_id', $this->site_building_level_id)
                ->where('site_building_room_id', $this->id)
                ->where('remarks', 'pending')
                // ->where('status', 1)
                // ->where('status', 2)
                ->orderBy('id', 'DESC')
                ->limit(1)
                ->get();

            if (count($questionnaire_survey) == 0) {
                $surveys[] = [
                    'questionnaire_answer_id' => $v->id,
                    'questionnaire_survey_status' => 0,
                    'questionnaire_survey_id' => 0,
                    'questionnaire_button' => $v->button,
                    'questionnaire_name' => $v->answer,
                    'questionnaire_user_role' => $v->sms_recepient,
                ];
            } else {
                foreach ($questionnaire_survey as $qv) {

                    $surveys[] = [
                        'questionnaire_answer_id' => $v->id,
                        'questionnaire_survey_status' => $qv->status,
                        'questionnaire_survey_id' => $qv->id,
                        'questionnaire_button' => $v->button,//str_replace('.svg','_na.svg',$v->button),
                        'questionnaire_name' => $v->answer,
                        'questionnaire_user_role' => $v->sms_recepient,
                    ];
                }
            }
        }
        return  $surveys; //$surveys;
    }

    public function getCountPendingAttribute()
    {

        //return $this->getBuildingLevelRoomSurveyDetails();
        $questionnaire_answers = QuestionnaireAnswerViewModel::get();
        $i = 0;
        foreach ($questionnaire_answers as $k => $v) {

            $questionnaire_survey = QuestionnaireSurvey::where('questionnaire_id', $v->questionnaire_id)
                ->where('questionnaire_answer_id', $v->id)
                ->where('site_id', $this->site_id)
                ->where('site_building_id', $this->site_building_id)
                ->where('site_building_level_id', $this->site_building_level_id)
                ->where('site_building_room_id', $this->id)
                ->where('remarks', 'pending')
                // ->where('status', 1)
                // ->where('status', 2)
                ->orderBy('id', 'DESC')
                ->limit(1)
                ->get();

            if (count($questionnaire_survey) != 0) {
                $i++;
            } 
        }
        return $i;

    }
}
