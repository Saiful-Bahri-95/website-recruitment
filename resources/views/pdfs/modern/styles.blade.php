<style>
    * {
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        color: #0f172a;
        margin: 0;
        background: #f1f5f9;
    }

    .page {
        width: 100%;
        min-height: 100vh;
        padding: 40px;
        page-break-after: always;
        position: relative;
        background: white;
    }

    .cover-page {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
        color: white;
    }

    .company-badge {
        display: inline-block;
        background: rgba(255,255,255,.1);
        border: 1px solid rgba(255,255,255,.2);
        padding: 8px 16px;
        border-radius: 999px;
        font-size: 12px;
        letter-spacing: 1px;
    }

    .cover-title {
        margin-top: 120px;
    }

    .cover-title h1 {
        font-size: 42px;
        margin: 0;
    }

    .cover-title p {
        color: rgba(255,255,255,.7);
        font-size: 15px;
    }

    .profile-card {
        margin-top: 60px;
        background: white;
        color: #0f172a;
        border-radius: 24px;
        padding: 30px;
    }

    .grid {
        display: table;
        width: 100%;
    }

    .col-left {
        display: table-cell;
        width: 32%;
        vertical-align: top;
        padding-right: 24px;
    }

    .col-right {
        display: table-cell;
        width: 68%;
        vertical-align: top;
    }

    .sidebar-card {
        background: #f8fafc;
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 20px;
    }

    .avatar {
        width: 120px;
        height: 120px;
        border-radius: 100%;
        background: #dbeafe;
        margin: 0 auto 20px;
    }

    .section {
        margin-bottom: 30px;
    }

    .section-title {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 15px;
        color: #1e3a8a;
        border-bottom: 2px solid #dbeafe;
        padding-bottom: 8px;
    }

    .info-item {
        margin-bottom: 12px;
    }

    .label {
        font-size: 11px;
        color: #64748b;
    }

    .value {
        font-size: 13px;
        font-weight: 600;
        margin-top: 2px;
    }

    .timeline {
        position: relative;
        margin-left: 15px;
    }

    .timeline-item {
        position: relative;
        padding-left: 25px;
        margin-bottom: 20px;
    }

    .timeline-item:before {
        content: '';
        position: absolute;
        left: 0;
        top: 5px;
        width: 10px;
        height: 10px;
        background: #2563eb;
        border-radius: 50%;
    }

    .timeline-item:after {
        content: '';
        position: absolute;
        left: 4px;
        top: 15px;
        width: 2px;
        height: calc(100% + 10px);
        background: #cbd5e1;
    }

    .timeline-item:last-child:after {
        display: none;
    }

    .doc-separator {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background: #0f172a;
        color: white;
        text-align: center;
    }

    .doc-separator h1 {
        font-size: 40px;
        margin-bottom: 10px;
    }

    .footer {
        position: absolute;
        bottom: 20px;
        left: 40px;
        right: 40px;
        font-size: 10px;
        color: #94a3b8;
        display: flex;
        justify-content: space-between;
    }
</style>
