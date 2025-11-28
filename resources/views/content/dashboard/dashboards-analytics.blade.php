@extends('layouts/layoutMaster')

@section('title', 'Analytics')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/swiper/swiper.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
@endsection

@section('page-style')
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-advance.css') }}">
<<<<<<< HEAD
=======
    <style>
        .multiplemonthdropdown .choices__list--multiple .choices__item{
            color: #fff !important;
            background-color: #e35205 !important;
            position: relative;
            font-size: 0.8125rem !important;
            border-radius: 0.25rem !important;
            padding: 0.255rem 0.625rem !important;
            cursor: default;
            line-height: 0.875;
            float: left;
            font-weight: 600 !important;
            border: 0 !important;
        }
        .multiplemonthdropdown .choices[data-type*=select-multiple] .choices__button, .choices[data-type*=text] .choices__button{
            border: 0 !important;
            margin-left: 0;
        }

        .multiplemonthdropdown .choices__inner{
            display: block;
            width: 100%;
            padding: 0.422rem 0.875rem;
            font-size: 0.9375rem;
            font-weight: 400;
            line-height: 1.5;
            color: #6f6b7d;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #dbdade;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            border-radius: 0.375rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            width: 250px;
            min-height: 38px;
        }
        .multiplemonthdropdown .choices__input{
            background: transparent;
            padding: 0 !important;
            margin: 0 !important;
        }
        #yearFilter_month{
            height: 38px;
            background-position: right 5px center;
            border-radius: 0.375rem;
        }
        #yearFilter{
            height: 38px;
            background-position: right 5px center;
            border-radius: 0.375rem;
        }
        #yearFilter_data{
            height: 38px;
            background-position: right 5px center;
            border-radius: 0.375rem;
        }
        #yearFilter_filedata{
            height: 38px;
            background-position: right 5px center;
            border-radius: 0.375rem;
        }
        .right-filters{
            gap: 15px;
        }

    </style>
>>>>>>> 9d9ed85b (for cleaner setup)
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/swiper/swiper.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    {{-- <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script> --}}
@endsection

@section('page-script')
<<<<<<< HEAD
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
@endsection

@section('content')

    <div class="row ">
        <div class="col-md-9">
        </div>
        <div class="col-md-3 float-right" style="margin-bottom: 15px;">
            <select name="scope" id="piechart" class="form-control form-select hospital" tabindex="3">
                <option value="">Select Location</option>
                <option value="1" selected>APAC</option>
                <option value="2">EMEA</option>
                <option value="3">North America</option>
                <option value="4">LATAM</option>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        {{-- <div class="col-lg-6 mb-4">
            <div class="swiper-container swiper-container-horizontal swiper swiper-card-advance-bg"
                id="swiper-with-pagination-cards">
                <div class="swiper-wrapper">
                    <div class="swiper-slide card-header bt">
                        <div class="row">
                            <div class="col-12 ">
                                <h5 class=" mb-1 mt-2">Project Overview</h5>
                                <small class="">A quick glance at all your open, closed, and in progress projects</small>
                            </div>
                            <div class="row align-items-center">
                                <!-- Left Column: Summary -->
                                <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1">
                                    <h5 class=" mt-5 mb-5">Project Summary</h5>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <ul class="list-unstyled mb-0">
                                               <li class="d-flex align-items-center mb-4">
                                                    <span class="mb-0 fw-semibold me-2 website-analytics-text-bg">{{ $totalProjects ?? 0 }}</span>
                                                    <span>Total Projects</span>
                                                </li>
                                                <li class="d-flex align-items-center mb-3">
                                                    <span class="mb-0 fw-semibold me-2 website-analytics-text-bg">
                                                        {{  $statusCounts[$statuses['Active']] ?? 0 }}
                                                    </span>
                                                    <span class="">Active</span>
                                                </li>
                                                <li class="d-flex align-items-center mb-3">
                                                    <span class="mb-0 fw-semibold me-2 website-analytics-text-bg">
                                                        {{  $statusCounts[$statuses['On Hold']] ?? 0 }}
                                                    </span>
                                                    <span class="">On Hold</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-6">
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-flex align-items-center mb-4">
                                                    <span class="mb-0 fw-semibold me-2 website-analytics-text-bg">
                                                        {{ $statusCounts[$statuses['Completed']] ?? 0 }}
                                                    </span>
                                                    <span class="">Completed</span>
                                                </li>
                                                <li class="d-flex align-items-center mb-3">
                                                    <span class="mb-0 fw-semibold me-2 website-analytics-text-bg">
                                                        {{ $statusCounts[$statuses['Cancelled']] ?? 0  }}
                                                    </span>
                                                    <span class="">Cancelled</span>
                                                </li>
                                                <li class="d-flex align-items-center mb-3">
                                                    <span class="mb-0 fw-semibold me-2 website-analytics-text-bg">
                                                        {{ $statusCounts[$statuses['Draft']] ?? 0 }}
                                                    </span>
                                                    <span class="">Draft</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column: Illustration -->
                                <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 text-center my-4 my-md-0">
                                    <img src="{{ asset('assets/img/illustrations/card-website-analytics-1.png') }}" alt="Project Overview" width="170" class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="col-lg-6 mb-4">
            <div class="swiper-container swiper-container-horizontal swiper swiper-card-advance-bg"
                id="swiper-with-pagination-cards">
                <div class="swiper-wrapper">
                    <div class="swiper-slide card-header bt">
                        <div class="row">
                            <div class="col-12 ">
                                <h5 class=" mb-1 mt-2">Project Overview</h5>
                                <small class="">A quick glance at all your open, closed, and in progress projects</small>
                            </div>
                            <div class="row align-items-center">
                                <!-- Left Column: Summary -->
                                <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1">
                                    <h5 class=" mt-5 mb-5">Project Summary</h5>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-flex align-items-center mb-4">
                                                    <span class="mb-0 fw-semibold me-2 website-analytics-text-bg">20</span>
                                                    <span class="">Total Projects</span>
                                                </li>
                                                <li class="d-flex align-items-center mb-3">
                                                    <span class="mb-0 fw-semibold me-2 website-analytics-text-bg">3</span>
                                                    <span class="">Open</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-sm-6">
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-flex align-items-center mb-4">
                                                    <span class="mb-0 fw-semibold me-2 website-analytics-text-bg">12</span>
                                                    <span class="">In Progress</span>
                                                </li>
                                                <li class="d-flex align-items-center mb-3">
                                                    <span class="mb-0 fw-semibold me-2 website-analytics-text-bg">5</span>
                                                    <span class="">Closed</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column: Illustration -->
                                <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 text-center my-4 my-md-0">
                                    <img src="{{ asset('assets/img/illustrations/card-website-analytics-1.png') }}" alt="Project Overview" width="170" class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="col-lg-6 mb-4">
            <div class="swiper-container swiper-container-horizontal swiper swiper-card-advance-bg"
                id="swiper-with-pagination-cards">
                <div class="swiper-wrapper">
                    <div class="swiper-slide card-header bt">
                        <div class="row">
                            <div class="col-12 ">
                                <h5 class=" mb-1 mt-2">Client Overview</h5>
                                <small class="">A quick glance at all your open, closed, and in progress projects</small>
                            </div>
                            <div class="row align-items-center">
                                <!-- Left Column: Summary -->
                                <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1">
                                    <h5 class=" mt-5 mb-5">Client Summary</h5>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-flex align-items-center mb-3">
                                                    <span class="mb-0 fw-semibold me-2 website-analytics-text-bg">{{ $totalCompanies ?? 0 }}</span>
                                                    <span class="">Total Client</span>
                                                </li>
                                                <li class="d-flex align-items-center mb-3">
                                                    <span class="mb-0 fw-semibold me-2 website-analytics-text-bg">
                                                        {{ $activeCompanies ?? 0 }}
                                                    </span>
                                                    <span class="">Active Client</span>
                                                </li>

                                            </ul>
                                        </div>
                                        <div class="col-sm-6">
                                            <ul class="list-unstyled mb-0">
                                               <li class="d-flex align-items-center mb-3">
                                                    <span class="mb-0 fw-semibold me-2 website-analytics-text-bg">
                                                        {{ $inactiveCompanies ?? 0 }}
                                                    </span>
                                                    <span class="">InActive Client</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column: Illustration -->
                                <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 text-center my-4 my-md-0">
                                    <img src="{{ asset('assets/img/illustrations/card-website-analytics-1.png') }}" alt="Project Overview" width="170" class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between pb-0 bt">
                    <div class="card-title mb-0">
                        <h5 class="mb-0">Progress and Completion Metrics</h5>
                        <small class="text-muted">This Month</small>
                    </div>
                </div>
                <div class="card-body pt-2">
                    <div class="row">
                        <!-- Summary Section -->
                        <div class="col-lg-4">
                            <div class="mb-2">
                                <h1 class="mb-0">120</h1>
                                <p class="mb-0">Total Leases Assigned</p>
                            </div>
                            <ul class="p-0 m-0">
                                <li class="d-flex gap-3 align-items-center mb-lg-3 pt-2 pb-1">
                                    <div class="badge rounded bg-label-primary p-1">
                                        <i class="ti ti-ticket ti-sm"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-nowrap">Leases Completed</h6>
                                        <small class="text-muted">90</small>
                                    </div>
                                </li>
                                <li class="d-flex gap-3 align-items-center mb-lg-3 pb-1">
                                    <div class="badge rounded bg-label-info p-1">
                                        <i class="ti ti-circle-check ti-sm"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-nowrap">TAT</h6>
                                        <small class="text-muted">2 Days</small>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <!-- Charts Section -->
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div id="completionPercentageChart"></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div id="slaAdherenceChart"></div>
                                    <div class="text-center mt-2">
                                        <strong>SLA Adherence</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card key-timelines-card card-border-shadow-primary h-100">
                <div class="card-header pb-0 d-flex justify-content-between mt-0 mb-0 bt" style="border-bottom: 1px solid #eee;">
                    <h4 class="">Key Timelines</h4>
                </div>
                <div class="card-body">
                    <!-- Lease Date Filters -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="lease_start" class="form-label">Lease Start Date</label>
                            <input type="date" id="lease_start" name="lease_start" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="lease_end" class="form-label">Lease End Date</label>
                            <input type="date" id="lease_end" name="lease_end" class="form-control">
                        </div>
                    </div>

                    <!-- Timeline List -->
                    <ul class="timeline-list">
                        <li>
                            Lease Renewals <strong>30 Days</strong>
                            <span class="badge bg-danger">5</span>
                        </li>
                        <li>
                            Lease Renewals <strong>60 Days</strong>
                            <span class="badge bg-info">8</span>
                        </li>
                        <li>
                            Lease Renewals <strong>90 Days</strong>
                            <span class="badge bg-warning">3</span>
                        </li>
                        <li>
                            Lease Renewals <strong>120 Days</strong>
                            <span class="badge bg-secondary">2</span>
                        </li>
                        <li>
                            Upcoming Expirations
                            <span class="badge bg-dark">6</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <!-- Leases in Progress -->
            <div class="card card-border-shadow-primary h-100">
                <div class="card-header pb-0 d-flex justify-content-between mt-0 mb-0 bt" style="border-bottom: 1px solid #eee;">
                    <h4 class="">Leases in Progress - Location wise</h4>
                </div>
                <div class="card-body">
                    <div id="leasesChart"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <!-- Open Queries -->
            <div class="card card-border-shadow-primary h-100">
                <div class="card-header pb-0 d-flex justify-content-between mt-0 mb-0 bt" style="border-bottom: 1px solid #eee;">
                    <h4 class="">Open Queries - Location wise</h4>
                </div>
                <div class="card-body">
                    <div id="queriesChart"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <!-- Pending Clarifications / Escalations -->
            <div class="card card-border-shadow-primary h-100">
                <div class="card-header pb-0 d-flex justify-content-between mt-0 mb-0 bt" style="border-bottom: 1px solid #eee;">
                    <h4 class="">Yet to Process Leases (Missing Docs / Clarifications)</h4>
                </div>
                <div class="card-body">
                    <div id="yetToProcessChart"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <!-- QC Pending / Rework Cases -->
            <div class="card card-border-shadow-primary h-100">
                <div class="card-header pb-0 d-flex justify-content-between mt-0 mb-0 bt" style="border-bottom: 1px solid #eee;">
                    <h4 class="">QC Pending / Rework Cases</h4>
                </div>
                <div class="card-body">
                    <div id="qcChart"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <!-- Open Queries (Pending Client Input) -->
            <div class="card card-border-shadow-primary h-100">
                <div class="card-header pb-0 d-flex justify-content-between mt-0 mb-0 bt" style="border-bottom: 1px solid #eee;">
                    <h4 class="">Open Queries (Pending Client Input)</h4>
                </div>
                <div class="card-body">
                    <div id="openQueriesChart"></div>
                </div>
            </div>
        </div>
        {{-- <div class="col-md-6 mb-4">
            <!-- Blocked Leases (Missing Docs / Clarifications) -->
            <div class="card card-border-shadow-primary h-100">
                <div class="card-header pb-0 d-flex justify-content-between mt-0 mb-0 bt" style="border-bottom: 1px solid #eee;">
                    <h4 class="">Blocked Leases (Missing Docs / Clarifications)</h4>
                </div>
                <div class="card-body">
                    <div id="blockedLeasesChart"></div>
                </div>
            </div>
        </div> --}}
        <div class="col-md-6 mb-4">
            <!-- Lease Revisions / Updated Documents Received -->
            <div class="card card-border-shadow-primary h-100">
                <div class="card-header pb-0 d-flex justify-content-between mt-0 mb-0 bt" style="border-bottom: 1px solid #eee;">
                    <h4 class="">Lease Revisions / Updated Documents Received</h4>
                </div>
                <div class="card-body">
                    <div id="leaseRevisionsChart"></div>
                </div>
            </div>
        </div>
        {{-- <div class="col-lg-6 mb-4">
            <div class="card key-timelines-card card-border-shadow-primary h-100">
                <div class="card-header pb-0 d-flex justify-content-between mt-0 mb-0 bt" style="border-bottom: 1px solid #eee;">
                    <h4 class="">Company Details</h4>
                </div>
                <div class="card-body">
                    <!-- Timeline List -->
                    <ul class="timeline-list custom-scrollbar-new ">
                        <li>
                            Total Company
                            <span class="badge dynamic-badge">{{ data_get($data, 'total_companies', 0) }}</span>
                        </li>
                        <li>
                            Invalid Company
                            <span class="badge dynamic-badge">{{ data_get($data, 'invalid_companies', 0) }}</span>
                        </li>
                        <li>
                            Valid Company
                            <span class="badge dynamic-badge">{{ data_get($data, 'valid_companies', 0) }}</span>
                        </li>
                        <li>
                            Total Companies Filed Annual Report
                            <span class="badge dynamic-badge">{{ data_get($data, 'total_filed', 0) }}</span>
                        </li>
                        <li>
                            Total Companies Filed Annual Year in the last 30 day
                            <span class="badge dynamic-badge">{{ count(data_get($data, 'filed_last_30_days', [])) }}</span>
                        </li>
                        <li>
                            Number of Companies breach SLA in Next 7 days
                            <span class="badge dynamic-badge">{{ data_get($data, 'sla_breaches_next_7_days', 0) }}</span>
                        </li>

                        <li>
                            Non-XBRL Delivered Year
                            <span class="badge dynamic-badge">{{ data_get($data, 'overallData.total_filed', 0) }}</span>
                        </li>
                        <li>
                            XBRL Delivered Year
                            <span class="badge dynamic-badge">{{ data_get($data, 'overallData.total_delivered', 0) }}</span>
                        </li>
                        <li>
                            Pending Companies Year
                            <span class="badge dynamic-badge">{{ data_get($data, 'pendingCompanies', 0) }}</span>
                        </li>
                        <li>
                            Damaged Companies
                            <span class="badge dynamic-badge">{{ data_get($data, 'DamagedCompanies', 0) }}</span>
                        </li>
                    <li>
                        Weekly Delivery Count
                        <span class="badge dynamic-badge">
                        @forelse(data_get($data, 'weeklyDeliveries', []) as $week)
                            {{ $week['delivered_count'] ?? 0 }}
                        @empty
                            0
                        @endforelse
                    </span>
                    </li>
                        <li>
                            Total Company Data Delivered
                            <span class="badge dynamic-badge">{{ data_get($data, 'totalCompanyDataDelivered', 0) }}</span>
                        </li>
                        <li>
                            Total GSTIN
                            <span class="badge dynamic-badge">{{ data_get($data, 'gstcount', 0) }}</span>
                        </li>
                        <li>
                            Total MSME
                            <span class="badge dynamic-badge">{{ data_get($data, 'overallData.total_records', 0) }}</span>
                        </li>
                        <li>
                            Total Udyam IDs
                            <span class="badge dynamic-badge">{{ data_get($data, 'overallData.total_invalid', 0) }}</span>
                        </li>
                        <li>
                            Total Company Master data
                            <span class="badge dynamic-badge">{{ data_get($data, 'totalCompanyMasterData', 0) }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div> --}}
    </div>
    @endsection

@section('extra-script')
<script>
// Convert hex to RGB
// Convert hex to RGB
function hexToRgb(hex){
    hex = hex.replace('#','');
    if(hex.length===3) hex = hex.split('').map(c=>c+c).join('');
    let bigint = parseInt(hex,16);
    return [(bigint>>16)&255,(bigint>>8)&255, bigint&255];
}

// Convert RGB to hex
function rgbToHex(r,g,b){
    return "#" + [r,g,b].map(x=>x.toString(16).padStart(2,'0')).join('');
}

// Lighten RGB color slightly (without going too light)
function lightenColor(rgb, amount=20){
    return rgb.map(x => Math.min(220, x + amount));
}

// Generate alternating shades (slightly lighter)
function generateAlternatingShades(base1, base2, count){
    let c1 = hexToRgb(base1), c2 = hexToRgb(base2), colors=[];
    for(let i=0;i<count;i++){
        let base = (i%2===0)? c1 : c2;
        // Lighten moderately
        let shade = lightenColor(base, 20);
        colors.push(rgbToHex(shade[0],shade[1],shade[2]));
    }
    return colors;
}

// Apply colors
let badges = document.querySelectorAll('.dynamic-badge');
let colors = generateAlternatingShades('#E35205','#97999B', badges.length);
badges.forEach((b,i)=> b.style.setProperty('background-color', colors[i], 'important'));
=======
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>
<script src="{{ asset('assets/js/bahamas-dashboards-analytics.js') }}"></script>
@endsection

@section('content')
 <div class="row ">
    <div class="col-md-9">
    </div>
    <div class="col-md-3 float-right" style="margin-bottom: 15px;">
    <select id="projectSelect" class="form-control form-select" tabindex="3">
        @foreach($projects as $p)
            <option value="{{ $p->id }}" {{ $selectedProjectId == $p->id ? 'selected' : '' }} data-category={{$p->project_category}}>
                {{ $p->project_name }}
            </option>
        @endforeach
    </select>
</div>
</div>

@once
<div id="la_project">
    @include('content.dashboard.laproject')
</div>
<div id="bahamas_project">
    @include('content.dashboard.bahamas-dashboards-analytics')
</div>
@endonce
@endsection

@section('extra-script')
<script>
    // small helper functions for badge color (kept from your original)
    function hexToRgb(hex){ hex = hex.replace('#',''); if(hex.length===3) hex = hex.split('').map(c=>c+c).join(''); let bigint = parseInt(hex,16); return [(bigint>>16)&255,(bigint>>8)&255, bigint&255]; }
    function rgbToHex(r,g,b){ return "#" + [r,g,b].map(x=>x.toString(16).padStart(2,'0')).join(''); }
    function lightenColor(rgb, amount=20){ return rgb.map(x => Math.min(220, x + amount)); }
    function generateAlternatingShades(base1, base2, count){ let c1 = hexToRgb(base1), c2 = hexToRgb(base2), colors=[]; for(let i=0;i<count;i++){ let base = (i%2===0)? c1 : c2; let shade = lightenColor(base, 20); colors.push(rgbToHex(shade[0],shade[1],shade[2])); } return colors; }

    // color badges if present
    document.addEventListener('DOMContentLoaded', function(){
        let badges = document.querySelectorAll('.dynamic-badge');
        if(badges.length){
            let colors = generateAlternatingShades('#E35205','#97999B', badges.length);
            badges.forEach((b,i)=> b.style.setProperty('background-color', colors[i], 'important'));
        }
    });

    // PROJECT SWITCHER: toggle blocks and dispatch events so charts can refresh
    $(function () {
        function toggleProjectBlocks(catRaw) {
            const cat = (typeof catRaw === 'string') ? catRaw.trim() : catRaw;
            $('#la_project, #bahamas_project').addClass('d-none');

            const showLA = (cat === 1 || cat === '1' || cat === '2' || cat === 2 || String(cat).toLowerCase().includes('la'));
            const showBahamas = (cat === 3 || cat === '3' || String(cat).toLowerCase().includes('bahamas') || String(cat).toLowerCase()==='bahamas');

            if (showLA) {
                $('#la_project').removeClass('d-none');
                window.dispatchEvent(new CustomEvent('la-shown', { detail: { category: cat } }));
            } else if (showBahamas) {
                $('#bahamas_project').removeClass('d-none');
                window.dispatchEvent(new CustomEvent('bahamas-shown', { detail: { category: cat } }));
            } else {
                $('#la_project').removeClass('d-none');
                window.dispatchEvent(new CustomEvent('la-shown', { detail: { category: cat } }));
            }
        }

        $('#projectSelect').on('change', function () {
            const catAttr = $(this).find('option:selected').attr('data-category');
            toggleProjectBlocks(catAttr);
        });

        // initial state
        const initialCatAttr = $('#projectSelect option:selected').attr('data-category') || $('#projectSelect').val() || 0;
        toggleProjectBlocks(initialCatAttr);
    });
>>>>>>> 9d9ed85b (for cleaner setup)
</script>
@endsection
