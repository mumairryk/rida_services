@extends("admin.template.layout")

@section("header")
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="{{asset('')}}admin-assets/plugins/table/datatable/custom_dt_customer.css">
@stop


@section("content")

<style>
    #example2_info{
        display: none
    }
</style>
<div class="card mb-5">
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-condensed table-striped" id="example2">
            <thead>
                <tr>
                <th>#</th>
                <th>Reported By</th>
                <th>User</th>
                <th>Problem</th>
                <th>Reason</th>
                <th>Reported On</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=0; ?>
                @foreach($list as $datarow) 
                    <?php $i++ ?>
                    <tr>
                        <td>{{$i}}</td>
                        <td>
                            {{$datarow->user->name}}
                        </td>
                        <td>
                            {{$datarow->report_user->name}}
                        </td>
                        <td>
                            {{$datarow->reason}}
                        </td>
                        <td>
                            {{report_user_problem($datarow->problem_id)}}
                        </td>
                        <td>{{web_date_in_timezone($datarow->created_at,'d-M-Y h:i A')}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
</div>
@stop

@section("script")
<script src="{{asset('')}}admin-assets/plugins/table/datatable/datatables.js"></script>
<script>
$('#example2').DataTable({
      "paging": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "responsive": true,
    });
    </script>
@stop