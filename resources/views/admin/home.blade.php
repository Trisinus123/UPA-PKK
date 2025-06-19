@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Section: Dashboard Header -->
    <div class="row mb-4">
        <div class="col">
            <h3 class="fw-bold text-dark">Dashboard Admin</h3>
            <p class="text-muted">Selamat datang kembali, berikut ringkasan data saat ini.</p>
        </div>
    </div>

    <!-- Section: Stats Cards -->
    <div class="row g-3 mb-5">
        <!-- Pending Jobs -->
        <div class="col-md-4">
            <div class="card text-white bg-info shadow-lg border-0 rounded-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase fw-semibold">Pending Jobs</h6>
                        <h2 class="fw-bold">{{ App\Models\Job::where('status', 'pending')->count() }}</h2>
                    </div>
                    <i class="fas fa-clock fa-3x opacity-75"></i>
                </div>
                <div class="card-footer border-0 bg-transparent">
                    <a href="{{ route('admin.jobs.approval') }}" class="text-white fw-semibold">Lihat Detail <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <!-- Approved Jobs -->
        <div class="col-md-4">
            <div class="card text-white bg-success shadow-lg border-0 rounded-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase fw-semibold">Approved Jobs</h6>
                        <h2 class="fw-bold">{{ App\Models\Job::where('status', 'approved')->count() }}</h2>
                    </div>
                    <i class="fas fa-check-circle fa-3x opacity-75"></i>
                </div>
                <div class="card-footer border-0 bg-transparent">
                    <a href="{{ route('admin.jobs.approval') }}?tab=approved" class="text-white fw-semibold">Lihat Detail <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <!-- Rejected Jobs -->
        <div class="col-md-4">
            <div class="card text-white bg-danger shadow-lg border-0 rounded-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase fw-semibold">Rejected Jobs</h6>
                        <h2 class="fw-bold">{{ App\Models\Job::where('status', 'rejected')->count() }}</h2>
                    </div>
                    <i class="fas fa-times-circle fa-3x opacity-75"></i>
                </div>
                <div class="card-footer border-0 bg-transparent">
                    <a href="{{ route('admin.jobs.approval') }}?tab=rejected" class="text-white fw-semibold">Lihat Detail <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!-- Charts Row -->
    <div class="row">
        <!-- User Registrations Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>User Registrations (Last 30 Days)</h4>
                </div>
                <div class="card-body">
                    <canvas id="userRegistrationsChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Job Postings Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Job Postings (Last 12 Months)</h4>
                </div>
                <div class="card-body">
                    <canvas id="jobPostingsChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // User Registrations Chart (ukuran lebih kecil)
const userCtx = document.getElementById('userRegistrationsChart').getContext('2d');
const userRegistrationsChart = new Chart(userCtx, {
    type: 'line',
    data: {
        labels: @json($userRegistrations['dates']),
        datasets: [{
            label: 'Registrations',
            data: @json($userRegistrations['counts']),
            backgroundColor: 'rgba(100, 149, 237, 0.2)',
            borderColor: 'rgba(70, 130, 180, 1)',
            borderWidth: 2,
            pointBackgroundColor: 'rgba(70, 130, 180, 1)',
            pointBorderColor: '#fff',
            pointRadius: 4,
            pointHoverRadius: 6,
            tension: 0.2,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    color: '#495057',
                    font: {
                        size: 12,
                        family: "'Segoe UI', Roboto, sans-serif"
                    },
                    padding: 20,
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0,0,0,0.7)',
                titleFont: {
                    size: 12,
                    weight: 'bold'
                },
                bodyFont: {
                    size: 11
                },
                padding: 10,
                cornerRadius: 4,
                displayColors: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0,0,0,0.05)',
                    drawBorder: false
                },
                ticks: {
                    color: '#6c757d',
                    font: {
                        size: 11
                    },
                    stepSize: Math.max(1, Math.round(Math.max(...@json($userRegistrations['counts'])) / 5))
                }
            },
            x: {
                grid: {
                    display: false,
                    drawBorder: false
                },
                ticks: {
                    color: '#6c757d',
                    font: {
                        size: 11
                    },
                    maxRotation: 45,
                    minRotation: 45
                }
            }
        },
        elements: {
            line: {
                cubicInterpolationMode: 'monotone'
            }
        }
    }
});

    // Job Postings Chart (ukuran lebih kecil)
    const jobCtx = document.getElementById('jobPostingsChart').getContext('2d');
    const jobPostingsChart = new Chart(jobCtx, {
        type: 'bar',
        data: {
            labels: @json($jobPostings['months']),
            datasets: [{
                label: 'Job Postings',
                data: @json($jobPostings['counts']),
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Tambahkan ini
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        boxWidth: 12,
                        font: {
                            size: 10
                        }
                    }
                },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 10
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 10
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
