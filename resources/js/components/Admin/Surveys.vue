<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <Table :dataFields="dataFields" :dataUrl="dataUrl" :actionButtons="actionButtons"
                                    :otherButtons="otherButtons" :primaryKey="primaryKey" v-on:AddNewSurvey="AddNewSurvey"
                                    v-on:editButton="editSurvey" v-on:DefaultScreen="DefaultScreen"
                                    v-on:downloadCsv="downloadCsv" ref="dataTable">
                                </Table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->

        <div class="modal fade" id="survey-form" tabindex="-1" aria-labelledby="survey-form" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New
                            Survey</h5>
                        <h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o"
                                aria-hidden="true"></i> Edit Survey</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="firstName" class="col-sm-4 col-form-label">Site <span
                                    class="font-italic text-danger"> *</span></label>
                            <div class="col-sm-8">
                                <select class="custom-select" v-model="survey.site_id"
                                    @change="getBuildings($event.target.value)">
                                    <option value="">Select Site</option>
                                    <option v-for="site in sites" :value="site.id"> {{ site.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="firstName" class="col-sm-4 col-form-label">Building <span
                                    class="font-italic text-danger"> *</span></label>
                            <div class="col-sm-8">
                                <select class="custom-select" v-model="survey.site_building_id"
                                    @change="getFloorLevels($event.target.value)">
                                    <option value="">Select Building</option>
                                    <option v-for="building in buildings" :value="building.id"> {{ building.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="firstName" class="col-sm-4 col-form-label">Floor <span
                                    class="font-italic text-danger"> *</span></label>
                            <div class="col-sm-8">
                                <select class="custom-select" v-model="survey.site_building_level_id"
                                    @change="getRoom($event.target.value)">
                                    <option value="">Select Floor</option>
                                    <option v-for="floor in floors" :value="floor.id"> {{ floor.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="firstName" class="col-sm-4 col-form-label">Room <span
                                    class="font-italic text-danger"> *</span></label>
                            <div class="col-sm-8">
                                <select class="custom-select" v-model="survey.site_building_room_id">
                                    <option value="">Select Room</option>
                                    <option v-for="room in rooms" :value="room.id"> {{ room.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="firstName" class="col-sm-4 col-form-label">Event<span
                                        class="font-italic text-danger"> *</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="survey.event"
                                        placeholder="Survey Name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="firstName" class="col-sm-4 col-form-label">Name<span
                                        class="font-italic text-danger"> *</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="survey.name" placeholder="Survey Name">
                                </div>
                            </div>
                            <div class="form-group row" v-show="edit_record">
                                <label for="isActive" class="col-sm-4 col-form-label">Active</label>
                                <div class="col-sm-8">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="isActive"
                                            v-model="survey.active">
                                        <label class="custom-control-label" for="isActive"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" v-show="add_record" @click="storeSurvey">Add New
                            Survey</button>
                        <button type="button" class="btn btn-primary" v-show="edit_record" @click="updateSurvey">Save
                            Changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import Table from '../Helpers/Table';
// Import this component
import datePicker from 'vue-bootstrap-datetimepicker';
// Import date picker css
import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';
// import the component
import Treeselect from '@riophae/vue-treeselect'

export default {
    name: "Surveys",
    data() {
        return {
            survey: {
                id: '',
                questionnaire_id: '',
                questionnaire_answer: '',
                site_id: '',
                site_building_id: '',
                site_building_level_id: '',
                site_building_room_id: '',
                event: '',
                name: '',
                active: false,

            },
            sites: [],
            buildings: [],
            floors: [],
            rooms: [],
            add_record: true,
            edit_record: false,
            dataFields: {
                remarks: "Remarks",
                questionnaire: "Questionnaire",
                questionnaire_answer: "Answer",
                company_name: "Company",
                building_name: "Building",
                building_floor_name: "Floor",
                building_room_name: "Room",
                active: {
                    name: "Status",
                    type: "Boolean",
                    status: {
                        0: '<span class="badge badge-danger">Deactivated</span>',
                        1: '<span class="badge badge-info">Active</span>'
                    }
                },
                updated_at: "Last Updated"
            },
            primaryKey: "id",
            dataUrl: "/admin/questionnaire/survey/list",
            actionButtons: {
                edit: {
                    title: 'Edit this Survey',
                    name: 'Edit',
                    apiUrl: '',
                    routeName: 'brand.edit',
                    button: '<i class="fas fa-edit"></i> Edit',
                    method: 'edit'
                },
                delete: {
                    title: 'Delete this Survey',
                    name: 'Delete',
                    apiUrl: '/admin/survey/delete',
                    routeName: '',
                    button: '<i class="fas fa-trash-alt"></i> Delete',
                    method: 'delete'
                },
            },
            otherButtons: {
                addNew: {
                    title: 'New Survey',
                    v_on: 'AddNewSurvey',
                    icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Survey',
                    class: 'btn btn-primary btn-sm',
                    method: 'add'
                },
                download: {
                    title: 'Download',
                    v_on: 'downloadCsv',
                    icon: '<i class="fa fa-download" aria-hidden="true"></i> Download CSV',
                    class: 'btn btn-primary btn-sm',
                    method: 'add'
                },
            },
        };
    },

    created() {
        this.getSites();
    },

    methods: {
        AddNewSurvey: function () {
            this.add_record = true;
            this.edit_record = false;
            this.survey.site_id = '';
            this.survey.site_building_id = '';
            this.survey.site_building_level_id = '';
            this.survey.site_building_room_id = '';
            this.survey.event = '';
            this.survey.name = '';

            $('#survey-form').modal('show');
        },

        storeSurvey: function () {
            let formData = new FormData();
            formData.append("site_id", this.survey.site_id);
            formData.append("site_building_id", this.survey.site_building_id);
            formData.append("site_building_level_id", this.survey.site_building_level_id);
            formData.append("site_building_room_id", this.survey.site_building_room_id);
            formData.append("event", this.survey.event);
            formData.append("name", this.survey.name);

            axios.post('/admin/survey/store', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
            })
                .then(response => {
                    toastr.success(response.data.message);
                    this.$refs.dataTable.fetchData();
                    $('#survey-form').modal('hide');
                })

        },

        editSurvey: function (id) {
            axios.get('/admin/survey/' + id)
                .then(response => {
                    var survey = response.data.data;
                    this.survey.id = id;
                    this.survey.event = survey.event;
                    this.survey.name = survey.name;
                    this.survey.active = survey.active;
                    this.add_record = false;
                    this.edit_record = true;
                    $('#survey-form').modal('show');
                });
        },

        updateSurvey: function () {
            let formData = new FormData();
            formData.append("id", this.survey.id);
            formData.append("event", this.survey.event);
            formData.append("name", this.survey.name);

            axios.post('/admin/survey/update', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
            })
                .then(response => {
                    toastr.success(response.data.message);
                    this.$refs.dataTable.fetchData();
                    $('#survey-form').modal('hide');
                })
        },

        DefaultScreen: function (data) {
            this.is_default = data.id;
            $('#confirmModal').modal('show');
        },

        downloadCsv: function () {
            axios.get('/admin/survey/download-csv')
                .then(response => {
                    const link = document.createElement('a');
                    link.href = response.data.data.filepath;
                    link.setAttribute('download', response.data.data.filename); //or any other extension
                    document.body.appendChild(link);
                    link.click();
                })
        },

        getSites: function () {
            axios.get('/admin/site/get-all')
                .then(response => this.sites = response.data.data);
        },

        getBuildings: function (id) {
            axios.get('/admin/site/get-buildings/' + id)
                .then(response => this.buildings = response.data.data);
        },

        getFloorLevels: function (id) {
            axios.get('/admin/site/floors/' + id)
                .then(response => this.floors = response.data.data);
        },
        getRoom: function (id) {
            axios.get('/admin/site/floors/rooms/' + id)
                .then(response => this.rooms = response.data.data);
        },
    },

    components: {
        Table,
        datePicker,
        Treeselect
    }
};
</script> 
