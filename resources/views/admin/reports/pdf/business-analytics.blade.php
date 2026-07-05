<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Business Analytics Report</title>
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
            width: 33.33%;
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

        .rating {
            color: #ffc107;
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
        <h1>Business Analytics Report</h1>
        <div class="subtitle">Dasmariñas Tourism System</div>
    </div>

    <div class="meta-info">
        <strong>Generated:</strong> {{ now()->format('F d, Y h:i A') }}
    </div>

    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-label">Total Businesses</div>
            <div class="stat-value">{{ number_format($stats['total_businesses']) }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Verified Businesses</div>
            <div class="stat-value">{{ number_format($stats['verified_businesses']) }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">With Reviews</div>
            <div class="stat-value">{{ number_format($stats['businesses_with_reviews']) }}</div>
        </div>
    </div>

    <div class="section-title">Top Performing Businesses</div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Business Name</th>
                <th>Category</th>
                <th>Views</th>
                <th>Reviews</th>
                <th>Avg Rating</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topBusinesses as $index => $business)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $business->name }}</td>
                    <td>{{ $business->category->name ?? 'N/A' }}</td>
                    <td>{{ number_format($business->views) }}</td>
                    <td>{{ $business->approved_reviews_count }}</td>
                    <td class="rating">
                        {{ $business->average_rating ? number_format($business->average_rating, 1) : 'N/A' }}
                        @if ($business->average_rating)
                            ★
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">No businesses found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        © {{ date('Y') }} Dasmariñas Tourism System. All rights reserved.
    </div>
</body>

</html>
