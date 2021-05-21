@extends('dashboard.layouts.app')
@section('title', __('Editing').' '.ucfirst(__('salon')))
@section('content')
<form role="form" class="form-horizontal" method="POST" action="{{ route('salons.update', $row->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="form-body">
        @component('input', ['type' => 'text', 'label' => 'Name', 'required' => true, 'value' => $row->name])
            name
        @endcomponent
        @component('input', ['type' => 'textarea', 'label' => 'Description', 'required' => false, 'value' => $row->description])
            description
        @endcomponent
        @component('input', ['type' => 'text', 'label' => 'E-Mail Address', 'required' => true, 'value' => $row->email])
            email
        @endcomponent
        @component('input', ['type' => 'text', 'label' => 'Phone', 'required' => true, 'value' => $row->phone])
            phone
        @endcomponent
        @if (!empty($row->image))
        @component('input_image', ['width' => 200, 'height' => 200, 'label' => 'Image', 'src' =>  url($row->image)  ])
            image
        @endcomponent
        @else @component('input_image', ['width' => 200, 'height' => 200, 'label' => 'Image', 'src' =>  url('uploads/user.png')  ])
        image
    @endcomponent 
    @endif
    @if (!empty($row->cover))
        @component('input_cover', ['width' => 200, 'height' => 200, 'label' => 'cover', 'src' =>  url($row->cover)  ])
            cover
        @endcomponent
        @else @component('input_cover', ['width' => 200, 'height' => 200, 'label' => 'cover', 'src' =>  url('uploads/user.png')  ])
        cover
    @endcomponent 
    @endif
        {{-- @component('input_image', ['width' => 200, 'height' => 200, 'label' => 'Image', 'src' =>"@if (!empty($row->image)) {{ url($row->image)}} @else url('uploads/user.png') @endif"])
            image
        @endcomponent --}}
         {{-- @component('input_image', ['width' => 800, 'height' => 400, 'label' => 'Cover', 'src' =>" @if (!empty($row->cover)) {{ url($row->cover)}} @else url('uploads/user.png') @endif"])
            cover
        @endcomponent --}}
        <div class="form-group form-md-line-input">
            <div class="col-md-2"></div>
            <div class="col-md-10">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ __('Commercial register') }}</div>
                    <div class="panel-body">
                        <a href="{{ route('file_show', $row->commercial_register) }}" target="_blank">{{ $row->commercial_register }}</a>
                    </div>
                </div>
            </div>
        </div>
        @component('input', ['type' => 'file', 'label' => 'Commercial register', 'required' => false])
            commercial_register
        @endcomponent
        <div class="form-group form-md-line-input">
            <label class="col-md-2 control-label">{{ ucfirst(__('governorate')) }}</label>
            <div class="col-md-10">
                <select class="js-example-basic-single js-states form-control" id="governorate_id" name="governorate_id">
                    @foreach(\Modules\Governorate\Entities\Governorate::latest()->get() as $governorate)
                    <option value="{{ $governorate->id }}" {{ old('governorate_id') == $governorate->id || $governorate->id == $row->governorate_id ? 'selected': ''}}>{{ $governorate->name }}</option>
                    @endforeach
                </select>
                <div class="form-control-focus"> </div>
            </div>
        </div>
        <div class="form-group form-md-line-input">
            <label class="col-md-2 control-label">{{ ucfirst(__('city')) }}</label>
            <div class="col-md-10">
                <select class="js-example-basic-single js-states form-control" id="city_id" name="city_id">
                </select>
                <div class="form-control-focus"> </div>
            </div>
        </div>
        <div class="form-group form-md-line-input">
            <label class="col-md-2 control-label">{{ ucfirst(__('Gender')) }}</label>
            <div class="col-md-10">
                <select class="js-example-basic-single js-states form-control" id="gender" name="gender">
                    <option value="male" {{ old('gender') == 'male' || $row->gender == 'male' ? 'selected': ''}}>{{ ucfirst(__('male')) }}</option>
                    <option value="female" {{ old('gender') == 'female' || $row->gender == 'female'  ? 'selected': ''}}>{{ ucfirst(__('female')) }}</option>
                </select>
                <div class="form-control-focus"> </div>
            </div>
        </div>
        <div class="form-group form-md-line-input repeater">
            <label class="col-md-2 control-label">{{ ucfirst(__('Working days')) }}</label>
            <div class="col-md-10"  data-repeater-list="works">
                @if(old('works'))  
                    @foreach(old('works') as $work)
                    <div class="row" data-repeater-item>
                        <div class="col-md-3">
                            <label>{{ __('From') }}</label>
                            <input type="time" class="form-control" placeholder="{{ __('From') }}"  name="from" value="{{ $work['from'] }}"/>
                        </div>
                        <div class="col-md-3">
                            <label>{{ __('To') }}</label>
                            <input type="time" class="form-control" placeholder="{{ __('To') }}" name="to" value="{{ $work['to'] }}"/>
                        </div>
                        <div class="col-md-4">
                            <label>{{ __('Day') }}</label>
                            <select class="form-control" name="day">
                                @foreach(\App\User::days() as $key => $day)
                                    <option value="{{ $key }}" {{ $key == $work['day'] ? 'selected': '' }}>{{ ucfirst($day) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <br />
                            <input data-repeater-delete type="button" class="btn btn-danger" value="{{ __('Delete') }}" />
                        </div>
                        <br />
                        <br />
                    </div>
                    @endforeach
                @elseif($row->works()->count() > 0)
                    @foreach($row->works()->get() as $work)
                    <div class="row" data-repeater-item>
                        <div class="col-md-3">
                            <label>{{ __('From') }}</label>
                            <input type="time" class="form-control" placeholder="{{ __('From') }}"  name="from" value="{{ $work->from }}"/>
                        </div>
                        <div class="col-md-3">
                            <label>{{ __('To') }}</label>
                            <input type="time" class="form-control" placeholder="{{ __('To') }}" name="to" value="{{ $work->to }}"/>
                        </div>
                        <div class="col-md-4">
                            <label>{{ __('Day') }}</label>
                            <select class="form-control" name="day">
                                @foreach(\App\User::days() as $key => $day)
                                    <option value="{{ $key }}" {{ $key == $work->day ? 'selected': '' }}>{{ ucfirst($day) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <br />
                            <input data-repeater-delete type="button" class="btn btn-danger" value="{{ __('Delete') }}" />
                        </div>
                        <br />
                        <br />
                    </div>
                    @endforeach
                @else
                <div class="row" data-repeater-item>
                    <div class="col-md-3">
                        <label>{{ __('From') }}</label>
                        <input type="time" class="form-control" placeholder="{{ __('From') }}"  name="from"/>
                    </div>
                    <div class="col-md-3">
                        <label>{{ __('To') }}</label>
                        <input type="time" class="form-control" placeholder="{{ __('To') }}"  name="to"/>
                    </div>
                    <div class="col-md-4">
                        <label>{{ __('Day') }}</label>
                        <select class="form-control" name="day">
                            @foreach(\App\User::days() as $key => $day)
                            <option value="{{ $key }}">{{ ucfirst($day) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <br />
                        <input data-repeater-delete type="button" class="btn btn-danger" value="{{ __('Delete') }}" />
                    </div>
                    <br />
                    <br />
                </div>
                @endif
                <br />
                <input data-repeater-create type="button" class="btn btn-success" value="{{ __('Add working day') }}" />
                <br />
                <br />
            </div>
        </div>
        <div class="form-group form-md-line-input">
            <label class="col-md-2 control-label">{{ ucfirst(__('services')) }}</label>
            <div class="col-md-10">
                <select class="js-example-basic-single js-states form-control" id="services[]" name="services[]" multiple="multiple">
                    @foreach(\Modules\Service\Entities\Service::all() as $service)
                    <option value="{{ $service->id }}" @if(old('services')) @if(in_array($service->id, old('services'))) selected @endif @else @if(in_array($service->id, $selected)) selected @endif  @endif>{{ $service->title }}</option>
                    @endforeach
                </select>
                <div class="form-control-focus"> </div>
            </div>
        </div>
        @component('checkbox', ['label' => 'Sponsored', 'value' => $row->is_sponsored])
            is_sponsored
        @endcomponent
        @component('checkbox', ['label' => 'Active', 'value' => $row->is_active])
            is_active
        @endcomponent
    </div>
    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-2 col-md-10">
                <button type="rest" class="btn btn-default">{{ __('Cancel')}}</button>
                <button type="submit" class="btn btn-primary">{{ __('Submit')}}</button>
            </div>
        </div>
    </div>
</form>
@endsection
@section('css')
 
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"></script>
<script>
$(document).ready(function() {
    $('.js-example-basic-single').select2();
    function readURL(input, id) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $(id).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $('.repeater').repeater({
        // (Optional)
        // start with an empty list of repeaters. Set your first (and only)
        // "data-repeater-item" with style="display:none;" and pass the
        // following configuration flag
        initEmpty: false,
        // (Optional)
        // "show" is called just after an item is added.  The item is hidden
        // at this point.  If a show callback is not given the item will
        // have $(this).show() called on it.
        show: function () {
            $(this).slideDown();
        },
        // (Optional)
        // "hide" is called when a user clicks on a data-repeater-delete
        // element.  The item is still visible.  "hide" is passed a function
        // as its first argument which will properly remove the item.
        // "hide" allows for a confirmation step, to send a delete request
        // to the server, etc.  If a hide callback is not given the item
        // will be deleted.
        hide: function (deleteElement) {
            if(confirm('Are you sure you want to delete this element?')) {
                $(this).slideUp(deleteElement);
            }
        },
        // (Optional)
        // Removes the delete button from the first list item,
        // defaults to false.
        isFirstItemUndeletable: true
    })
    $("#image").change(function() {
        readURL(this, "#image_image");
    });
    $("#cover").change(function() {
        readURL(this, "#cover_image");
    });
    function loadCities() {
        var selected_city = "{{ $row->city }}";
        var id = $('#governorate_id').val();
        var action = "{{ url('/api/governorates/find?include=cities') }}";
        $.ajax({
            url:  action,
            type: 'POST',
            dataType: 'JSON',
            data: {id: id},
            headers: {
                'X-localization': '{{ App::getLocale() }}',
            },
            success: function(data, status){
                console.log(data.data.cities.data)
                let cities = data.data.cities.data;
                var i;
                $("#city_id").empty()
                for(i = 0; i < cities.length; i++) {
                    $("#city_id").append(`<option value="${cities[i].id}" ${selected_city == cities[i].id ? 'selected': ''}>${cities[i].name}</option>`)
                }
            }
        });
    }
    loadCities();
    $('#governorate_id').change(loadCities)
});
</script>
@endsection