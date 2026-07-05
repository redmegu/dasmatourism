<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>System Usage Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #667eea;
        }

        .header h1 {
            color: #667eea;
            margin: 0 0 5px 0;
            font-size: 24px;
        }

        .header .subtitle {
            color: #666;
            font-size: 14px;
        }

        .meta-info {
            text-align: right;
            margin-bottom: 20px;
            color: #666;
            font-size: 11px;
        }

        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }

        .stat-box {
            display: table-cell;
            width: 25%;
            padding: 15px;
            text-align: center;
            background: #f8f9fa;
            border: 1px solid #e0e0e0;
        }

        .stat-label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #667eea;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 30px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #667eea;
            color: #667eea;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: #667eea;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
        }

        td {
            padding: 8px 10px;
            border-bottom: 1px solid #e0e0e0;
            font-size: 11px;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            font-size: 10px;
            color: #999;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>System Usage Report</h1>
        <div class="subtitle">Dasmariñas Tourism System</div>
    </div>

    <div class="meta-info">
        <strong>Report Period:</strong> Last {{ $period == '7days' ? '7' : ($period == '30days' ? '30' : '90') }}
        Days<br>
        <strong>Generated:</strong> {{ now()->format('F d, Y h:i A') }}
    </div>

    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-label">Total Page Views</div>
            <div class="stat-value">{{ number_format($stats['total_page_views'] ?? 0) }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Unique Visitors</div>
            <div class="stat-value">{{ number_format($stats['unique_visitors'] ?? 0) }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">New Users</div>
            <div class="stat-value">{{ number_format($stats['registered_users'] ?? 0) }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Total Searches</div>
            <div class="stat-value">{{ number_format($stats['total_searches'] ?? 0) }}</div>
        </div>
    </div>

    <div class="section-title">Daily Page Views</div>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Views</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dailyViews as $view)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($view->date)->format('M d, Y') }}</td>
                    <td>{{ number_format($view->count) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" style="text-align: center;">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Most Visited Pages</div>
    <table>
        <thead>
            <tr>
                <th>Page</th>
                <th>Views</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topPages as $page)
                <tr>
                    <td>{{ ucfirst($page->page) }}</td>
                    <td>{{ number_format($page->count) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" style="text-align: center;">No data available</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        © {{ date('Y') }} Dasmariñas Tourism System. All rights reserved.
    </div>
</body>

</html>
