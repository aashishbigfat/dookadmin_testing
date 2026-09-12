<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Meta Leads CRM Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
            background: #f8fafc; 
            line-height: 1.6;
        }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header { 
            background: white; 
            padding: 20px; 
            border-radius: 8px; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.1); 
            margin-bottom: 20px; 
        }
        .nav { display: flex; gap: 20px; margin-top: 10px; align-items: center; }
        .nav a { 
            text-decoration: none; 
            color: #64748b; 
            padding: 8px 16px; 
            border-radius: 4px; 
            transition: all 0.2s; 
        }
        .nav a:hover, .nav a.active { background: #3b82f6; color: white; }
        .auto-refresh { 
            margin-left: auto; 
            display: flex; 
            align-items: center; 
            gap: 10px;
            color: #64748b;
            font-size: 14px;
        }
        .refresh-indicator { 
            width: 8px; 
            height: 8px; 
            border-radius: 50%; 
            background: #10b981; 
            animation: pulse 2s infinite; 
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .stats-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); 
            gap: 20px; 
            margin-bottom: 30px; 
        }
        .stat-card { 
            background: white; 
            padding: 20px; 
            border-radius: 8px; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.1); 
            transition: transform 0.2s;
        }
        .stat-card:hover { transform: translateY(-2px); }
        .stat-number { font-size: 2rem; font-weight: bold; color: #1f2937; }
        .stat-label { color: #64748b; margin-top: 4px; font-size: 14px; }
        .stat-trend { color: #10b981; font-size: 0.875rem; margin-top: 4px; }
        .stat-trend.error { color: #ef4444; }
        .actions { 
            background: white; 
            padding: 20px; 
            border-radius: 8px; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.1); 
            margin-bottom: 20px; 
        }
        .btn { 
            background: #3b82f6; 
            color: white; 
            border: none; 
            padding: 10px 20px; 
            border-radius: 6px; 
            cursor: pointer; 
            font-size: 14px; 
            transition: all 0.2s; 
            margin-right: 10px; 
            margin-bottom: 10px;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover { background: #2563eb; transform: translateY(-1px); }
        .btn-success { background: #10b981; }
        .btn-success:hover { background: #059669; }
        .btn-warning { background: #f59e0b; }
        .btn-warning:hover { background: #d97706; }
        .btn:disabled { 
            background: #9ca3af; 
            cursor: not-allowed; 
            transform: none;
        }
        .chart-container { 
            background: white; 
            padding: 20px; 
            border-radius: 8px; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.1); 
            margin-bottom: 20px;
        }
        .loading { 
            text-align: center; 
            padding: 40px; 
            color: #64748b; 
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .spinner { 
            width: 20px; 
            height: 20px; 
            border: 2px solid #e5e7eb; 
            border-top: 2px solid #3b82f6; 
            border-radius: 50%; 
            animation: spin 1s linear infinite; 
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .alert { 
            padding: 12px 16px; 
            border-radius: 6px; 
            margin-bottom: 20px; 
            position: relative;
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .alert-info { background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }
        .progress { 
            width: 100%; 
            height: 8px; 
            background: #e5e7eb; 
            border-radius: 4px; 
            overflow: hidden; 
            margin-top: 10px; 
        }
        .progress-bar { 
            height: 100%; 
            background: #3b82f6; 
            transition: width 0.3s; 
            border-radius: 4px;
        }
        .import-options { 
            display: flex; 
            flex-wrap: wrap; 
            gap: 10px; 
            margin-top: 15px; 
            align-items: center;
        }
        .import-status { 
            background: #f8fafc; 
            padding: 15px; 
            border-radius: 6px; 
            margin-top: 15px; 
            border-left: 4px solid #3b82f6; 
        }
        .limit-selector { 
            margin-left: 10px; 
            padding: 8px; 
            border: 1px solid #d1d5db; 
            border-radius: 4px; 
            font-size: 14px;
        }
        .recent-leads {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .recent-leads-header {
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .recent-leads-content {
            max-height: 400px;
            overflow-y: auto;
        }
        .lead-item {
            padding: 15px 20px;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background 0.2s;
        }
        .lead-item:hover {
            background: #f9fafb;
        }
        .lead-item:last-child {
            border-bottom: none;
        }
        .lead-info h4 {
            margin: 0 0 5px 0;
            color: #1f2937;
            font-size: 16px;
        }
        .lead-info p {
            margin: 0;
            color: #64748b;
            font-size: 14px;
        }
        .lead-meta {
            text-align: right;
            font-size: 12px;
            color: #9ca3af;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            margin-top: 5px;
            display: inline-block;
        }
        .status-new { background: #dbeafe; color: #1e40af; }
        .status-sent_to_crm { background: #d1fae5; color: #065f46; }
        .status-crm_error { background: #fed7d7; color: #c53030; }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        @media (max-width: 768px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
            .nav {
                flex-direction: column;
                align-items: flex-start;
            }
            .auto-refresh {
                margin-left: 0;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Meta Leads CRM Dashboard</h1>
            <div class="nav">
                <a href="/crm/dashboard" class="active">Dashboard</a>
                <a href="/crm/leads">All Leads</a>
                <div class="auto-refresh">
                    <div class="refresh-indicator"></div>
                    Auto-refresh: <span id="refreshTimer">30</span>s
                </div>
            </div>
        </div>

        <div id="alerts"></div>

        <div class="actions">
            <h3>Quick Actions</h3>
            <div class="import-options">
                <button class="btn btn-success" onclick="importRecentLeads()" id="importRecentBtn">
                    Import Recent Leads
                </button>
                <select class="limit-selector" id="leadLimit">
                    <option value="25">25 leads</option>
                    <option value="50" selected>50 leads</option>
                    <option value="100">100 leads</option>
                    <option value="200">200 leads</option>
                </select>
                <button class="btn btn-warning" onclick="importAllLeads()" id="importAllBtn">
                    Import All (Background)
                </button>
                <button class="btn" onclick="refreshStats()" id="refreshBtn">
                    Refresh Statistics
                </button>
                <a href="/crm/leads" class="btn">View All Leads</a>
            </div>
            
            <div id="importProgress" style="display: none;">
                <p id="progressText">Importing leads... Please wait.</p>
                <div class="progress">
                    <div class="progress-bar" id="progressBar"></div>
                </div>
            </div>

            <div class="import-status" id="importStatus">
                <strong>System Status:</strong>
                <ul style="margin-top: 8px; margin-left: 20px;">
                    <li><strong>Automatic Sync:</strong> Runs every 10 minutes to fetch new leads</li>
                    <li><strong>Webhook:</strong> Real-time lead capture when forms are submitted</li>
                    <li><strong>CRM Integration:</strong> Leads automatically sent to TutterflyyCRM</li>
                    <li><strong>Recent Leads:</strong> Manual import of latest leads (fast)</li>
                    <li><strong>Import All:</strong> Comprehensive import (use sparingly)</li>
                </ul>
            </div>
        </div>

        <div class="stats-grid" id="statsGrid">
            <div class="loading">
                <div class="spinner"></div>
                Loading statistics...
            </div>
        </div>

        <div class="grid-2">
            <div class="chart-container">
                <h3>Leads Overview</h3>
                <canvas id="leadsChart" width="400" height="200"></canvas>
            </div>

            <div class="recent-leads">
                <div class="recent-leads-header">
                    <h3>Recent Leads</h3>
                    <button class="btn" onclick="loadRecentLeads()" style="margin: 0;">Refresh</button>
                </div>
                <div class="recent-leads-content" id="recentLeadsContent">
                    <div class="loading">
                        <div class="spinner"></div>
                        Loading recent leads...
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Set CSRF token for all axios requests
        if (document.querySelector('meta[name="csrf-token"]')) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }
        
        let leadsChart = null;
        let refreshInterval = null;
        let countdownInterval = null;
        let refreshCounter = 30;

        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardStats();
            loadRecentLeads();
            startAutoRefresh();
        });

        function startAutoRefresh() {
            // Refresh data every 30 seconds
            refreshInterval = setInterval(() => {
                loadDashboardStats();
                loadRecentLeads();
                refreshCounter = 30;
            }, 30000);

            // Update countdown timer every second
            countdownInterval = setInterval(() => {
                refreshCounter--;
                if (refreshCounter <= 0) {
                    refreshCounter = 30;
                }
                document.getElementById('refreshTimer').textContent = refreshCounter;
            }, 1000);
        }

        function loadDashboardStats() {
            axios.get('/api/crm/dashboard-stats')
                .then(response => {
                    const stats = response.data;
                    displayStats(stats);
                    createChart(stats);
                })
                .catch(error => {
                    console.error('Error loading stats:', error);
                    showAlert('Failed to load dashboard statistics', 'error');
                });
        }

        function displayStats(stats) {
            const statsGrid = document.getElementById('statsGrid');
            const conversionRate = stats.total_leads > 0 ? ((stats.converted_leads / stats.total_leads) * 100).toFixed(1) : 0;
            const crmSuccessRate = stats.sent_to_crm > 0 ? (((stats.sent_to_crm - stats.crm_errors) / stats.sent_to_crm) * 100).toFixed(1) : 0;
            
            statsGrid.innerHTML = `
                <div class="stat-card">
                    <div class="stat-number">${stats.total_leads || 0}</div>
                    <div class="stat-label">Total Leads</div>
                    <div class="stat-trend">All time</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${stats.new_leads || 0}</div>
                    <div class="stat-label">New Leads</div>
                    <div class="stat-trend">Ready for contact</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${stats.sent_to_crm || 0}</div>
                    <div class="stat-label">Sent to CRM</div>
                    <div class="stat-trend ${stats.crm_errors > 0 ? 'error' : ''}">${stats.crm_errors || 0} errors</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${stats.contacted_leads || 0}</div>
                    <div class="stat-label">Contacted</div>
                    <div class="stat-trend">Follow-up rate</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${stats.converted_leads || 0}</div>
                    <div class="stat-label">Converted</div>
                    <div class="stat-trend">Success rate: ${conversionRate}%</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${stats.today_leads || 0}</div>
                    <div class="stat-label">Today's Leads</div>
                    <div class="stat-trend">Real-time</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${stats.this_month_leads || 0}</div>
                    <div class="stat-label">This Month</div>
                    <div class="stat-trend">Monthly performance</div>
                </div>
            `;
        }

        function createChart(stats) {
            const ctx = document.getElementById('leadsChart').getContext('2d');
            
            if (leadsChart) leadsChart.destroy();

            leadsChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['New', 'Sent to CRM', 'Contacted', 'Converted', 'Lost', 'CRM Errors'],
                    datasets: [{
                        data: [
                            stats.new_leads || 0, 
                            stats.sent_to_crm || 0, 
                            stats.contacted_leads || 0, 
                            stats.converted_leads || 0, 
                            stats.lost_leads || 0,
                            stats.crm_errors || 0
                        ],
                        backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#059669', '#ef4444', '#f97316'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: { 
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { 
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        }

        function loadRecentLeads() {
            axios.get('/todays-leads')
                .then(response => {
                    displayRecentLeads(response.data.leads || []);
                })
                .catch(error => {
                    console.error('Error loading recent leads:', error);
                    document.getElementById('recentLeadsContent').innerHTML = 
                        '<div class="loading" style="color: #ef4444;">Failed to load recent leads</div>';
                });
        }

        function displayRecentLeads(leads) {
            const container = document.getElementById('recentLeadsContent');
            
            if (leads.length === 0) {
                container.innerHTML = '<div class="loading">No leads found for today</div>';
                return;
            }

            const leadsHtml = leads.slice(0, 10).map(lead => {
                const statusClass = `status-${lead.status}`;
                const statusText = lead.status.replace('_', ' ').toUpperCase();
                
                return `
                    <div class="lead-item">
                        <div class="lead-info">
                            <h4>${lead.name || 'No Name'}</h4>
                            <p>${lead.email || 'No Email'} | ${lead.phone || 'No Phone'}</p>
                            <p><strong>Destination:</strong> ${lead.destination || 'Unknown'} | <strong>Source:</strong> ${lead.source}</p>
                            <span class="status-badge ${statusClass}">${statusText}</span>
                        </div>
                        <div class="lead-meta">
                            <div>Lead ID: ${lead.facebook_lead_id || lead.id}</div>
                            <div>Time: ${lead.created_at}</div>
                        </div>
                    </div>
                `;
            }).join('');

            container.innerHTML = leadsHtml;
        }

        function importRecentLeads() {
            const limit = document.getElementById('leadLimit').value;
            const progressDiv = document.getElementById('importProgress');
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');
            const importBtn = document.getElementById('importRecentBtn');
            
            // Disable button and show progress
            importBtn.disabled = true;
            progressDiv.style.display = 'block';
            progressBar.style.width = '10%';
            progressText.textContent = `Importing recent ${limit} leads... Please wait.`;
            
            axios.post('/api/crm/import-recent-leads', { limit: parseInt(limit) })
                .then(response => {
                    progressBar.style.width = '100%';
                    setTimeout(() => {
                        progressDiv.style.display = 'none';
                        importBtn.disabled = false;
                        
                        const data = response.data;
                        let message = `Import completed! ${data.imported_new} new leads imported, ${data.already_existed} already existed.`;
                        
                        if (data.crm_success || data.crm_errors) {
                            message += ` CRM: ${data.crm_success} successful, ${data.crm_errors} errors.`;
                        }
                        
                        showAlert(message, 'success');
                        loadDashboardStats();
                        loadRecentLeads();
                    }, 1000);
                })
                .catch(error => {
                    progressDiv.style.display = 'none';
                    importBtn.disabled = false;
                    
                    let errorMessage = 'Failed to import leads. ';
                    
                    if (error.response && error.response.data && error.response.data.error) {
                        errorMessage += error.response.data.error;
                    } else {
                        errorMessage += 'Please try with fewer leads or check your connection.';
                    }
                    
                    showAlert(errorMessage, 'error');
                });
        }

        function importAllLeads() {
            const progressDiv = document.getElementById('importProgress');
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');
            const importBtn = document.getElementById('importAllBtn');
            
            importBtn.disabled = true;
            progressDiv.style.display = 'block';
            progressBar.style.width = '20%';
            progressText.textContent = 'Starting comprehensive import...';
            
            axios.post('/api/crm/import-all-leads')
                .then(response => {
                    progressBar.style.width = '100%';
                    setTimeout(() => {
                        progressDiv.style.display = 'none';
                        importBtn.disabled = false;
                        
                        if (response.data.job_id) {
                            showAlert(`Comprehensive import started! Job ID: ${response.data.job_id}. This may take several minutes.`, 'info');
                        } else {
                            const data = response.data;
                            let message = `Import completed! ${data.imported_new || 0} new leads imported, ${data.already_existed || 0} already existed.`;
                            showAlert(message, 'success');
                        }
                        
                        loadDashboardStats();
                        loadRecentLeads();
                    }, 1500);
                })
                .catch(error => {
                    progressDiv.style.display = 'none';
                    importBtn.disabled = false;
                    showAlert('Failed to start comprehensive import. Please try the Recent Leads option instead.', 'error');
                });
        }

        function refreshStats() {
            const refreshBtn = document.getElementById('refreshBtn');
            refreshBtn.disabled = true;
            refreshBtn.textContent = 'Refreshing...';
            
            loadDashboardStats();
            loadRecentLeads();
            
            setTimeout(() => {
                refreshBtn.disabled = false;
                refreshBtn.textContent = 'Refresh Statistics';
                showAlert('Statistics refreshed!', 'success');
            }, 1000);
        }

        function showAlert(message, type) {
            const alertsDiv = document.getElementById('alerts');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.textContent = message;
            alertsDiv.appendChild(alert);
            
            // Auto-remove after 8 seconds
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 8000);
        }

        // Cleanup intervals when page unloads
        window.addEventListener('beforeunload', () => {
            if (refreshInterval) clearInterval(refreshInterval);
            if (countdownInterval) clearInterval(countdownInterval);
        });
    </script>
</body>
</html>