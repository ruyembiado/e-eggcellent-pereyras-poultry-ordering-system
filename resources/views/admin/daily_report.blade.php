@extends('layouts.auth')
@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0">Daily Report</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <form method="GET" action="{{ route('daily.report') }}" class="d-print-none">
                    <div class="d-flex flex-column align-items-start" style="width: auto;">
                        <label for="date" class="mb-0">Select Date:</label>
                        <input type="date" name="date" value="{{ $date }}"
                            class="form-control form-control-sm" style="width: auto;" onchange="this.form.submit()" />
                    </div>
                </form>

                <div class="print-buttons">
                    <button onclick="printReport()" class="btn btn-sm btn-primary d-print-none">
                        <i class="fas fa-print"></i> Print Report
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
                                    <p class="mb-0">Binanuan, Barbaza, Antique</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <h2 class="mb-0">Daily Report</h2>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="date mb-1 text-start">
                                <p class="m-0">Day: {{ \Carbon\Carbon::parse($date)->format('l') }}</p>
                                <p class="m-0">Date: {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</p>
                            </div>
                        </td>
                    </tr>
                </table>

                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Customer Name</th>
                                <th>Products</th>
                                <th>No. of Orders</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($report->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-center">No data available for this date.</td>
                                </tr>
                            @else
                                @foreach ($report as $data)
                                    <tr>
                                        <td>{{ $data['customer'] }}</td>
                                        <td>{{ $data['products'] }}</td>
                                        <td>{{ $data['quantity'] }}</td>
                                        <td>₱{{ number_format($data['total'], 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="bg-light">
                                    <td colspan="2" class="text-start h6">Grand Total:</td>
                                    <td class="h6">{{ $report->sum('quantity') }}</td>
                                    <td class="h6">₱{{ number_format($report->sum('total'), 2) }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function printReport() {
            printJS({
                printable: 'print-section',
                type: 'html',
                css: [
                    // '{{ asset('css/styles.css') }}',
                    'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css'
                ],
            });
        }
    </script>
@endsection
