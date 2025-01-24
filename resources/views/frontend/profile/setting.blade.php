{{--<form role="form" method="POST" >--}}
 <div class="callout callout-secondary">

         @csrf
         <div class="row">
             <div class="col-sm-6">
             <x-front.tab-panel-input
                                     title='First Name'
                                     name='first_name'
                                     :value='$user->first_name'
                                     input='text'
                                     :required="true">
             </x-front.tab-panel-input>
             </div>
             <div class="col-sm-6">
             <x-front.tab-panel-input
                                     title='Last Name'
                                     name='last_name'
                                     :value='$user->last_name'
                                     input='text'
                                     :required="true">
             </x-front.tab-panel-input>
             </div>
         </div>
         <div class="row">
             <div class="col-sm-6">
                 <div class="form-group">
                     <label>{{__('Phone')}}</label>
                     <div class="input-group">
                         <div class="input-group-prepend">
                             <span class="input-group-text"><i class="fas fa-phone"></i></span>
                         </div>
                         <input type="text" value="{{$user->internal_phone}}" name="phone" id="phone" class="form-control" data-inputmask="'mask': ['999-999-9999 [x99999]', '+099 99 99 9999[9]-9999']" data-mask="" inputmode="text">
                     </div>
                     <!-- /.input group -->
                 </div>
             </div>
            <div class="col-sm-6">
                 <div class="form-group">
                     <label>{{__('Mobile Phone')}}</label>
                     <div class="input-group">
                         <div class="input-group-prepend">
                             <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                         </div>
                         <input type="text" value="{{$user->mobile}}" name="mobile_phone" id="mobile_phone" class="form-control" data-inputmask="'mask': ['999-999-9999 [x99999]', '+099 99 99 9999[9]-9999']" data-mask="" inputmode="text">
                     </div>
                     <!-- /.input group -->
                 </div>
             </div>

         </div>

     <div class="row">

            <div class="col-sm-6">
                 <div class="form-group danger">
                     <label>{{__('Email')}}</label>
                     <div class="input-group">
                         <div class="input-group-prepend">
                             <button type="button" class="btn btn-danger"><i class="fas fa-at"></i></button>

                         </div>
                         <input type="text" name="email" id="email" class="form-control" value="{{$user->email}}">
                         <div class="input-group-append">
                             <button type="button" class="btn btn-danger">{{__('It\'s your login too !')}}</button>
                         </div>
                     </div>
                     <!-- /.input group -->
                 </div>
             </div>
             <div class="col-sm-6">
                 <div class="form-group">
                     <label>{{__('Gender')}}</label>
                     <div class="input-group">
                         <div class="input-group-prepend">
                             <span class="input-group-text"><i class="fas fa-venus-mars"></i></i></span>
                         </div>
                         <select name="gender" id="gender" class="form-control">
                             <option value="M" @if ($user->gender =='M') selected @endif>{{__('Male')}}</option>
                             <option value="F" @if ($user->gender =='F') selected @endif>{{__('Female')}}</option>
                         </select>
                     </div>
                     <!-- /.input group -->
                 </div>
             </div>
         </div>



{{--          <div class="card-footer">--}}

{{--          </div>--}}

  </div>
{{--    <button type="submit" class="btn btn-primary float-right">Valider</button>--}}
{{--</form>--}}
