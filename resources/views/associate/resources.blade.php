@extends('associate.main')

@section('title', 'Asif Associates | Resources')

@section('content')
<div class="col-md-12 container-fluid">

    <div class="my-4">
        <h4 class="mb-0"><i class="fe fe-folder fe-16 mr-2"></i> Resources</h4>
        <small class="text-muted">Templates, formats and documents from Asif Associates</small>
    </div>

    @forelse(['Tax', 'Audit', 'Advisory', 'Corporate'] as $cat)
        @if(isset($resources[$cat]) && $resources[$cat]->count())
        <div class="card shadow mb-4">
            <div class="card-header d-flex align-items-center">
                <strong class="card-title mb-0">
                    @if($cat === 'Tax') <i class="fe fe-file-text fe-16 mr-2 text-warning"></i>
                    @elseif($cat === 'Audit') <i class="fe fe-search fe-16 mr-2 text-info"></i>
                    @elseif($cat === 'Advisory') <i class="fe fe-briefcase fe-16 mr-2 text-success"></i>
                    @else <i class="fe fe-layers fe-16 mr-2 text-primary"></i>
                    @endif
                    {{ $cat }}
                </strong>
                <span class="badge badge-secondary ml-2">{{ $resources[$cat]->count() }}</span>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resources[$cat] as $res)
                        <tr>
                            <td>
                                <strong>{{ $res->name }}</strong>
                                <br><small class="text-muted">{{ $res->original_filename }}</small>
                            </td>
                            <td class="text-muted">{{ $res->description ?? '—' }}</td>
                            <td class="text-nowrap">
                                <a href="{{ route('ass.resources.download', $res->id) }}"
                                   class="btn btn-primary btn-sm" title="Download">
                                    <i class="fe fe-download fe-12"></i> Download
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    @empty
    @endforelse

    @if($resources->isEmpty())
    <div class="card shadow">
        <div class="card-body text-center text-muted py-5">
            <i class="fe fe-folder fe-32 mb-3 d-block"></i>
            No resources available yet. Contact admin.
        </div>
    </div>
    @endif

</div>
@endsection
