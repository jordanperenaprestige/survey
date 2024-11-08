<template>
	<div>
		<!-- Main content -->
		<section class="content">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header">
								<h3 class="card-title">Building Rooms</h3>
							</div>
							<div class="card-body">
								<Table :dataFields="dataFields" :dataUrl="dataUrl" :actionButtons="roomactionButtons"
									:otherButtons="otherButtons" :primaryKey="primaryKey" v-on:AddNewRoom="AddNewRoom"
									v-on:editButton="editRoom" v-on:DeleteRoom="DeleteRoom" ref="roomsDataTable">
								</Table>
							</div>
						</div>
					</div>
				</div>
				<!-- /.row -->
			</div><!-- /.container-fluid -->
		</section>
		<!-- /.content -->

		<!-- Modal Add New / Edit User -->
		<div class="modal fade" id="room-form" tabindex="-1" aria-labelledby="room-form" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New
							Room</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o"
								aria-hidden="true"></i> Edit Room</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Building <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<select class="custom-select" v-model="room.site_building_id"
										@change="getFloorLevel($event.target.value)">
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
									<select class="custom-select" v-model="room.site_building_level_id">
										<option value="">Select Floor</option>
										<option v-for="floor in floors" :value="floor.id"> {{ floor.name }}</option>
									</select>
								</div>
							</div>


							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Name <span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="room.name" placeholder="Room Name"
										required>
								</div>
							</div>
							<div class="form-group row" v-show="edit_record">
								<label for="active" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="is_active"
											v-model="room.active">
										<label class="custom-control-label" for="is_active"></label>
									</div>
								</div>
							</div>
						</div>
						<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeRoom">Add New
							Room</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateRoom">Save
							Changes</button>
					</div>
				</div>
			</div>
		</div>
		<!-- End Modal Add New User -->
		<!-- Modal -->
		<div class="modal fade" id="roomDeleteModal" tabindex="-1" aria-labelledby="roomDeleteModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header bg-danger">
						<h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
					</div>
					<div class="modal-body">
						<h6>Do you really want to delete?</h6>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" @click="removeRoom">OK</button>
					</div>
				</div>
			</div>
		</div>

	</div>
</template>
<script>
import Table from '../Helpers/Table';

export default {
	name: "Room",
	data() {
		return {
			room: {
				id: '',
				site_building_id: '',
				site_building_level_id: '',
				name: '',
				active: false,
			},
			buildings: [],
			floors: [],
			map_preview: '',
			map_file: '',
			add_record: true,
			edit_record: false,
			id_to_deleted: 0,
			dataFields: {
				name: "Room",
				building_name: "Building",
				building_floor_name: "Floor",
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
			dataUrl: "/admin/site/room/list",
			roomactionButtons: {
				edit: {
					title: 'Edit this Room',
					name: 'Edit',
					apiUrl: '',
					routeName: 'room.edit',
					button: '<i class="fas fa-edit"></i> Edit',
					method: 'edit'
				},
				delete: {
					title: 'Delete this Room',
					name: 'Delete',
					apiUrl: '/admin/site/room/delete',
					routeName: '',
					button: '<i class="fas fa-trash-alt"></i> Delete',
					method: 'custom_delete',
					v_on: 'DeleteRoom',
				},
			},
			otherButtons: {
				addNew: {
					title: 'New Room',
					v_on: 'AddNewRoom',
					icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Room',
					class: 'btn btn-primary btn-sm',
					method: 'add'
				},
			}
		};
	},

	created() {
	},

	methods: {
		GetBuildings: function () {
			axios.get('/admin/site/buildings')
				.then(response => this.buildings = response.data.data);
		},
		// GetLevels: function () {
		// 	axios.get('/admin/site/levels')
		// 		.then(response => this.levels = response.data.data);
		// },
		getFloorLevel: function (id) {
			axios.get('/admin/site/floors/' + id)
				.then(response => this.floors = response.data.data);
		},

		AddNewRoom: function () {
			this.GetBuildings();
			this.add_record = true;
			this.edit_record = false;
			this.room.site_building_id = '';
			this.room.site_building_level_id = '',
				this.room.name = '';
			this.room.active = true;
			$('#room-form').modal('show');
		},

		storeRoom: function () {
			axios.post('/admin/site/room/store', this.room)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.roomsDataTable.fetchData();
					$('#room-form').modal('hide');
				})
		},

		editRoom: function (id) {
			this.GetBuildings();
			axios.get('/admin/site/room/' + id)
				.then(response => {
					var room = response.data.data;
					this.getFloorLevel(room.site_building_id);
					this.room.id = room.id;
					this.room.site_building_id = room.site_building_id;
					this.room.site_building_level_id = room.site_building_level_id;
					this.room.name = room.name;
					this.room.active = room.active;
					this.add_record = false;
					this.edit_record = true;
					$('#room-form').modal('show');
				});
		},

		updateRoom: function () {
			axios.post('/admin/site/room/update', this.room)
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.roomsDataTable.fetchData();
					$('#room-form').modal('hide');
				})
		},

		DeleteRoom: function (data) {
			this.id_to_deleted = data.id;
			$('#roomDeleteModal').modal('show');
		},

		removeRoom: function () {
			axios.get('/admin/site/room/delete/' + this.id_to_deleted)
				.then(response => {
					this.$refs.roomsDataTable.fetchData();
					this.id_to_deleted = 0;
					$('#roomDeleteModal').modal('hide');
				});
		},
	},

	components: {
		Table
	}
};
</script> 
