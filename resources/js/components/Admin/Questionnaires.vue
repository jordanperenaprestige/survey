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
									v-on:AddNewQuestionnaire="AddNewQuestionnaire" v-on:editButton="editQuestionnaire"
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

		<div class="modal fade" id="questionnaire-form" tabindex="-1" aria-labelledby="questionnaire-form"
			aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" v-show="add_record"><i class="fa fa-plus" aria-hidden="true"></i> Add New
							Questionnaire</h5>
						<h5 class="modal-title" v-show="edit_record"><i class="fa fa-pencil-square-o"
								aria-hidden="true"></i> Edit Questionnaire</h5>
						<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="card-body">
							<div class="form-group row">
								<label for="firstName" class="col-sm-4 col-form-label">Question<span
										class="font-italic text-danger"> *</span></label>
								<div class="col-sm-8">
									<input type="text" class="form-control" v-model="questionnaire.question"
										placeholder="Question">
								</div>
							</div>
							<div v-for="(answer, index) in questionnaire.answers" v-bind:key="index">
								
								<div class="position-absolute" style="right: 2.5rem; z-index: 9;">
									<button type="button" class="btn btn-outline-danger"
										@click="deleteRow(index, answer.id)"><i class="fas fa-trash-alt"></i></button>
								</div>
								<div class="form-group row">
									<label for="answer" class="col-sm-3 col-form-label">Answer<span class="font-italic text-danger">
										*</span></label>
									<div class="col-sm-8">
										<input type="text" class="form-control" v-model="questionnaire.answers"
											placeholder="Answer">
									</div>
								</div>

							</div>

							<div class="form-group row">
								<div class="col-sm-12">
									<button type="button" class="btn btn-primary" @click="addAnswer">Add Answer</button>
								</div>
							</div>

							<div class="form-group row" v-show="edit_record">
								<label for="isActive" class="col-sm-4 col-form-label">Active</label>
								<div class="col-sm-8">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="isActive"
											v-model="questionnaire.active">
										<label class="custom-control-label" for="isActive"></label>
									</div>
								</div>
							</div>
						</div>
						<!-- /.card-body -->
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" v-show="add_record" @click="storeQuestionnaire">Add
							New
							Questionnaire</button>
						<button type="button" class="btn btn-primary" v-show="edit_record" @click="updateQuestionnaire">Save
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
	name: "Questionnaires",
	data() {
		return {
			questionnaire: {
				id: '',
				question: '',
				active: false,
				answers: []

			},
			add_record: true,
			edit_record: false,
			dataFields: {
				serial_number: "ID",
				questions: "Question",
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
			dataUrl: "/admin/questionnaire/list",
			actionButtons: {
				edit: {
					title: 'Edit this Questionnaire',
					name: 'Edit',
					apiUrl: '',
					routeName: 'brand.edit',
					button: '<i class="fas fa-edit"></i> Edit',
					method: 'edit'
				},
				delete: {
					title: 'Delete this Questionnaire',
					name: 'Delete',
					apiUrl: '/admin/questionnaire/delete',
					routeName: '',
					button: '<i class="fas fa-trash-alt"></i> Delete',
					method: 'delete'
				},
			},
			otherButtons: {
				addNew: {
					title: 'New Questionnaire',
					v_on: 'AddNewQuestionnaire',
					icon: '<i class="fa fa-plus" aria-hidden="true"></i> New Questionnaire',
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

	},

	methods: {
		AddNewQuestionnaire: function () {
			this.add_record = true;
			this.edit_record = false;
			this.questionnaire.question = '';
			this.questionnaire.answers = [];
			this.addAnswer();

			$('#questionnaire-form').modal('show');
		},

		deleteRow: function (index, id) {
			// axios.get('/admin/manage-ads/material/delete/' + id)
			// 	.then(response => {
			// 		if (response.status == false)
			// 			return false;

					this.questionnaire.answers.splice(index, 1);
				// });
		},

		storeQuestionnaire: function () {
			let formData = new FormData();
			formData.append("question", this.questionnaire.question);
			formData.append("question", this.questionnaire.question);
			axios.post('/admin/questionnaire/store', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#questionnaire-form').modal('hide');
				})

		},

		editQuestionnaire: function (id) {
			axios.get('/admin/questionnaire/' + id)
				.then(response => {
					var questionnaire = response.data.data;
					this.questionnaire.id = id;
					this.questionnaire.question = questionnaire.questions;
					this.questionnaire.active = questionnaire.active;
					this.add_record = false;
					this.edit_record = true;
					$('#questionnaire-form').modal('show');
				});
		},

		updateQuestionnaire: function () {
			let formData = new FormData();
			formData.append("id", this.questionnaire.id);
			formData.append("question", this.questionnaire.question);

			axios.post('/admin/questionnaire/update', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					toastr.success(response.data.message);
					this.$refs.dataTable.fetchData();
					$('#questionnaire-form').modal('hide');
				})
		},

		DefaultScreen: function (data) {
			this.is_default = data.id;
			$('#confirmModal').modal('show');
		},

		downloadCsv: function () {
			axios.get('/admin/site/download-csv')
				.then(response => {
					const link = document.createElement('a');
					link.href = response.data.data.filepath;
					link.setAttribute('download', response.data.data.filename); //or any other extension
					document.body.appendChild(link);
					link.click();
				})
		},

		addAnswer: function () {
			this.questionnaire.answers.push({
				id: '',
				name: '',

			});
		},
	},

	components: {
		Table,
		datePicker,
		Treeselect
	}
};
</script> 
