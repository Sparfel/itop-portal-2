
<?php //echo 'Un autre formulaire (Donnée Pro)';?>
<div class="row">
    <div class="col-sm-6">
        <div class="table-responsive">
{{--            <table class="table table-hover">--}}
            <table class="table">
                <tbody>
                <?php $fields_array = [
                    [ 'name' => 'department', 'label'=>'Department' ],
                    [ 'name' => 'service', 'label'=>'Service' ],

                    // [ 'name' => 'date_of_birth', 'type' => 'date'],
                    [ 'name' => 'domain', 'label'=>'Domain'],
                    // [ 'name' => 'url_facebook', 'type' => 'url' ],
                     [ 'name' => 'created_at', 'type' => 'datetime' , 'label'=>'Creation date'],

                ]; ?>
                @foreach ($fields_array as $field)
                    <tr>
                        @php
                            $field_name = $field['name'];
                            $field_label = $field['label'];
                            $field_type = isset($field['type'])? $field['type'] : '';
                        @endphp

        {{--                <th>{{ __("labels.backend.users.fields.".$field_name) }}</th>--}}
                        <th>{{ __($field_label) }}</th>

                        @if ($field_name == 'date_of_birth' && $user->$field_name != '')
                            <td>
                                @if(auth()->user()->id == $user->id)
                                    {{ $user->$field_name->isoFormat('LL') }}
                                @else
                                    {{ $user->$field_name->format('jS \\of F') }}
                                @endif
                            </td>
                        @elseif ($field_type == 'date' && $user->$field_name != '')
                            <td>
                                {{ $user->$field_name->isoFormat('LL') }}
                            </td>
                        @elseif ($field_type == 'datetime' && $user->$field_name != '')
                            <td>
                                {{ $user->$field_name->isoFormat('llll') }}
                            </td>
                        @elseif ($field_type == 'url')
                            <td>
                                <a href="{{ $user->$field_name }}" target="_blank">{{ $user->$field_name }}</a>
                            </td>
                        @else
                            <td>{{ $user->$field_name }}</td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="table-responsive">
            <table class="table ">
                <tbody>
                <?php $fields_array = [
                    [ 'name' => 'pc1', 'label'=>'PC n°1' ],
                    [ 'name' => 'pc2', 'label'=>'PC n°2' ],
                    [ 'name' => 'pc3', 'label'=>'PC n°3' ],
                    // [ 'name' => 'date_of_birth', 'type' => 'date'],
                    [ 'name' => 'updated_at', 'type' => 'datetime' , 'label'=>'Update date'],
                ]; ?>
                @foreach ($fields_array as $field)
                    <tr>
                        @php
                            $field_name = $field['name'];
                            $field_label = $field['label'];
                            $field_type = isset($field['type'])? $field['type'] : '';
                        @endphp

        {{--                <th>{{ __("labels.backend.users.fields.".$field_name) }}</th>--}}
                        <th>{{ __($field_label) }}</th>

                        @if ($field_name == 'date_of_birth' && $user->$field_name != '')
                            <td>
                                @if(auth()->user()->id == $user->id)
                                    {{ $user->$field_name->isoFormat('LL') }}
                                @else
                                    {{ $user->$field_name->format('jS \\of F') }}
                                @endif
                            </td>
                        @elseif ($field_type == 'date' && $user->$field_name != '')
                            <td>
                                {{ $user->$field_name->isoFormat('LL') }}
                            </td>
                        @elseif ($field_type == 'datetime' && $user->$field_name != '')
                            <td>
                                {{ $user->$field_name->isoFormat('llll') }}
                            </td>
                        @elseif ($field_type == 'url')
                            <td>
                                <a href="{{ $user->$field_name }}" target="_blank">{{ $user->$field_name }}</a>
                            </td>
                        @else
                            <td>{{ $user->$field_name }}</td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="callout callout-secondary">

    @csrf
    <div class="row">
        <div class="col-sm-6">
            <x-front.tab-panel-input
                title='Office n°1'
                name='office1'
                :options=$offices
{{--                :options="array('bureau 1', 'bureau 2')"--}}
                input='select2'
                :value='$user->office_id'
                :required="false">
            </x-front.tab-panel-input>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>{{__('DECT Phone')}}</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    </div>
                    <input type="text" value="{{$user->dect_phone}}" name="dect_phone" id="dect_phone" class="form-control" data-inputmask="'mask': ['999-999-9999 [x99999]', '+099 99 99 9999[9]-9999']" data-mask="" inputmode="text">
                </div>
                <!-- /.input group -->
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-sm-6">
            <x-front.tab-panel-input
                title='Office n°2'
                name='office2'
                {{--                :options="$pref->locations_list"--}}
                :options=$offices
                {{--                :options="array('bureau 1', 'bureau 2')"--}}
                input='select2'
                :value='$user->office_id_2'
                :required="false">
            </x-front.tab-panel-input>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>{{__('Phone n°2')}}</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    </div>
                    <input type="text" value="{{$user->internal_phone_2}}" name="intenal_phone_2" id="intenal_phone_2" class="form-control" data-inputmask="'mask': ['999-999-9999 [x99999]', '+099 99 99 9999[9]-9999']" data-mask="" inputmode="text">
                </div>
                <!-- /.input group -->
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
                <label>{{__('Fives Cellphone')}}</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                    </div>
                    <input type="text" value="{{$user->cellphone_id}}" name="cellphone_id" id="cellphone_id" class="form-control" data-inputmask="'mask': ['999-999-9999 [x99999]', '+099 99 99 9999[9]-9999']" data-mask="" inputmode="text">
                </div>
                <!-- /.input group -->
            </div>
        </div>

    </div>



    {{--          <div class="card-footer">--}}

    {{--          </div>--}}

</div>
