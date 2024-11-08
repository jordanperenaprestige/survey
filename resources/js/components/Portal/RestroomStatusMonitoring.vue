<template>
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">SUPERVISOR RESTROOM STATUS MONITORING</h3>
              <div class="card-tools">
                <!-- <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                  <div class="input-group-append">
                    <button type="submit" class="btn btn-default">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div> -->
              </div>
            </div>
            <!-- /.card-header -->
            <div class="row" v-for="(building, index) in buildings">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title">{{ building.descriptions }}</h3>
                  </div>
                  <div class="card-body table-responsive p-0" style="height:100%;">
                    <!-- <table class="table table-head-fixed text-nowrap"> -->
                    <table class="table table-head-fixed" style="width: 100%;">
                      <tr>
                        <td>
                          <table style="width:100%; table-layout: fixed">
                            <tr>
                              <td>FLOOR</td>
                              <td>LOCATION</td>
                              <td>NO. OF ISSUES</td>
                              <td v-for="(questionnaire, index) in questionnaire_answers">
                                {{ questionnaire.answer }}
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                      <template v-for="(building_level, index) in building.building_levels">
                        <tr data-widget="expandable-table" aria-expanded="false">
                          <td>
                            <table style="width:100%; table-layout: fixed">
                              <tr>
                                <td><i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
                                  {{ building_level.name }}</td>
                                <td>ALL RESTROOMS</td>
                                <td></td>
                                <td v-for="(questionnaire, index) in questionnaire_answers">
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                        <tr class="expandable-body">
                          <td>
                            <div style="display: none;">
                              <table style="width:100%; table-layout: fixed">
                                <tr v-for="(building_level_room, index) in building_level.building_level_rooms">
                                  <td>&nbsp;</td>
                                  <td>{{ building_level_room.name }}</td>
                                  <td></td>
                                  <td
                                    v-for="(building_floor_room_survey, index) in building_level_room.building_floor_room_surveys">
                                    <button
                                      v-if="(building_floor_room_survey.questionnaire_survey_id > 0) && (building_floor_room_survey.questionnaire_survey_status == 1)"
                                      @click="modalStatus($event)"
                                      :id="building_floor_room_survey.questionnaire_survey_id"
                                      class="button_red_round"></button>
                                    <button
                                      v-else-if="(building_floor_room_survey.questionnaire_survey_id > 0) && (building_floor_room_survey.questionnaire_survey_status == 2)"
                                      @click="modalStatus($event)"
                                      :id="building_floor_room_survey.questionnaire_survey_id"
                                      class="button_green_round"></button>
                                    <button v-else class="button_border_black_round">{{
                                      building_floor_room_survey.status }}</button>
                                  </td>
                                </tr>
                              </table>
                            </div>
                          </td>
                          <td></td>
                        </tr>
                      </template>
                    </table>
                  </div>

                </div>
                <!-- /.card -->
              </div>
            </div>
            <!-- /.row -->

            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>

      <div class="modal fade" id="batchModal" tabindex="-1" role="dialog" aria-labelledby="batchModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h1>{{ questionnaire_surveys.questionnaire }}</h1>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group row">
                <label for="question" class="col-sm-4 col-form-label">
                  <h4>{{ questionnaire_surveys.questionnaire_answer }}</h4>
                </label>

              </div>
              <div class="form-group row">
                <label for="question" class="col-sm-4 col-form-label">Status <span class="font-italic text-danger">
                    *</span></label>
                <div class="col-sm-4"><button type="button" :class="[`btn btn-block`, `${pending}`]"
                    @click="getStatus($event)" :id="1">Pending</button>
                </div>
                <div class="col-sm-4"><button type="button" :class="[`btn btn-block`, `${done}`]"
                    @click="getStatus($event)" :id="2">Done</button>
                </div>
              </div>

            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" :class="[`btn btn-secondary`]" data-bs-dismiss="modal">Close</button>
              <button type="button" :class="[`btn btn-primary`]" @click="updateSurvey">Save changes</button>
            </div>
          </div>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
</template>
<script>

$("tr.collapse").find("span#collapse").click(function () {
  $(this).parents("tr.collapse").toggleClass("active");

  if ($(this).text() == "Open")
    $(this).text("Close")
  else
    $(this).text("Open");

});

export default {
  name: "RestroomStatusMonitoring",
  data() {
    return {
      questionnaire: {
        survey_id: '',
        survey: '',
      },
      sites: [],
      questionnaire_answers: [],
      buildings: [],
      questionnaire_surveys: [],
      pending: '',
      done: '',
      status: 0,
    }
  },

  created() {
    this.getQuestionnaireAnswer();
    this.getBuildings();
  },

  methods: {
    getQuestionnaireAnswer: function () {
      axios.get('/api/v1/employee/get-concerns')
        .then(response => this.questionnaire_answers = response.data.data);
    },
    getBuildings: function () {
      axios.get('/api/v1/employee/get-buildings')
        .then(response => this.buildings = response.data.data);
    },
    modalStatus: function (event) {
      var id = event.target.id;
      //this.questionnaire.survey = id;
      axios.get('/api/v1/employee/get-survey/' + id)
        .then(response => {
          var survey = response.data.data;
          this.questionnaire_surveys = survey;
          var survey_status = this.questionnaire_surveys.status;
          var survey_id = this.questionnaire_surveys.id;
          this.questionnaire.survey_id = survey_id;
          this.status = survey_status;
          if (survey_status == 1) {
            this.pending = 'bg-gradient-danger';
            this.done = 'btn-outline-success';
          } else {
            this.pending = 'btn-outline-danger';
            this.done = 'bg-gradient-success';
          }
        });

      $('#batchModal').modal('show');
    },
    getStatus: function (event) {
      var id = event.target.id;
      if (id == 1) {
        this.pending = 'bg-gradient-danger';
        this.done = 'btn-outline-success';
        this.status = 1;
      } else {
        this.pending = 'btn-outline-danger';
        this.done = 'bg-gradient-success';
        this.status = 2;
      }
    },
    updateSurvey: function () { 
      let formData = new FormData();
      formData.append("status", this.status);
      formData.append("id", this.questionnaire.survey_id);
      axios.post('/api/v1/employee/update-status', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        },
      })
        .then(response => {
          toastr.success(response.data.message);
          location.reload();

        })
    },
  },
};
</script> 
