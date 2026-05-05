<x-layout.app>
    <div class="row gap-2">
        <div class="col-sm-8 mx-auto">
            @include('pages.account.profile._partials.user-info')
        </div>
        <div class="col-sm-8 mx-auto">
            @include('pages.account.profile._partials.account-setting')
        </div>
    </div>

    @include('pages.account.profile._modal-form')
    @include('pages.account.profile._modal-reset-password')

</x-layout.app>
