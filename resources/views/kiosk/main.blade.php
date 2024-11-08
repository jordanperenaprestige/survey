@extends('layout.kiosk_default.master')
@section('content')
<div>
    <div class="row">
    </div>
</div>
<employee-concern :default-room ="{{$default_room}}"></employee-concern> 

<!-- /.content -->
@stop

@push('scripts')
<script>
    var screensaver_handle = null;

    //setInterval(getUpdate, (2 * 60 * 1000));
    //setInterval(getUpdate, 10000);

    // function getUpdate() {
    //     $.get("/api/v1/get-update", function(data) {
    //       //  console.log(data);
    //         if (data.data.length > 0) {
    //            // location.reload();
    //         }
    //     });
    // }
</script>
@endpush