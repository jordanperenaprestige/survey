<template>
  <div class="container-fluid">
    <div>
      <div class="row">
        <div class="col-sm-6 border_testing">
          <div class="header" @click="reloadPage">{{ header.title }}</div>
          <div class="title" @click="reloadPage">{{ header.description }}</div>
        </div>
        <div class="col-sm-6 text-right date_local_admin border_testing">
          <div @click="reloadPage" style="padding-top: 40px;">{{ header.date_local_admin }}</div>
        </div>
      </div>
      <div class="row div_padding" v-show="show_concerns">
        <div class="col-1 border_testing"></div>
        <div class="col-10 grid-container border_testing">
          <div v-for="(questionnaire, index) in questionnaires" :class="['div_center']">
            <div :class="['survey-btn-' + questionnaire.id + ' item-default-btn border_testing']">
              <img :src="questionnaire.button" @click="switchImage($event)" :id="questionnaire.id">
            </div>
            <div :class="['border_testing survey-answer survey' + index]">{{ formatName(index, questionnaire.answer) }}
            </div>
          </div>
        </div>
        <div class="col-1 border_testing"></div>
      </div>
      


      <div class="row div_padding" v-show="show_concern_pendings">
        <div class="col-1 border_testing"></div>
        <div class="col-10 grid-container border_testing">
          <div v-for="(survey_pending, index) in survey_pendings" :class="['div_center']">
            <div
              :class="[' border_testing survey-btn-pending-' + survey_pending.questionnaire_answer_id, (survey_pending.questionnaire_survey_id > 0) ? 'item-pending-btn' : 'item-touch-btn']"
              v-if="survey_pending.questionnaire_user_role == user_role">
              <img v-if="survey_pending.questionnaire_survey_id > 0" :src="survey_pending.questionnaire_button"
                @click="switchImagePending($event)" :id="'pending_' + survey_pending.questionnaire_answer_id"
                :alt="survey_pending.questionnaire_button">
              <img v-else :src="survey_pending.questionnaire_button">
            </div>
            <div :class="['item-clear-btn border_testing']" v-else>
              <img :class="['not-pending-admin']" :src="survey_pending.questionnaire_button.replace('.svg', '_na.svg')">
            </div>
            <div :class="['border_testing  survey-answer survey' + index]"
              v-if="survey_pending.questionnaire_user_role == user_role">
              {{ formatName(index, survey_pending.questionnaire_name) }}
            </div>
            <div v-else :class="['border_testing  survey-answer-grey survey' + index]">
              {{ formatName(index, survey_pending.questionnaire_name) }}
            </div>
          </div>
        </div>

        <div class="col-1"></div>
      </div>
      <div class="row submit_button" v-show="show_submit_pending_btn">
        <div class="col-4 border_testing"></div>
        <div :class="['col-4  border_testing resolve-default-btn']" @click="submit_pending()"
          @touchstart="startTouch('submit', 'submit')" @touchend="endTouch('submit', 'submit')">
          <div>
            <svg width="250" height="70" viewBox="113 -45 100 100" xmlns="http://www.w3.org/2000/svg">
              <text>RESOLVE</text>
              Sorry, your browser does not support inline SVG.
            </svg>
          </div>
        </div>
        <div class="col-4 border_testing"></div>
      </div>
      <div class="show-success border_testing" v-show="show_success">
        <div class="row justify-content-center border_testing">
          <div class="col-1"></div>
          <div class="col-10 item-thank-you-btn">
            <div><img src="assets/images/svg/thank_you.svg"></div>
          </div>
          <div class="col-1"></div>
        </div>
        <div class="row justify-content-center border_testing">
          <div class="col-12 header-thank-you">
            THANK YOU!
          </div>
        </div>
        <div class="row justify-content-center border_testing">
          <div class="col-12 title-thank-you">
            The concern has been successfully submitted.
          </div>
        </div>
        <div class="row justify-content-center border_testing">
          <div class="col-12 title-thank-you-din">
            This will be attended shortly.
          </div>
        </div>
      </div>
      <div class="show-success border_testing" v-show="show_pending_success">
        <div class="row justify-content-center">
          <div class="col-1"></div>
          <div class="col-10 item-thank-you-btn">
            <div><img src="assets/images/svg/thank_you.svg"></div>
          </div>
          <div class="col-1"></div>
        </div>
        <div class="row justify-content-center border_testing">
          <div class="col-12 header-thank-you">
            THANK YOU!
          </div>
        </div>
        <div class="row justify-content-center border_testing">
          <div class="col-12 title-thank-you">
            The concern has been successfully resolved.
          </div>
        </div>
      </div>
      <div class="show-success border_testing" v-show="show_switch_room_success">
        <div class="row justify-content-center border_testing">
          <div class="row justify-content-center">
            <div class="col-5"></div>
            <div class="col-2 item-thank-you-btn">
              <div><img src="assets/images/svg/thank_you.svg"></div>
            </div>
            <div class="col-5"></div>
          </div>
        </div>
        <div class="row justify-content-center border_testing">
          <div class="col-12 header-thank-you">
            THANK YOU!
          </div>
        </div>
        <div class="row justify-content-center border_testing">
          <div class="col-12 title-thank-you">
            The room has been changed successfully.
          </div>
        </div>
        <div class="row justify-content-center border_testing">
          <div class="col-12 title-thank-you-din">
            This will be attended shortly.
          </div>
        </div>
      </div>
      <div class="row submit_button" v-show="show_submit_btn">
        <div class="col-5 border_testing"></div>
        <div :class="['col-2 border_testing submit-default-btn']" @click="submit()"
          @touchstart="startTouch('submit', 'submit')" @touchend="endTouch('submit', 'submit')">
          <div>
            <svg width="250" height="70" viewBox="106 -45 100 100" xmlns="http://www.w3.org/2000/svg">
              <text>{{ btn_name }}</text>
              Sorry, your browser does not support inline SVG.
            </svg>
          </div>

        </div>
        <div class="col-5 border_testing"></div>
      </div>
      <div class="row submit_button" v-show="hide_submit_btn">
        <div class="col-5 border_testing"></div>
        <div :class="['col-2 border_testing submit-clear-btn']">
          <!-- <svg width="160.44" height="195.61" viewBox="-30 -10 168.44 151.61" xmlns="http://www.w3.org/2000/svg">
            <text>{{ btn_name }}</text>
            Sorry, your browser does not support inline SVG.
          </svg> -->
          <div>
            <svg width="250" height="70" viewBox="106 -45 100 100" xmlns="http://www.w3.org/2000/svg">
              <text>{{ btn_name }}</text>
              Sorry, your browser does not support inline SVG.
            </svg>
          </div>
        </div>
        <div class="col-5 border_testing"></div>
      </div>
      <div class="row thank_you_padding" v-show="hide_thank_you_padding">
        <div></div>
      </div>
      <div class="row" v-show="show_footer">
        <div class="col-md-6 text-left text-bottom passingID local_marker border_testing" data-toggle="modal"
          data-target="#modal-default" @click="pincodeModal($event)" :id=1>
          <img src="assets/images/svg/location_marker.svg">
          {{ this.room.building_level_room }}
        </div>
        <div class="col-md-6 text-right border_testing" data-toggle="modal" data-target="#modal-default"
          @click="pincodeModal($event)" :id=0><img src="assets/images/svg/alpha_solutions.svg">
        </div>
      </div>
    </div>
    <div class="row">
      <!-- <div class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static"> -->
      <div class="modal fade" id="modal-default">
        <!-- <div class="modal fade zzz" id="modal-default"> -->
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="">
              <div class="header_modal">ENTER YOUR PIN
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            </div>
            <div class="modal-body">
              <div class="grid-input">
                <div>
                  <input class="form-control transparent-input" type='text' v-model="room.input_one">
                </div>
                <div>
                  <input class="form-control transparent-input" type='text' v-model="room.input_two">
                </div>
                <div>
                  <input class="form-control transparent-input" type='text' v-model="room.input_three">
                </div>
                <div>
                  <input class="form-control transparent-input" type='text' v-model="room.input_four">
                </div>
              </div>
            </div>
            <div class="grid-container-pin">
              <div v-for="(pin_button, index) in pin_buttons">
                <div :class="['pin-default-btn button-' + pin_button]" @click="input(pin_button)"
                  @touchstart="startTouch(pin_button, 'pin')" @touchend="endTouch(pin_button, 'pin')">
                  <svg v-if="pin_button === 'Clear'" width="50" height="50" viewBox="-27 -110 168.44 151.61"
                    xmlns="http://www.w3.org/2000/svg">
                    <text style="font-size: 3.6rem;">Clear</text>
                    Sorry, your browser does not support inline SVG.
                  </svg>
                  <svg v-else width="50" height="50" viewBox="-75 -118 168.44 151.61"
                    xmlns="http://www.w3.org/2000/svg">
                    <text style="font-size: 5rem;">{{ pin_button }}</text>
                    Sorry, your browser does not support inline SVG.
                  </svg>
                </div>
              </div>
            </div>
            <div class="row" style="margin-bottom:2rem;">
              <div :class="['continue-default-btn']" v-show="show_continue_btn" @click="loginLocalAdmin"
                @touchstart="startTouch('continue', 'continue')" @touchend="endTouch('continue', 'continue')">
                <div :class="['button-continue']">
                  <svg width="200" height="195" viewBox="-15 -30 180 171" xmlns="http://www.w3.org/2000/svg">
                    <text style="font-size: 25px;">CONTINUE</text>
                    Sorry, your browser does not support inline SVG.
                  </svg>
                </div>
              </div>
              <div style=" fill: #FFFFFF; height: 50px; width: 20px;" v-show="hide_continue_btn">

                <svg width="220.44" height="190.61" viewBox="-25 -10 168.44 151.61" xmlns="http://www.w3.org/2000/svg">
                  <text>CONTINUE</text>
                  Sorry, your browser does not support inline SVG.
                </svg>
              </div>
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <div class="row">
      <div class="modal fade" id="modal-test" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="">
              <div class="header_modal">ENTER YOUR PIN
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
            </div>
            <div class="modal-body">
              <div class="grid-input">
                <div>
                  <input class="form-control transparent-input" type='text' v-model="room.input_one">
                </div>
                <div>
                  <input class="form-control transparent-input" type='text' v-model="room.input_two">
                </div>
                <div>
                  <input class="form-control transparent-input" type='text' v-model="room.input_three">
                </div>
                <div>
                  <input class="form-control transparent-input" type='text' v-model="room.input_four">
                </div>
              </div>
            </div>
            <div class="grid-container-pin">
              <div v-for="(pin_button, index) in pin_buttons">
                <div :class="['pin-default-btn button-' + pin_button]" @click="input(pin_button)"
                  @touchstart="startTouch(pin_button, 'pin')" @touchend="endTouch(pin_button, 'pin')">
                  <svg v-if="pin_button === 'Clear'" width="50" height="50" viewBox="-27 -110 168.44 151.61"
                    xmlns="http://www.w3.org/2000/svg">
                    <text style="font-size: 3.6rem;">Clear</text>
                    Sorry, your browser does not support inline SVG.
                  </svg>
                  <svg v-else width="50" height="50" viewBox="-75 -118 168.44 151.61"
                    xmlns="http://www.w3.org/2000/svg">
                    <text style="font-size: 5rem;">{{ pin_button }}</text>
                    Sorry, your browser does not support inline SVG.
                  </svg>
                </div>
              </div>
            </div>
            <div class="row" style="margin-bottom:2rem;">
              <div :class="['continue-default-btn']" v-show="show_continue_btn" @click="loginLocalAdmin"
                @touchstart="startTouch('continue', 'continue')" @touchend="endTouch('continue', 'continue')">
                <div :class="['button-continue']">
                  <svg width="200" height="195" viewBox="-15 -30 180 171" xmlns="http://www.w3.org/2000/svg">
                    <text style="font-size: 25px;">CONTINUE</text>
                    Sorry, your browser does not support inline SVG.
                  </svg>
                </div>
              </div>
              <div style=" fill: #FFFFFF; height: 50px; width: 20px;" v-show="hide_continue_btn">

                <svg width="220.44" height="190.61" viewBox="-25 -10 168.44 151.61" xmlns="http://www.w3.org/2000/svg">
                  <text>CONTINUE</text>
                  Sorry, your browser does not support inline SVG.
                </svg>
              </div>
            </div>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <div class="grid-container-select border_testing" v-show="show_rooms">
      <div class="row justify-content-center">
        <div class="col-4"></div>
        <div class="col-4">
          <div
            style="position: absolute; left: 35px; top: -18px; bottom: 36px; z-index: 1; font-weight: bold; font-size: 25px; color: #0c2610; background-color: rgb(242, 235, 220);">
            SITE</div>
          <div>
            <CustomSelectSite :options=sites :default="'Select Site'" class="select" @input="getBuildings($event)" />
          </div>
        </div>
        <div class="col-4"></div>
      </div>
      <div class="row justify-content-center">
        <div class="col-4"></div>
        <div class="col-4">
          <div
            style="position: absolute; left: 35px; top: -18px; bottom: 36px; z-index: 1; font-weight: bold; font-size: 25px; color: #0c2610; background-color: rgb(242, 235, 220);">
            BUILDING</div>
          <div>
            <CustomSelectBuilding :options=buildings :default="'Select Building'" class="select"
              @input="getFloorLevels($event)" />
          </div>
        </div>
        <div class="col-4"></div>
      </div>
      <div class="row justify-content-center">
        <div class="col-4"></div>
        <div class="col-4">
          <div
            style="position: absolute; left: 35px; top: -18px; bottom: 36px; z-index: 1; font-weight: bold; font-size: 25px; color: #0c2610; background-color: rgb(242, 235, 220);">
            FLOOR</div>
          <div>
            <CustomSelectFloor :options=floors :default="'Select Floor'" class="select" @input="getRoom($event)" />
          </div>
        </div>
        <div class="col-4"></div>
      </div>
      <div class="row justify-content-center">
        <div class="col-4"></div>
        <div class="col-4">
          <div
            style="position: absolute; left: 35px; top: -18px; bottom: 36px; z-index: 1; font-weight: bold; font-size: 25px; color: #0c2610; background-color: rgb(242, 235, 220);">
            ROOM</div>
          <div>
            <CustomSelectRoom :options=floor_rooms :default="'Select Room'" class="select" @input="getRoomId($event)" />
          </div>
        </div>
        <div class="col-4"></div>
      </div>
      <div class="row justify-content-center">
        <div class="col-5 border_testing"></div>
        <div :class="['col-2 border_testing save-default-btn']" v-show="show_save_btn" @click="switchRoom"
          @touchstart="startTouch('save', 'save')" @touchend="endTouch('save', 'save')">
          <div :class="['button-save']">
            <svg width="200" height="195" viewBox="-18 -45 180 171">
              <text style="font-size: 45px;">SAVE</text>
              Sorry, your browser does not support inline SVG.
            </svg>
          </div>
        </div>
        <div :class="['col-2 border_testing save-clear-btn']" v-show="hide_save_btn">
          <div>
            <svg width="200" height="195"  viewBox="0 -45 180 171" xmlns="http://www.w3.org/2000/svg">
              <text>SAVE</text>
              Sorry, your browser does not support inline SVG.
            </svg>
          </div>
        </div>
        <div class="col-5 border_testing"></div>
      </div>
    </div>
  </div>
  </div>
</template>
<script>
import CustomSelectSite from "./CustomSelectSite.vue";
import CustomSelectBuilding from "./CustomSelectBuilding.vue";
import CustomSelectFloor from "./CustomSelectFloor.vue";
import CustomSelectRoom from "./CustomSelectRoom.vue";
export default {
  components: {
    CustomSelectSite,
    CustomSelectBuilding,
    CustomSelectFloor,
    CustomSelectRoom,
  },
  props: ['defaultRoom'],
  mounted() {
    // Do something useful with the data in the template
    // alert(this.defaultRoom);
  },

  name: "Questionnaires",
  data() {
    return {
      header: {
        title: 'Survey Kiosk',
        description: 'Enter your pin.',
        date_local_admin: '',
        time: '',
      },
      filter: {
        local_admin_id: '',
        building_id: '',
        level_id: '',
        room_id: '',
      },
      room: {
        id: '',
        input_one: '',
        input_two: '',
        input_three: '',
        input_four: '',
        building_level_room: '',
      },
      survey: {
        questionnaire_id: '',
        questionnaire_answer_id: '',
        site_id: '',
        site_building_id: '',
        site_building_level_id: '',
        site_building_room_id: '',
      },
      kiosk: {
        id: '',
        site_id: '',
        site_building_id: '',
        site_building_level_id: '',
        site_building_room_id: '',
      },
      btn_name: '',
      show_admin_button: true,
      show_concerns: true,
      hide_submit_btn: true,
      hide_thank_you_padding: false,
      show_concern_pendings: false,
      show_submit_pending_btn: false,
      show_rooms: false,
      show_submit_btn: false,
      show_submit_pending_button: false,
      show_save_btn: false,
      hide_save_btn: true,
      show_success: false,
      show_pending_success: false,
      show_continue_btn: false,
      hide_continue_btn: true,
      show_switch_room_success: false,
      show_default_room: true,
      show_footer: true,
      questionnaires: [],
      rooms: [],
      concern: [],
      concern_pending: [],
      view: ['login'],
      check_logo: 'assets/images/logos/check.png',
      check_red_logo: 'assets/images/logos/check_red.png',
      check_green_logo: 'assets/images/logos/check_green.png',
      submit_logo: 'assets/images/logos/buttons/submit.png',
      resolve_logo: 'assets/images/logos/buttons/resolve.png',
      save_logo: 'assets/images/logos/buttons/save.png',
      success_logo: 'assets/images/logos/buttons/success.png',
      acc_transparent_logo: 'assets/images/logos/accenture-logo-transparent.png',
      pin_buttons: ['1', '2', '3', '4', '5', '6', '7', '8', '9', 'Clear', '0', 'X'],
      input_digit: [],
      max: 1,
      survey_pendings: [],
      message: '',
      user: [],
      user_role: '',
      user_role_name: '',
      jordan: 'pogi',
      pincode_modal: 0,
      sites: [],
      buildings: [],
      floors: [],
      floor_rooms: [],
    }
  },

  created() {
    this.getQuestionnaires();
    this.getDefaultRoom();
    this.getSites();
    this.getDate();
    this.getTime();
  },

  methods: {
    reloadPage: function () {
      window.location.reload();
    },
    getQuestionnaires: function () {
      axios.get('/api/v1/employee/get-concerns')
        .then(response => {
          this.questionnaires = response.data.data
        });
    },
    getDefaultRoom: function () {
      $('.close, .close_two').hide();
      if (this.defaultRoom != 0) {
        axios.get('/api/v1/employee/get-default-room/' + this.defaultRoom)
          .then(response => {
            var room = response.data.data; console.log(room);
            this.room.id = room.id;
            this.survey.site_id = room.site_id;
            this.survey.site_building_id = room.site_building_id;
            this.survey.site_building_level_id = room.site_building_level_id;
            this.survey_pendings = room.building_floor_room_survey_pendings;
            this.room.building_level_room = room.site_name + '.' + room.building_name + '.' + room.building_floor_name + '.' + room.name;
            this.user_role = '';
            this.user_role_name = '';
          });
        this.header.title = 'Help us serve you better.';
        this.header.description = 'Select all that applies.';
        this.btn_name = '';
      } else {
        $(document).ready(function () {
          $('.passingID').trigger("click");
          $('.close, .close_two').hide();


        });
      }
    },
    getDefaultRoomInterval: function () {
      axios.get('/api/v1/employee/get-default-room/' + this.defaultRoom)
        .then(response => {
          var room = response.data.data;
          this.survey_pendings = room.building_floor_room_survey_pendings;
          console.log(this.survey_pendings);
        });
    },
    getRooms: function () {
      axios.get('/api/v1/employee/get-rooms', { params: { filters: this.filter } })
        .then(response => {
          this.rooms = response.data.data;

        });

    },
    switchImage(event) {
      var id = event.target.id;
      const index = this.concern.indexOf(id);
      if (index > -1) {
        this.concern.splice(index, 1);
        $(".survey-btn-" + id).removeClass('item-touch-btn').addClass('item-default-btn');
        this.show_button();
      } else {// alert('2');
        this.concern.push(id);
        $(".survey-btn-" + id).removeClass('item-default-btn').addClass('item-touch-btn');
        this.show_button();
      }
    },
    pincodeModal(event) {
      var id = event.target.id;
      this.pincode_modal = id;
      $('.close, .close_two').hide();
      // $('.modal-backdrop').remove();
    },

    switchImagePending(event) {
      var id = event.target.id;
      const index = this.concern_pending.indexOf(id);
      if (index > -1) {
        this.concern_pending.splice(index, 1);
        var src = new URL(event.target.src);
        $(".survey-btn-pending-" + id.replace('pending_', '')).removeClass('item-touch-btn').addClass('item-pending-btn');
        this.show_pending_button();
      } else {
        this.concern_pending.push(id);
        axios.get('/api/v1/employee/get-answer-details/' + id.replace('pending_', ''))
          .then(response => {
            var answer = response.data.data;
            $(".survey-btn-pending-" + id.replace('pending_', '')).removeClass('item-pending-btn').addClass('item-touch-btn');
            this.show_pending_button();
          });
      }
      console.log(this.concern_pending);
    },



    submit: function () {
      let formData = new FormData();
      formData.append("concern", this.concern);
      formData.append("room_id", this.room.id);
      axios.post('/api/v1/employee/store-concern/', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        },
      })
        .then(response => {
          //toastr.success(response.data.message);
          // this.$refs.dataTable.fetchData();

        });
      this.show_concerns = false;
      this.show_submit_btn = false;
      this.hide_submit_btn = false;
      this.hide_thank_you_padding = true;
      this.show_success = true;
      this.input_digit = [];
      this.room.input_one = '';
      this.room.input_two = '';
      this.room.input_three = '';
      this.room.input_four = '';
      this.show_continue_btn = false;
      this.hide_continue_btn = true;
      this.show_admin_button = false;
      this.btn_name = '';
      this.header.title = '';
      this.header.description = ''; //test
      setTimeout(function () { window.location.reload(); }, 5000);
    },
    submit_pending: function () {
      let formData = new FormData();
      formData.append("concern_pending", this.concern_pending);
      formData.append("room_id", this.room.id);
      formData.append("site_id", this.survey.site_id);
      formData.append("site_building_id", this.survey.site_building_id);
      formData.append("site_building_level_id", this.survey.site_building_level_id);
      formData.append("user_id", this.filter.local_admin_id.id);
      axios.post('/api/v1/employee/store-concern-pending/', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        },
      })
        .then(response => {
          //toastr.success(response.data.message);
          // this.$refs.dataTable.fetchData();

        });
      // this.hide_thank_you_padding = true;  
      this.show_concern_pendings = false;
      this.show_submit_pending_btn = false;
      this.show_pending_success = true;
      this.show_admin_button = false;
      this.header.title = '';
      this.header.description = '';
      this.hide_thank_you_padding = true;
      setTimeout(function () { window.location.reload(); }, 5000);

    },
    show_button: function () {
      if (this.concern.length > 0) {
        this.show_submit_btn = true;
        this.hide_submit_btn = false;
        this.btn_name = 'SUBMIT';
      } else {
        this.hide_submit_btn = true;
        this.show_submit_btn = false;
        this.btn_name = '';
      }
    },
    show_pending_button: function () {
      if (this.concern_pending.length > 0) {
        this.show_submit_pending_button = true;
      } else {
        this.show_submit_pending_button = false;
      }
    },

    input: function (value) {
      if (value == "X") {
        this.input_digit.pop();
        if (this.input_digit.length == 0) {
          this.room.input_one = '';
        } else if (this.input_digit.length == 1) {
          this.room.input_two = '';
        } else if (this.input_digit.length == 2) {
          this.room.input_three = '';
        }
        else if (this.input_digit.length == 3) {
          this.room.input_four = '';
          this.show_continue_btn = false;
          this.hide_continue_btn = true;
        } else {

        }

      } else if (value == "Clear") {
        this.input_digit = [];
        this.room.input_one = '';
        this.room.input_two = '';
        this.room.input_three = '';
        this.room.input_four = '';
        this.show_continue_btn = false;
        this.hide_continue_btn = true;
      } else {
        var count = this.input_digit.length;
        if (count <= 3) {
          this.input_digit.push(value);
          if (count == 0) {
            this.room.input_one = value;
            this.room.input_two = '';
          } else if (count == 1) {
            this.room.input_two = value;
            this.room.input_three = '';
          }
          else if (count == 2) {
            this.room.input_three = value;
            this.room.input_four = '';
          }
          else if (count == 3) {
            this.room.input_four = value;
            // this.show_continue_btn = true;
            this.loginLocalAdmin();
            this.hide_continue_btn = false;
          }
        }

      }
    },

    loginLocalAdmin: function () { //alert(this.survey.site_id +'-'+this.survey.site_building_id +'-'+ this.survey.site_building_level_id);
      var password = this.room.input_one + this.room.input_two + this.room.input_three + this.room.input_four;
      let formData = new FormData();
      formData.append("password", password);
      formData.append("pincode_modal", this.pincode_modal); // first or room
      formData.append("default_room", this.defaultRoom);
      formData.append("site_id", this.survey.site_id);
      formData.append("site_building_id", this.survey.site_building_id);
      formData.append("site_building_level_id", this.survey.site_building_level_id);

      axios.post('/api/v1/employee/local-login/', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        },
      })
        .then(response => {
          if (response.data.data) {
            this.filter.local_admin_id = response.data.data;
            this.input_digit = [];
            this.room.input_one = '';
            this.room.input_two = '';
            this.room.input_three = '';
            this.room.input_four = '';
            this.show_continue_btn = false;
            this.hide_continue_btn = true;
            $('.close').click();
            this.show_concerns = false;
            if (this.pincode_modal == 0) {
              this.show_concern_pendings = true;
              this.show_submit_pending_btn = true;
              this.show_concern = false;
              this.show_submit_btn = false;
              this.hide_submit_btn = false;


            } else {
              this.header.description = "Let’s get started!";
              this.show_rooms = true;
              this.show_footer = false;
              this.show_concern_pendings = false;//
              this.show_submit_pending_btn = false;//
              this.show_concern_pendings = false;
              this.show_admin_button = false;
              this.show_default_room = false;
              this.show_submit_btn = false;
              this.hide_submit_btn = false;
              this.show_save_btn = false;
              this.getSites();
            }
            this.user = response.data.data;
            this.user_role = this.user.roles[0].id;
            this.user_role_name = this.user.roles[0].name;
            this.header.date_local_admin = 'Admin - (' + this.user_role_name + ')' + this.user.full_name;
          } else {
            this.input_digit = [];
            this.room.input_one = '';
            this.room.input_two = '';
            this.room.input_three = '';
            this.room.input_four = '';
            this.show_continue_btn = false;
            this.hide_continue_btn = true;
            toastr.error('Unauthorized Access!');
          }
        });
    },
    switchRoom: function () {
      var room_id = this.kiosk.site_building_room_id;
      var existing_path = window.location.pathname.replace('/', '');
      if (room_id != existing_path) {
        this.$router
          .push({ path: '/' + room_id })
          .then(() => { this.$router.go() });
      } else {
        this.reloadPage();
      }

      this.header.title = 'Help us serve you better.';
      this.header.title = 'Select all that applies';
    },

    getSites: function () {
      //alert(this.filter.local_admin_id.id + ' site');alert('dddd');
      // console.log(this.filter);
      axios.get('/api/v1/employee/site/get-all', { params: { filters: this.filter.local_admin_id.id } })
        .then(
          response => {
            this.sites = response.data.data;
            console.log(this.sites);
          });
      $("div:contains('Select Site')").css({ "color": "#f2ebdc" });
      $("div:contains('Select Building')").css({ "color": "#f2ebdc" });
      $("div:contains('Select Floor')").css({ "color": "#f2ebdc" });
      $("div:contains('Select Room')").css({ "color": "#f2ebdc" });


    },

    getBuildings: function (id) {
      // alert(this.filter.local_admin_id.id + ' building');

      axios.get('/api/v1/employee/site/get-buildings', { params: { local_admin_id: this.filter.local_admin_id.id, site_id: id } })
        .then(response => {
          this.buildings = response.data.data;
          this.floors = [];
          this.floor_rooms = [];

        });
      if (id != 'Select Site') {
        $('.select-site').css({ "color": "#0c2610" });
        $('.select-building').css({ "color": "#f2ebdc" });
        $('.select-floor').css({ "color": "#f2ebdc" });
        $('.select-room').css({ "color": "#f2ebdc" });
      }
    },

    getFloorLevels: function (id) {
      axios.get('/api/v1/employee/site/floors', { params: { local_admin_id: this.filter.local_admin_id.id, building_id: id } })
        .then(response => {
          this.floors = response.data.data;
          this.floor_rooms = [];
        });

      if (id != 'Select Building') {
        $('.select-building').css({ "color": "#0c2610" });
        $('.select-floor').css({ "color": "#f2ebdc" });
        $('.select-room').css({ "color": "#f2ebdc" });
      }
    },
    getRoom: function (id) {
      this.filter.room_id = id;
      axios.get('/api/v1/employee/site/floors/rooms', { params: { local_admin_id: this.filter.local_admin_id.id, level_id: id } })
        .then(
          response => {
            this.floor_rooms = response.data.data;
            if (this.floor_rooms.length != 0) {
              this.show_save_btn = true;
              this.hide_save_btn = false;
            } else {
              this.show_save_btn = false;
              this.hide_save_btn = true;
            }
          });
      if (id != 'Select Floor') {
        $('.select-floor').css({ "color": "#0c2610" });
        $('.select-room').css({ "color": "#f2ebdc" });
      }
    },

    getRoomId: function (id) {
      this.kiosk.site_building_room_id = id;
      if (id != 'Select Room') {
        $('.select-room').css({ "color": "#0c2610" });
      }
    },

    getDate: function () {
      $('#modal-default').removeData('bs.modal').modal({ backdrop: true, keyboard: true });
      //     $('#modal-default').data('bs.modal').options.backdrop = 'static';
      // $('#modal-default').data('bs.modal').options.keyboard = false;
      var today = new Date();
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
      var yyyy = today.getFullYear();

      this.header.date_local_admin = mm + '/' + dd + '/' + yyyy;
    },

    startTouch: function (id, button) {
      //console.log('start');
      switch (button) {
        case 'pin':
          $('.button-' + id).css({ backgroundColor: "#0c2610", fill: "#FFFFFF" });
          break;
        default:
          $('.button-' + id).css({ backgroundColor: "#735439", fill: "#FFFFFF" });
      }
    },

    endTouch: function (id, button) {
      console.log('end');
      switch (button) {
        case 'pin':
          $('.button-' + id).css({ backgroundColor: "#FFFFFF", fill: "#735439" });
          break;
        case 'site_name':
          //$('.button-' + id).css({ backgroundColor: "#0c2610", fill: "#FFFFFF" });
          var id = $('.site_name').find('option:selected').attr('id');
          console.log(id);
          break;
        default:
          $('.button-' + id).css({ backgroundColor: "#FFFFFF", fill: "#0c2610" });
      }
    },

    getTime: function () {
      let a;
      let time;
      setInterval(() => {
        // a = new Date();
        // time = a.getHours() + ':' + a.getMinutes() + ':' + a.getSeconds();
        var now = new Date();
        var TwentyFourHour = now.getHours();
        var hour = now.getHours();
        var min = now.getMinutes();
        var sec = now.getSeconds();
        var mid = 'pm';
        if (sec < 10) {
          sec = "0" + sec;
        }
        if (min < 10) {
          min = "0" + min;
        }
        if (hour > 12) {
          hour = hour - 12;
        }
        if (hour < 10) {
          hour = "0" + hour;
        }
        if (hour == 0) {
          hour = 12;
        }
        if (TwentyFourHour < 12) {
          mid = 'am';
        }
        this.header.time = hour + ':' + min + ':' + sec + ' ' + mid;
        console.log(time);
      }, 1000);
    },

    formatName: function (index, name) {
      switch (name) {
        case 'DIRTY ROOM W/ FOUL ODOR':
          $('.survey' + index).html('<div>DIRTY ROOM W/</div><div>FOUL ODOR</div>');
          break;
        case 'DEFECTIVE FURNITURE':
          $('.survey' + index).html('<div>DEFECTIVE</div><div>FURNITURE</div>');
          break;
        case 'LOW TOILETRIES':
          $('.survey' + index).html('<div>LOW</div><div>TOILETRIES</div>');
          break;
        case 'HOT TEMPERATURE':
          $('.survey' + index).html('<div>HOT</div><div>TEMPERATURE</div>');
          break;
        case 'LOW SHEETS / TOWELS':
          $('.survey' + index).html('<div>LOW</div><div>SHEETS / TOWELS</div>');
          break;
        case 'TRASH CAN FULL':
          $('.survey' + index).html('<div>TRASH CAN</div><div>FULL</div>');
          break;
        case 'BUSTED LIGHTS':
          $('.survey' + index).html('<div>BUSTED</div><div>LIGHT</div>');
          break;
        case 'NO INTERNET CONNECTION':
          $('.survey' + index).html('<div>NO INTERNET</div><div>CONNECTION</div>');
          break;
        case 'LOW PANTRY SUPPLY':
          $('.survey' + index).html('<div>LOW PANTRY</div><div>SUPPLY</div>');
          break;
        case 'DEFECTIVE APPLIANCE':
          $('.survey' + index).html('<div>DEFECTIVE</div><div>APPLIANCE</div>');
          break;
        default:
      }
    }
  },
};
</script>
<style lang="scss">
*,
*::before,
*::after {
  box-sizing: border-box;
}

// h1 {
//   text-align: center;
// }</style>
