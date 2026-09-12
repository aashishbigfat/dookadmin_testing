<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Meta Leads CRM - All Leads</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f8fafc; }
        .container { max-width: 1600px; margin: 0 auto; padding: 20px; }
        .header { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .nav { display: flex; gap: 20px; margin-top: 10px; }
        .nav a { text-decoration: none; color: #64748b; padding: 8px 16px; border-radius: 4px; transition: all 0.2s; }
        .nav a:hover, .nav a.active { background: #3b82f6; color: white; }
        .filters { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .filter-row { display: grid; grid-template-columns: 1fr 1fr 1fr 1fr 1fr auto; gap: 15px; align-items: end; }
        .filter-group label { display: block; margin-bottom: 5px; font-weight: 500; color: #374151; }
        .filter-group input, .filter-group select { width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px; }
        .btn { background: #3b82f6; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 14px; transition: all 0.2s; }
        .btn:hover { background: #2563eb; }
        .btn-secondary { background: #6b7280; }
        .btn-secondary:hover { background: #4b5563; }
        .btn-success { background: #10b981; }
        .btn-success:hover { background: #059669; }
        .leads-container { background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; }
        .leads-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .leads-table th { background: #f9fafb; padding: 12px 8px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb; }
        .leads-table td { padding: 12px 8px; border-bottom: 1px solid #f3f4f6; }
        .leads-table tr:hover { background: #f9fafb; }
        .status-badge { padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 500; }
        .status-new { background: #dbeafe; color: #1d4ed8; }
        .status-contacted { background: #fef3c7; color: #d97706; }
        .status-converted { background: #d1fae5; color: #065f46; }
        .status-lost { background: #fee2e2; color: #dc2626; }
        .status-sent_to_crm { background: #dcfce7; color: #16a34a; }
        .status-crm_error { background: #fef2f2; color: #dc2626; }
        .status-select { padding: 4px 8px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 12px; }
        .pagination { display: flex; justify-content: center; padding: 20px; gap: 10px; }
        .pagination button { padding: 8px 12px; border: 1px solid #d1d5db; background: white; cursor: pointer; border-radius: 4px; }
        .pagination button:hover { background: #f3f4f6; }
        .pagination button.active { background: #3b82f6; color: white; border-color: #3b82f6; }
        .loading { text-align: center; padding: 40px; color: #64748b; }
        .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .lead-details { max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .destination-badge { background: #e0f2fe; color: #0369a1; padding: 2px 8px; border-radius: 8px; font-size: 11px; font-weight: 500; }
        .source-badge { padding: 2px 8px; border-radius: 8px; font-size: 11px; font-weight: 500; }
        .source-facebook { background: #eff6ff; color: #1d4ed8; }
        .source-instagram { background: #fdf4ff; color: #a21caf; }
        .source-unknown { background: #f3f4f6; color: #6b7280; }
        .button-group { display: flex; gap: 5px; flex-direction: column; }
        .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 20px; }
        .stat-card { background: white; padding: 15px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .stat-number { font-size: 24px; font-weight: bold; color: #1f2937; }
        .stat-label { color: #6b7280; font-size: 14px; margin-top: 5px; }
        @media (max-width: 768px) { 
            .filter-row { grid-template-columns: 1fr; } 
            .container { padding: 10px; }
            .leads-table { font-size: 11px; }
            .leads-table th, .leads-table td { padding: 6px 4px; }
            .stats-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Meta Leads CRM - All Leads</h1>
            <div class="nav">
                <a href="/crm/dashboard">Dashboard</a>
                <a href="/crm/leads" class="active">All Leads</a>
            </div>
        </div>

        <div id="alerts"></div>

        <!-- Quick Stats -->
        <div class="stats-row" id="quickStats" style="display: none;">
            <div class="stat-card">
                <div class="stat-number" id="totalLeads">0</div>
                <div class="stat-label">Total Leads</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="todayLeads">0</div>
                <div class="stat-label">Today's Leads</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="fbLeads">0</div>
                <div class="stat-label">Facebook Leads</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="igLeads">0</div>
                <div class="stat-label">Instagram Leads</div>
            </div>
        </div>

        <div class="filters">
            <h3 style="margin-bottom: 15px;">Filter Leads</h3>
            <div class="filter-row">
                <div class="filter-group">
                    <label>Search</label>
                    <input type="text" id="searchInput" placeholder="Search name, email, phone, destination...">
                </div>
                <div class="filter-group">
                    <label>Status</label>
                    <select id="statusFilter">
                        <option value="all">All Status</option>
                        <option value="new">New</option>
                        <option value="contacted">Contacted</option>
                        <option value="converted">Converted</option>
                        <option value="lost">Lost</option>
                        <option value="sent_to_crm">Sent to CRM</option>
                        <option value="crm_error">CRM Error</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Source</label>
                    <select id="sourceFilter">
                        <option value="all">All Sources</option>
                        <option value="facebook">Facebook</option>
                        <option value="instagram">Instagram</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>From Date</label>
                    <input type="date" id="fromDate">
                </div>
                <div class="filter-group">
                    <label>To Date</label>
                    <input type="date" id="toDate">
                </div>
                <div class="filter-group">
                    <div class="button-group">
                        <button class="btn" onclick="applyFilters()">Filter</button>
                        <button class="btn btn-secondary" onclick="clearFilters()">Clear</button>
                        <button class="btn btn-success" onclick="filterTodaysLeads()">Today's Leads</button>
                        <button class="btn btn-secondary" onclick="toggleStats()" style="font-size: 12px;">Toggle Stats</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="leads-container">
            <div id="leadsContent">
                <div class="loading">Loading leads...</div>
            </div>
        </div>
    </div>

    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        let currentPage = 1;
        let currentFilters = {};

        document.addEventListener('DOMContentLoaded', function() {
            loadLeads();
            loadQuickStats();
        });

        function loadLeads(page = 1) {
            currentPage = page;
            const params = new URLSearchParams({
                page: page,
                per_page: 20,
                ...currentFilters
            });

            axios.get(`/api/crm/leads?${params}`)
                .then(response => {
                    displayLeads(response.data);
                })
                .catch(error => {
                    console.error('Error loading leads:', error);
                    showAlert('Failed to load leads', 'error');
                });
        }

        function loadQuickStats() {
            axios.get('/api/crm/dashboard-stats')
                .then(response => {
                    const stats = response.data;
                    document.getElementById('totalLeads').textContent = stats.total_leads || 0;
                    document.getElementById('todayLeads').textContent = stats.today_leads || 0;
                    document.getElementById('fbLeads').textContent = stats.platform_stats?.facebook || 0;
                    document.getElementById('igLeads').textContent = stats.platform_stats?.instagram || 0;
                })
                .catch(error => {
                    console.warn('Could not load stats:', error);
                });
        }

        function toggleStats() {
            const statsRow = document.getElementById('quickStats');
            if (statsRow.style.display === 'none' || !statsRow.style.display) {
                statsRow.style.display = 'grid';
                loadQuickStats();
            } else {
                statsRow.style.display = 'none';
            }
        }

        function displayLeads(data) {
            const content = document.getElementById('leadsContent');
            
            if (data.data.length === 0) {
                content.innerHTML = '<div class="loading">No leads found</div>';
                return;
            }

            let tableHtml = `
                <table class="leads-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Destination</th>
                            <th>Source</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            data.data.forEach(lead => {
                const createdDate = new Date(lead.lead_created_time || lead.created_at).toLocaleDateString();
                
                tableHtml += `
                    <tr>
                        <td>${createdDate}</td>
                        <td class="lead-details" title="${lead.name || 'N/A'}">${lead.name || 'N/A'}</td>
                        <td class="lead-details" title="${lead.email || 'N/A'}">${lead.email || 'N/A'}</td>
                        <td>${lead.phone || 'N/A'}</td>
                        <td>
                            ${lead.destination ? `<span class="destination-badge" title="${lead.destination}">${lead.destination}</span>` : 'N/A'}
                        </td>
                        <td>
                            ${getSourceBadge(lead.source || 'facebook')}
                        </td>
                        <td>
                            <span class="status-badge status-${lead.status}">${capitalizeFirst(lead.status || 'new')}</span>
                        </td>
                        <td>
                            <select class="status-select" onchange="updateStatus(${lead.id}, this.value)">
                                <option value="new" ${lead.status === 'new' ? 'selected' : ''}>New</option>
                                <option value="contacted" ${lead.status === 'contacted' ? 'selected' : ''}>Contacted</option>
                                <option value="converted" ${lead.status === 'converted' ? 'selected' : ''}>Converted</option>
                                <option value="lost" ${lead.status === 'lost' ? 'selected' : ''}>Lost</option>
                                <option value="sent_to_crm" ${lead.status === 'sent_to_crm' ? 'selected' : ''}>Sent to CRM</option>
                                <option value="crm_error" ${lead.status === 'crm_error' ? 'selected' : ''}>CRM Error</option>
                            </select>
                        </td>
                    </tr>
                `;
            });

            tableHtml += '</tbody></table>';

            // Add pagination
            if (data.last_page > 1) {
                tableHtml += '<div class="pagination">';
                
                if (data.current_page > 1) {
                    tableHtml += `<button onclick="loadLeads(${data.current_page - 1})">Previous</button>`;
                }

                for (let i = Math.max(1, data.current_page - 2); i <= Math.min(data.last_page, data.current_page + 2); i++) {
                    tableHtml += `<button class="${i === data.current_page ? 'active' : ''}" onclick="loadLeads(${i})">${i}</button>`;
                }

                if (data.current_page < data.last_page) {
                    tableHtml += `<button onclick="loadLeads(${data.current_page + 1})">Next</button>`;
                }

                tableHtml += '</div>';
            }

            content.innerHTML = tableHtml;
        }

        function getSourceBadge(source) {
            if (source === 'facebook') {
                return '<span class="source-badge source-facebook">Facebook</span>';
            } else if (source === 'instagram') {
                return '<span class="source-badge source-instagram">Instagram</span>';
            } else {
                return '<span class="source-badge source-unknown">Unknown</span>';
            }
        }

        function applyFilters() {
            currentFilters = {
                search: document.getElementById('searchInput').value,
                status: document.getElementById('statusFilter').value,
                source: document.getElementById('sourceFilter').value,
                from_date: document.getElementById('fromDate').value,
                to_date: document.getElementById('toDate').value
            };

            Object.keys(currentFilters).forEach(key => {
                if (!currentFilters[key] || currentFilters[key] === 'all') {
                    delete currentFilters[key];
                }
            });

            loadLeads(1);
        }

        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = 'all';
            document.getElementById('sourceFilter').value = 'all';
            document.getElementById('fromDate').value = '';
            document.getElementById('toDate').value = '';
            currentFilters = {};
            loadLeads(1);
        }

        function filterTodaysLeads() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('fromDate').value = today;
            document.getElementById('toDate').value = today;
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = 'all';
            document.getElementById('sourceFilter').value = 'all';
            
            currentFilters = {
                from_date: today,
                to_date: today
            };
            
            loadLeads(1);
            showAlert("Showing today's leads", 'success');
        }

        function updateStatus(leadId, status) {
            axios.put(`/api/crm/leads/${leadId}/status`, { status })
                .then(response => {
                    showAlert('Status updated successfully!', 'success');
                    loadLeads(currentPage);
                    loadQuickStats(); // Refresh stats
                })
                .catch(error => {
                    console.error('Error updating status:', error);
                    showAlert('Failed to update status', 'error');
                });
        }

        function capitalizeFirst(str) {
            if (!str) return '';
            return str.charAt(0).toUpperCase() + str.slice(1).replace(/_/g, ' ');
        }

        function showAlert(message, type) {
            const alertsDiv = document.getElementById('alerts');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.textContent = message;
            alertsDiv.appendChild(alert);
            
            setTimeout(() => alert.remove(), 5000);
        }

        // Event listeners
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });

        // Auto-refresh leads every 2 minutes
        setInterval(() => {
            if (document.visibilityState === 'visible') {
                loadLeads(currentPage);
                loadQuickStats();
            }
        }, 120000);
    </script>
</body>
</html>