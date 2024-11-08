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
                                    :otherButtons="otherButtons" :primaryKey="primaryKey"
                                    v-on:AddNewReservation="AddNewReservation" v-on:editButton="editReservation"
                                    v-on:DefaultScreen="DefaultScreen" v-on:downloadCsv="downloadCsv" ref="dataTable">
                                </Table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->

        <div class="modal fade" id="reservation-form" tabindex="-1" aria-labelledby="reservation-form" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New
                            Reservation</h5>
                        <h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o"
                                aria-hidden="true"></i> Edit Reservation</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="firstName" class="col-sm-4 col-form-label">Site <span
                                    class="font-italic text-danger"> *</span></label>
                            <div class="col-sm-8">
                                <select class="custom-select" v-model="reservation.site_id"
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
                                <select class="custom-select" v-model="reservation.site_building_id"
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
                                <select class="custom-select" v-model="reservation.site_building_level_id"
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
                                <select class="custom-select" v-model="reservation.site_building_room_id">
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
                                    <input type="text" class="form-control" v-model="reservation.event"
                                        placeholder="Reservation Name">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="firstName" class="col-sm-4 col-form-label">Name<span
                                        class="font-italic text-danger"> *</span></label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" v-model="reservation.name"
                                        placeholder="Reservation Name">
                                </div>
                            </div>
                            <div class="form-group row" v-show="edit_record">
                                <label for="isActive" class="col-sm-4 col-form-label">Active</label>
                                <div class="col-sm-8">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="isActive"
                                            v-model="reservation.active">
                                        <label class="custom-control-label" for="isActive"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" v-show="add_record" @click="storeReservation">Add New
                            Reservation</button>
                        <button type="button" class="btn btn-primary" v-show="edit_record" @click="updateReservation">Save
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
    name: "Reservations",
    data() {
        return {
            reservation: {
                id: '',
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

                event: "Event",
                name: "Name",
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
            dataUrl: "/admin/reservation/list",
            actionButtons: {
                edit: {
                    title: 'Edit this Reservation',
                    name: 'Edit',
                    apiUrl: '',
                    routeName: 'brand.edit',
                    button: '<i class="fas fa-edit"></i> Edit',
                    method: 'edit'
                },
                delete: {
                    title: 'Delete this Reservation',
                    name: 'Delete',
                    apiUrl: '/admin/reservation/delete',
                    routeName: '',
                    button: '<i class="fas fa-trash-alt"></i> Delete',
                    method: 'delete'
                },
            },
            otherButtons: {
                addNew: {
                    title: 'New Reservation',
                    v_on: 'AddNewReservation',
                    icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Reservation',
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
        AddNewReservation: function () {
            this.add_record = true;
            this.edit_record = false;
            this.reservation.site_id = '';
            this.reservation.site_building_id = '';
            this.reservation.site_building_level_id = '';
            this.reservation.site_building_room_id = '';
            this.reservation.event = '';
            this.reservation.name = '';

            $('#reservation-form').modal('show');
        },

        storeReservation: function () {
            let formData = new FormData();
            formData.append("site_id", this.reservation.site_id);
            formData.append("site_building_id", this.reservation.site_building_id);
            formData.append("site_building_level_id", this.reservation.site_building_level_id);
            formData.append("site_building_room_id", this.reservation.site_building_room_id);
            formData.append("event", this.reservation.event);
            formData.append("name", this.reservation.name);

            axios.post('/admin/reservation/store', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
            })
                .then(response => {
                    toastr.success(response.data.message);
                    this.$refs.dataTable.fetchData();
                    $('#reservation-form').modal('hide');
                })

        },

        editReservation: function (id) {
            axios.get('/admin/reservation/' + id)
                .then(response => {
                    var reservation = response.data.data;
                    this.reservation.id = id;
                    this.reservation.event = reservation.event;
                    this.reservation.name = reservation.name;
                    this.reservation.active = reservation.active;
                    this.add_record = false;
                    this.edit_record = true;
                    $('#reservation-form').modal('show');
                });
        },

        updateReservation: function () {
            let formData = new FormData();
            formData.append("id", this.reservation.id);
            formData.append("event", this.reservation.event);
            formData.append("name", this.reservation.name);

            axios.post('/admin/reservation/update', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                },
            })
                .then(response => {
                    toastr.success(response.data.message);
                    this.$refs.dataTable.fetchData();
                    $('#reservation-form').modal('hide');
                })
        },

        DefaultScreen: function (data) {
            this.is_default = data.id;
            $('#confirmModal').modal('show');
        },

        downloadCsv: function () {
            axios.get('/admin/reservation/download-csv')
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
