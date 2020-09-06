@extends('dashboard.layouts.app')
@section('title', __('Viewing').' '.ucfirst(__('addresses')))
@section('content')
<div class="row">
        <div class="box">
            <div class="box-header">
            @can('address.create')
                    <a href="{{ route('addresses.create') }}"  class="btn btn-primary"> {{ __('Add New')}}
                        <i class="fa fa-plus"></i>
                    </a>
            @endcan
            </div>
            <div class="box-body">
                <table class="table table-striped table-bordered" id="example1">
                    <thead>
                        <tr>
                            <th> {{ __('ID') }} </th>
                            <th> {{ __('Address') }} </th>
                            <th> {{ __('Created at') }} </th>
                            <th> {{ __('Actions')}} </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $row)
                        <tr>
                            <td> {{ $row->id }} </td>
                            <td> {{ $row->address }} </td>
                            <td> {{ $row->created_at }} </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-xs btn-success" type="button" data-toggle="dropdown" aria-expanded="false">{{ __('Actions')}}
                                        <i class="fa fa-angle-down"></i>
                                    </button>
                                    <ul class="dropdown-menu pull-left" role="menu">
                                        @can('address.view')
                                        <li>
                                            <a href="{{ route('addresses.show', $row->id) }}">
                                                <i class="fa fa-eye"></i> {{ __('View') }} </a>
                                        </li>
                                        @endcan
                                        @can('address.edit')
                                        <li>
                                            <a href="{{ route('addresses.edit', $row->id) }}">
                                                <i class="fa fa-pencil-square-o"></i> {{ __('Edit') }} </a>
                                        </li>
                                        @endcan
                                        @can('address.delete')
                                        <li>
                                            <a class="delete_btn" data-id="{{ $row->id }}" data-toggle="modal" data-target="#delete_modal">
                                                <i class="fa fa-trash"></i> {{ __('Delete') }} </a>
                                        </li>
                                        @endcan
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr class="odd gradeX"><td colspan="7" class="alert alert-danger">{{ __('No data') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Modal -->
<div id="delete_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header alert alert-danger">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">{{ __('Are you sure you want to delete ?') }}</h4>
            </div>
            <div class="modal-footer">
                <form id="delete_form" method="POST" action="{{ route('addresses.destroy', 0) }}">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-danger">{{ __('Yes, delete it!') }}</button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
@section('css')
<!-- DataTables -->
@if(App::getLocale() != 'ar')
<link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
@else
<link rel="stylesheet" href="{{ asset('plugins/datatables/datatables.bootstrap-rtl.css') }}">
@endif
@endsection
@section('js')
<!-- DataTables -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script>
$(document).ready(function () {
    $('.delete_btn').click(function (){
        var id = $(this).attr('data-id');
        var action = $('#delete_form').attr('action');
        var new_action = action.substr(0, action.lastIndexOf('/') + 1) + id;
        $('#delete_form').attr('action', new_action);
    });
    @if(count($rows) > 0)
    $("#example1").DataTable(
        
        {
            @if(App::getLocale() == 'ar')
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Arabic.json"
            },
            @endif
            "order": [[ 2, "desc" ]],
        }
        
    );
    @endif
});
</script>
@endsection
