@extends('layout.admin.master')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Dashboard</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <!-- <div class="row">
      <div class="col-lg-12">
        <admin-sceen_uptime></admin-sceen_uptime>
      </div> 
    </div> -->
    <div class="row">
      <div class="col-lg-12">
        <admin-dashboard_restroom_monitoring></admin-dashboard_restroom_monitoring>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <admin-dashboard_questionnaire_survey></admin-dashboard_questionnaire_survey>
      </div>
      <div class="col-lg-6">
        <!-- <admin-dashboard_questionnaire_survey_two></admin-dashboard_questionnaire_survey_two> -->
      </div>
    </div>
    <div class="row">
    </div>
  </div>
</div>

@stop

@push('scripts')
@endpush