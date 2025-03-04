@extends('adminlte::page')

@section('content_header')
    <h1><i class="fa-solid fa-shield-halved"></i> {{ __('Edit Role') }}</h1>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Card AdminLTE -->
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">{{ __('Edit Role') }}</h3>
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

                <form action="{{ route('roles.update', $role) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <!-- Nom du rôle -->
                    <div class="form-group">
                        <label for="name">{{ __('Name') }}</label>
                        <input type="text" name="name" id="name"
                               value="{{ $role->name ?? old('name') }}"
                               class="form-control" required autofocus>
                    </div>

                    <!-- Permissions -->
                    @if ($permissions->count())
                        <div class="form-group">
                            <label>{{ __('Permissions') }}</label>
                            <div>
                                @foreach ($permissions as $id => $name)
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               name="permissions[]"
                                               id="permission-{{ $id }}"
                                               class="custom-control-input"
                                               value="{{ $id }}"
                                               @if(in_array($id, old('permissions', [])) || $role->permissions->contains($id)) checked @endif>
                                        <label class="custom-control-label" for="permission-{{ $id }}">{{ $name }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Bouton Sauvegarder -->
                    <div class="form-group">
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
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
