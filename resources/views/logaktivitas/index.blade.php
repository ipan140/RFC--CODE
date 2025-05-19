@extends('partials.headerFooter')

@section('content')
    @include('partials.sidebar')

    <div class="main-content p-3"> {{-- Gunakan padding saja, tanpa container --}}
        <div class="card shadow-sm w-100">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-table me-2"></i>Riwayat Aktivitas
                </h5>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped align-middle mb-0">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>No</th> {{-- Tambahkan kolom nomor --}}
                                <th>Log</th>
                                <th>Event</th>
                                <th>Model</th>
                                <th>ID Model</th>
                                <th>Deskripsi</th>
                                <th>Oleh</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @forelse($logs as $log)
                                <tr>
                                    <td>{{ $loop->iteration + ($logs->currentPage() - 1) * $logs->perPage() }}</td> {{-- Nomor urut --}}
                                    <td>{{ $log->log_name }}</td>
                                    <td>
                                        @if($log->event === 'created')
                                            <span class="badge bg-success">Created</span>
                                        @elseif($log->event === 'updated')
                                            <span class="badge bg-warning text-dark">Updated</span>
                                        @elseif($log->event === 'deleted')
                                            <span class="badge bg-danger">Deleted</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($log->event) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ class_basename($log->subject_type) }}</td>
                                    <td>{{ $log->subject_id }}</td>
                                    <td class="text-start">
                                        @if ($log->properties->has('attributes'))
                                            @foreach ($log->properties['attributes'] as $key => $value)
                                                <div><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</div>
                                            @endforeach
                                        @else
                                            {{ $log->description }}
                                        @endif
                                    </td>
                                    <td>{{ optional($log->causer)->name ?? '-' }}</td>
                                    <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Belum ada data log.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="p-3">
                    {{ $logs->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
