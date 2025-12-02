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
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/swiper/swiper.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    {{-- <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script> --}}
@endsection

@section('page-script')
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
</script>
@endsection
