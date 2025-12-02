<div class="row mb-3">
    <div class="col-lg-6 mb-4">
        <div class="card key-timelines-card card-border-shadow-primary h-100">
            <div class="card-header d-flex justify-content-between align-items-center mt-0 mb-0 bt" style="border-bottom: 1px solid #eee;">
                <h4 class="mb-0">Company Status - YTD</h4>

                <div class="form-group mb-0">
                    @php
                        $selectedYear = $data['year'] ?? date('Y');
                        $years = [2024, 2025];
                        if (!in_array($selectedYear, $years)) $years[] = $selectedYear;
                        sort($years);
                    @endphp
                    <select id="yearFilter_data" class="form-select form-select-sm">
                        @foreach ($years as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="card-body">
                <div id="company-chart" data-company-url="{{ route('dashboard.company') }}"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card key-timelines-card card-border-shadow-primary h-100">
            <div class="card-header d-flex justify-content-between mt-0 mb-0 bt" style="border-bottom: 1px solid #eee;">
                <h4 class="mb-0">XBRL VS Non XBRL Delivered YTD</h4>
                <div class="form-group mb-0">
                    @php
                        $selectedYear = $data['year'] ?? date('Y');
                        $years = [2024, 2025];
                        if (!in_array($selectedYear, $years)) $years[] = $selectedYear;
                        sort($years);
                    @endphp
                    <select id="yearFilter_filedata" class="form-select form-select-sm">
                        @foreach ($years as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div id="filetype-chart"
                     data-company-url="{{ route('dashboard.filetype') }}"
                     style="min-height: 350px"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 mb-4">
        <div class="card key-timelines-card card-border-shadow-primary h-100">
            <div class="card-header d-flex justify-content-between align-items-center mt-0 mb-0 bt" style="border-bottom: 1px solid #eee;">
                <h4 class="mb-0">Delivered SLA - 30/45/60 days - YTD</h4>
                <div class="form-group mb-0" >
                    @php
                        $selectedYear = $data['year'] ?? date('Y');
                        $years = [2024, 2025];
                        if (!in_array($selectedYear, $years)) $years[] = $selectedYear;
                        sort($years);
                    @endphp
                    <select id="yearFilter" class="form-select form-select-sm">
                        @foreach ($years as $year)
                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="card-body">
                <div id="sla-chart"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 mb-4 multiplemonthdropdown">
        <div class="card key-timelines-card card-border-shadow-primary h-100">
            <div class="card-header pb-0 d-flex justify-content-between mt-0 mb-0 bt" style="padding: 1rem 1.25rem; position: relative;">
                <h4 class="mb-0" style="font-weight:700; color:#4b5563;">Monthly Delivered Count</h4>

                <div class="d-flex right-filters">
                    <div class="form-group mb-0">
                        @php
                            $months = [
                                1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',
                                7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec'
                            ];
                        @endphp
                        <select id="monthFilter" class="form-select form-select-sm" multiple>
                            @foreach($months as $num => $m)
                                <option value="{{ $num }}">{{ $m }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-0">
                        @php
                            $selectedYear = $data['year'] ?? date('Y');
                            $years = [2024, 2025];
                            if (!in_array($selectedYear, $years)) $years[] = $selectedYear;
                            sort($years);
                        @endphp
                        <select id="yearFilter_month" class="form-select form-select-sm">
                            @foreach ($years as $year)
                                <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>

            <div class="card-body">
                <div id="monthly-chart" style="height: 350px;"></div>
            </div>
        </div>
    </div>
</div>
