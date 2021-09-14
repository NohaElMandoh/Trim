@extends('dashboard.layouts.app')
@section('title', __('Editing').' '.ucfirst(__('user')))
@section('content')
    <form role="form" class="form-horizontal" method="POST" action="{{ route('factories.update', $row->id) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-body">
            @component('input', ['type' => 'text', 'label' => 'Name', 'required' => true, 'value' => $row->name])
                name
            @endcomponent
            @component('input', ['type' => 'text', 'label' => 'E-Mail Address', 'required' => true, 'value' => $row->email])
                email
            @endcomponent
            @component('input', ['type' => 'text', 'label' => 'Phone', 'required' => true, 'value' => $row->phone])
                phone
            @endcomponent

            @component('input', ['type' => 'password', 'label' => 'Password', 'required' => true])
                password
            @endcomponent

            @component('input', ['type' => 'text', 'label' => 'address', 'required' => true, 'value' => $row->address])
                address
            @endcomponent

            @component('input', ['type' => 'text', 'label' => 'Sales manager name', 'required' => true,'value' => $row->name_sales])
                name_sales
            @endcomponent
            @component('input', ['type' => 'text', 'label' => 'Sales phone', 'required' => true,'value' => $row->phone2])
                phone2
            @endcomponent

            @component('input_image', ['width' => 200, 'height' => 200, 'label' => 'Image', 'src' =>url($row->image)])
                image
            @endcomponent
            <div class="form-group form-md-line-input">
                <label class="col-md-2 control-label">{{ ucfirst(__('roles')) }}</label>
                <div class="col-md-10">
                    <select class="js-example-basic-single js-states form-control" id="roles[]" name="roles[]"
                            multiple="multiple">

                        <option value="4" selected>{{ucfirst(__('factories'))}}</option>


                    </select>
                    <div class="form-control-focus"></div>
                </div>
            </div>

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
    <script>
        $(document).ready(function () {
            $('.js-example-basic-single').select2();

            function readURL(input, id) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $(id).attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#image").change(function () {
                readURL(this, "#image_image");
            });

            function loadCities() {
                var selected_city = "{{ $row->city }}";
                var id = $('#governorate_id').val();
                var action = "{{ url('/api/governorates/find?include=cities') }}";
                $.ajax({
                    url: action,
                    type: 'POST',
                    dataType: 'JSON',
                    data: {id: id},
                    headers: {
                        'X-localization': '{{ App::getLocale() }}',
                    },
                    success: function (data, status) {
                        console.log(data.data.cities.data)
                        let cities = data.data.cities.data;
                        var i;
                        $("#city_id").empty()
                        for (i = 0; i < cities.length; i++) {
                            $("#city_id").append(`<option value="${cities[i].id}" ${selected_city == cities[i].id ? 'selected' : ''}>${cities[i].name}</option>`)
                        }
                    }
                });
            }

            loadCities();
            $('#governorate_id').change(loadCities)
        });
    </script>
@endsection
