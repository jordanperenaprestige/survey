<template>
	<section class="content">
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="card">
						<div class="card-header">
							<!-- <div class="card-tools"> -->
							<form class="col-sm-12">
								<div class="form-group row">
									<h3 class="card-title justify-content-center">Trends</h3>
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
											<date-picker v-model="filter.day" placeholder="Day" :config="options_D" id="day"
												autocomplete="off" @dp-change="daySelected"></date-picker>
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
											<label for="year" class="col-form-label">Year</label>
											<date-picker v-model="filter.year" placeholder="Year" :config="options_Y"
												id="year" autocomplete="off" @dp-change="yearSelected"></date-picker>
										</div>
										<div v-show="by_start">
											<label for="userName" class="col-form-label">Start Date</label>
											<date-picker v-model="filter.start_date" placeholder="YYYY-MM-DD"
												:config="options" id="date_from" autocomplete="off"
												@dp-change="customizedSelected"></date-picker>
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
									<div class="col-sm-4">
										<label for="userName" class="col-form-label">Site</label>
										<select class="custom-select mr-2" v-model="filter.site_id" @change="filterChart()">
											<option value="">Select All</option>
											<option v-for="site in sites" :value="site.id"> {{ site.name }}</option>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<div class="col-sm-4">
										<label for="userName" class="col-form-label">Ave. Time: <span
												id="average_time"></span>&nbsp;m
										</label>
									</div>
									<div class="col-sm-2">
										<label for="userName" class="col-form-label">SMS: <span id="total_sms"></span>&nbsp;
										</label>
									</div>
								</div>
							</form>
							<!-- </div> -->
						</div>
						<div class="card-body">
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
											style="min-height: 220px; height: 230px;  max-height: 230px; max-width:490px;"></canvas>
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
import moment from 'moment';
// Import date picker js
import datePicker from 'vue-bootstrap-datetimepicker';
// Import date picker css
import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';
export default {
	name: "Dashboard_Merchant_Population",
	data() {
		return {
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
			options_W: {
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
			by_week: false,
			by_month: false,
			by_year: false,
			by_lifetime: false,
			by_start: false,
			by_end: false,
			survey_number_day: 0,
			reports_total: 0,
			incidents_total: 0,
			average_time: 0,
			total_sms: 0,
			customized: '',
			first_last_survey: '',
		}
	},

	created() {
		this.getSites();
		this.filterChart();
		this.filterBy();
	},

	methods: {
		getLifetime: function () {
			this.filter.start_date = '';
			this.filter.end_date = '';
			axios.get('/admin/reports/getFirstLastsurvey')
				.then(response => {
					this.first_last_survey = response.data.data;
					this.by_day = false;
					this.by_week = false;
					this.by_month = false;
					this.by_year = false;
					this.by_start = false;
					this.by_end = false;
					this.filter.start_date = '';
					this.filter.end_date = '';

					this.filter.start_date = this.first_last_survey[0];
					this.filter.end_date = this.first_last_survey[1];
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

					var difference_in_time = d_end.getTime() - d_start.getTime();
					var difference_in_days = difference_in_time / (1000 * 3600 * 24); console.log(this.filter);

					if (y_start == y_end) {
						if (difference_in_days == 0) {
							this.filter.day = d_end;
							this.filterChartByDay();
						} else if (difference_in_days >= 1 && difference_in_days <= 7) {
							var week_of_month_start = Math.ceil((date_start - 1 - day_start) / 7);
							var week_of_month_end = Math.ceil((date_end - 1 - day_end) / 7);
							if (y_start == y_end) {
								if (week_of_month_start == week_of_month_end) {
									this.customize = 'week';
									this.filter.week = this.filter.end_date;
									this.filterChartByWeek();
								} else {
									this.filter.customized = 'month';
									this.filter.month = this.filter.end_date.substring(0, 7);
									this.filterChartByMonth();
								}
							} else {
								// wishlist
							}
						}
						else if (difference_in_days >= 8 && difference_in_days <= 31) {
							var week_of_month_start = Math.ceil((date_start - 1 - day_start) / 7);
							var week_of_month_end = Math.ceil((date_end - 1 - day_end) / 7);
							if (y_start == y_end) {
								if (week_of_month_start == week_of_month_end) {
									this.filterChartByDaily();
								} else {
									this.filterChartByYear();
								}
							} else {
								if (m_start == m_end) {
									this.filter.customized = 'month';
									this.filter.month = this.filter.end_date.substring(0, 7);
									this.filterChartByMonth();
								} else {
									this.filterChartByYear();
								}
							}
						} else {
							this.filterChartByYear();
						}
					} else {
						this.filterChartByYears();
					}
				});
		},
		getSites: function () {
			axios.get('/admin/site/get-all')
				.then(response => this.sites = response.data.data);
		},
		filterBy: function () {

			if (this.filter.by == 0) {//lifetime
				this.getLifetime();
			} else if (this.filter.by == 1) {//day
				this.by_day = true;
				this.by_week = false;
				this.by_month = false;
				this.by_year = false;
				this.by_start = false;
				this.by_end = false;
				this.filterChartByDay();
			}
			else if (this.filter.by == 2) {//week
				this.by_day = false;
				this.by_week = true;
				this.by_month = false;
				this.by_year = false;
				this.by_start = false;
				this.by_end = false;
				this.filterChartByWeek();
			}
			else if (this.filter.by == 3) {//month
				this.by_day = false;
				this.by_week = false;
				this.by_month = true;
				this.by_year = false;
				this.by_start = false;
				this.by_end = false;
				const currentMonth = moment(new Date()).format("YYYY-MM");
				this.filter.month = currentMonth; (this.filter.month == '') ? currentMonth : this.filter.month;
				this.filterChartByMonth();
			} else if (this.filter.by == 4) {//year
				this.by_day = false;
				this.by_week = false;
				this.by_month = false;
				this.by_year = true;
				this.by_start = false;
				this.by_end = false;
				const currentYear = moment().year().toString();
				this.filter.year = (this.filter.year == '') ? currentYear : this.filter.year;
				this.filterChartByYear();
			} else { //customize
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

				var difference_in_time = d_end.getTime() - d_start.getTime();
				var difference_in_days = difference_in_time / (1000 * 3600 * 24); console.log(this.filter);

				if (y_start == y_end) {
					if (difference_in_days == 0) {
						this.filter.day = d_end; console.log('>>>>>>>>if' + difference_in_days); console.log(this.filter + '<<<<<<');
						this.filterChartByDay();
					} else if (difference_in_days >= 1 && difference_in_days <= 7) {
						var week_of_month_start = Math.ceil((date_start - 1 - day_start) / 7);
						var week_of_month_end = Math.ceil((date_end - 1 - day_end) / 7);
						if (y_start == y_end) {
							if (week_of_month_start == week_of_month_end) {
								this.customize = 'week';
								this.filter.week = this.filter.end_date;
								this.filterChartByWeek();
							} else {
								this.filter.customized = 'month';
								this.filter.month = this.filter.end_date.substring(0, 7);
								this.filterChartByMonth();
							}
						} else {
							// wishlist
						}
					}
					else if (difference_in_days >= 8 && difference_in_days <= 31) {
						var week_of_month_start = Math.ceil((date_start - 1 - day_start) / 7);
						var week_of_month_end = Math.ceil((date_end - 1 - day_end) / 7);
						if (y_start == y_end) {
							if (week_of_month_start == week_of_month_end) {
								this.filterChartByDaily();
							} else {
								this.filterChartByYear();
							}
						} else {
							if (m_start == m_end) {
								this.filter.customized = 'month';
								this.filter.month = this.filter.end_date.substring(0, 7);
								this.filterChartByMonth();
							} else {

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

		filterChart: function () {
			const moment = require('moment');
			if (this.filter.by == 0) {
				this.getLifetime();
			} else if (this.filter.by == 1) {//day
				this.filterChartByDay();
			}
			else if (this.filter.by == 2) {//Week
				this.filterChartByWeek();
			}
			else if (this.filter.by == 3) {//Month
				const currentMonth = moment().month();
				this.filter.month = (this.filter.month == '') ? currentMonth : this.filter.month;
				this.filterChartByMonth();

			} else if (this.filter.by == 4) {//Year
				const currentYear = moment().year();
				this.filter.year = (this.filter.year == '') ? currentYear : this.filter.year;
				this.filterChartByYear();

			} else {//lifetime
				const firstDateOfMonth = moment().startOf('year').format('YYYY-MM-DD');
				const lastDateOfMonth = moment().endOf('month').format('YYYY-MM-DD');
				this.filter.start_date = (this.filter.start_date == '') ? firstDateOfMonth : this.filter.start_date;
				this.filter.end_date = (this.filter.end_date == '') ? lastDateOfMonth : this.filter.end_date;
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
				var difference_in_time = d_end.getTime() - d_start.getTime();
				var difference_in_days = difference_in_time / (1000 * 3600 * 24); console.log(this.filter);

				if (y_start == y_end) {
					if (difference_in_days == 0) {
						this.filter.day = d_end; console.log('>>>>>>>>if' + difference_in_days); console.log(this.filter + '<<<<<<');
						this.filterChartByDay();
					} else if (difference_in_days >= 1 && difference_in_days <= 7) {
						var week_of_month_start = Math.ceil((date_start - 1 - day_start) / 7);
						var week_of_month_end = Math.ceil((date_end - 1 - day_end) / 7);
						if (y_start == y_end) {
							if (week_of_month_start == week_of_month_end) {
								this.customize = 'week';
								this.filter.week = this.filter.end_date;
								this.filterChartByWeek();
							} else {
								this.filter.customized = 'month';
								this.filter.month = this.filter.end_date.substring(0, 7);
								this.filterChartByMonth();
							}
						} else {
							// wishlist
						}
					}
					else if (difference_in_days >= 8 && difference_in_days <= 31) {
						var week_of_month_start = Math.ceil((date_start - 1 - day_start) / 7);
						var week_of_month_end = Math.ceil((date_end - 1 - day_end) / 7);
						if (y_start == y_end) {
							if (week_of_month_start == week_of_month_end) {
								this.filterChartByDaily();
							} else {
								this.filterChartByYear();
							}
						} else {
							if (m_start == m_end) {
								this.filter.customized = 'month';
								this.filter.month = this.filter.end_date.substring(0, 7);
								this.filterChartByMonth();
							} else {
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
			filter.day = '';
			filter.week = '';
			filter.month = '';
			filter.year = '';
			const firstDayYear = moment().startOf('year').format('YYYY-MM-DD');
			const currentDay = moment(new Date()).format("YYYY-MM-DD");
			filter.start_date = (filter.start_date == '') ? firstDayYear : filter.start_date;
			filter.end_date = (filter.end_date == '') ? currentDay : filter.end_date;

			$.get("/admin/reports/trend-report-by-daily/list", filter, function (data) {
				let datasets = [];
				var yValues = [];

				var key_label = [];
				$('#report_legend').html(data.data.legend);
				$.each(data.data, function (key, value) {
					if (key != 'legend') {
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
					}
				});

				let sum_reports_total = 0;

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
					},
					legend: {
						display: true
					},
				}
				if (window.report_bar != undefined)
					window.report_bar.destroy();
				window.report_bar = new Chart(reportBarChartCanvasDay, {
					type: 'bar',
					data: reportBarChartDataDay,
					options: reportBarChartOptionsDay
				})
			});

			$.get("/admin/reports/trend-incident-by-daily/list", filter, function (data) {

				let datasets = [];
				var yValues = [];
				var key_label = [];

				$('#incident_legend').html(data.data.legend);
				$.each(data.data, function (key, value) {
					if (key != 'legend') {
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
					}
				});

				let sum_incidents_total = 0;

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
					},
					legend: {
						display: true
					},
				}
				if (window.incident_bar != undefined)
					window.incident_bar.destroy();
				window.incident_bar = new Chart(incidentBarChartCanvasDay, {
					type: 'bar',
					data: incidentBarChartDataDay,
					options: incidentBarChartOptionsDay
				})
			});

			$.get("/admin/reports/donut-report-by-daily/list", filter, function (data) {
				let labels = [];
				let data_value = [];
				let incident_report = 0;
				let color = [];
				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						labels.push(value.questionnaire);
						incident_report += parseInt(value.tenant_survey);
						data_value.push(value.percentage_share);
						color.push(value.questionnaire_color);
					});
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
							backgroundColor: color,
						}
					]
				}
				var cleanliness = '#90EE90';
				var supplies = '#808000';
				var functionality = '#FAF884';
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

							ctx.fillText("INCIDENTS", (textX - 30), textY + 35);

							ctx.save();
						}
					}],
					options: {
						pieOptions,
						events: ["mousemove", "mouseout", "click", "touchstart", "touchmove", "touchend"],
					}
				});
			});

			$.get("/admin/reports/donut-report-by-daily-answer/list", filter, function (data) {
				let labels_answer = [];
				let data_value_answer = [];
				let incident_report_answer = 0;
				let randomBackgroundColor = [];
				var cleanliness = '#90EE90';
				var supplies = '#808000';
				var functionality = '#FAF884';

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
					randomBackgroundColor = [];
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
					type: 'pie',
					data: pieData_answer,
					options: pieOptions_answer
				})
			});
			$.get("/admin/reports/average-time-by-daily/list", filter, function (data) {
				console.log(data.data);
				$('#average_time').text(data.data);
			});
			$.get("/admin/reports/total-sms-by-daily/list", filter, function (data) {
				console.log(data.data);
				$('#total_sms').text(data.data);
			});
		},

		filterChartByDailyAll: function () {
			var filter = this.filter;
			filter.day = '';
			filter.week = '';
			filter.month = '';
			filter.year = '';
			const firstDayYear = moment().startOf('year').format('YYYY-MM-DD');
			const currentDay = moment(new Date()).format("YYYY-MM-DD");
			filter.start_date = (filter.start_date == '') ? firstDayYear : filter.start_date;
			filter.end_date = (filter.end_date == '') ? currentDay : filter.end_date;
			$.get("/admin/reports/trend-report-by-daily-all/list", filter, function (data) {
				let datasets = [];
				var yValues = [];
				var key_label = [];
				$('#report_legend').html(data.data.legend);
				$.each(data.data, function (key, value) {
					if (key != 'legend') {
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
					}
				});

				let sum_reports_total = 0;

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
					},
					legend: {
						display: true
					},
				}
				if (window.report_bar != undefined)
					window.report_bar.destroy();
				window.report_bar = new Chart(reportBarChartCanvasDay, {
					type: 'bar',
					data: reportBarChartDataDay,
					options: reportBarChartOptionsDay
				})

			});

			$.get("/admin/reports/trend-incident-by-daily-all/list", filter, function (data) {

				let datasets = [];
				var yValues = [];
				var key_label = [];
				$('#incident_legend').html(data.data.legend);
				$.each(data.data, function (key, value) {
					if (key != 'legend') {
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
					}
				});

				let sum_incidents_total = 0;
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
					type: 'bar',
					data: incidentBarChartDataDay,
					options: incidentBarChartOptionsDay
				})

			});

			$.get("/admin/reports/donut-report-by-daily/list", filter, function (data) {
				let labels = [];
				let data_value = [];
				let incident_report = 0;
				let color = [];
				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						labels.push(value.questionnaire);
						incident_report += parseInt(value.tenant_survey);
						data_value.push(value.percentage_share);
						color.push(value.questionnaire_color);
					});
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
							backgroundColor: color,
						}
					]
				}
				var cleanliness = '#90EE90';
				var supplies = '#808000';
				var functionality = '#FAF884';

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

							ctx.fillText("INCIDENTS", (textX - 30), textY + 35);

							ctx.save();
						}
					}],
					options: {
						pieOptions,
						events: ["mousemove", "mouseout", "click", "touchstart", "touchmove", "touchend"],
					}
				});
			});

			$.get("/admin/reports/donut-report-by-daily-answer/list", filter, function (data) {
				let labels_answer = [];
				let data_value_answer = [];
				let incident_report_answer = 0;
				let randomBackgroundColor = [];
				var cleanliness = '#90EE90';
				var supplies = '#808000';
				var functionality = '#FAF884';


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
					randomBackgroundColor = [];
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
					type: 'pie',
					data: pieData_answer,
					options: pieOptions_answer
				})
			});
			$.get("/admin/reports/average-time-by-daily/list", filter, function (data) {
				console.log(data.data);
				$('#average_time').text(data.data);
			});
			$.get("/admin/reports/total-sms-by-daily/list", filter, function (data) {
				console.log(data.data);
				$('#total_sms').text(data.data);
			});
		},

		filterChartByDay: function () {
			var filter = this.filter;
			filter.week = '';
			filter.month = '';
			filter.year = '';
			const currentDay = moment(new Date()).format("YYYY-MM-DD");
			filter.day = (filter.day == '' || filter.day == null) ? currentDay : filter.day;

			this.filter.day = filter.day;
			console.log('filterChartByDay D ' + filter.day + ' W ' + filter.week + ' M ' + filter.month + ' Y ' + filter.year);
			$.get("/admin/reports/trend-report-by-day/list", filter, function (data) {
				let datasets_day = [];
				var yValues = [];

				console.log(data.data.legend);
				$('#report_legend').html(data.data.legend);
				$.each(data.data, function (key, value) {
					if (key != 'legend') {
						let background_colorz = value.building_color;
						yValues.push(value.reports);
						datasets_day.push({
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
					}
				});
				let sum_reports_total = 0;

				yValues.forEach(num => {
					sum_reports_total += num;
				})

				this.reports_total = sum_reports_total;
				$('#reports_total').text(sum_reports_total);

				var areaChartDataDay = {
					labels: ['00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23'],
					datasets: datasets_day
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
					},
					legend: {
						display: true
					},
				}
				if (window.report_bar != undefined)
					window.report_bar.destroy();
				window.report_bar = new Chart(reportBarChartCanvasDay, {
					type: 'bar',
					data: reportBarChartDataDay,
					options: reportBarChartOptionsDay
				})

			});

			$.get("/admin/reports/trend-incident-by-day/list", filter, function (data) {
				let datasetsz = [];
				var yValues = [];

				let dynamicColorsz = ['#FE5E80', '#899AE8', '#353535', '#a9b7d8', '#a59fa2', '#f79fba', '#727272', '#191970', '#A0CFEC', '#D5D6EA', '#50C878', '#6B8E23', '#556B2F', '#FFFFC2', '#B5A642', '#513B1C', '#CB6D51', '#CC7A8B', '#FFDFDD', '#B048B5', '#F8F0E3', '#EAEEE9', '#D891EF'];

				$('#incident_legend').html(data.data.legend);
				$.each(data.data, function (key, value) {
					if (key != 'legend') {
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
					}
				});
				let sum_incidents_total = 0;

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
					},
					legend: {
						display: true
					},
				}
				if (window.incident_bar != undefined)
					window.incident_bar.destroy();

				window.incident_bar = new Chart(reportBarChartCanvasz, {

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
							backgroundColor: colors,
						}
					]
				}
				var cleanliness = '#90EE90';
				var supplies = '#808000';
				var functionality = '#FAF884';

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

							ctx.fillText("INCIDENTS", (textX - 30), textY + 35);

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
				var cleanliness = '#90EE90';
				var supplies = '#808000';
				var functionality = '#FAF884';


				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						var jordan = value.questionnaire_answer;
						var color = value.questionnaire_color;
						labels_answer.push(jordan);
						color_answers.push(color);




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
					randomBackgroundColor = color_answers;
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

					type: 'pie',
					data: pieData_answer,
					options: pieOptions_answer
				})
			});

			$.get("/admin/reports/average-time-by-day/list", filter, function (data) {
				console.log(data.data);
				$('#average_time').text(data.data);
			});
			$.get("/admin/reports/total-sms-by-day/list", filter, function (data) {
				console.log(data.data);
				$('#total_sms').text(data.data);
			});
		},

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
			$.get("/admin/reports/trend-report-by-week/list", filter, function (data) {
				console.log('trend-report-by-week');
				let datasets = [];
				var yValues = [];
				//console.log('>>>>>xxxxxx>>>>>>>'); console.log(data.data.legend); console.log('<<<<<<vvvvvv<<<<<');
				$('#report_legend').html(data.data.legend);
				$.each(data.data, function (key, value) {
					if (key != 'legend') {
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
							data: [value.sun, value.mon, value.tue, value.wed, value.thu, value.fri, value.sat]
						});
					}

				});
				let sum_reports_total = 0;


				yValues.forEach(num => {
					sum_reports_total += num;
				})
				this.reports_total = sum_reports_total;
				var jordan = obj.filter.week;
				$('#reports_total').text(sum_reports_total);
				var number_day_week = new Date(filter.week);
				if (number_day_week.getDay() == 0) { //if sunday

					var set_week = moment(new Date(number_day_week.setDate(number_day_week.getDate() + 1))).format("YYYY-MM-DD");;

				} else {
					var set_week = obj.filter.week;
				}

				//date.setDate(date.getDate() + 1);
				var sun = obj.setToDate(new Date(set_week), 0);
				var mon = obj.setToDate(new Date(set_week), 1);
				var tue = obj.setToDate(new Date(set_week), 2);
				var wed = obj.setToDate(new Date(set_week), 3);
				var thu = obj.setToDate(new Date(set_week), 4);
				var fri = obj.setToDate(new Date(set_week), 5);
				var sat = obj.setToDate(new Date(set_week), 6);

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
						display: true
					},
				}
				if (window.report_bar != undefined)
					window.report_bar.destroy();

				window.report_bar = new Chart(reportBarChartCanvas, {

					type: 'bar',
					data: reportBarChartData,
					options: reportBarChartOptions
				})
			});

			$.get("/admin/reports/trend-incident-by-week/list", filter, function (data) {
				console.log('trend-incident-by-week'); console.log(filter);
				let datasetsz = [];
				var yValues = [];

				let dynamicColorsz = ['#FE5E80', '#899AE8', '#353535', '#a9b7d8', '#a59fa2', '#f79fba', '#727272'];


				$('#incident_legend').html(data.data.legend);
				$.each(data.data, function (key, value) {
					if (key != 'legend') {
						let background_color = value.building_color;
						yValues.push(value.reports);
						datasetsz.push({
							label: value.building_name + '(Incident(s): ' + value.reports + ')',

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


				yValues.forEach(num => {
					sum_incidents_total += num;
				})
				this.reports_total = sum_incidents_total;
				$('#incidents_total').text(sum_incidents_total);

				var number_day_week = new Date(filter.week);
				if (number_day_week.getDay() == 0) { //if sunday

					var set_week = moment(new Date(number_day_week.setDate(number_day_week.getDate() + 1))).format("YYYY-MM-DD");;

				} else {
					var set_week = obj.filter.week;
				}

				var sun = obj.setToDate(new Date(set_week), 0);
				var mon = obj.setToDate(new Date(set_week), 1);
				var tue = obj.setToDate(new Date(set_week), 2);
				var wed = obj.setToDate(new Date(set_week), 3);
				var thu = obj.setToDate(new Date(set_week), 4);
				var fri = obj.setToDate(new Date(set_week), 5);
				var sat = obj.setToDate(new Date(set_week), 6);

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
						display: true
					},
				}
				if (window.incident_bar != undefined)
					window.incident_bar.destroy();

				window.incident_bar = new Chart(reportBarChartCanvasz, {

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
							backgroundColor: colors,
						}
					]
				}
				var cleanliness = '#90EE90';
				var supplies = '#808000';
				var functionality = '#FAF884';

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

							ctx.fillText("INCIDENTS", (textX - 30), textY + 35);

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
				var cleanliness = '#90EE90';
				var supplies = '#808000';
				var functionality = '#FAF884';


				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						var jordan = value.questionnaire_answer;
						var color = value.questionnaire_color;
						labels_answer.push(jordan);
						color_answers.push(color);




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
					randomBackgroundColor = color_answers;
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

					type: 'pie',
					data: pieData_answer,
					options: pieOptions_answer
				})
			});
			$.get("/admin/reports/average-time-by-week/list", filter, function (data) {
				console.log(data.data);
				$('#average_time').text(parseFloat(data.data));
			});
			$.get("/admin/reports/total-sms-by-week/list", filter, function (data) {
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

			$.get("/admin/reports/trend-report-by-month/list", filter, function (data) {
				let datasets = [];
				let week_range = [];
				var yValues = [];

				$('#report_legend').html(data.data[0].legend);
				$.each(data.data[0], function (key, value) {
					if (key != 'legend') {
						let background_color = value.building_color;
						yValues.push(value.reports);
						var bar = value.bar;

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
					}

				});
				$.each(data.data[1], function (key, value) {
					week_range.push(value);
				});

				let sum_reports_total = 0;


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
					},
					legend: {
						display: true
					},
				}
				if (window.report_bar != undefined)
					window.report_bar.destroy();

				window.report_bar = new Chart(reportBarChartCanvas, {

					type: 'bar',
					data: reportBarChartData,
					options: reportBarChartOptions
				})
			});
			$.get("/admin/reports/trend-incident-by-month/list", filter, function (data) {
				let datasets = [];
				let week_range = [];
				var yValues = [];
				let dynamicColors = ['#FE5E80', '#899AE8', '#353535', '#a9b7d8', '#ff00cc', '#ff0000'];


				$('#incident_legend').html(data.data[0].legend);

				$.each(data.data[0], function (key, value) {
					if (key != 'legend') {
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

						});
					}



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
					},
					legend: {
						display: true
					},
				}
				if (window.incident_bar != undefined)
					window.incident_bar.destroy();

				window.incident_bar = new Chart(reportBarChartCanvas, {

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
							backgroundColor: colors,
						}
					]
				}
				var cleanliness = '#90EE90';
				var supplies = '#808000';
				var functionality = '#FAF884';

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

							ctx.fillText("INCIDENTS", (textX - 30), textY + 35);

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
				var cleanliness = '#90EE90';
				var supplies = '#808000';
				var functionality = '#FAF884';


				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						var jordan = value.questionnaire_answer;
						var color = value.questionnaire_color;
						labels_answer.push(jordan);
						color_answers.push(color);




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
					randomBackgroundColor = color_answers;
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

					type: 'pie',
					data: pieData_answer,
					options: pieOptions_answer
				})
			});

			$.get("/admin/reports/average-time-by-month/list", filter, function (data) {

				$('#average_time').text(parseFloat(data.data));
			});
			$.get("/admin/reports/total-sms-by-month/list", filter, function (data) {

				$('#total_sms').text(parseFloat(data.data));
			});
		},

		filterChartByYear: function () {
			//alert('year');
			var filter = this.filter;
			filter.day = '';
			filter.week = '';
			filter.month = '';

			console.log('YEar D ' + filter.day + ' W ' + filter.week + ' M ' + filter.month + ' Y ' + filter.year);
			const currentYear = moment().year().toString();
			filter.year = (filter.year == '' || filter.year == null) ? currentYear : filter.year;

			this.filter.year = filter.year;


			$.get("/admin/reports/trend-report-by-year/list", filter, function (data) {
				let datasets = [];
				var yValues = [];
				let dynamicColors = ['#FE5E80', '#899AE8', '#353535', '#a9b7d8', '#00FF00', '#808000', '#FFA500', '#86608E', '#B666D2', '#F3E8EA', '#F5F5F5'];
				$('#report_legend').html('');
				$('#report_legend').html(data.data.legend);
				$.each(data.data, function (key, value) {
					if (key != 'legend') {
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
					}
				});
				let sum_reports_total = 0;


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
					},
					legend: {
						display: true
					},
				}
				if (window.report_bar != undefined)
					window.report_bar.destroy();

				window.report_bar = new Chart(reportBarChartCanvas, {

					type: 'bar',
					data: reportBarChartData,
					options: reportBarChartOptions
				})
			});
			$.get("/admin/reports/trend-incident-by-year/list", filter, function (data) {
				let datasets = [];
				var yValues = [];
				let dynamicColors = ['#FE5E80', '#899AE8', '#353535', '#a9b7d8', '#00FF00', '#808000', '#FFA500', '#86608E', '#B666D2', '#F3E8EA', '#F5F5F5'];
				$('#incident_legend').html('');
				$('#incident_legend').html(data.data.legend);
				$.each(data.data, function (key, value) {
					if (key != 'legend') {
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
					}
				});

				let sum_incidents_total = 0;


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
					},
					legend: {
						display: true
					},
				}
				if (window.incident_bar != undefined)
					window.incident_bar.destroy();

				window.incident_bar = new Chart(reportBarChartCanvas, {

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
							backgroundColor: colors,
						}
					]
				}
				var cleanliness = '#90EE90';
				var supplies = '#808000';
				var functionality = '#FAF884';

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

							ctx.fillText("INCIDENTS", (textX - 30), textY + 35);

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
				var cleanliness = '#90EE90';
				var supplies = '#808000';
				var functionality = '#FAF884';


				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						var jordan = value.questionnaire_answer;
						var color = value.questionnaire_color;
						labels_answer.push(jordan);
						color_answers.push(color);




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
					randomBackgroundColor = color_answers;
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

					type: 'pie',
					data: pieData_answer,
					options: pieOptions_answer
				})
			});
			$.get("/admin/reports/average-time-by-year/list", filter, function (data) {

				$('#average_time').text(parseFloat(data.data));
			});
			$.get("/admin/reports/total-sms-by-year/list", filter, function (data) {
				console.log(data.data);
				$('#total_sms').text(parseFloat(data.data));
			});

		},

		filterChartByLifetime: function () {
			var filter = this.filter;
			filter.day = '';
			filter.week = '';
			filter.month = '';

			console.log('Lifetime D ' + filter.day + ' W ' + filter.week + ' M ' + filter.month + ' Y ' + filter.year);
			const currentYear = moment().year().toString();
			filter.year = (filter.year == '' || filter.year == null) ? currentYear : filter.year;

			this.filter.year = filter.year;
			$.get("/admin/reports/trend-report-by-year/list", filter, function (data) {
				let datasets = [];
				var yValues = [];

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

				window.report_bar = new Chart(reportBarChartCanvas, {

					type: 'bar',
					data: reportBarChartData,
					options: reportBarChartOptions
				})
			});
			$.get("/admin/reports/trend-incident-by-year/list", filter, function (data) {
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

				window.incident_bar = new Chart(reportBarChartCanvas, {

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
							backgroundColor: colors,
						}
					]
				}
				var cleanliness = '#90EE90';
				var supplies = '#808000';
				var functionality = '#FAF884';

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

							ctx.fillText("INCIDENTS", (textX - 30), textY + 35);

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
				var cleanliness = '#90EE90';
				var supplies = '#808000';
				var functionality = '#FAF884';


				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						var jordan = value.questionnaire_answer;
						var color = value.questionnaire_color;
						labels_answer.push(jordan);
						color_answers.push(color);




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
					randomBackgroundColor = color_answers;
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

					type: 'pie',
					data: pieData_answer,
					options: pieOptions_answer
				})
			});
			$.get("/admin/reports/average-time-by-year/list", filter, function (data) {

				$('#average_time').text(parseFloat(data.data));
			});
			$.get("/admin/reports/total-sms-by-year/list", filter, function (data) {
				console.log(data.data);
				$('#total_sms').text(parseFloat(data.data));
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
			$.get("/admin/reports/trend-report-by-years/list", filter, function (data) {
				let datasets = [];
				var yValues = [];

				var key_label = [];
				$('#report_legend').html(data.data.legend);
				$.each(data.data, function (key, value) {
					if (key != 'legend') {
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
					}
				});

				let sum_reports_total = 0;


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
					},
					legend: {
						display: true
					},
				}
				if (window.report_bar != undefined)
					window.report_bar.destroy();
				window.report_bar = new Chart(reportBarChartCanvasDay, {

					type: 'bar',
					data: reportBarChartDataDay,
					options: reportBarChartOptionsDay
				})

			});

			$.get("/admin/reports/trend-incident-by-years/list", filter, function (data) {

				let datasets = [];
				var yValues = [];

				var key_label = [];
				$('#incident_legend').html(data.data.legend);
				$.each(data.data, function (key, value) {
					if (key != 'legend') {
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
					}
				});

				let sum_incidents_total = 0;


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
					},
					legend: {
						display: true
					},
				}
				if (window.incident_bar != undefined)
					window.incident_bar.destroy();
				window.incident_bar = new Chart(incidentBarChartCanvasDay, {

					type: 'bar',
					data: incidentBarChartDataDay,
					options: incidentBarChartOptionsDay
				})

			});

			$.get("/admin/reports/donut-report-by-daily/list", filter, function (data) {
				let labels = [];
				let data_value = [];
				let incident_report = 0;
				let color = [];
				if (data.data.length > 0) {
					$.each(data.data, function (key, value) {
						console.log(',,,,,,,,' + value.tenant_survey);
						labels.push(value.questionnaire);
						incident_report += parseInt(value.tenant_survey);
						data_value.push(value.percentage_share);
						color.push(value.questionnaire_color);
					});

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
							backgroundColor: color,
						}
					]
				}
				var cleanliness = '#90EE90';
				var supplies = '#808000';
				var functionality = '#FAF884';

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

							ctx.fillText("INCIDENTS", (textX - 30), textY + 35);

							ctx.save();
						}
					}],
					options: {
						pieOptions,
						events: ["mousemove", "mouseout", "click", "touchstart", "touchmove", "touchend"],
					}
				});
			});

			$.get("/admin/reports/donut-report-by-daily-answer/list", filter, function (data) {
				let labels_answer = [];
				let data_value_answer = [];
				let incident_report_answer = 0;
				let randomBackgroundColor = [];
				var cleanliness = '#90EE90';
				var supplies = '#808000';
				var functionality = '#FAF884';


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
					randomBackgroundColor = [];
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

					type: 'pie',
					data: pieData_answer,
					options: pieOptions_answer
				})
			});
			$.get("/admin/reports/average-time-by-years/list", filter, function (data) {
				console.log(data.data);
				$('#average_time').text(data.data);
			});
			$.get("/admin/reports/total-sms-by-years/list", filter, function (data) {
				console.log(data.data);
				$('#total_sms').text(data.data);
			});
		},


		dateSelected: function (e) {
			console.log(filter.day);
			//this.$nextTick(() => console.log(filter.day))
		},

		daySelected: function (e) {


			if (this.filter.day != null) {
				this.filterChartByDay();
			}

		},
		weekSelected: function (e) {


			if (this.filter.week != null) {
				this.filterChartByWeek();
			}
		},
		monthSelected: function (e) {

			if (this.filter.month != null) {
				this.filterChartByMonth();
			}
		},
		yearSelected: function (e) {


			if (this.filter.year != null) {
				console.log('ys');
				console.log(e);
				this.filterChartByYear();
			}
		},
		customizedSelected: function (e) {
			if (this.filter.by != 0) {
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
				var lastDay = moment(new Date(d_end.getFullYear(), d_end.getMonth() + 1, 0)).format("DD");
				var difference_in_time = d_end.getTime() - d_start.getTime();
				var difference_in_days = difference_in_time / (1000 * 3600 * 24); console.log(this.filter);

				if (y_start == y_end) {
					if (difference_in_days == 0) {
						this.filter.day = d_end;
						this.filterChartByDay();
					} else if (difference_in_days >= 1 && difference_in_days <= 7) {
						var week_of_month_start = Math.ceil((date_start - 1 - day_start) / 7);
						var week_of_month_end = Math.ceil((date_end - 1 - day_end) / 7);
						if (y_start == y_end) {
							if (week_of_month_start == week_of_month_end) {
								this.customize = 'week';
								this.filter.week = this.filter.end_date;
								this.filterChartByWeek();
							} else {
								this.filter.customized = 'month';
								this.filter.month = this.filter.end_date.substring(0, 7);
								this.filterChartByMonth();
							}
						} else {
							// wishlist
						}
					}
					else if (difference_in_days >= 8 && difference_in_days <= lastDay) {
						var week_of_month_start = Math.ceil((date_start - 1 - day_start) / 7);
						var week_of_month_end = Math.ceil((date_end - 1 - day_end) / 7);
						if (y_start == y_end) {

							if (week_of_month_start == week_of_month_end) {
								if (m_start == m_end) {
									this.filterChartByDaily();
								} else {
									this.filterChartByYear();
								}
							} else {
								if (m_start == m_end) {
									this.filter.customized = 'month';
									this.filter.month = this.filter.end_date.substring(0, 7);
									this.filterChartByMonth();
								} else {
									this.filterChartByYear();
								}
							}
						} else {
							if (m_start == m_end) {

								this.filterChartByMonth();
							} else {
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
		setToDate: function (date, day_num) {
			var day = date.getDay() || 7;


			date.setHours(-24 * (day - day_num));

			var myDate = new Date(date);
			var month_day = myDate.toLocaleString('en-PH', {
				day: '2-digit',
				month: '2-digit',
				year: 'numeric',
			}).substring(0, 5);

			return month_day;
		},

	},
	components: {
		datePicker
	}


};
</script> 
