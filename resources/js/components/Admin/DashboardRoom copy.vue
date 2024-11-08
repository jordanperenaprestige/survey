<template>
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<form class="col-sm-12">
								<div class="form-group row">
									<div class="col-sm-12">
										<label>{{ this.room_name }}</label>
									</div>
								</div>
								<div class="form-group row">
									<div class="col-sm-6">
										<label>Status</label>
									</div>
									<div class="col-sm-6">
										<label>Select to Apply</label>
									</div>
								</div>
								<div class="form-group row" v-for="(  room_survey, index  ) in room_surveys">
									<div class="col-sm-4">
										{{ room_survey.questionnaire_name }}
									</div>
									<div class="col-sm-3"><button type="button"
											:class="[`btn btn-block`, room_survey.pending]"
											@click="getRoomAnswerStatus($event);"
											:id="`pending_${room_survey.survey_id}_${room_survey.questionnaire_answer_id}_${room_survey.site_building_room_id}_${room_survey.questionnaire_id}_${room_survey.site_id}_${room_survey.site_building_id}_${room_survey.site_building_level_id}`">Pending</button>
									</div>
									<div class="col-sm-3"><button type="button" :class="[`btn btn-block`, room_survey.done]"
											@click="getRoomAnswerStatus($event)"
											:id="`done_${room_survey.survey_id}_${room_survey.questionnaire_answer_id}_${room_survey.site_building_room_id}_${room_survey.questionnaire_id}_${room_survey.site_id}_${room_survey.site_building_id}_${room_survey.site_building_level_id}`">Done</button>
									</div>
								</div>
								<div><button type="button" class="btn btn-primary pull-right"
										@click="updateRoomAnswerStatus">Save
										Changes</button></div>

							</form>
						</div>
						<div class="card-body">
							<div class="row">
								<form class="col-sm-12">
									<div class="form-group row">
										<h3 class="card-title">INCIDENT REPORTS</h3>
									</div>
									<div class="form-group row">
										<div class="col-sm-2">
											<label for="userName" class="col-form-label">Filter By</label>
											<select class="custom-select mr-2" v-model="filter.by" @change="filterBy()">
												<option value="">Select All</option>
												<option v-for="(filter, index) in filters_by" :value="index"> {{ filter }}
												</option>
											</select>
										</div>
										<div class="col-sm-2">
											<div v-show="by_day">
												<!-- v-show="show_concerns" -->
												<label for="day" class="col-form-label">Day</label>
												<date-picker v-model="filter.day" placeholder="Day" :config="options_D"
													id="day" autocomplete="off" @dp-change="daySelected"></date-picker>
											</div>
											<div v-show="by_week">
												<!-- v-show="show_concerns" -->
												<label for="week" class="col-form-label">Week</label>
												<date-picker v-model="filter.week" placeholder="Week" :config="options_D"
													id="week" autocomplete="off" @dp-change="weekSelected"></date-picker>
											</div>
											<div v-show="by_month">
												<!-- v-show="show_concerns" -->
												<label for="month" class="col-form-label">Month</label>
												<date-picker v-model="filter.month" placeholder="Month" :config="options_M"
													id="month" autocomplete="off" @dp-change="monthSelected"></date-picker>
											</div>
											<div v-show="by_year">
												<!-- v-show="show_concerns" -->
												<label for="month" class="col-form-label">Year</label>
												<date-picker v-model="filter.year" placeholder="Year" :config="options_Y"
													id="month" autocomplete="off" @dp-change="yearSelected"></date-picker>
											</div>
											<div v-show="by_start">
												<label for="userName" class="col-form-label">Start Date</label>
												<date-picker v-model="filter.start_date" placeholder="YYYY-MM-DD"
													:config="options" id="date_from" autocomplete="off"></date-picker>
											</div>
										</div>
										<div class="col-sm-2">

											<div v-show="by_end">
												<label for="userName" class="col-form-label">End Date</label>
												<date-picker v-model="filter.end_date" placeholder="YYYY-MM-DD"
													:config="options" id="date_end" autocomplete="off"
													@dp-change="customizedSelected"></date-picker>
											</div>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-sm-2">
											<label for="userName" class="col-form-label">Ave. Time: <span
													id="average_time"></span>&nbsp;m
											</label>
										</div>
										<div class="col-sm-2">
											<label for="userName" class="col-form-label">SMS: <span
													id="total_sms"></span>&nbsp;
											</label>
										</div>
									</div>
								</form>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<label for="" class="col-form-label">Reports Total: <span id="reports_total">{{
										reports_total }}</span></label>
									<div>The chart below provides a breakdown of total reported concern.</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-1">
								</div>
								<div class="col-sm-10">
									<div id="report_legend"></div>
								</div>
								<div class="col-sm-1">
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="chart-responsive">
										<canvas id="reportBarChart"
											style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<label for="" class="col-form-label">Incidents Total: <span id="incidents_total">{{
										incidents_total }}</span></label>
									<div>The chart below provides a breakdown of RESOLVED concerns only.</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-1">
								</div>
								<div class="col-sm-10">
									<div id="incident_legend"></div>
								</div>
								<div class="col-sm-1">
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="chart-responsive">
										<canvas id="incidentBarChart"
											style="min-height: 250px; height: 250px; max-height: 280px; max-width: 100%;"></canvas>
									</div>
								</div>
							</div>
							<div class="row">
								<!-- <div class="col-md-1"></div> -->
								<div class="col-md-6">
									<div class="chart-responsive">
										<canvas id="pieChartSurvey"
											style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
									</div>
								</div>
								<div class="col-md-6">
									<div class="chart-responsive">
										<canvas id="pieChartSurveyAnswer"
											style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
									</div>
								</div>
							</div>
							<div class="row"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</template>
<script>
import Table from '../Helpers/Table';
// Import this component
import Multiselect from 'vue-multiselect';
// Import date picker js
import datePicker from 'vue-bootstrap-datetimepicker';
// Import date picker css
import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';
import moment from 'moment';
import { shallowReactive } from 'vue';

export default {
	name: "DASHBOARDROOMS",
	data() {
		return {
			room_name: '',
			room_surveys: [],
			concern: [],
			pending: '',
			done: '',
			//for reports
			filter: {
				site_id: '',
				select_date: '',
				start_date: '',
				end_date: '',
				day: '',
				week: '',
				month: '',
				year: '',
				by: 0,
			},
			sites: [],
			options: {
				format: 'YYYY-MM-DD',
				useCurrent: false,
			},
			options_YYYY_MM_DD: {
				format: 'YYYY-MM-DD',
				useCurrent: false,
			},
			options_Y: {
				format: 'Y',
				useCurrent: false,
			},
			options_M: {
				format: 'YYYY-MM',
				useCurrent: false,
			},
			options_D: {
				format: 'YYYY-MM-DD',
				useCurrent: false,
			},
			options_YYYY_MM_DD: {
				format: 'YYYY-MM-DD',
				useCurrent: false,
			},
			options_YYYY_MM_DD: {
				format: 'YYYY-MM-DD',
				useCurrent: false,
			},
			filters_by: ['Lifetime', 'Day', 'Week', 'Month', 'Year', 'Custom date'],
			by_day: false,
			by_month: false,
			by_year: false,
			by_lifetime: false,
			survey_number_day: 0,
			reports_total: 0,
			incidents_total: 0,
			average_time: 0,
			total_sms: 0,
		}
	},
	created() {
		this.getRoom();
		this.getSites();
		//this.filterChart();
		this.filterBy();
	},

	methods: {
		getRoom: function () {
			axios.get('/admin/dashboad/room/get-survey')
				.then(response => {
					var room_survey = response.data.data;
					var site = room_survey[0].site_name;
					var building = room_survey[0].site_building_name;
					var level = room_survey[0].site_building_floor_name;
					var room = room_survey[0].site_building_room_name;
					this.room_surveys = room_survey;
					this.room_name = site + '/ ' + building + '/ ' + level + '/ ' + room;
				});
		},

		getRoomAnswerStatus: function (event) {

			var pending_done = event.target.id;
			var id = pending_done.split("_");
			//pending_survey_answer_room
			if (/pending/i.test(pending_done)) {

				$("#" + pending_done).removeClass("btn-outline-danger").addClass("bg-gradient-danger");
				$("#done_" + id[1] + "_" + id[2] + "_" + id[3] + "_" + id[4] + "_" + id[5] + "_" + id[6] + "_" + id[7]).removeClass("bg-gradient-success").addClass("btn-outline-success");
				const pending_index = this.concern.indexOf("pending_" + id[1] + "_" + id[2] + "_" + id[3] + "_" + id[4] + "_" + id[5] + "_" + id[6] + "_" + id[7]);

				if (pending_index > -1) {

				} else {
					this.concern.push(pending_done);
					const done_index = this.concern.indexOf(String("done_" + id[1] + "_" + id[2] + "_" + id[3] + "_" + id[4] + "_" + id[5] + "_" + id[6] + "_" + id[7]));
					if (done_index > -1) {
						this.concern.splice(done_index, 1);
					}
				}
			} else {
				$("#" + pending_done).removeClass("btn-outline-success").addClass("bg-gradient-success");
				$("#pending_" + id[1] + "_" + id[2] + "_" + id[3] + "_" + id[4] + "_" + id[5] + "_" + id[6] + "_" + id[7]).removeClass("bg-gradient-danger").addClass("btn-outline-danger");
				const done_index = this.concern.indexOf("done_" + id[1] + "_" + id[2] + "_" + id[3] + "_" + id[4] + "_" + id[5] + "_" + id[6] + "_" + id[7]);

				if (done_index > -1) {

				} else {
					this.concern.push(pending_done);

					const pending_index = this.concern.indexOf(String("pending_" + id[1] + "_" + id[2] + "_" + id[3] + "_" + id[4] + "_" + id[5] + "_" + id[6] + "_" + id[7]));
					if (pending_index > -1) {
						this.concern.splice(pending_index, 1);
					}
				}

			}

		},
		updateRoomAnswerStatus: function () {
			let formData = new FormData();
			formData.append("concern", this.concern);
			axios.post('/admin/dashboard/room/store-update', formData, {
				headers: {
					'Content-Type': 'multipart/form-data'
				},
			})
				.then(response => {
					if (response.data.message == 'undefined') {
						toastr.success('Successfully Modified!');
					} else {
						//toastr.success(response.data.message);
						toastr.success('Successfully Modified!');
					}

				})
			//this.filterChart();
			this.filterBy();
		},
		//////////for reports
		getSites: function () {
			axios.get('/admin/site/get-all')
				.then(response => this.sites = response.data.data);
		},
		filterBy: function () {

			if (this.filter.by == 0) {//lifetime
				this.by_day = false;
				this.by_week = false;
				this.by_month = false;
				this.by_year = false;
				this.by_start = false;
				this.by_end = false;
				const firstDayYear = moment().startOf('year').format('YYYY-MM-DD');
				const currentDay = moment(new Date()).format("YYYY-MM-DD");
				this.filter.start_date = (this.filter.start_date == '') ? firstDayYear : this.filter.start_date;
				this.filter.end_date = (this.filter.end_date == '') ? currentDay : this.filter.end_date;
				this.filterChartByDaily();
			} else if (this.filter.by == 1) {//day
				// this.clear_filter();
				this.by_day = true;
				this.by_week = false;
				this.by_month = false;
				this.by_year = false;
				this.by_start = false;
				this.by_end = false;

				const currentDay = moment(new Date()).format("YYYY-MM-DD");
				this.filter.day = (this.filter.day == '') ? currentDay : this.filter.day;
				//alert(this.filter.day);
				this.filterChartByDay();
			}
			else if (this.filter.by == 2) {//week
				this.clear_filter();
				this.by_day = false;
				this.by_week = true;
				this.by_month = false;
				this.by_year = false;
				this.by_start = false;
				this.by_end = false;

				const currentDay = moment(new Date()).format("YYYY-MM-DD");
				this.filter.week = (this.filter.week == '') ? currentDay : this.filter.week;
				this.filterChartByWeek();
			}
			else if (this.filter.by == 3) {//month
				//this.clear_filter();
				this.by_day = false;
				this.by_week = false;
				this.by_month = true;
				this.by_year = false;
				this.by_start = false;
				this.by_end = false;
				const currentMonth = moment(new Date()).format("YYYY-MM");
				//console.log(currentMonth);
				this.filter.month = currentMonth; (this.filter.month == '') ? currentMonth : this.filter.month;
				//alert(this.filter.month);
				this.filterChartByMonth();
			} else if (this.filter.by == 4) {//year
				//this.clear_filter();
				this.by_day = false;
				this.by_week = false;
				this.by_month = false;
				this.by_year = true;
				this.by_start = false;
				this.by_end = false;
				//const currentYear = moment(new Date()).format("YYYY");
				//console.log(currentYear);

				// this.filter.year = currentYear; (this.filter.year == '') ? currentYear : this.filter.year;
				// this.filterChartByYear();
				const currentYear = moment().year().toString();
				this.filter.year = (this.filter.year == '') ? currentYear : this.filter.year;
				this.filterChartByYear();
			} else { //customize
				// this.clear_filter();
				this.by_day = false;
				this.by_week = false;
				this.by_month = false;
				this.by_year = false;
				this.by_start = true;
				this.by_end = true;
				var d_start = new Date(this.filter.start_date);
				var d_end = new Date(this.filter.end_date);
				var m_start = d_start.getMonth();
				var m_end = d_end.getMonth();
				var y_start = d_start.getFullYear();
				var y_end = d_end.getFullYear();
				var date_start = d_start.getDate();
				var day_start = d_start.getDay();
				var date_end = d_end.getDate();
				var day_end = d_end.getDay();
				// To calculate the no. of days between two dates
				var difference_in_time = d_end.getTime() - d_start.getTime();
				var difference_in_days = difference_in_time / (1000 * 3600 * 24); console.log(this.filter);
				// // Difference_In_Days; 
				if (y_start == y_end) {

					if (difference_in_days == 0) {
						//alert(difference_in_days + 'day for hour');// 01 - 23 hour
						this.filter.day = d_end; console.log('>>>>>>>>if' + difference_in_days); console.log(this.filter + '<<<<<<');
						this.filterChartByDay();
					} else if (difference_in_days >= 1 && difference_in_days <= 7) {
						console.log('>>>>>>>>else if' + difference_in_days);
						console.log('difference_in_days >= 1 && difference_in_days <= 7');
						console.log(this.filter);
						var week_of_month_start = Math.ceil((date_start - 1 - day_start) / 7);
						var week_of_month_end = Math.ceil((date_end - 1 - day_end) / 7);
						if (y_start == y_end) {
							console.log(y_start + '== ' + y_end + 'y_start == y_end<<<<');
							if (week_of_month_start == week_of_month_end) {
								//alert('if' + week_of_month_start + '==' + week_of_month_end + 'week_of_month_start == week_of_month_end<<<<');
								//this.filterChartByWeek();
								this.filterChartByDailyAll();
							} else {
								//alert('else' + week_of_month_start + '!=' + week_of_month_end + 'week_of_month_start != week_of_month_end<<<<');
								//	alert('zzzzzzzzzzzzzzzzzzzzzzzz'+this.filter.start_date +' '+this.filter.end_date);
								this.filterChartByWeek();
							}
						} else {
							// wishlist
						}
					}
					else if (difference_in_days >= 8 && difference_in_days <= 31) {
						var week_of_month_start = Math.ceil((date_start - 1 - day_start) / 7);
						var week_of_month_end = Math.ceil((date_end - 1 - day_end) / 7);
						//alert(m_start + '== ' + m_end);
						//alert(y_start + '== ' + y_end);
						//this.filterChartByMonth();
						if (y_start == y_end) {
							if (week_of_month_start == week_of_month_end) {
								alert(week_of_month_start + '==' + week_of_month_end);
								this.filterChartByDaily();
							} else {
								alert('week_of_month_start != week_of_month_end' + week_of_month_start + '!=' + week_of_month_end);
								//this.filterChartByWeek();
								//this.filterChartByDaily();
								this.filterChartByYear();
								//this.filterChartByDaily();
							}
						} else {
							if (m_start == m_start) {
								alert(m_start + '==' + m_start);
								this.filterChartByMonth();
							} else {
								alert(m_start + '!=' + m_start);
								this.filterChartByYear();
							}
						}
					} else {
						this.filterChartByYear();
					}
				} else {
					this.filterChartByYears();
				}
			}
		},

		// 		filterChart: function () {
		// 			const moment = require('moment');
		// alert(this.survey_number_day);
		// 			if (this.filter.by == 0) {//day
		// 				const currentDay = moment(new Date()).format("YYYY-MM-DD");
		// 				this.filter.day = (this.filter.day == '') ? currentDay : this.filter.day;
		// 				this.filterChartByDay();

		// 			}
		// 			else if (this.filter.by == 1) {//Week
		// 				const currentDay = moment(new Date()).format("YYYY-MM-DD");
		// 				this.filter.week = (this.filter.week == '') ? currentDay : this.filter.week;
		// 				this.filterChartByWeek();
		// 			}
		// 			else if (this.filter.by == 2) {//Month
		// 				const currentMonth = moment().month();
		// 				this.filter.month = (this.filter.month == '') ? currentMonth : this.filter.month;
		// 				this.filterChartByMonth();

		// 			} else if (this.filter.by == 3) {//Year
		// 				const currentYear = moment().year(); alert(currentYear);
		// 				this.filter.year = (this.filter.year == '') ? currentYear : this.filter.year;
		// 				this.filterChartByYear();

		// 			} else {//lifetime
		// 				const firstDateOfMonth = moment().startOf('year').format('YYYY-MM-DD');
		// 				const lastDateOfMonth = moment().endOf('month').format('YYYY-MM-DD');
		// 				this.filter.start_date = (this.filter.start_date == '') ? firstDateOfMonth : this.filter.start_date;
		// 				this.filter.end_date = (this.filter.end_date == '') ? lastDateOfMonth : this.filter.end_date;
		// 				this.filterChartByLifetime();

		// 			}
		// 		},
		clear_filter: function () {
			this.filter.select_date = '';
			this.filter.start_date = '';
			this.filter.end_date = '';
			this.filter.week = '';
			this.filter.day = '';
			this.filter.month = '';
			this.filter.year = '';

		},
		
		filterChartByDaily: function () {
			var filter = this.filter;
			
			$.get("/admin/dashboard/trend-report-by-daily/list", filter, function (data) {
				var xValues = [];
				var yValues = [];
				var barColors = [];

				let dynamicColors = [
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7', '#747679',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7', '#747679',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7', '#747679',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7', '#747679',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7', '#747679',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7', '#747679',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7', '#747679',
				];
				$.each(data.data, function (key, value) {
					let background_color = value.building_color;
					xValues.push(value.day);
					yValues.push(value.total_survey);
					barColors.push(value.building_color);
				});
				let sum_reports_total = 0;

				// calculate sum using forEach() method
				yValues.forEach(num => {
					sum_reports_total += num;
				})

				this.reports_total = sum_reports_total;
				$('#reports_total').text(sum_reports_total);
				var reportBarChartCanvas = $('#reportBarChart').get(0).getContext('2d')
				var reportBarChartOptions = {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					},
					plugins: {
						labels: {
							render: 'value'
						}
					}
				}
				if (window.report_bar != undefined)
					window.report_bar.destroy();
				//if(bar) bar.destroy();
				window.report_bar = new Chart(reportBarChartCanvas, {
					//new Chart(reportBarChartCanvas, {
					type: 'bar',
					//data: reportBarChartData,
					data: {
						labels: xValues,
						datasets: [{
							backgroundColor: barColors,
							data: yValues,
							label: '(Report(s): ' + yValues + ')',
							backgroundColor: barColors,
							borderColor: barColors,
							pointRadius: false,
							pointColor: '#3b8bba',
							pointStrokeColor: barColors,
							pointHighlightFill: '#fff',
							pointHighlightStroke: barColors,

						}]
					},
					options: reportBarChartOptions
				})

			});

			$.get("/admin/dashboard/trend-incident-by-daily/list", filter, function (data) {
				var xValues = [];
				var yValues = [];
				var barColors = [];

				let dynamicColors = [
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7', '#747679',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7', '#747679',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7', '#747679',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7', '#747679',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7', '#747679',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7', '#747679',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7',
					'#ffe1bc', '#eca855', '#e3645e', '#417c42', '#782020', '#c70000', '#7a0012', '#9d0000', '#ccff66', '#f1dc81', '#717480', '#5b668f', '#ea4363', '#794913', '#e57395', '#ae743a', '#df9404', '#179443', '#1db954', '#f7df47', '#fac04e', '#6ab8b3', '#94aba1', '#ff6a46', '#84bd9b', '#e1f9ca', '#80a4b7', '#b4d9d7', '#9e1b32', '#6dc6e7', '#747679',
				];
				$.each(data.data, function (key, value) {
					let background_color = value.building_color;
					xValues.push(value.day);
					yValues.push(value.total_survey);
					barColors.push(value.building_color);
				});

				let sum_incidents_total = 0;

				// calculate sum using forEach() method
				yValues.forEach(num => {
					sum_incidents_total += num;
				})

				this.incidents_total = sum_incidents_total;
				$('#incidents_total').text(sum_incidents_total);

				var reportBarChartCanvas = $('#incidentBarChart').get(0).getContext('2d')
				var reportBarChartOptions = {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					},
					plugins: {
						labels: {
							render: 'value'
						}
					}
				}
				if (window.incident_bar != undefined)
					window.incident_bar.destroy();
				//if(bar) bar.destroy();
				window.incident_bar = new Chart(reportBarChartCanvas, {
					//new Chart(reportBarChartCanvas, {
					type: 'bar',
					//data: reportBarChartData,
					data: {
						labels: xValues,
						datasets: [{
							backgroundColor: barColors,
							data: yValues,
							label: '(Incident(s): ' + yValues + ')',
							backgroundColor: barColors,
							borderColor: barColors,
							pointRadius: false,
							pointColor: '#3b8bba',
							pointStrokeColor: barColors,
							pointHighlightFill: '#fff',
							pointHighlightStroke: barColors,

						}]
					},
					options: reportBarChartOptions
				})

			});

			$.get("/admin/dashboard/donut-report-by-daily/list", filter, function (data) {
				let labels = [];
				let data_value = [];
				let incident_report = 0;
				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						labels.push(value.questionnaire);
						incident_report += parseInt(value.tenant_survey);
						data_value.push(value.percentage_share);
					});
					// console.log(labels);
				}
				else {
					labels = ['Empty']
					data_value = [1];
				}

				var donutData = {
					labels: labels,
					datasets: [
						{
							data: data_value,
							backgroundColor: ['#728FCE', '#90EE90', '#FED8B1'],
						}
					]
				}
				var cleanliness = '#728FCE';
				var supplies = '#90EE90';
				var functionality = '#FED8B1';

				var pieChartSurveyCanvas = $('#pieChartSurvey').get(0).getContext('2d')
				var pieData = donutData;
				var pieOptions = {
					maintainAspectRatio: false,
					responsive: true,
					inGraphDataShow: true,
					inGraphDataRadiusPosition: 2,
					inGraphDataFontColor: 'white'
				}
				if (window.doughnut_chart != undefined)
					window.doughnut_chart.destroy();

				window.doughnut_chart = new Chart(pieChartSurveyCanvas, {
					//var myChart = new Chart(pieChartSurveyCanvas, {
					type: 'doughnut',
					data: pieData,
					plugins: [{
						beforeDraw: function (chart) {
							var width = chart.chart.width,
								height = chart.chart.height,
								ctx = chart.chart.ctx;

							ctx.restore();
							var fontSize = 1.5;
							ctx.font = fontSize + "em sans-serif";
							ctx.textBaseline = "middle";

							var text = incident_report,
								textX = Math.round((width - ctx.measureText(text).width) / 2),
								textY = height / 2;

							ctx.fillText(text, textX, textY);

							ctx.restore();
							var fontSize = 1;
							ctx.font = fontSize + "em sans-serif";
							ctx.textBaseline = "middle";

							ctx.fillText("INCIDENTS", (textX - 35), textY + 35);

							ctx.save();
						}
					}],
					options: {
						pieOptions,
						events: ["mousemove", "mouseout", "click", "touchstart", "touchmove", "touchend"],
					}
				});
			});

			$.get("/admin/dashboard/donut-report-by-daily-answer/list", filter, function (data) {
				let labels_answer = [];
				let data_value_answer = [];
				let incident_report_answer = 0;
				let randomBackgroundColor = [];
				var cleanliness = '#728FCE';
				var supplies = '#90EE90';
				var functionality = '#FED8B1';


				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						var jordan = value.questionnaire_answer;
						labels_answer.push(jordan);
						incident_report_answer += parseInt(value.tenant_survey);
						data_value_answer.push(value.percentage_share);
						if (value.questionnaire == 'CLEANLINESS') {
							randomBackgroundColor.push(cleanliness);
						} else if (value.questionnaire == 'SUPPLIES') {
							randomBackgroundColor.push(supplies);
						} else {
							randomBackgroundColor.push(functionality);
						}

					});
				}
				else {
					labels_answer = ['Empty']
					data_value_answer = [1];
					randomBackgroundColor = [cleanliness];
				}

				var donutData_answer = {
					labels: labels_answer,
					datasets: [
						{
							data: data_value_answer,
							backgroundColor: randomBackgroundColor,
						}
					]
				}

				var pieChartSurveyCanvas_answer = $('#pieChartSurveyAnswer').get(0).getContext('2d')
				var pieData_answer = donutData_answer;
				var pieOptions_answer = {
					maintainAspectRatio: false,
					responsive: true,
					plugins: {
						labels: [
							{
								render: 'label',
								position: 'outside'
							},
							{
								render: 'percentage'
							}
						],


					},
					legend: {
						display: false,
					},
				}
				if (window.doughnut_chart_answer != undefined)
					window.doughnut_chart_answer.destroy();

				window.doughnut_chart_answer = new Chart(pieChartSurveyCanvas_answer, {
					//new Chart(pieChartSurveyCanvas_answer, {
					type: 'pie',
					data: pieData_answer,
					options: pieOptions_answer
				})
			});
			$.get("/admin/dashboard/average-time-by-daily/list", filter, function (data) {
				console.log(data.data);
				$('#average_time').text(data.data);
			});
			$.get("/admin/dashboard/total-sms-by-daily/list", filter, function (data) {
				console.log(data.data);
				$('#total_sms').text(data.data);
			});
		},

		filterChartByDay: function () { //alert('hellop');
			var filter = this.filter;
			filter.week = '';
			filter.month = '';
			filter.year = '';
			const currentDay = moment(new Date()).format("YYYY-MM-DD");
			filter.day = (filter.day == '' || filter.day == null) ? currentDay : filter.day;

			this.filter.day = filter.day; //alert(this.filter.day);
			console.log('filterChartByDay D ' + filter.day + ' W ' + filter.week + ' M ' + filter.month + ' Y ' + filter.year);
			$.get("/admin/dashboard/trend-report-by-day/list", filter, function (data) {
				let datasetsz = [];
				var yValues = [];

				//let dynamicColorsz = ['#FE5E80', '#899AE8', '#353535', '#a9b7d8', '#a59fa2', '#f79fba', '#727272', '#191970', '#A0CFEC', '#D5D6EA', '#50C878', '#6B8E23', '#556B2F', '#FFFFC2', '#B5A642', '#513B1C', '#CB6D51', '#CC7A8B', '#FFDFDD', '#B048B5', '#F8F0E3', '#EAEEE9', '#D891EF'];


				$.each(data.data, function (key, value) {
					let background_colorz = value.building_color;
					yValues.push(value.reports);
					datasetsz.push({
						label: value.building_name + '(Report: ' + value.reports + ')',
						backgroundColor: background_colorz,
						borderColor: background_colorz,
						pointRadius: false,
						pointColor: '#3b8bba',
						pointStrokeColor: background_colorz,
						pointHighlightFill: '#fff',
						pointHighlightStroke: background_colorz,
						data: [value.twentyfour, value.one, value.two, value.three, value.four, value.five, value.six, value.seven, value.eight, value.nine, value.ten, value.eleven, value.twelve, value.thirteen, value.forteen, value.fifteen, value.sixteen, value.seventeen, value.eighteen, value.nineteen, value.twenty, value.twentyone, value.twentytwo, value.twentythree]
					});
				});
				let sum_reports_total = 0;

				// calculate sum using forEach() method
				yValues.forEach(num => {
					sum_reports_total += num;
				})

				this.reports_total = sum_reports_total;
				$('#reports_total').text(sum_reports_total);


				var areaChartDataz = {
					labels: ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'],
					datasets: datasetsz
				};

				var barChartDataz = $.extend(true, {}, areaChartDataz);

				var reportBarChartCanvasz = $('#reportBarChart').get(0).getContext('2d')
				var reportBarChartDataz = $.extend(true, {}, barChartDataz)

				var reportBarChartOptionsz = {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					},
					plugins: {
						labels: {
							render: 'value'
						}
					}
				}
				if (window.report_bar != undefined)
					window.report_bar.destroy();
				window.report_bar = new Chart(reportBarChartCanvasz, {
					//new Chart(reportBarChartCanvasz, {
					type: 'bar',
					data: reportBarChartDataz,
					options: reportBarChartOptionsz
				})

			});

			$.get("/admin/dashboard/trend-incident-by-day/list", filter, function (data) {
				let datasetsz = [];
				var yValues = [];

				let dynamicColorsz = ['#FE5E80', '#899AE8', '#353535', '#a9b7d8', '#a59fa2', '#f79fba', '#727272', '#191970', '#A0CFEC', '#D5D6EA', '#50C878', '#6B8E23', '#556B2F', '#FFFFC2', '#B5A642', '#513B1C', '#CB6D51', '#CC7A8B', '#FFDFDD', '#B048B5', '#F8F0E3', '#EAEEE9', '#D891EF'];


				$.each(data.data, function (key, value) {
					let background_colorz = value.building_color;
					yValues.push(value.reports);
					datasetsz.push({
						label: value.building_name + '(Incident: ' + value.reports + ')',
						backgroundColor: background_colorz,
						borderColor: background_colorz,
						pointRadius: false,
						pointColor: '#3b8bba',
						pointStrokeColor: background_colorz,
						pointHighlightFill: '#fff',
						pointHighlightStroke: background_colorz,
						data: [value.twentyfour, value.one, value.two, value.three, value.four, value.five, value.six, value.seven, value.eight, value.nine, value.ten, value.eleven, value.twelve, value.thirteen, value.forteen, value.fifteen, value.sixteen, value.seventeen, value.eighteen, value.nineteen, value.twenty, value.twentyone, value.twentytwo, value.twentythree]
					});
				});
				let sum_incidents_total = 0;

				// calculate sum using forEach() method
				yValues.forEach(num => {
					sum_incidents_total += num;
				})

				this.reports_total = sum_incidents_total;
				$('#incidents_total').text(sum_incidents_total);

				var areaChartDataz = {
					labels: ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'],
					datasets: datasetsz
				};

				var barChartDataz = $.extend(true, {}, areaChartDataz);

				var reportBarChartCanvasz = $('#incidentBarChart').get(0).getContext('2d')
				var reportBarChartDataz = $.extend(true, {}, barChartDataz)

				var reportBarChartOptionsz = {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					},
					plugins: {
						labels: {
							render: 'value'
						}
					}
				}
				if (window.incident_bar != undefined)
					window.incident_bar.destroy();
				//if(bar) bar.destroy();
				window.incident_bar = new Chart(reportBarChartCanvasz, {
					//new Chart(reportBarChartCanvasz, {
					type: 'bar',
					data: reportBarChartDataz,
					options: reportBarChartOptionsz
				})

			});

			$.get("/admin/reports/donut-report-by-day/list", filter, function (data) {
				let labels = [];
				let colors = [];
				let data_value = [];
				let incident_report = 0;
				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						labels.push(value.questionnaire);
						colors.push(value.questionnaire_color);
						incident_report += parseInt(value.tenant_survey);
						data_value.push(value.percentage_share);
					});
					// console.log(labels);
				}
				else {
					labels = ['Empty']
					data_value = [1];
				}

				var donutData = {
					labels: labels,
					datasets: [
						{
							data: data_value,
							backgroundColor: colors,//['#728FCE', '#90EE90', '#FED8B1'],
						}
					]
				}
				var cleanliness = '#728FCE';
				var supplies = '#90EE90';
				var functionality = '#FED8B1';

				var pieChartSurveyCanvas = $('#pieChartSurvey').get(0).getContext('2d')
				var pieData = donutData;
				var pieOptions = {
					maintainAspectRatio: false,
					responsive: true,
					inGraphDataShow: true,
					inGraphDataRadiusPosition: 2,
					inGraphDataFontColor: 'white'
				}
				if (window.doughnut_chart != undefined)
					window.doughnut_chart.destroy();

				window.doughnut_chart = new Chart(pieChartSurveyCanvas, {
					//var myChart = new Chart(pieChartSurveyCanvas, {
					type: 'doughnut',
					data: pieData,
					plugins: [{
						beforeDraw: function (chart) {
							var width = chart.chart.width,
								height = chart.chart.height,
								ctx = chart.chart.ctx;

							ctx.restore();
							var fontSize = 1.5;
							ctx.font = fontSize + "em sans-serif";
							ctx.textBaseline = "middle";

							var text = incident_report,
								textX = Math.round((width - ctx.measureText(text).width) / 2),
								textY = height / 2;

							ctx.fillText(text, textX, textY);

							ctx.restore();
							var fontSize = 1;
							ctx.font = fontSize + "em sans-serif";
							ctx.textBaseline = "middle";

							ctx.fillText("INCIDENTS", (textX - 35), textY + 35);

							ctx.save();
						}
					}],
					options: {
						pieOptions,
						events: ["mousemove", "mouseout", "click", "touchstart", "touchmove", "touchend"],
					}
				});
			});

			$.get("/admin/reports/donut-report-by-day-answer/list", filter, function (data) {
				let labels_answer = [];
				let color_answers = [];
				let data_value_answer = [];
				let incident_report_answer = 0;
				let randomBackgroundColor = [];
				var cleanliness = '#728FCE';
				var supplies = '#90EE90';
				var functionality = '#FED8B1';


				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						var jordan = value.questionnaire_answer;
						var color = value.questionnaire_color;
						labels_answer.push(jordan);
						color_answers.push(color);
						//console.log(value.questionnaire);
						//labels_answer.push(value.questionnaire);
						//data_value.push(1);
						// incident_report.push(value.tenant_survey);
						incident_report_answer += parseInt(value.tenant_survey);
						data_value_answer.push(value.percentage_share);
						if (value.questionnaire == 'CLEANLINESS') {
							randomBackgroundColor.push(cleanliness);
						} else if (value.questionnaire == 'SUPPLIES') {
							randomBackgroundColor.push(supplies);
						} else {
							randomBackgroundColor.push(functionality);
						}

					});
				}
				else {
					labels_answer = ['Empty']
					data_value_answer = [1];
					randomBackgroundColor = color_answers;//[cleanliness];
				}
				//console.log();
				var donutData_answer = {
					labels: labels_answer,
					datasets: [
						{
							data: data_value_answer,
							backgroundColor: randomBackgroundColor,
						}
					]
				}

				var pieChartSurveyCanvas_answer = $('#pieChartSurveyAnswer').get(0).getContext('2d')
				var pieData_answer = donutData_answer;
				var pieOptions_answer = {
					maintainAspectRatio: false,
					responsive: true,
					plugins: {
						labels: [
							{
								render: 'label',
								position: 'outside'
							},
							{
								render: 'percentage'
							}
						],


					},
					legend: {
						display: false,
					},
				}
				if (window.doughnut_chart_answer != undefined)
					window.doughnut_chart_answer.destroy();

				window.doughnut_chart_answer = new Chart(pieChartSurveyCanvas_answer, {
					//new Chart(pieChartSurveyCanvas_answer, {
					type: 'pie',
					data: pieData_answer,
					options: pieOptions_answer
				})
			});
			$.get("/admin/dashboard/average-time-by-day/list", filter, function (data) {
				console.log(data.data);
				$('#average_time').text(data.data);
			});
			$.get("/admin/dashboard/total-sms-by-day/list", filter, function (data) {
				console.log(data.data);
				$('#total_sms').text(data.data);
			});
		},

		// filterChartByWeek: function () {
		// 	var obj = this;
		// 	var filter = this.filter;

		// 	$.get("/admin/dashboard/trend-report-by-week/list", filter, function (data) {
		// 		let datasets = [];
		// 		var yValues = [];
		// 		let dynamicColors = ['#FE5E80', '#899AE8', '#353535', '#a9b7d8', '#a59fa2', '#f79fba', '#727272'];
		// 		$.each(data.data, function (key, value) {
		// 			let background_color = value.building_color;
		// 			yValues.push(value.reports);
		// 			datasets.push({
		// 				label: value.building_name + '(Report(s): ' + value.reports + ')',
		// 				backgroundColor: background_color,
		// 				borderColor: background_color,
		// 				pointRadius: false,
		// 				pointColor: '#3b8bba',
		// 				pointStrokeColor: background_color,
		// 				pointHighlightFill: '#fff',
		// 				pointHighlightStroke: background_color,
		// 				data: [value.mon, value.tue, value.wed, value.thu, value.fri, value.sat, value.sun]
		// 			});
		// 		});
		// 		let sum_reports_total = 0;

		// 		// calculate sum using forEach() method
		// 		yValues.forEach(num => {
		// 			sum_reports_total += num;
		// 		})
		// 		this.reports_total = sum_reports_total;
		// 		$('#reports_total').text(sum_reports_total);
		// 		var sun = obj.setToDate(new Date(obj.filter.week), 0);
		// 		var mon = obj.setToDate(new Date(obj.filter.week), 1);
		// 		var tue = obj.setToDate(new Date(obj.filter.week), 2);
		// 		var wed = obj.setToDate(new Date(obj.filter.week), 3);
		// 		var thu = obj.setToDate(new Date(obj.filter.week), 4);
		// 		var fri = obj.setToDate(new Date(obj.filter.week), 5);
		// 		var sat = obj.setToDate(new Date(obj.filter.week), 6);

		// 		let aLabels = [sun, mon, tue, wed, thu, fri, sat];

		// 		var areaChartData = {
		// 			labels: aLabels,
		// 			datasets: datasets
		// 		};

		// 		var barChartData = $.extend(true, {}, areaChartData);

		// 		var reportBarChartCanvas = $('#reportBarChart').get(0).getContext('2d')
		// 		var reportBarChartData = $.extend(true, {}, barChartData)

		// 		var reportBarChartOptions = {
		// 			responsive: true,
		// 			maintainAspectRatio: false,
		// 			scales: {
		// 				xAxes: [{
		// 					stacked: true,
		// 				}],
		// 				yAxes: [{
		// 					stacked: true
		// 				}]
		// 			},
		// 			plugins: {
		// 				labels: {
		// 					render: 'value'
		// 				}
		// 			}
		// 		}
		// 		if (window.report_bar != undefined)
		// 			window.report_bar.destroy();
		// 		//if(bar) bar.destroy();
		// 		window.report_bar = new Chart(reportBarChartCanvas, {
		// 			//new Chart(reportBarChartCanvas, {
		// 			type: 'bar',
		// 			data: reportBarChartData,
		// 			options: reportBarChartOptions
		// 		})

		// 	});

		// 	$.get("/admin/dashboard/trend-incident-by-week/list", filter, function (data) {
		// 		let datasetsz = [];
		// 		var yValues = [];

		// 		let dynamicColorsz = ['#FE5E80', '#899AE8', '#353535', '#a9b7d8', '#a59fa2', '#f79fba', '#727272'];


		// 		$.each(data.data, function (key, value) {
		// 			let background_colorz = dynamicColorsz[key];
		// 			yValues.push(value.reports);
		// 			datasetsz.push({
		// 				label: value.building_name + '(Incident: ' + value.reports + ')',
		// 				backgroundColor: background_colorz,
		// 				borderColor: background_colorz,
		// 				pointRadius: false,
		// 				pointColor: '#3b8bba',
		// 				pointStrokeColor: background_colorz,
		// 				pointHighlightFill: '#fff',
		// 				pointHighlightStroke: background_colorz,
		// 				data: [value.mon, value.tue, value.wed, value.thu, value.fri]
		// 			});
		// 		});
		// 		let sum_incidents_total = 0;

		// 		// calculate sum using forEach() method
		// 		yValues.forEach(num => {
		// 			sum_incidents_total += num;
		// 		})
		// 		this.reports_total = sum_incidents_total;
		// 		$('#incidents_total').text(sum_incidents_total);
		// 		var sun = obj.setToDate(new Date(obj.filter.week), 0);
		// 		var mon = obj.setToDate(new Date(obj.filter.week), 1);
		// 		var tue = obj.setToDate(new Date(obj.filter.week), 2);
		// 		var wed = obj.setToDate(new Date(obj.filter.week), 3);
		// 		var thu = obj.setToDate(new Date(obj.filter.week), 4);
		// 		var fri = obj.setToDate(new Date(obj.filter.week), 5);
		// 		var sat = obj.setToDate(new Date(obj.filter.week), 6);

		// 		let aLabels = [sun, mon, tue, wed, thu, fri, sat];

		// 		var areaChartDataz = {
		// 			labels: aLabels,
		// 			datasets: datasetsz
		// 		};

		// 		var barChartDataz = $.extend(true, {}, areaChartDataz);

		// 		var reportBarChartCanvasz = $('#incidentBarChart').get(0).getContext('2d')
		// 		var reportBarChartDataz = $.extend(true, {}, barChartDataz)

		// 		var reportBarChartOptionsz = {
		// 			responsive: true,
		// 			maintainAspectRatio: false,
		// 			scales: {
		// 				xAxes: [{
		// 					stacked: true,
		// 				}],
		// 				yAxes: [{
		// 					stacked: true
		// 				}]
		// 			},
		// 			plugins: {
		// 				labels: {
		// 					render: 'value'
		// 				}
		// 			}
		// 		}
		// 		if (window.incident_bar != undefined)
		// 			window.incident_bar.destroy();
		// 		//if(bar) bar.destroy();
		// 		window.incident_bar = new Chart(reportBarChartCanvasz, {
		// 			//new Chart(reportBarChartCanvasz, {
		// 			type: 'bar',
		// 			data: reportBarChartDataz,
		// 			options: reportBarChartOptionsz
		// 		})

		// 	});

		// 	$.get("/admin/reports/donut-report-by-day/list", filter, function (data) {
		// 		let labels = [];
		// 		let colors = [];
		// 		let data_value = [];
		// 		let incident_report = 0;
		// 		if (data.data.length > 0) {
		// 			$.each(data.data, function (key, value) {
		// 				labels.push(value.questionnaire);
		// 				colors.push(value.questionnaire_color);
		// 				incident_report += parseInt(value.tenant_survey);
		// 				data_value.push(value.percentage_share);
		// 			});
		// 			// console.log(labels);
		// 		}
		// 		else {
		// 			labels = ['Empty']
		// 			data_value = [1];
		// 		}

		// 		var donutData = {
		// 			labels: labels,
		// 			datasets: [
		// 				{
		// 					data: data_value,
		// 					backgroundColor: colors,//['#728FCE', '#90EE90', '#FED8B1'],
		// 				}
		// 			]
		// 		}
		// 		var cleanliness = '#728FCE';
		// 		var supplies = '#90EE90';
		// 		var functionality = '#FED8B1';

		// 		var pieChartSurveyCanvas = $('#pieChartSurvey').get(0).getContext('2d')
		// 		var pieData = donutData;
		// 		var pieOptions = {
		// 			maintainAspectRatio: false,
		// 			responsive: true,
		// 			inGraphDataShow: true,
		// 			inGraphDataRadiusPosition: 2,
		// 			inGraphDataFontColor: 'white'
		// 		}
		// 		if (window.doughnut_chart != undefined)
		// 			window.doughnut_chart.destroy();

		// 		window.doughnut_chart = new Chart(pieChartSurveyCanvas, {
		// 			//var myChart = new Chart(pieChartSurveyCanvas, {
		// 			type: 'doughnut',
		// 			data: pieData,
		// 			plugins: [{
		// 				beforeDraw: function (chart) {
		// 					var width = chart.chart.width,
		// 						height = chart.chart.height,
		// 						ctx = chart.chart.ctx;

		// 					ctx.restore();
		// 					var fontSize = 1.5;
		// 					ctx.font = fontSize + "em sans-serif";
		// 					ctx.textBaseline = "middle";

		// 					var text = incident_report,
		// 						textX = Math.round((width - ctx.measureText(text).width) / 2),
		// 						textY = height / 2;

		// 					ctx.fillText(text, textX, textY);

		// 					ctx.restore();
		// 					var fontSize = 1;
		// 					ctx.font = fontSize + "em sans-serif";
		// 					ctx.textBaseline = "middle";

		// 					ctx.fillText("INCIDENTS", (textX - 35), textY + 35);

		// 					ctx.save();
		// 				}
		// 			}],
		// 			options: {
		// 				pieOptions,
		// 				events: ["mousemove", "mouseout", "click", "touchstart", "touchmove", "touchend"],
		// 			}
		// 		});
		// 	});

		// 	$.get("/admin/reports/donut-report-by-day-answer/list", filter, function (data) {
		// 		let labels_answer = [];
		// 		let color_answers = [];
		// 		let data_value_answer = [];
		// 		let incident_report_answer = 0;
		// 		let randomBackgroundColor = [];
		// 		var cleanliness = '#728FCE';
		// 		var supplies = '#90EE90';
		// 		var functionality = '#FED8B1';


		// 		if (data.data.length > 0) {
		// 			$.each(data.data, function (key, value) {
		// 				var jordan = value.questionnaire_answer;
		// 				var color = value.questionnaire_color;
		// 				labels_answer.push(jordan);
		// 				color_answers.push(color);
		// 				//console.log(value.questionnaire);
		// 				//labels_answer.push(value.questionnaire);
		// 				//data_value.push(1);
		// 				// incident_report.push(value.tenant_survey);
		// 				incident_report_answer += parseInt(value.tenant_survey);
		// 				data_value_answer.push(value.percentage_share);
		// 				if (value.questionnaire == 'CLEANLINESS') {
		// 					randomBackgroundColor.push(cleanliness);
		// 				} else if (value.questionnaire == 'SUPPLIES') {
		// 					randomBackgroundColor.push(supplies);
		// 				} else {
		// 					randomBackgroundColor.push(functionality);
		// 				}

		// 			});
		// 		}
		// 		else {
		// 			labels_answer = ['Empty']
		// 			data_value_answer = [1];
		// 			randomBackgroundColor = color_answers;//[cleanliness];
		// 		}
		// 		//console.log();
		// 		var donutData_answer = {
		// 			labels: labels_answer,
		// 			datasets: [
		// 				{
		// 					data: data_value_answer,
		// 					backgroundColor: randomBackgroundColor,
		// 				}
		// 			]
		// 		}

		// 		var pieChartSurveyCanvas_answer = $('#pieChartSurveyAnswer').get(0).getContext('2d')
		// 		var pieData_answer = donutData_answer;
		// 		var pieOptions_answer = {
		// 			maintainAspectRatio: false,
		// 			responsive: true,
		// 			plugins: {
		// 				labels: [
		// 					{
		// 						render: 'label',
		// 						position: 'outside'
		// 					},
		// 					{
		// 						render: 'percentage'
		// 					}
		// 				],


		// 			},
		// 			legend: {
		// 				display: false,
		// 			},
		// 		}
		// 		if (window.doughnut_chart_answer != undefined)
		// 			window.doughnut_chart_answer.destroy();

		// 		window.doughnut_chart_answer = new Chart(pieChartSurveyCanvas_answer, {
		// 			//new Chart(pieChartSurveyCanvas_answer, {
		// 			type: 'pie',
		// 			data: pieData_answer,
		// 			options: pieOptions_answer
		// 		})
		// 	});

		// 	$.get("/admin/dashboard/average-time-by-week/list", filter, function (data) {
		// 		console.log(data.data);
		// 		$('#average_time').text(parseFloat(data.data));
		// 	});
		// 	$.get("/admin/dashboard/total-sms-by-week/list", filter, function (data) {
		// 		console.log(data.data);
		// 		$('#total_sms').text(parseFloat(data.data));
		// 	});
		// },
		filterChartByWeek: function () {
			var obj = this;
			var filter = this.filter;
			filter.day = '';
			filter.month = '';
			filter.year = '';
			console.log('week D ' + filter.day + ' W ' + filter.week + ' M ' + filter.month + ' Y ' + filter.year);
			const currentDay = moment(new Date()).format("YYYY-MM-DD");
			filter.week = (filter.week == '') ? currentDay : filter.week;
			this.filter.week = filter.week;
			$.get("/admin/dashboard/trend-report-by-week/list", filter, function (data) {
				console.log('trend-report-by-week');
				let datasets = [];
				var yValues = [];
				//let dynamicColors = ['#FE5E80', '#899AE8', '#353535', '#a9b7d8', '#a59fa2', '#f79fba', '#727272'];
				console.log('>>>>>xxxxxx>>>>>>>');console.log(data.data.legend);console.log('<<<<<<vvvvvv<<<<<');
				$('#report_legend').html(data.data.legend);
				$.each(data.data, function (key, value) {
					if (key != 'legend') {
						let background_color = value.building_color;//dynamicColors[key];
						yValues.push(value.reports);
						datasets.push({
							label: value.building_name + '(Report(s): ' + value.reports + ')',
							//label: '',
							backgroundColor: background_color,
							borderColor: background_color,
							pointRadius: false,
							pointColor: '#3b8bba',
							pointStrokeColor: background_color,
							pointHighlightFill: '#fff',
							pointHighlightStroke: background_color,
							data: [value.sun, value.mon, value.tue, value.wed, value.thu, value.fri, value.sat]
						});
					}

				});
				let sum_reports_total = 0;

				// calculate sum using forEach() method
				yValues.forEach(num => {
					sum_reports_total += num;
				})
				this.reports_total = sum_reports_total;
				$('#reports_total').text(sum_reports_total);
				var sun = obj.setToDate(new Date(obj.filter.week), 0);
				var mon = obj.setToDate(new Date(obj.filter.week), 1);
				var tue = obj.setToDate(new Date(obj.filter.week), 2);
				var wed = obj.setToDate(new Date(obj.filter.week), 3);
				var thu = obj.setToDate(new Date(obj.filter.week), 4);
				var fri = obj.setToDate(new Date(obj.filter.week), 5);
				var sat = obj.setToDate(new Date(obj.filter.week), 6);

				let aLabels = [sun, mon, tue, wed, thu, fri, sat];
				var areaChartData = {
					labels: aLabels,
					datasets: datasets
				};

				var barChartData = $.extend(true, {}, areaChartData);

				var reportBarChartCanvas = $('#reportBarChart').get(0).getContext('2d')
				var reportBarChartData = $.extend(true, {}, barChartData)

				var reportBarChartOptions = {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					},
					plugins: {
						labels: {
							render: 'value'
						}
					},
					legend: {
						display: false
					},
				}
				if (window.report_bar != undefined)
					window.report_bar.destroy();
				//if(bar) bar.destroy();
				window.report_bar = new Chart(reportBarChartCanvas, {
					//new Chart(reportBarChartCanvas, {
					type: 'bar',
					data: reportBarChartData,
					options: reportBarChartOptions
				})
			});

			$.get("/admin/dashboard/trend-incident-by-week/list", filter, function (data) {
				console.log('trend-incident-by-week'); console.log(filter);
				let datasetsz = [];
				var yValues = [];

				let dynamicColorsz = ['#FE5E80', '#899AE8', '#353535', '#a9b7d8', '#a59fa2', '#f79fba', '#727272'];


				$('#incident_legend').html(data.data.legend);
				$.each(data.data, function (key, value) {
					if (key != 'legend') {
						let background_color = value.building_color;//dynamicColors[key];
						yValues.push(value.reports);
						datasetsz.push({
							label: value.building_name + '(Incident(s): ' + value.reports + ')',
							//label: '',
							backgroundColor: background_color,
							borderColor: background_color,
							pointRadius: false,
							pointColor: '#3b8bba',
							pointStrokeColor: background_color,
							pointHighlightFill: '#fff',
							pointHighlightStroke: background_color,
							data: [value.sun, value.mon, value.tue, value.wed, value.thu, value.fri, value.sat]
						});
					}

				});
				let sum_incidents_total = 0;

				// calculate sum using forEach() method
				yValues.forEach(num => {
					sum_incidents_total += num;
				})
				this.reports_total = sum_incidents_total;
				$('#incidents_total').text(sum_incidents_total);


				var sun = obj.setToDate(new Date(obj.filter.week), 0);
				var mon = obj.setToDate(new Date(obj.filter.week), 1);
				var tue = obj.setToDate(new Date(obj.filter.week), 2);
				var wed = obj.setToDate(new Date(obj.filter.week), 3);
				var thu = obj.setToDate(new Date(obj.filter.week), 4);
				var fri = obj.setToDate(new Date(obj.filter.week), 5);
				var sat = obj.setToDate(new Date(obj.filter.week), 6);

				let aLabels = [sun, mon, tue, wed, thu, fri, sat];

				var areaChartDataz = {
					labels: aLabels,
					datasets: datasetsz
				};

				var barChartDataz = $.extend(true, {}, areaChartDataz);

				var reportBarChartCanvasz = $('#incidentBarChart').get(0).getContext('2d')
				var reportBarChartDataz = $.extend(true, {}, barChartDataz)

				var reportBarChartOptionsz = {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					},
					plugins: {
						labels: {
							render: 'value'
						}
					},
					legend: {
						display: false
					},
				}
				if (window.incident_bar != undefined)
					window.incident_bar.destroy();
				//if(bar) bar.destroy();
				window.incident_bar = new Chart(reportBarChartCanvasz, {
					//new Chart(reportBarChartCanvasz, {
					type: 'bar',
					data: reportBarChartDataz,
					options: reportBarChartOptionsz
				})

			});

			$.get("/admin/dashboard/donut-report-by-day/list", filter, function (data) {
				let labels = [];
				let colors = [];
				let data_value = [];
				let incident_report = 0;
				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						labels.push(value.questionnaire);
						colors.push(value.questionnaire_color);
						incident_report += parseInt(value.tenant_survey);
						data_value.push(value.percentage_share);
					});
					// console.log(labels);
				}
				else {
					labels = ['Empty']
					data_value = [1];
				}

				var donutData = {
					labels: labels,
					datasets: [
						{
							data: data_value,
							backgroundColor: colors,//['#728FCE', '#90EE90', '#FED8B1'],
						}
					]
				}
				var cleanliness = '#728FCE';
				var supplies = '#90EE90';
				var functionality = '#FED8B1';

				var pieChartSurveyCanvas = $('#pieChartSurvey').get(0).getContext('2d')
				var pieData = donutData;
				var pieOptions = {
					maintainAspectRatio: false,
					responsive: true,
					inGraphDataShow: true,
					inGraphDataRadiusPosition: 2,
					inGraphDataFontColor: 'white'
				}
				if (window.doughnut_chart != undefined)
					window.doughnut_chart.destroy();

				window.doughnut_chart = new Chart(pieChartSurveyCanvas, {
					//var myChart = new Chart(pieChartSurveyCanvas, {
					type: 'doughnut',
					data: pieData,
					plugins: [{
						beforeDraw: function (chart) {
							var width = chart.chart.width,
								height = chart.chart.height,
								ctx = chart.chart.ctx;

							ctx.restore();
							var fontSize = 1.5;
							ctx.font = fontSize + "em sans-serif";
							ctx.textBaseline = "middle";

							var text = incident_report,
								textX = Math.round((width - ctx.measureText(text).width) / 2),
								textY = height / 2;

							ctx.fillText(text, textX, textY);

							ctx.restore();
							var fontSize = 1;
							ctx.font = fontSize + "em sans-serif";
							ctx.textBaseline = "middle";

							ctx.fillText("INCIDENTS", (textX - 35), textY + 35);

							ctx.save();
						}
					}],
					options: {
						pieOptions,
						events: ["mousemove", "mouseout", "click", "touchstart", "touchmove", "touchend"],
					}
				});
			});

			$.get("/admin/dashboard/donut-report-by-day-answer/list", filter, function (data) {
				let labels_answer = [];
				let color_answers = [];
				let data_value_answer = [];
				let incident_report_answer = 0;
				let randomBackgroundColor = [];
				var cleanliness = '#728FCE';
				var supplies = '#90EE90';
				var functionality = '#FED8B1';


				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						var jordan = value.questionnaire_answer;
						var color = value.questionnaire_color;
						labels_answer.push(jordan);
						color_answers.push(color);
						//console.log(value.questionnaire);
						//labels_answer.push(value.questionnaire);
						//data_value.push(1);
						// incident_report.push(value.tenant_survey);
						incident_report_answer += parseInt(value.tenant_survey);
						data_value_answer.push(value.percentage_share);
						if (value.questionnaire == 'CLEANLINESS') {
							randomBackgroundColor.push(cleanliness);
						} else if (value.questionnaire == 'SUPPLIES') {
							randomBackgroundColor.push(supplies);
						} else {
							randomBackgroundColor.push(functionality);
						}

					});
				}
				else {
					labels_answer = ['Empty']
					data_value_answer = [1];
					randomBackgroundColor = color_answers;//[cleanliness];
				}
				//console.log();
				var donutData_answer = {
					labels: labels_answer,
					datasets: [
						{
							data: data_value_answer,
							backgroundColor: randomBackgroundColor,
						}
					]
				}

				var pieChartSurveyCanvas_answer = $('#pieChartSurveyAnswer').get(0).getContext('2d')
				var pieData_answer = donutData_answer;
				var pieOptions_answer = {
					maintainAspectRatio: false,
					responsive: true,
					plugins: {
						labels: [
							{
								render: 'label',
								position: 'outside'
							},
							{
								render: 'percentage'
							}
						],


					},
					legend: {
						display: false,
					},
				}
				if (window.doughnut_chart_answer != undefined)
					window.doughnut_chart_answer.destroy();

				window.doughnut_chart_answer = new Chart(pieChartSurveyCanvas_answer, {
					//new Chart(pieChartSurveyCanvas_answer, {
					type: 'pie',
					data: pieData_answer,
					options: pieOptions_answer
				})
			});
			$.get("/admin/dashboard/average-time-by-week/list", filter, function (data) {
				console.log(data.data);
				$('#average_time').text(parseFloat(data.data));
			});
			$.get("/admin/dashboard/total-sms-by-week/list", filter, function (data) {
				console.log(data.data);
				$('#total_sms').text(parseFloat(data.data));
			});
		},	
		filterChartByMonth: function () {

			var filter = this.filter;
			filter.day = '';
			filter.week = '';
			filter.year = '';
			const currentMonth = moment(new Date()).format("YYYY-MM");
			filter.month = (filter.month == '') ? currentMonth : filter.month;
			$.get("/admin/dashboard/trend-report-by-month/list", filter, function (data) {
				let datasets = [];
				let week_range = [];
				var yValues = [];
				let dynamicColors = ['#FE5E80', '#899AE8', '#353535', '#a9b7d8', '#ff00cc', '#ff0000'];


				$.each(data.data[0], function (key, value) {  ///console.log('>>>>'); console.log(value);
					let background_color = value.building_color;
					yValues.push(value.reports);
					var bar = value.bar;
					//var week = value.week_range;
					datasets.push({
						label: value.building_name + '(Reports: ' + value.reports + ')',
						backgroundColor: background_color,
						borderColor: background_color,
						pointRadius: false,
						pointColor: '#3b8bba',
						pointStrokeColor: background_color,
						pointHighlightFill: '#fff',
						pointHighlightStroke: background_color,
						data: bar
					});
					//week_range.push(week);
				});
				$.each(data.data[1], function (key, value) {
					week_range.push(value);
				});

				let sum_reports_total = 0;

				// calculate sum using forEach() method
				yValues.forEach(num => {
					sum_reports_total += num;
				})
				this.reports_total = sum_reports_total;
				$('#reports_total').text(sum_reports_total);

				var areaChartData = {
					labels: week_range,
					datasets: datasets
				};

				var barChartData = $.extend(true, {}, areaChartData);

				var reportBarChartCanvas = $('#reportBarChart').get(0).getContext('2d')
				var reportBarChartData = $.extend(true, {}, barChartData)

				var reportBarChartOptions = {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					},
					plugins: {
						labels: {
							render: 'value'
						}
					}
				}
				if (window.report_bar != undefined)
					window.report_bar.destroy();
				//if(bar) bar.destroy();
				window.report_bar = new Chart(reportBarChartCanvas, {
					//new Chart(reportBarChartCanvas, {
					type: 'bar',
					data: reportBarChartData,
					options: reportBarChartOptions
				})
			});
			$.get("/admin/dashboard/trend-incident-by-month/list", filter, function (data) {
				let datasets = [];
				let week_range = [];
				var yValues = [];
				let dynamicColors = ['#FE5E80', '#899AE8', '#353535', '#a9b7d8', '#ff00cc', '#ff0000'];
				//console.log('>>>>data');  console.log(data.data); 
				$.each(data.data[0], function (key, value) {
					let background_color = value.building_color;
					yValues.push(value.reports);
					var bar = value.bar;
					var week = value.week_range;
					datasets.push({
						label: value.building_name + '(Incident(s): ' + value.reports + ')',
						backgroundColor: background_color,
						borderColor: background_color,
						pointRadius: false,
						pointColor: '#3b8bba',
						pointStrokeColor: background_color,
						pointHighlightFill: '#fff',
						pointHighlightStroke: background_color,
						data: bar
						//data: [value.week_one, value.week_two, value.week_three, value.week_four]
					});

					console.log('range:'); console.log(week_range);
					console.log(datasets);
				});
				$.each(data.data[1], function (key, value) {
					week_range.push(value);
				});
				let sum_incidents_total = 0;
				yValues.forEach(num => {
					sum_incidents_total += num;
				})
				this.reports_total = sum_incidents_total;
				$('#incidents_total').text(sum_incidents_total);

				var areaChartData = {
					labels: week_range,
					//labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5'],
					datasets: datasets
				};

				var barChartData = $.extend(true, {}, areaChartData);

				var reportBarChartCanvas = $('#incidentBarChart').get(0).getContext('2d')
				var reportBarChartData = $.extend(true, {}, barChartData)

				var reportBarChartOptions = {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					},
					plugins: {
						labels: {
							render: 'value'
						}
					}
				}
				if (window.incident_bar != undefined)
					window.incident_bar.destroy();
				//if(bar) bar.destroy();
				window.incident_bar = new Chart(reportBarChartCanvas, {
					//new Chart(reportBarChartCanvas, {
					type: 'bar',
					data: reportBarChartData,
					options: reportBarChartOptions
				})
			});

			$.get("/admin/reports/donut-report-by-day/list", filter, function (data) {
				let labels = [];
				let colors = [];
				let data_value = [];
				let incident_report = 0;
				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						labels.push(value.questionnaire);
						colors.push(value.questionnaire_color);
						incident_report += parseInt(value.tenant_survey);
						data_value.push(value.percentage_share);
					});
					// console.log(labels);
				}
				else {
					labels = ['Empty']
					data_value = [1];
				}

				var donutData = {
					labels: labels,
					datasets: [
						{
							data: data_value,
							backgroundColor: colors,//['#728FCE', '#90EE90', '#FED8B1'],
						}
					]
				}
				var cleanliness = '#728FCE';
				var supplies = '#90EE90';
				var functionality = '#FED8B1';

				var pieChartSurveyCanvas = $('#pieChartSurvey').get(0).getContext('2d')
				var pieData = donutData;
				var pieOptions = {
					maintainAspectRatio: false,
					responsive: true,
					inGraphDataShow: true,
					inGraphDataRadiusPosition: 2,
					inGraphDataFontColor: 'white'
				}
				if (window.doughnut_chart != undefined)
					window.doughnut_chart.destroy();

				window.doughnut_chart = new Chart(pieChartSurveyCanvas, {
					//var myChart = new Chart(pieChartSurveyCanvas, {
					type: 'doughnut',
					data: pieData,
					plugins: [{
						beforeDraw: function (chart) {
							var width = chart.chart.width,
								height = chart.chart.height,
								ctx = chart.chart.ctx;

							ctx.restore();
							var fontSize = 1.5;
							ctx.font = fontSize + "em sans-serif";
							ctx.textBaseline = "middle";

							var text = incident_report,
								textX = Math.round((width - ctx.measureText(text).width) / 2),
								textY = height / 2;

							ctx.fillText(text, textX, textY);

							ctx.restore();
							var fontSize = 1;
							ctx.font = fontSize + "em sans-serif";
							ctx.textBaseline = "middle";

							ctx.fillText("INCIDENTS", (textX - 35), textY + 35);

							ctx.save();
						}
					}],
					options: {
						pieOptions,
						events: ["mousemove", "mouseout", "click", "touchstart", "touchmove", "touchend"],
					}
				});
			});

			$.get("/admin/reports/donut-report-by-day-answer/list", filter, function (data) {
				let labels_answer = [];
				let color_answers = [];
				let data_value_answer = [];
				let incident_report_answer = 0;
				let randomBackgroundColor = [];
				var cleanliness = '#728FCE';
				var supplies = '#90EE90';
				var functionality = '#FED8B1';


				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						var jordan = value.questionnaire_answer;
						var color = value.questionnaire_color;
						labels_answer.push(jordan);
						color_answers.push(color);
						//console.log(value.questionnaire);
						//labels_answer.push(value.questionnaire);
						//data_value.push(1);
						// incident_report.push(value.tenant_survey);
						incident_report_answer += parseInt(value.tenant_survey);
						data_value_answer.push(value.percentage_share);
						if (value.questionnaire == 'CLEANLINESS') {
							randomBackgroundColor.push(cleanliness);
						} else if (value.questionnaire == 'SUPPLIES') {
							randomBackgroundColor.push(supplies);
						} else {
							randomBackgroundColor.push(functionality);
						}

					});
				}
				else {
					labels_answer = ['Empty']
					data_value_answer = [1];
					randomBackgroundColor = color_answers;//[cleanliness];
				}
				//console.log();
				var donutData_answer = {
					labels: labels_answer,
					datasets: [
						{
							data: data_value_answer,
							backgroundColor: randomBackgroundColor,
						}
					]
				}

				var pieChartSurveyCanvas_answer = $('#pieChartSurveyAnswer').get(0).getContext('2d')
				var pieData_answer = donutData_answer;
				var pieOptions_answer = {
					maintainAspectRatio: false,
					responsive: true,
					plugins: {
						labels: [
							{
								render: 'label',
								position: 'outside'
							},
							{
								render: 'percentage'
							}
						],


					},
					legend: {
						display: false,
					},
				}
				if (window.doughnut_chart_answer != undefined)
					window.doughnut_chart_answer.destroy();

				window.doughnut_chart_answer = new Chart(pieChartSurveyCanvas_answer, {
					//new Chart(pieChartSurveyCanvas_answer, {
					type: 'pie',
					data: pieData_answer,
					options: pieOptions_answer
				})
			});

			$.get("/admin/dashboard/average-time-by-month/list", filter, function (data) {
				console.log(data.data);
				$('#average_time').text(parseFloat(data.data));
			});
			$.get("/admin/dashboard/total-sms-by-month/list", filter, function (data) {
				console.log(data.data);
				$('#total_sms').text(parseFloat(data.data));
			});
		},
		filterChartByYear: function () {
			var filter = this.filter;
			filter.day = '';
			filter.week = '';
			filter.month = '';

			console.log('YEar D ' + filter.day + ' W ' + filter.week + ' M ' + filter.month + ' Y ' + filter.year);
			const currentYear = moment().year().toString();
			filter.year = (filter.year == '' || filter.year == null) ? currentYear : filter.year;

			this.filter.year = filter.year;

			$.get("/admin/dashboard/trend-report-by-year/list", filter, function (data) {
				let datasets = [];
				var yValues = [];
				let dynamicColors = ['#FE5E80', '#899AE8', '#353535', '#a9b7d8', '#00FF00', '#808000', '#FFA500', '#86608E', '#B666D2', '#F3E8EA', '#F5F5F5'];


				$.each(data.data, function (key, value) {
					let background_color = value.building_color;
					yValues.push(value.reports);
					datasets.push({
						label: value.building_name + '(Report(s): ' + value.reports + ')',
						backgroundColor: background_color,
						borderColor: background_color,
						pointRadius: false,
						pointColor: '#3b8bba',
						pointStrokeColor: background_color,
						pointHighlightFill: '#fff',
						pointHighlightStroke: background_color,
						data: [value.jan, value.feb, value.mar, value.apr, value.may, value.jun, value.jul, value.aug, value.sep, value.oct, value.nov, value.dec]
					});
				});
				let sum_reports_total = 0;

				// calculate sum using forEach() method
				yValues.forEach(num => {
					sum_reports_total += num;
				})
				this.reports_total = sum_reports_total;
				$('#reports_total').text(sum_reports_total);

				var areaChartData = {
					labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
					datasets: datasets
				};

				var barChartData = $.extend(true, {}, areaChartData);

				var reportBarChartCanvas = $('#reportBarChart').get(0).getContext('2d')
				var reportBarChartData = $.extend(true, {}, barChartData)

				var reportBarChartOptions = {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					},
					plugins: {
						labels: {
							render: 'value'
						}
					}
				}
				if (window.report_bar != undefined)
					window.report_bar.destroy();
				//if(bar) bar.destroy();
				window.report_bar = new Chart(reportBarChartCanvas, {
					//new Chart(reportBarChartCanvas, {
					type: 'bar',
					data: reportBarChartData,
					options: reportBarChartOptions
				})
			});
			$.get("/admin/dashboard/trend-incident-by-year/list", filter, function (data) {
				let datasets = [];
				var yValues = [];
				let dynamicColors = ['#FE5E80', '#899AE8', '#353535', '#a9b7d8', '#00FF00', '#808000', '#FFA500', '#86608E', '#B666D2', '#F3E8EA', '#F5F5F5'];


				$.each(data.data, function (key, value) {
					let background_color = value.building_color;
					yValues.push(value.reports);
					datasets.push({
						label: value.building_name + '(Incident(s): ' + value.reports + ')',
						backgroundColor: background_color,
						borderColor: background_color,
						pointRadius: false,
						pointColor: '#3b8bba',
						pointStrokeColor: background_color,
						pointHighlightFill: '#fff',
						pointHighlightStroke: background_color,
						data: [value.jan, value.feb, value.mar, value.apr, value.may, value.jun, value.jul, value.aug, value.sep, value.oct, value.nov, value.dec]
					});
				});

				let sum_incidents_total = 0;

				// calculate sum using forEach() method
				yValues.forEach(num => {
					sum_incidents_total += num;
				})
				this.incidents_total = sum_incidents_total;
				$('#incidents_total').text(sum_incidents_total);

				var areaChartData = {
					labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
					datasets: datasets
				};

				var barChartData = $.extend(true, {}, areaChartData);

				var reportBarChartCanvas = $('#incidentBarChart').get(0).getContext('2d')
				var reportBarChartData = $.extend(true, {}, barChartData)

				var reportBarChartOptions = {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					},
					plugins: {
						labels: {
							render: 'value'
						}
					}
				}
				if (window.incident_bar != undefined)
					window.incident_bar.destroy();
				//if(bar) bar.destroy();
				window.incident_bar = new Chart(reportBarChartCanvas, {
					//new Chart(reportBarChartCanvas, {
					type: 'bar',
					data: reportBarChartData,
					options: reportBarChartOptions
				})
			});


			$.get("/admin/reports/donut-report-by-day/list", filter, function (data) {
				let labels = [];
				let colors = [];
				let data_value = [];
				let incident_report = 0;
				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						labels.push(value.questionnaire);
						colors.push(value.questionnaire_color);
						incident_report += parseInt(value.tenant_survey);
						data_value.push(value.percentage_share);
					});
					// console.log(labels);
				}
				else {
					labels = ['Empty']
					data_value = [1];
				}

				var donutData = {
					labels: labels,
					datasets: [
						{
							data: data_value,
							backgroundColor: colors,//['#728FCE', '#90EE90', '#FED8B1'],
						}
					]
				}
				var cleanliness = '#728FCE';
				var supplies = '#90EE90';
				var functionality = '#FED8B1';

				var pieChartSurveyCanvas = $('#pieChartSurvey').get(0).getContext('2d')
				var pieData = donutData;
				var pieOptions = {
					maintainAspectRatio: false,
					responsive: true,
					inGraphDataShow: true,
					inGraphDataRadiusPosition: 2,
					inGraphDataFontColor: 'white'
				}
				if (window.doughnut_chart != undefined)
					window.doughnut_chart.destroy();

				window.doughnut_chart = new Chart(pieChartSurveyCanvas, {
					//var myChart = new Chart(pieChartSurveyCanvas, {
					type: 'doughnut',
					data: pieData,
					plugins: [{
						beforeDraw: function (chart) {
							var width = chart.chart.width,
								height = chart.chart.height,
								ctx = chart.chart.ctx;

							ctx.restore();
							var fontSize = 1.5;
							ctx.font = fontSize + "em sans-serif";
							ctx.textBaseline = "middle";

							var text = incident_report,
								textX = Math.round((width - ctx.measureText(text).width) / 2),
								textY = height / 2;

							ctx.fillText(text, textX, textY);

							ctx.restore();
							var fontSize = 1;
							ctx.font = fontSize + "em sans-serif";
							ctx.textBaseline = "middle";

							ctx.fillText("INCIDENTS", (textX - 35), textY + 35);

							ctx.save();
						}
					}],
					options: {
						pieOptions,
						events: ["mousemove", "mouseout", "click", "touchstart", "touchmove", "touchend"],
					}
				});
			});

			$.get("/admin/reports/donut-report-by-day-answer/list", filter, function (data) {
				let labels_answer = [];
				let color_answers = [];
				let data_value_answer = [];
				let incident_report_answer = 0;
				let randomBackgroundColor = [];
				var cleanliness = '#728FCE';
				var supplies = '#90EE90';
				var functionality = '#FED8B1';


				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						var jordan = value.questionnaire_answer;
						var color = value.questionnaire_color;
						labels_answer.push(jordan);
						color_answers.push(color);
						//console.log(value.questionnaire);
						//labels_answer.push(value.questionnaire);
						//data_value.push(1);
						// incident_report.push(value.tenant_survey);
						incident_report_answer += parseInt(value.tenant_survey);
						data_value_answer.push(value.percentage_share);
						if (value.questionnaire == 'CLEANLINESS') {
							randomBackgroundColor.push(cleanliness);
						} else if (value.questionnaire == 'SUPPLIES') {
							randomBackgroundColor.push(supplies);
						} else {
							randomBackgroundColor.push(functionality);
						}

					});
				}
				else {
					labels_answer = ['Empty']
					data_value_answer = [1];
					randomBackgroundColor = color_answers;//[cleanliness];
				}
				//console.log();
				var donutData_answer = {
					labels: labels_answer,
					datasets: [
						{
							data: data_value_answer,
							backgroundColor: randomBackgroundColor,
						}
					]
				}

				var pieChartSurveyCanvas_answer = $('#pieChartSurveyAnswer').get(0).getContext('2d')
				var pieData_answer = donutData_answer;
				var pieOptions_answer = {
					maintainAspectRatio: false,
					responsive: true,
					plugins: {
						labels: [
							{
								render: 'label',
								position: 'outside'
							},
							{
								render: 'percentage'
							}
						],


					},
					legend: {
						display: false,
					},
				}
				if (window.doughnut_chart_answer != undefined)
					window.doughnut_chart_answer.destroy();

				window.doughnut_chart_answer = new Chart(pieChartSurveyCanvas_answer, {
					//new Chart(pieChartSurveyCanvas_answer, {
					type: 'pie',
					data: pieData_answer,
					options: pieOptions_answer
				})
			});
			$.get("/admin/dashboard/average-time-by-year/list", filter, function (data) {
				console.log(data.data);
				$('#average_time').text(parseFloat(data.data));
			});
			$.get("/admin/dashboard/total-sms-by-year/list", filter, function (data) {
				console.log(data.data);
				$('#total_sms').text(parseFloat(data.data));
			});

		},
		filterChartByLifetime: function () {
			//console.log(this.filter);
			//alert('yearz');

			//alert(this.filter.lifetime);
			var filter = this.filter;
			$.get("admin/dashboard/donut-report-by-day/list", filter, function (data) {
				let labels = [];
				let data_value = [];
				let incident_report = 0;

				//alert('sss');

				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						//labels.push(value.questionnaire_answer);
						labels.push(value.questionnaire);
						//data_value.push(1);
						// incident_report.push(value.tenant_survey);
						incident_report += parseInt(value.tenant_survey);
						data_value.push(value.percentage_share);
						//randomBackgroundColor.push(dynamicColors());
					});
				}
				else {
					labels = ['Empty']
					data_value = [1];
					//randomBackgroundColor = ['#d2d6de'];
				}

				var donutData = {
					labels: labels,
					datasets: [
						{
							data: data_value,
							backgroundColor: ['#728FCE', '#90EE90', '#FED8B1', '#A52A2A', '#a59fa2'],
						}
					]
				}

				var pieChartSurveyCanvas = $('#pieChartSurvey').get(0).getContext('2d')
				var pieData = donutData;
				var pieOptions = {
					maintainAspectRatio: false,
					responsive: true,
				}

				// new Chart(pieChartSurveyCanvas, {
				//     type: 'pie',
				//     data: pieData,
				//     options: pieOptions
				// })
				var myChart = new Chart(pieChartSurveyCanvas, {
					type: 'doughnut',
					data: pieData,
					plugins: [{ //plugin added for this chart
						beforeDraw: function (chart) {
							var width = chart.chart.width,
								height = chart.chart.height,
								ctx = chart.chart.ctx;

							ctx.restore();
							var fontSize = 2.5;
							ctx.font = fontSize + "em sans-serif";
							ctx.textBaseline = "middle";

							var text = incident_report,
								textX = 250,//Math.round((width - ctx.measureText(text).width) / 2),
								textY = height / 2;

							ctx.fillText(text, textX + 45, textY);

							ctx.restore();
							var fontSize = 2;
							ctx.font = fontSize + "em sans-serif";
							ctx.textBaseline = "middle";

							ctx.fillText("INCIDENTS", textX, textY + 45);

							ctx.save();
						}
					}],
					options: pieOptions
				});
			});
			$.get("/admin/dashboard/average-time-by-lifetime/list", filter, function (data) {
				console.log(data.data);
				$('#average_time').text(parseFloat(data.data));
			});


		},

		filterChartByYears: function () {
			var filter = this.filter;
			filter.day = '';
			filter.week = '';
			filter.month = '';
			filter.year = '';
			const firstDayYear = moment().startOf('year').format('YYYY-MM-DD');
			const currentDay = moment(new Date()).format("YYYY-MM-DD");
			filter.start_date = (filter.start_date == '') ? firstDayYear : filter.start_date;
			filter.end_date = (filter.end_date == '') ? currentDay : filter.end_date;
			$.get("/admin/dashboard/trend-report-by-years/list", filter, function (data) {
				let datasets = [];
				var yValues = [];

				var key_label = [];
				$.each(data.data, function (key, value) {
					var data_key = [];
					var data_value = [];
					var oData = value.data;
					for (key in oData) {
						data_key.push(key);
						data_value.push(oData[key]);
					}
					key_label.push(data_key);

					let background_colorz = value.building_color;
					yValues.push(value.reports);
					datasets.push({
						label: value.building_name + '(Report: ' + value.reports + ')',
						backgroundColor: background_colorz,
						borderColor: background_colorz,
						pointRadius: false,
						pointColor: '#3b8bba',
						pointStrokeColor: background_colorz,
						pointHighlightFill: '#fff',
						pointHighlightStroke: background_colorz,
						data: data_value,
					});
				});

				let sum_reports_total = 0;

				// calculate sum using forEach() method
				yValues.forEach(num => {
					sum_reports_total += num;
				})

				this.reports_total = sum_reports_total;
				$('#reports_total').text(sum_reports_total);


				var areaChartDataDay = {
					labels: key_label[0],
					datasets: datasets
				};

				var barChartDataDay = $.extend(true, {}, areaChartDataDay);

				var reportBarChartCanvasDay = $('#reportBarChart').get(0).getContext('2d')
				var reportBarChartDataDay = $.extend(true, {}, barChartDataDay)

				var reportBarChartOptionsDay = {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					},
					plugins: {
						labels: {
							render: 'value'
						}
					}
				}
				if (window.report_bar != undefined)
					window.report_bar.destroy();
				window.report_bar = new Chart(reportBarChartCanvasDay, {
					type: 'bar',
					data: reportBarChartDataDay,
					options: reportBarChartOptionsDay
				})

			});

			$.get("/admin/dashboard/trend-incident-by-years/list", filter, function (data) {
				let datasets = [];
				var yValues = [];

				var key_label = [];
				$.each(data.data, function (key, value) {
					var data_key = [];
					var data_value = [];
					var oData = value.data;
					for (key in oData) {
						data_key.push(key);
						data_value.push(oData[key]);
					}
					key_label.push(data_key);

					let background_colorz = value.building_color;
					yValues.push(value.reports);
					datasets.push({
						label: value.building_name + '(Incident(s): ' + value.reports + ')',
						backgroundColor: background_colorz,
						borderColor: background_colorz,
						pointRadius: false,
						pointColor: '#3b8bba',
						pointStrokeColor: background_colorz,
						pointHighlightFill: '#fff',
						pointHighlightStroke: background_colorz,
						data: data_value,
					});
				});

				let sum_incidents_total = 0;

				// calculate sum using forEach() method
				yValues.forEach(num => {
					sum_incidents_total += num;
				})

				this.incidents_total = sum_incidents_total;
				$('#incidents_total').text(sum_incidents_total);


				var areaChartDataDay = {
					labels: key_label[0],
					datasets: datasets
				};

				var barChartDataDay = $.extend(true, {}, areaChartDataDay);

				var incidentBarChartCanvasDay = $('#incidentBarChart').get(0).getContext('2d')
				var incidentBarChartDataDay = $.extend(true, {}, barChartDataDay)

				var incidentBarChartOptionsDay = {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						xAxes: [{
							stacked: true,
						}],
						yAxes: [{
							stacked: true
						}]
					},
					plugins: {
						labels: {
							render: 'value'
						}
					}
				}
				if (window.incident_bar != undefined)
					window.incident_bar.destroy();
				window.incident_bar = new Chart(incidentBarChartCanvasDay, {
					//new Chart(reportBarChartCanvasz, {
					type: 'bar',
					data: incidentBarChartDataDay,
					options: incidentBarChartOptionsDay
				})

			});

			$.get("/admin/dashboard/donut-report-by-daily/list", filter, function (data) {
				let labels = [];
				let data_value = [];
				let incident_report = 0;
				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						labels.push(value.questionnaire);
						incident_report += parseInt(value.tenant_survey);
						data_value.push(value.percentage_share);
					});
					// console.log(labels);
				}
				else {
					labels = ['Empty']
					data_value = [1];
				}

				var donutData = {
					labels: labels,
					datasets: [
						{
							data: data_value,
							backgroundColor: ['#728FCE', '#90EE90', '#FED8B1'],
						}
					]
				}
				var cleanliness = '#728FCE';
				var supplies = '#90EE90';
				var functionality = '#FED8B1';

				var pieChartSurveyCanvas = $('#pieChartSurvey').get(0).getContext('2d')
				var pieData = donutData;
				var pieOptions = {
					maintainAspectRatio: false,
					responsive: true,
					inGraphDataShow: true,
					inGraphDataRadiusPosition: 2,
					inGraphDataFontColor: 'white'
				}
				if (window.doughnut_chart != undefined)
					window.doughnut_chart.destroy();

				window.doughnut_chart = new Chart(pieChartSurveyCanvas, {
					//var myChart = new Chart(pieChartSurveyCanvas, {
					type: 'doughnut',
					data: pieData,
					plugins: [{
						beforeDraw: function (chart) {
							var width = chart.chart.width,
								height = chart.chart.height,
								ctx = chart.chart.ctx;

							ctx.restore();
							var fontSize = 1.5;
							ctx.font = fontSize + "em sans-serif";
							ctx.textBaseline = "middle";

							var text = incident_report,
								textX = Math.round((width - ctx.measureText(text).width) / 2),
								textY = height / 2;

							ctx.fillText(text, textX, textY);

							ctx.restore();
							var fontSize = 1;
							ctx.font = fontSize + "em sans-serif";
							ctx.textBaseline = "middle";

							ctx.fillText("INCIDENTS", (textX - 35), textY + 35);

							ctx.save();
						}
					}],
					options: {
						pieOptions,
						events: ["mousemove", "mouseout", "click", "touchstart", "touchmove", "touchend"],
					}
				});
			});

			$.get("/admin/dashboard/donut-report-by-daily-answer/list", filter, function (data) {
				let labels_answer = [];
				let data_value_answer = [];
				let incident_report_answer = 0;
				let randomBackgroundColor = [];
				var cleanliness = '#728FCE';
				var supplies = '#90EE90';
				var functionality = '#FED8B1';


				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						var jordan = value.questionnaire_answer;
						labels_answer.push(jordan);
						incident_report_answer += parseInt(value.tenant_survey);
						data_value_answer.push(value.percentage_share);
						if (value.questionnaire == 'CLEANLINESS') {
							randomBackgroundColor.push(cleanliness);
						} else if (value.questionnaire == 'SUPPLIES') {
							randomBackgroundColor.push(supplies);
						} else {
							randomBackgroundColor.push(functionality);
						}

					});
				}
				else {
					labels_answer = ['Empty']
					data_value_answer = [1];
					randomBackgroundColor = [cleanliness];
				}

				var donutData_answer = {
					labels: labels_answer,
					datasets: [
						{
							data: data_value_answer,
							backgroundColor: randomBackgroundColor,
						}
					]
				}

				var pieChartSurveyCanvas_answer = $('#pieChartSurveyAnswer').get(0).getContext('2d')
				var pieData_answer = donutData_answer;
				var pieOptions_answer = {
					maintainAspectRatio: false,
					responsive: true,
					plugins: {
						labels: [
							{
								render: 'label',
								position: 'outside'
							},
							{
								render: 'percentage'
							}
						],


					},
					legend: {
						display: false,
					},
				}
				if (window.doughnut_chart_answer != undefined)
					window.doughnut_chart_answer.destroy();

				window.doughnut_chart_answer = new Chart(pieChartSurveyCanvas_answer, {
					//new Chart(pieChartSurveyCanvas_answer, {
					type: 'pie',
					data: pieData_answer,
					options: pieOptions_answer
				})
			});
			$.get("/admin/dashboard/average-time-by-years/list", filter, function (data) {
				console.log(data.data);
				$('#average_time').text(data.data);
			});
			$.get("/admin/dashboard/total-sms-by-years/list", filter, function (data) {
				console.log(data.data);
				$('#total_sms').text(data.data);
			});
		},

		dateSelected: function (e) {
			//console.log(filter.day);
			//this.$nextTick(() => console.log(filter.day))
			console.log(this.filter);
		},

		// daySelected: function (e) {
		// 	//alert(this.filter.day + 'day');
		// 	this.filterChartByDay();
		// 	console.log(this.filter);
		// },
		// weekSelected: function (e) {
		// 	//alert(this.filter.week + 'week');
		// 	this.filterChartByWeek();
		// 	console.log(this.filter);
		// },
		// monthSelected: function (e) {
		// 	//alert(this.filter.month + 'month');
		// 	this.filterChartByMonth();
		// 	console.log(this.filter);
		// },
		// yearSelected: function (e) {
		// 	//alert(this.filter.year + 'year');
		// 	this.filterChartByYear();
		// 	console.log(this.filter);
		// },
		daySelected: function (e) {
			//alert(this.filter.day + 'daySelected');
			//alert('Site:' + this.filter.site_id);
			if (this.filter.day != null) {
				this.filterChartByDay();// nakukuha sa filterChartByDaily
			}

		},
		weekSelected: function (e) {
			//alert(this.filter.week + 'weekSelected');
			//alert('Site:' + this.filter.site_id);
			if (this.filter.week != null) {
				this.filterChartByWeek();// nakukuha sa filterChartByDaily
			}
		},
		monthSelected: function (e) {
			//alert(this.filter.month + 'monthSelected');
			//alert('Site:' + this.filter.site_id);

			if (this.filter.month != null) {
				this.filterChartByMonth();// nakukuha sa filterChartByDaily
			}
		},
		yearSelected: function (e) {
			//alert(this.filter.year + 'yearSelected');
			//alert('Site:' + this.filter.site_id);

			if (this.filter.year != null) {
				console.log('ys');
				console.log(e);
				this.filterChartByYear();// nakukuha sa filterChartByDaily
			}
		},
		// customizedSelected: function (e) {
		// 	console.log('sa customizedSelected'); console.log(this.filter);
		// 	var d_start = new Date(this.filter.start_date);
		// 	var d_end = new Date(this.filter.end_date);
		// 	var m_start = d_start.getMonth();
		// 	var m_end = d_end.getMonth();
		// 	var y_start = d_start.getFullYear();
		// 	var y_end = d_end.getFullYear();
		// 	var date_start = d_start.getDate();
		// 	var day_start = d_start.getDay();
		// 	var date_end = d_end.getDate();
		// 	var day_end = d_end.getDay();
		// 	// To calculate the no. of days between two dates
		// 	var difference_in_time = d_end.getTime() - d_start.getTime();
		// 	var difference_in_days = difference_in_time / (1000 * 3600 * 24); console.log(this.filter);
		// 	// // Difference_In_Days; 
		// 	if (y_start == y_end) {

		// 		if (difference_in_days == 0) {
		// 			//alert(difference_in_days + 'day for hour');// 01 - 23 hour
		// 			this.filter.day = d_end; console.log('>>>>>>>>if' + difference_in_days); console.log(this.filter + '<<<<<<');
		// 			this.filterChartByDay();
		// 		} else if (difference_in_days >= 1 && difference_in_days <= 7) {
		// 			console.log('>>>>>>>>else if' + difference_in_days);
		// 			console.log('difference_in_days >= 1 && difference_in_days <= 7');
		// 			console.log(this.filter);
		// 			var week_of_month_start = Math.ceil((date_start - 1 - day_start) / 7);
		// 			var week_of_month_end = Math.ceil((date_end - 1 - day_end) / 7);
		// 			if (y_start == y_end) {
		// 				console.log(y_start + '== ' + y_end + 'y_start == y_end<<<<');
		// 				if (week_of_month_start == week_of_month_end) {
		// 					//alert('if' + week_of_month_start + '==' + week_of_month_end + 'week_of_month_start == week_of_month_end<<<<');
		// 					//this.filterChartByWeek();
		// 					this.filterChartByDailyAll();
		// 				} else {
		// 					//alert('else' + week_of_month_start + '!=' + week_of_month_end + 'week_of_month_start != week_of_month_end<<<<');
		// 					//	alert('zzzzzzzzzzzzzzzzzzzzzzzz'+this.filter.start_date +' '+this.filter.end_date);
		// 					this.filterChartByWeek();
		// 				}
		// 			} else {
		// 				// wishlist
		// 			}
		// 		}
		// 		else if (difference_in_days >= 8 && difference_in_days <= 31) {
		// 			var week_of_month_start = Math.ceil((date_start - 1 - day_start) / 7);
		// 			var week_of_month_end = Math.ceil((date_end - 1 - day_end) / 7);
		// 			//alert(m_start + '== ' + m_end);
		// 			//alert(y_start + '== ' + y_end);
		// 			//this.filterChartByMonth();
		// 			if (y_start == y_end) {
		// 				if (week_of_month_start == week_of_month_end) {
		// 					alert(week_of_month_start + '==' + week_of_month_end);
		// 					this.filterChartByDaily();
		// 				} else {
		// 					alert('week_of_month_start != week_of_month_end' + week_of_month_start + '!=' + week_of_month_end);
		// 					//this.filterChartByWeek();
		// 					//this.filterChartByDaily();
		// 					this.filterChartByYear();
		// 					//this.filterChartByDaily();
		// 				}
		// 			} else {
		// 				if (m_start == m_start) {
		// 					alert(m_start + '==' + m_start);
		// 					this.filterChartByMonth();
		// 				} else {
		// 					alert(m_start + '!=' + m_start);
		// 					this.filterChartByYear();
		// 				}
		// 			}
		// 		} else {
		// 			this.filterChartByYear();
		// 		}
		// 	} else {
		// 		this.filterChartByYears();
		// 	}
		// },
		customizedSelected: function (e) {
			console.log('sa customizedSelected'); console.log(this.filter);
			var d_start = new Date(this.filter.start_date);
			var d_end = new Date(this.filter.end_date);
			var m_start = d_start.getMonth();
			var m_end = d_end.getMonth();
			var y_start = d_start.getFullYear();
			var y_end = d_end.getFullYear();
			var date_start = d_start.getDate();
			var day_start = d_start.getDay();
			var date_end = d_end.getDate();
			var day_end = d_end.getDay();
			// To calculate the no. of days between two dates
			var difference_in_time = d_end.getTime() - d_start.getTime();
			var difference_in_days = difference_in_time / (1000 * 3600 * 24); console.log(this.filter);
			// // Difference_In_Days; 
			if (y_start == y_end) {

				if (difference_in_days == 0) {
					//alert(difference_in_days + 'day for hour');// 01 - 23 hour
					this.filter.day = d_end; console.log('>>>>>>>>if' + difference_in_days); console.log(this.filter + '<<<<<<');
					this.filterChartByDay();
				} else if (difference_in_days >= 1 && difference_in_days <= 7) {
					console.log('>>>>>>>>else if' + difference_in_days);
					console.log('difference_in_days >= 1 && difference_in_days <= 7');
					console.log(this.filter);
					var week_of_month_start = Math.ceil((date_start - 1 - day_start) / 7);
					var week_of_month_end = Math.ceil((date_end - 1 - day_end) / 7);
					if (y_start == y_end) {
						console.log(y_start + '== ' + y_end + 'y_start == y_end<<<<');
						if (week_of_month_start == week_of_month_end) {
							//alert('if' + week_of_month_start + '==' + week_of_month_end + 'week_of_month_start == week_of_month_end<<<<');
							//this.filterChartByWeek();
							this.filterChartByDailyAll();
						} else {
							//alert('else' + week_of_month_start + '!=' + week_of_month_end + 'week_of_month_start != week_of_month_end<<<<');
							//	alert('zzzzzzzzzzzzzzzzzzzzzzzz'+this.filter.start_date +' '+this.filter.end_date);
							this.filterChartByWeek();
						}
					} else {
						// wishlist
					}
				}
				else if (difference_in_days >= 8 && difference_in_days <= 31) {
					var week_of_month_start = Math.ceil((date_start - 1 - day_start) / 7);
					var week_of_month_end = Math.ceil((date_end - 1 - day_end) / 7);
					//alert(m_start + '== ' + m_end);
					//alert(y_start + '== ' + y_end);
					//this.filterChartByMonth();
					if (y_start == y_end) {
						if (week_of_month_start == week_of_month_end) {
							alert(week_of_month_start + '==' + week_of_month_end);
							this.filterChartByDaily();
						} else {
							alert('week_of_month_start != week_of_month_end' + week_of_month_start + '!=' + week_of_month_end);
							//this.filterChartByWeek();
							//this.filterChartByDaily();
							this.filterChartByYear();
							//this.filterChartByDaily();
						}
					} else {
						if (m_start == m_start) {
							alert(m_start + '==' + m_start);
							this.filterChartByMonth();
						} else {
							alert(m_start + '!=' + m_start);
							this.filterChartByYear();
						}
					}
				} else {
					this.filterChartByYear();
				}
			} else {
				this.filterChartByYears();
			}
		},
		diff_weeks: function (dt2, dt1) {

			var diff = (dt2.getTime() - dt1.getTime()) / 1000;
			diff /= (60 * 60 * 24 * 7);
			return Math.abs(Math.round(diff));

		},
		setToDate: function (date, day_num) {
			var day = date.getDay() || 7;
			if (day !== 1) {

				date.setHours(-24 * (day - day_num));
			}

			var myDate = new Date(date);
			var month_day = myDate.toLocaleString('en-PH', {
				day: '2-digit',
				month: '2-digit',
				year: 'numeric',
			}).substring(0, 5);

			return month_day;
		},

	},

	mounted() {
		var obj = this;
		//this.filterChartFirstLast();
	},


	components: {
		datePicker
	}
};
</script> 