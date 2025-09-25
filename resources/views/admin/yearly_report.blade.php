@extends('layouts.auth')
@section('content')
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
	<h1 class="h3 mb-0">Yearly Report</h1>
</div>

<div class="card shadow mb-4">
	<div class="card-body">
		<div class="d-flex justify-content-between align-items-start mb-4">
			<form method="GET" action="{{ route('yearly.report') }}" class="d-print-none col-md-3">
				<div class="row g-2 align-items-center">
					<div class="d-flex flex-column col-md-6">
						<label for="year" class="form-label mb-0">Select Year:</label>
						<select name="year" id="year" class="form-control form-control-sm"
							onchange="this.form.submit()">
							@for ($y = date('Y'); $y >= 2020; $y--)
							<option value="{{ $y }}"
								{{ request('year', $selected_year) == $y ? 'selected' : '' }}>{{ $y }}
							</option>
							@endfor
						</select>
					</div>
				</div>
			</form>

			<div class="print-buttons d-flex gap-1">
				<button onclick="printReport()" class="btn btn-sm btn-primary d-print-none">
					<i class="fas fa-print"></i> Print Report
				</button>
				<button onclick="exportExcel()" class="btn btn-sm btn-success d-print-none">
					<i class="fas fa-file-excel"></i> Export Excel
				</button>
			</div>
		</div>

		<div id="print-section">
			<table class="report-header m-auto" width="100%" cellspacing="0" cellpadding="0"
				style="border-collapse: collapse;">
				<tr>
					<td style="vertical-align: middle;" class="text-center">
						<div class="d-flex align-items-center justify-content-center">
							<div class="company-logo">
								<img src="{{ asset('img/eggcellent-logo.webp') }}" alt="Company Logo"
								style="height: 100px; display: block;" />
						</div>
						<div class="company-text">
							<h4 class="mb-0">Eggcellent: Pereyra's Egg Poultry</h4>
							<p class="mb-0">
								Binanuan, Barbaza, Antique
							</p>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td class="text-center">
					<h2 class="mb-0">Year {{ $year_name }} Report</h2>
				</td>
			</tr>
			<tr>
				<td>
					<div class="date mb-1 text-start">
						<p class="m-0">
							Year: {{ $selected_year }}
						</p>
					</div>
				</td>
			</tr>
		</table>

		<div class="table-responsive">
			<table class="table table-bordered" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>Month</th>
						<th>Products</th>
						<th>No. of Orders</th>
						<th>Total</th>
					</tr>
				</thead>
				<tbody>
					@if ($report->isEmpty())
					<tr>
						<td colspan="4" class="text-center">No data available for this year.</td>
					</tr>
					@else
					@foreach ($report as $month)
					<tr>
						<td>{{ \Carbon\Carbon::create()->month($month['month_number'])->format('F') }}</td>
						<td>{{ $month['products'] }}</td>
						<td>{{ $month['quantity'] }}</td>
						<td>₱{{ number_format($month['total'], 2) }}</td>
					</tr>
					@endforeach
					@php
					$totalQuantity = $report->sum('quantity');
					$grandTotal = $report->sum('total');
					@endphp
					<tr class="bg-light">
						<td colspan="2" class="text-start h6">Grand Total</td>
						<td class="h6">{{ $totalQuantity }}</td>
						<td class="h6">₱{{ number_format($grandTotal, 2) }}</td>
					</tr>
					@endif
				</tbody>
			</table>
		</div>

		<div class="col-12 d-flex justify-content-end print-footer">
			<div class="d-flex flex-column justify-content-end align-items-center">
				<strong>ALLAN C. PEREYRA</strong>
				<span>Poultry Owner</span>
			</div>
		</div>
	</div>
</div>
</div>

<script>
function printReport() {
printJS( {
printable: 'print-section',
type: 'html',
css: [
'{{ asset('css/styles.css') }}',
'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css'
],
});
}
</script>

<script>
function exportExcel() {
let headers = [
"Month",
"Products",
"No. of Orders",
"Total"
];

let data = [
@if(!$report->isEmpty())
@foreach ($report as $month)
[
"{{ \Carbon\Carbon::create()->month($month['month_number'])->format('F') }}",
"{{ $month['products'] }}",
"{{ $month['quantity'] }}",
"₱{{ number_format($month['total'], 2) }}"
],
@endforeach
[
"Grand Total",
"",
"{{ $report->sum('quantity') }}",
"₱{{ number_format($report->sum('total'), 2) }}"
]
@else
["No data available for this year.",
"",
"",
""]
@endif
];

let worksheet = XLSX.utils.aoa_to_sheet([headers, ...data]);
let workbook = XLSX.utils.book_new();
XLSX.utils.book_append_sheet(workbook, worksheet, "Yearly Report");

XLSX.writeFile(workbook, "yearly_report_{{ $selected_year }}.xlsx");
}
</script>
@endsection