@extends('adminlte::page')

@section('content_header')
    <h1><i class="fa-solid fa-lock"></i> {{ __('Permissions') }}</h1>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Bouton d'ajout -->
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ route('permissions.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> {{ __('Add Permission') }}
                </a>
            </div>
        </div>
        <!-- Card contenant la table des permissions -->
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-list"></i> {{ __('List of Permissions') }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Roles') }}</th>
                                <th style="width: 15%">{{ __('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($permissions as $permission)
                                <tr>
                                    <td>{{ $permission->name }}</td>
                                    <td>{{ $permission->roles->implode('name', ', ') }}</td>
                                    <td>
                                        <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-edit"></i> {{ __('Edit') }}
                                        </a>
                                        &nbsp;
                                        <form action="{{ route('permissions.destroy', $permission) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">{{ __('No permissions found.') }}</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
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
    <!-- Ajoutez vos styles personnalisés ici -->
@endsection

@section('js')
    <!-- Ajoutez vos scripts personnalisés ici -->
@endsection
