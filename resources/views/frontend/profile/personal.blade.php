<div class="callout callout-secondary">

    @csrf
    <div class="row">
        <div class="col-sm-6">
            <x-front.tab-panel-input
                title='Address'
                name='address'
                :value='$user->address'
                input='text'
                :required="false">
            </x-front.tab-panel-input>
        </div>
        <div class="col-sm-6">
            <x-front.tab-panel-input
                title='Postal Code'
                name='postal_code'
                :value='$user->postal_code'
                input='text'
                :required="false">
            </x-front.tab-panel-input>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <x-front.tab-panel-input
                title='City'
                name='city'
                :value='$user->city'
                input='text'
                :required="false">
            </x-front.tab-panel-input>
        </div>
        <div class="col-sm-6">
            <x-front.tab-panel-input
                title='Country'
                name='country'
                :value='$user->country'
                input='text'
                :required="false">
            </x-front.tab-panel-input>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <x-front.tab-panel-input
                label="My address is visible"
                name='is_address_visible'
                :value='$user->is_address_visible'
                input='checkbox' >
            </x-front.tab-panel-input>
        </div>
    </div>
</div>
