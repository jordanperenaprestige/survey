<template>
    <div class="container">
        <div class="row justify-content-center">
            <div v-show="show_concerns">
                <div class="row justify-content-center">
                    <div class="col-12 concern-title">
                        HELP US IMPROVE OUR RESTROOMS
                    </div>
                </div>
                <div class="grid-container">
                    <div v-for="(questionnaire, index) in questionnaires">
                        <div style="padding: 0em 0em 0em 0.8em;">
                            <img :src="questionnaire.button" @click="switchImage($event)" :id="questionnaire.id">
                        </div>
                        <div style="text-align: center;">{{ questionnaire.answer }}</div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-5">
                    </div>
                    <div class="col-2 concern-title submit_button">
                        <div v-show="show_submit_button">
                            <img :src="this.submit_logo" @click="submit()">
                        </div>
                    </div>
                    <div class="col-5">
                    </div>
                </div>
            </div>
            <div v-show="show_concern_pendings">
                <div class="row justify-content-center">
                    <div class="col-12 concern-title">
                        <p @click="reloadPage">Local Admin: {{ this.user_role_name }} - {{ this.user.full_name }}</p>
                    </div>
                </div>
                <div class="grid-container">
                    <div v-for="(survey_pending, index) in survey_pendings">
                        <div v-if="survey_pending.questionnaire_user_role == user_role" style="padding: 0em 0em 0em 0em;">
                            <img v-if="survey_pending.questionnaire_survey_id > 0"
                                :src="survey_pending.questionnaire_button.replace('.png', '_red.png')"
                                @click="switchImagePending($event)"
                                :id="'pending_' + survey_pending.questionnaire_answer_id"
                                :alt="survey_pending.questionnaire_button.replace('.png', '_red.png')">
                            <img v-else :src="check_green_logo">
                        </div>
                        <div v-else style="padding: 0em 0em 0em 0em;">
                            <img :src="survey_pending.questionnaire_button.replace('.png', '_gray.png')">
                        </div>
                        <!-- test -->
                        <div v-if="survey_pending.questionnaire_user_role == user_role"
                            style="font-family: Henry Sans Regular; color: #FFFFFF; font-size: 16px; text-align: center; padding: 0.em;">
                            {{ survey_pending.questionnaire_name }}
                        </div>
                        <div v-else style="font-family: Henry Sans Regular; 
    color: #D3D3D3; font-size: 16px; text-align: center; padding: 0.em;">
                            {{ survey_pending.questionnaire_name }}
                        </div>
                    </div>
                </div>
                <div v-show="show_submit_pending_button" class="row concern-title submit_button">
                    <div class=" col-12">
                        <img :src="this.resolve_logo" @click="submit_pending()">
                    </div>
                </div>
            </div>
            <div v-show="show_success" class="show-success">
                <div class="row justify-content-center">
                    <div class="col-6 mr-2">
                        <img :src="this.success_logo">
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-12 concern-title">
                        THANK YOU!
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-12 ">
                        The concern has been successfully submitted.
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-12 ">
                        This will be attended shortly.
                    </div>
                </div>
            </div>
            <div v-show="show_pending_success" class="show-success">
                <div class="row justify-content-center">
                    <div class="col-6">
                        <img :src="this.success_logo">
                    </div>
                    <div class="col-12 concern-title">
                        THANK YOU!
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-12 ">
                        The concern has been successfully resolved.
                    </div>
                </div>
            </div>
            <div v-show="show_switch_room_success" class="show-success">
                <div class="row justify-content-center">
                    <div class="col-6">
                        <img :src="this.success_logo">
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-12 concern-title">
                        THANK YOU!
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-12 ">
                        The room has been changed successfully.
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-12 ">
                        This will be attended shortly.
                    </div>
                </div>
            </div>
            <div class="modal fade" id="modal-default" data-keyboard="false" data-backdrop="static">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" style="color: #212529;">ENTER 4-DIGIT PIN TO PROCEED</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="grid-input">
                                <div><input class="form-control transparent-input" type='text' :maxlength="max"
                                        v-model="room.input_one"></div>
                                <div><input class="form-control transparent-input" type='text' :maxlength="max"
                                        v-model="room.input_two"></div>
                                <div><input class="form-control transparent-input" type='text' :maxlength="max"
                                        v-model="room.input_three"></div>
                                <div><input class="form-control transparent-input" type='text' :maxlength="max"
                                        v-model="room.input_four"></div>
                            </div>

                            <div class="col-2 grid-keypad">
                                <div v-for="(login_button, index) in login_buttons">
                                    <div><img :src="'assets/images/logos/keypad/' + login_button + '.png'"
                                            @click="input(login_button)" class="button1"></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default close_two" data-dismiss="modal">Close</button>
                            <button v-show="show_resolve" type="button" @click="loginLocalAdmin"
                                class="btn btn-warning">Submit</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <div v-show="show_rooms">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <p class="concern-title" @click="reloadPage">FIRST KIOSK</p>
                        <p style="color: #ffdb00;" @click="reloadPage">First Immediate Response Support Team</p>
                    </div>
                </div>
                <div class="row justify-content-center" style="margin-top: 2rem;">
                    <div class="col-12">
                        <select class="custom-select" v-model="kiosk.site_id" @change="getBuildings($event.target.value)">
                            <option value="">Select Site</option>
                            <option v-for="site in sites" :value="site.id"> {{ site.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="row justify-content-center" style="margin-top: 2rem;">
                    <div class="col-12">
                        <select class="custom-select" v-model="kiosk.site_building_id"
                            @change="getFloorLevels($event.target.value)">
                            <option value="">Select Building</option>
                            <option v-for="building in buildings" :value="building.id"> {{ building.name }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="row justify-content-center" style="margin-top: 2rem;">
                    <div class="col-12">
                        <select class="custom-select" v-model="kiosk.site_building_level_id"
                            @change="getRoom($event.target.value)">
                            <option value="">Select Floor</option>
                            <option v-for="floor in floors" :value="floor.id"> {{ floor.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="row justify-content-center" style="margin-top: 2rem;">
                    <div class="col-12">
                        <select class="custom-select" v-model="kiosk.site_building_room_id">
                            <option value="">Select Room</option>
                            <option v-for="room in roomss" :value="room.id"> {{ room.name }}</option>
                        </select>
                    </div>
                </div>
                <div v-show="show_save_button" class="row justify-content-center" style="margin-top: 2rem;">
                    <div class="-2">
                        <img :src="this.save_logo" @click="switchRoom">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div>
                <div v-show="show_default_room">
                    <div class="row justify-content-right col-12" style="position: absolute; bottom: 0px;">
                        <div class="col-2 first-title" style="text-align: left; color: #ffdb00;" data-toggle="modal"
                            data-target="#modal-default" @click="pincodeModal($event)" :id=0>First</div>
                        <div class="col-9 passingID first-title" style="text-align: right;color: #ffffff"
                            data-toggle="modal" data-target="#modal-default" @click="pincodeModal($event)" :id=1>{{
                                this.room.building_level_room }}
                        </div>
                        <!-- <div class="col-4 first-title" data-toggle="modal" data-target="#modal-default"
                        @click="pincodeModal($event)" :id=0>
                        FIRST
                    </div>
                    <div class="col-7 building-floor-room-title2 passingID" data-toggle="modal" data-target="#modal-default"
                        @click="pincodeModal($event)" :id=1>
                        {{ this.room.building_level_room }}
                    </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {

    props: ['defaultRoom'],
    mounted() {
        // Do something useful with the data in the template
        // alert(this.defaultRoom);
    },

    name: "Questionnaires",
    data() {
        return {
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
            show_admin_button: true,
            show_concerns: true,
            show_concern_pendings: false,
            show_rooms: false,
            show_submit_button: false,
            show_submit_pending_button: false,
            show_save_button: false,
            show_success: false,
            show_pending_success: false,
            show_resolve: false,
            show_switch_room_success: false,
            show_default_room: true,
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
            login_buttons: ['1', '2', '3', '4', '5', '6', '7', '8', '9', 'clear', '0', 'backspace'],
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
            roomss: [],

        }
    },

    created() {
        this.getQuestionnaires();
        this.getDefaultRoom();
        //setInterval(this.getDefaultRoomInterval, 30000);
        this.getSites();

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
                    // this.room.id = room.id;
                    // this.survey.site_id = room.site_id;
                    // this.survey.site_building_id = room.site_building_id;
                    // this.survey.site_building_level_id = room.site_building_level_id;
                    this.survey_pendings = room.building_floor_room_survey_pendings;
                    // this.room.building_level_room = room.building_name + '.' + room.building_floor_name + '.' + room.name;
                    // this.user_role = '';
                    // this.user_role_name = '';
                    //  console.log(room);

                });
        },
        getRooms: function () {
            axios.get('/api/v1/employee/get-rooms', { params: { filters: this.filter } })
                .then(response => this.rooms = response.data.data);
        },
        switchImage(event) {
            var id = event.target.id;
            const index = this.concern.indexOf(id);
            if (index > -1) {//  alert('1');
                this.concern.splice(index, 1);
                axios.get('/api/v1/employee/get-answer-details/' + id)
                    .then(response => {
                        var answer = response.data.data;
                        $("#" + id).attr('src', answer.button);
                        this.show_button();
                    });
            } else {// alert('2');
                this.concern.push(id);
                $("#" + id).attr('src', this.check_logo);
                this.show_button();
            }
            //console.log(this.concern);
        },
        pincodeModal(event) {
            var id = event.target.id;
            this.pincode_modal = id;
        },

        switchImagePending(event) {

            var id = event.target.id;
            const index = this.concern_pending.indexOf(id);
            if (index > -1) {
                this.concern_pending.splice(index, 1); // 2nd parameter means remove one item only
                var src = new URL(event.target.src);
                $("#" + id).attr('src', event.target.alt);
                this.show_pending_button();
            } else {
                this.concern_pending.push(id);
                axios.get('/api/v1/employee/get-answer-details/' + id.replace('pending_', ''))
                    .then(response => {
                        var answer = response.data.data;
                        $("#" + id).attr('src', this.check_green_logo);
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
            this.show_success = true;
            this.input_digit = [];
            this.room.input_one = '';
            this.room.input_two = '';
            this.room.input_three = '';
            this.room.input_four = '';
            this.show_resolve = false;
            this.show_admin_button = false;
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

            this.show_concern_pendings = false;
            this.show_pending_success = true;
            this.show_admin_button = false;

            setTimeout(function () { window.location.reload(); }, 5000);

        },
        show_button: function () {
            if (this.concern.length > 0) {
                this.show_submit_button = true;
            } else {
                this.show_submit_button = false;
            }
        },
        show_pending_button: function () {
            if (this.concern_pending.length > 0) {
                this.show_submit_pending_button = true;
            } else {
                this.show_submit_pending_button = false;
            }
        },
        // onChange(event) {
        //     if (event.target.value > 0) {
        //         this.show_save_button = true;
        //     } else {
        //         this.show_save_button = false;
        //     }
        // },
        input: function (value) {
            if (value == "backspace") {
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
                    this.show_resolve = false;
                } else {

                }

            } else if (value == "clear") {
                this.input_digit = [];
                this.room.input_one = '';
                this.room.input_two = '';
                this.room.input_three = '';
                this.room.input_four = '';
                this.show_resolve = false;
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
                        this.show_resolve = true;
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
                        this.show_resolve = false;
                        $('.close').click();
                        this.show_concerns = false;
                        if (this.pincode_modal == 0) {
                            this.show_concern_pendings = true;
                        } else {
                            this.show_rooms = true;
                            this.show_concern_pendings = false;
                            this.show_admin_button = false;
                            this.show_default_room = false;
                            this.getRooms();
                            this.getSites();
                        }
                        this.user = response.data.data;
                        this.user_role = this.user.roles[0].id;
                        this.user_role_name = this.user.roles[0].name;
                    } else {
                        this.input_digit = [];
                        this.room.input_one = '';
                        this.room.input_two = '';
                        this.room.input_three = '';
                        this.room.input_four = '';
                        this.show_resolve = false;
                        toastr.error('Unauthorized Access!');
                    }
                });
        },
        switchRoom: function () {
            var room_id = this.kiosk.site_building_room_id
            this.$router
                .push({ path: '/' + room_id })
                .then(() => { this.$router.go() })

            // let formData = new FormData();
            // formData.append("room_id", this.room.id);
            // axios.post('/api/v1/employee/switch-room/', formData, {
            //     headers: {
            //         'Content-Type': 'multipart/form-data'
            //     },
            // })
            //     .then(response => {
            //         this.show_switch_room_success = true;
            //         this.show_rooms = false;
            //         setTimeout(function () { window.location.reload(); }, 5000);
            //     });
        },

        getSites: function () {
            //alert(this.filter.local_admin_id.id + ' site');alert('dddd');
            // console.log(this.filter);
            axios.get('/api/v1/employee/site/get-all', { params: { filters: this.filter.local_admin_id.id } })
                .then(response => this.sites = response.data.data);
        },

        getBuildings: function (id) {
            // alert(this.filter.local_admin_id.id + ' building');

            axios.get('/api/v1/employee/site/get-buildings', { params: { local_admin_id: this.filter.local_admin_id.id, site_id: id } })
                .then(response => this.buildings = response.data.data);
        },

        getFloorLevels: function (id) {
            // alert(this.filter.local_admin_id.id + ' level');
            //this.filter.level_id = id;
            axios.get('/api/v1/employee/site/floors', { params: { local_admin_id: this.filter.local_admin_id.id, building_id: id } })
                .then(response => this.floors = response.data.data);
        },
        getRoom: function (id) {
            // alert(this.filter.local_admin_id.id + ' room');
            this.filter.room_id = id;
            axios.get('/api/v1/employee/site/floors/rooms', { params: { local_admin_id: this.filter.local_admin_id.id, level_id: id } })
                .then(
                    response => {
                        this.roomss = response.data.data;
                        if (this.roomss.length != 0) {
                            this.show_save_button = true;
                        } else {
                            this.show_save_button = false;
                        }
                    });
        },

    },
};
</script> 
