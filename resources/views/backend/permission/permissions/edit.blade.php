@extends('adminlte::page')

@section('content_header')
    <h1><i class="fa-solid fa-lock"></i> {{ __('Edit Permission') }}</h1>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Card AdminLTE -->
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Edit Permission') }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('permissions.update', $permission) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <!-- Nom de la permission -->
                            <div class="form-group">
                                <label for="name">{{ __('Name') }}</label>
                                <input type="text" name="name" id="name"
                                       value="{{ $permission->name ?? old('name') }}"
                                       class="form-control" required autofocus>
                            </div>

                            <!-- Roles assignés -->
                            @if ($roles->count())
                                <div class="form-group">
                                    <label>{{ __('Roles') }}</label>
                                    <div>
                                        @foreach ($roles as $id => $name)
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="roles[]" id="role-{{ $id }}" class="custom-control-input" value="{{ $id }}"
                                                       @if(in_array($id, old('roles', [])) || $permission->roles->contains($id)) checked @endif>
                                                <label class="custom-control-label" for="role-{{ $id }}">{{ $name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Bouton Sauvegarder -->
                            <div class="form-group">
                                <a href="{{ route('permissions.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> {{ __('Back to the list') }}
                                </a>
                                &nbsp;
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> {{ __('Save') }}
                                </button>

                            </div>
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
@endsection

@section('footer')
    &nbsp;
@endsection

@section('css')
    <!-- Vos styles personnalisés -->
@endsection

@section('js')
    <!-- Vos scripts personnalisés -->
@endsection
