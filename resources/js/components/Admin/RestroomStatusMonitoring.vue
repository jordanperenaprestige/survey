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
            <div class="row" v-for="(building, index) in   buildings  ">
              <div class="col-12">
                <div class="card">
                  <div class="card-header">
                    <h3 class="card-title"><label>{{ building.name }}</label></h3>
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
                      <template v-for="(building_level, index) in   building.building_levels  ">
                        <tr data-widget="expandable-table" aria-expanded="false">
                          <td>
                            <table style="width:100%; table-layout: fixed">
                              <tr>
                                <td><i class="expandable-table-caret fas fa-caret-right fa-fw"></i>
                                  {{ building_level.name }}</td>
                                <td>ALL RESTROOMS</td>
                                <td>{{ building_level.test_level_room }}

                                  <!-- <ul v-if="index % 2 === 0" class="columns">
                                    <li>
                                      {{ arr[index] }}
                                    </li>
                                    <li v-if="!!arr[index + 1]">
                                      {{ arr[index + 1] }}
                                    </li>
                                  </ul> -->

                                </td>
                                <td v-for="(rest_room_pending, index) in   building_level.rest_room_pendings">

                                  <button v-if="(rest_room_pending.length > 0)" class="button_red_round"></button>
                                  <button v-else class="button_green_round"></button>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                        <tr class="expandable-body">
                          <td>
                            <div style="display: none;">
                              <table style="width:100%; table-layout: fixed">
                                <tr v-for="(  building_level_room, index  ) in   building_level.building_level_rooms  ">
                                  <td>&nbsp;</td>
                                  <td @click="roomStatus($event)" :id="building_level_room.id">{{
                                    building_level_room.name
                                  }}</td>
                                  <td>{{ building_level_room.count_pending }}</td>
                                  <td
                                    v-for="(  building_floor_room_survey, index  ) in   building_level_room.building_floor_room_surveys  "> 
                                    <button
                                      v-if="(building_floor_room_survey.questionnaire_survey_id > 0) && (building_floor_room_survey.questionnaire_survey_status === 1)"
                                      @click="modalStatus($event)"
                                      :id="building_floor_room_survey.questionnaire_survey_id" class="button_red_round">
                                    </button>
                                    <button
                                      v-else-if="(building_floor_room_survey.questionnaire_survey_id > 0) && (building_floor_room_survey.questionnaire_survey_status === 2)"
                                      @click="modalStatus($event)"
                                      :id="building_floor_room_survey.questionnaire_survey_id"
                                      class="button_green_round"></button>
                                    <button v-else class="button_green_round">{{
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

      <div class="modal fade" id="answerModal" tabindex="-1" role="dialog" aria-labelledby="answerModalLabel"
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
                <!-- <div class="col-sm-4"><button type="button" :class="[`btn btn-block`, `${pending}`]"
                    @click="getStatus($event)" :id="1">Pending</button>
                </div>
                <div class="col-sm-4"><button type="button" :class="[`btn btn-block`, `${done}`]"
                    @click="getStatus($event)" :id="2">Done</button>
                </div> -->
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
              <button type="button" :class="[`btn btn-primary`]" data-bs-dismiss="modal" @click="updateSurvey">Save
                changes</button>
            </div>
          </div>
        </div>
      </div>

      <!-- <div class="modal fade" id="survey-form" tabindex="-1" role="dialog" aria-labelledby="survey-form" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="firstName" class="col-sm-4 col-form-label">Site <span
                                    class="font-italic text-danger"> *</span></label>
                            <div class="col-sm-8">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

      <div class="modal fade" id="roomModal" tabindex="-1" role="dialog" aria-labelledby="roomModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <!-- <h1>{{ questionnaire_surveys.questionnaire }}</h1> -->
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <!-- <div class="row"> -->
              <div class="form-group row">
                <!-- <label for="question" class="col-sm-4 col-form-label">Status <span class="font-italic text-danger">
                    *</span></label> -->
                <div class="col-sm-6">Status</div>
                <div class="col-sm-6">Select to Apply</div>


              </div>
              <!-- </div> -->
              <div class="form-group row" v-for="(  room_survey, index  ) in   room_surveys  ">
                <!-- <div class="form-group row"> -->
                <div class="col-sm-4">
                  <label for="question" class="col-form-label">
                    {{ room_survey.questionnaire_name }}
                  </label>


                </div>
                <div class="col-sm-3"><button type="button" :class="[`btn btn-block`, room_survey.pending]"
                    @click="getRoomAnswerStatus($event);"
                    :id="`pending_${room_survey.survey_id}_${room_survey.site_building_room_id}`">Pending</button>
                </div>
                <div class="col-sm-3"><button type="button" :class="[`btn btn-block`, room_survey.done]"
                    @click="getRoomAnswerStatus($event)"
                    :id="`done_${room_survey.survey_id}_${room_survey.site_building_room_id}`">Done</button>
                </div>
                <!-- </div> -->

              </div>
            </div>
            <!-- <div class="modal-footer justify-content-between">
              <button type="button" :class="[`btn btn-secondary`]" data-bs-dismiss="modal">Close</button>
              <button type="button" :class="[`btn btn-primary`]" @click="updateSurvey">Save changes</button>
            </div> -->
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
      room_surveys: [],
      add_room_surveys: [],
      pending: '',
      done: '',
      status: 0,
      multiple_status: [],
    }
  },

  created() {
    this.getQuestionnaireAnswer();
    this.getBuildings();
    setInterval(this.getQuestionnaireAnswer, 10000);
    setInterval(this.getBuildings, 10000);
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

      $('#answerModal').modal('show');
    },

    roomStatus: function (event) {
      var id = event.target.id;
      window.location.href = '/admin/dashboad/room/update/' + id;
      // axios.get('/api/v1/employee/get-room-survey/' + id)
      //   .then(response => {
      //     var room_survey = response.data.data;
      //     console.log(room_survey);
      //     this.room_surveys = room_survey;
      //     console.log(room_survey);
      //   });

      // $('#roomModal').modal('show');
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
    getRoomAnswerStatus: function (event) {
      var pending_done = event.target.id;
      var id = pending_done.split("_");
      if (/pending/i.test(pending_done)) {
        $("#" + pending_done).removeClass("btn-outline-danger").addClass("bg-gradient-danger");
        $("#done_" + id[1] + "_" + id[2]).removeClass("bg-gradient-success").addClass("btn-outline-success");

        let formData = new FormData();
        formData.append("status", 1);
        formData.append("id", id[1]);
        axios.post('/api/v1/employee/update-status', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          },
        })
          .then(response => {
            toastr.success(response.data.message);
            $('.close').click();
            //location.reload();

          })

      } else {
        $("#" + pending_done).removeClass("btn-outline-success").addClass("bg-gradient-success");
        $("#pending_" + id[1] + "_" + id[2]).removeClass("bg-gradient-danger").addClass("btn-outline-danger");

        let formData = new FormData();
        formData.append("status", 2);
        formData.append("id", id[1]);
        axios.post('/api/v1/employee/update-status', formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          },
        })
          .then(response => {
            toastr.success(response.data.message);
            //location.reload();

          })
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
          //location.reload();

        })
    },
  },
};
</script> 
