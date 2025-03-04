@extends('adminlte::page')

@section('css')
    <!-- Ajoutez ici vos styles personnalisés si besoin -->
@endsection

@section('content_header')
    <h1><i class="fa-solid fa-shield-halved"></i> {{ __('Roles') }}</h1>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Bouton pour ajouter un rôle -->
        <div class="row mb-3">
            <div class="col-12">
                <a href="{{ route('roles.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> {{ __('Add Role') }}
                </a>
            </div>
        </div>

        <!-- Card contenant la table -->
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-list"></i> {{ __('List of Roles') }}
                        </h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Permissions') }}</th>
                                <th style="width: 15%">{{ __('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($roles as $role)
                                <tr>
                                    <td>{{ $role->name }}</td>
                                    <td>{{ $role->permissions_count }}</td>
                                    <td>
                                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-info">
{{--                                        <a href="{{ route('permission-editor.roles.edit', $role) }}" class="btn btn-sm btn-info">--}}
                                            <i class="fas fa-edit"></i> {{ __('Edit') }}
                                        </a>
                                        &nbsp;
                                        <form action="{{ route('roles.destroy', $role) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('{{ __('Are you sure?') }}');">
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
                                    <td colspan="3">{{ __('No roles found.') }}</td>
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

@section('js')
    <!-- Ajoutez ici vos scripts personnalisés si besoin -->
@endsection
