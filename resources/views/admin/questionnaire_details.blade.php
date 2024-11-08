@extends('layout.admin.master')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Questionnaire : {{$questionnaire_details->questions}}
          <a type="button" href="/admin/questionnaires" class="btn btn-outline-primary btn-sm"><i class="fas fa-arrow-left"></i>&nbsp;Back to Questionnaires</a>
        </h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/admin">Home</a></li>
          <li class="breadcrumb-item"><a href="/admin/sites">Questionnairess</a></li>
          <li class="breadcrumb-item active">Site : {{$questionnaire_details->questions}}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<admin-questionnaire-answers></admin-questionnaire-answers>
<!-- <admin-building-floors></admin-building-floors>
<admin-building-rooms></admin-building-rooms>
 -->
@stop

@push('scripts')
@endpush