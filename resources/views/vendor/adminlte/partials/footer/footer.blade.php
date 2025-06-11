{{--<footer class="main-footer">--}}
{{--    @yield('footer')--}}

{{--    <div class="float-right d-none d-sm-inline-block">--}}
{{--        <a href="https://github.com/sparfell" target="_blank"><i class="fab fa-github"></i></a>--}}

{{--    </div>--}}
{{--    <strong>--}}
{{--        Projet <a href="#">iTop Portal</a>.--}}
{{--    </strong>--}}
{{--    | <a href="/documentation">Documentation</a> |--}}
{{--    <a href="/contributors">Contributeurs</a> |--}}
{{--</footer>--}}
{{--<footer class="main-footer">--}}
{{--    <div class="float-right d-none d-sm-inline">--}}
{{--        <strong>Version </strong>{{Config::get('app.version')}}--}}
{{--    </div>--}}
{{--    <strong>Copyright © 2025 </strong><a href="https://github.com/Sparfel/itop-portal-2" target="_blank">Sparfel Portal</a>--}}
{{--</footer>--}}


<footer class="main-footer">
    <div class="row">
        <div class="col-md-4 text-left">
            <strong>Copyright © 2025 <a href="https://github.com/Sparfel/itop-portal-2" target="_blank"><i class="fab fa-github"></i> Sparfel Portal</a></strong>
        </div>
        <div class="col-md-4 text-center">
            - <strong><a href="https://portal-doc.hennebont-kerroch.fr" target="_blank">Documentation</a></strong> -
        </div>
        <div class="col-md-4 text-right">
            <div class="d-none d-sm-inline">
                <strong>Version </strong>{{ Config::get('app.version') }}
            </div>
        </div>
    </div>
</footer>
