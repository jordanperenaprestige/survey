<?php

namespace App\Models\ViewModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Str;
use App\Models\Company;
use App\Models\Questionnaire;
use App\Models\QuestionnaireAnswer;
use App\Models\Site;
use App\Models\SiteBuilding;
use App\Models\SiteBuildingLevel;
use App\Models\SiteBuildingRoom;

class QuestionnaireSurveyViewModel extends Model
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
    protected $table = 'questionnaire_surveys';

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
        'questionnaire',
        'questionnaire_answer',
        'company_name',
        'building_name',
        'building_color',
        'building_floor_name',
        'building_room_name',
        'building_floor_room_concerns'

    ];

    // public function getSiteDetails()
    // {   
    //     return $this->hasMany('App\Models\SiteMeta', 'site_id', 'id');
    // }

    /****************************************
    *           ATTRIBUTES PARTS            *
    ****************************************/
    public function getQuestionnaireAttribute() 
    {
        return Questionnaire::find($this->questionnaire_id)->questions;
    }
    public function getQuestionnaireColorAttribute() 
    {
        return Questionnaire::find($this->questionnaire_id)->color;
    }
    public function getQuestionnaireAnswerAttribute() 
    {
        return QuestionnaireAnswer::find($this->questionnaire_answer_id)->answer;
    }
    public function getCompanyNameAttribute() 
    {
        return Site::find($this->site_id)->name;
    }
    public function getBuildingNameAttribute() 
    {
        return SiteBuilding::find($this->site_building_id)->name;
    }
    public function getBuildingColorAttribute() 
    {
        return SiteBuilding::find($this->site_building_id)->color;
    }
    public function getBuildingFloorNameAttribute() 
    {
        return SiteBuildingLevel::find($this->site_building_level_id)->name;
    }

    public function getBuildingRoomNameAttribute() 
    {
        return SiteBuildingRoom::find($this->site_building_room_id)->name;
    }

    public function getBuildingFloorRoomConcernsAttribute() 
    {
        return 'dddddddddddddddd';
        // return QuestionnaireSurveyViewModel::where('questionnaire_id', $this->questionnaire_id)
        // ->where('site_id', $this->site_id)
        // ->where('site_building_id', $this->site_building_id)
        // ->where('site_building_level_id', $this->site_building_level_id)
        // ->where('site_building_room_id', $this->site_building_room_id)
        // ->get();
        //  'questionnaire_id',
        // 'questionnaire_answer_id',
        // 'site_id',
        // 'site_building_id',
        // 'site_building_level_id',
        // 'site_building_room_id',
    }
}
