<div class="card card-info card-outline">
    <div class="card-header">
        <h3 class="card-title"> <i class="fa-solid fa-ticket"></i> {{ __('Manage Tickets') }}</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <ul class="nav nav-pills flex-column">
            <li class="nav-item active">
                <a href="#" class="nav-link">
                    <i class="fa-solid fa-ticket"></i> {{__('All')}}
                    <span class="badge bg-secondary float-right"> {{ $totalTickets }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fa-solid fa-triangle-exclamation"></i> {{__('Critical')}}
                    <span class="badge @if ($requestsByPriority> 0) bg-danger @else bg-secondary @endif float-right">{{ $requestsByPriority }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fa-regular fa-file-lines"></i> {{__('New')}}
                    <span class="badge bg-secondary float-right">{{$requestsByStatus['new'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fa-solid fa-user-check"></i> {{__('Assigned')}}
                    <span class="badge bg-secondary float-right">{{$requestsByStatus['assigned'] }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fa-regular fa-square-check"></i> {{__('Resolved')}}
                    <span class="badge bg-secondary float-right">{{$requestsByStatus['resolved'] }}</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- /.card-body -->
</div>

