@extends('admin.adminlayouts.adminlayout')

@section('head')

    {!! HTML::style("assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css") !!}
    {!! HTML::style("assets/global/plugins/bootstrap-select/bootstrap-select.min.css") !!}
    {!! HTML::style("assets/global/plugins/select2/select2.css") !!}
    {!! HTML::style("assets/global/plugins/jquery-multi-select/css/multi-select.css") !!}

@stop


@section('mainarea')


    <!-- BEGIN PAGE HEADER-->
    <h3 class="page-title">
        {{$pageTitle}}
    </h3>
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <i class="fa fa-home"></i>
                <a href="{{route('admin.dashboard.index')}}">Home</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href="{{ route('admin.settings.edit','setting') }}">Settings</a>
                <i class="fa fa-angle-right"></i>
            </li>
            <li>
                <a href=""> Setting</a>
            </li>
        </ul>

    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->

            <div id="load">

                {{--INLCUDE ERROR MESSAGE BOX--}}
                @include('admin.common.error')
                {{--END ERROR MESSAGE BOX--}}


            </div>
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-cogs"></i>Edit {{$pageTitle}}
                    </div>
                    <div class="tools">
                    </div>
                </div>

                <div class="portlet-body form">

                    <!------------------------ BEGIN FORM---------------------->
                    {!! Form::model($setting, ['method' => 'PUT','files' => true,'class'=>'form-horizontal form-bordered' ,
                    'id' => 'edit_setting_form']) !!}

                    <div class="form-body">

                        <div class="form-group">
                            <label class="control-label col-md-2">Website Logo</label>
                            <div class="col-md-6">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail">

                                        <img src="{{$setting->getLogoImageAttribute()}}" height="30px" width="117px"/>

                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail">
                                    </div>
                                    <div>
                                    <span class="btn default btn-file">
                                        <span class="fileinput-new">
                                            Change image </span>
                                        <span class="fileinput-exists">
                                            Change </span>
                                        <input type="file" name="logo">
                                    </span>
                                        <a href="#" class="btn btn-sm red fileinput-exists" data-dismiss="fileinput">
                                            Remove </a>
                                    </div>
                                </div>
                                <div class="clearfix margin-top-10">
                                <span class="label label-danger">
                                    NOTE! </span> Image Size must be 117px x 30px

                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">Website: <span class="required">
                                * </span>
                            </label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="website" placeholder="Website Title"
                                       value="{{ $setting->website }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">Email: <span class="required">
                                * </span>
                            </label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="email" placeholder="Email"
                                       value="{{ $setting->email}}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-2 control-label">Name: <span class="required"> * </span></label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" placeholder="Name"
                                       value="{{ $setting->name}}">
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-2">Currency</label>
                            <div class="col-md-6">
                                <select class="bs-select form-control" data-show-subtext="true" name="currency">
                                    @foreach(\App\Models\Setting::CURRENCIES as $currency)
                                        <option @if($setting->currency == $currency['code']) selected
                                                @endif value="{{$currency['code']}}">{!!  $currency['symbol']!!}  {{$currency['code']}}
                                            ({{$currency['name']}})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <button type="button" onclick="updateSetting({{$setting->id}})" class="btn green"><i
                                            class="fa fa-check"></i> Submit
                                    </button>

                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                    <!------------------------- END FORM ----------------------->

                    </div>
                </div>
                <!-- END EXAMPLE TABLE PORTLET-->

            </div>
        </div>
        <!-- END PAGE CONTENT-->


    @stop

    @section('footerjs')

        <!-- BEGIN PAGE LEVEL PLUGINS -->
            {!! HTML::script("assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js") !!}
            {!! HTML::script('assets/global/plugins/bootstrap-select/bootstrap-select.min.js') !!}

            {!! HTML::script('assets/global/plugins/select2/select2.min.js') !!}
            {!! HTML::script('assets/global/plugins/jquery-multi-select/js/jquery.multi-select.js') !!}
            {!! HTML::script('assets/admin/pages/scripts/components-dropdowns.js') !!}



            <script>
                jQuery(document).ready(function () {
                    ComponentsDropdowns.init();
                });

                function updateSetting(id) {

                    let url = "{{ route('admin.settings.update',':id') }}";
                    url = url.replace(':id', id);
                    $.easyAjax({
                        type: 'POST',
                        url: url,
                        container: '#edit_setting_form',
                        file: true
                    });
                }
            </script>
            <!-- END PAGE LEVEL PLUGINS -->
@stop
