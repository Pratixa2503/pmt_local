
    <div class="row mb-3">
        <div class="col-lg-6 mb-4">
            <div class="swiper-container swiper-container-horizontal swiper swiper-card-advance-bg"
                id="swiper-with-pagination-cards">
                <div class="swiper-wrapper">
                    <div class="swiper-slide card-header bt">
                        <div class="row">
                            <div class="col-12 ">
                                <h5 class=" mb-1 mt-2">Project Overview</h5>
                                <!-- <small class="">A quick glance at all your open, closed, and in progress projects</small> -->
                            </div>
                            <div class="row align-items-center">
                                <!-- Left Column: Summary -->
                                <div class="col-lg-12 col-md-12 col-12 order-2 order-md-1">

                                    <div class="row">
                                       @php
                                            $counts = collect($countByStatus);

                                            // If "Total Projects" not provided, compute it from the per-status counts.
                                            $hasTotal = $counts->has('Total Projects');
                                            $total    = $hasTotal ? $counts->get('Total Projects') : $counts->sum();

                                            // Build display list: Total first, then all statuses.
                                            $display = collect(['Total Projects' => $total])
                                                ->merge($hasTotal ? $counts->except('Total Projects') : $counts);

                                            // Optional: control display order (uncomment and set your order)
                                            // $order = ['Total Projects', 'Open', 'Active', 'On Hold', 'Completed'];
                                            // $display = $display->sortBy(fn($v, $k) => array_search($k, $order) !== false ? array_search($k, $order) : PHP_INT_MAX);
                                        @endphp

                                        <div class="row g-3">
                                            @foreach($display->chunk(2) as $pair)
                                                <div class="col-12">
                                                    <div class="row g-3">
                                                        @foreach($pair as $label => $value)
                                                            <div class="col-sm-6">
                                                                <ul class="list-unstyled mb-0">
                                                                    <li class="d-flex align-items-center mb-3">
                                                                        <span class="mb-0 fw-semibold me-2 website-analytics-text-bg">{{ $value ?? 0 }}</span>
                                                                        <span class="">{{ e($label) }}</span>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        @endforeach

                                                        {{-- If odd count, keep grid aligned by adding an empty cell --}}
                                                        @if($pair->count() === 1)
                                                            <div class="col-sm-6"></div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
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
                                <h1 class="mb-0">{{$leaseinfo['total'] ?? 0}}</h1>
                                <p class="mb-0">Total Leases Assigned</p>
                            </div>
                            <ul class="p-0 m-0">
                                <li class="d-flex gap-3 align-items-center mb-lg-3 pt-2 pb-1">
                                    <div class="badge rounded bg-label-primary p-1">
                                        <i class="ti ti-ticket ti-sm"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-nowrap">Leases Completed</h6>
                                        <small class="text-muted">{{$leaseinfo['delivered'] ?? 0}}</small>
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
