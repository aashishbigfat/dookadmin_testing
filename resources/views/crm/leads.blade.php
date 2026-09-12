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
        .container { max-width: 1400px; margin: 0 auto; padding: 20px; }
        .header { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .nav { display: flex; gap: 20px; margin-top: 10px; }
        .nav a { text-decoration: none; color: #64748b; padding: 8px 16px; border-radius: 4px; transition: all 0.2s; }
        .nav a:hover, .nav a.active { background: #3b82f6; color: white; }
        .filters { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .filter-row { display: grid; grid-template-columns: 1fr 1fr 1fr 1fr auto; gap: 15px; align-items: end; }
        .filter-group label { display: block; margin-bottom: 5px; font-weight: 500; color: #374151; }
        .filter-group input, .filter-group select { width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 14px; }
        .btn { background: #3b82f6; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-size: 14px; transition: all 0.2s; }
        .btn:hover { background: #2563eb; }
        .btn-secondary { background: #6b7280; }
        .btn-secondary:hover { background: #4b5563; }
        .leads-container { background: white; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden; }
        .leads-table { width: 100%; border-collapse: collapse; }
        .leads-table th { background: #f9fafb; padding: 12px; text-align: left; font-weight: 600; color: #374151; border-bottom: 1px solid #e5e7eb; }
        .leads-table td { padding: 12px; border-bottom: 1px solid #f3f4f6; }
        .leads-table tr:hover { background: #f9fafb; }
        .status-badge { padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: 500; }
        .status-new { background: #dbeafe; color: #1d4ed8; }
        .status-contacted { background: #fef3c7; color: #d97706; }
        .status-converted { background: #d1fae5; color: #065f46; }
        .status-lost { background: #fee2e2; color: #dc2626; }
        .status-select { padding: 4px 8px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 12px; }
        .pagination { display: flex; justify-content: center; padding: 20px; gap: 10px; }
        .pagination button { padding: 8px 12px; border: 1px solid #d1d5db; background: white; cursor: pointer; border-radius: 4px; }
        .pagination button:hover { background: #f3f4f6; }
        .pagination button.active { background: #3b82f6; color: white; border-color: #3b82f6; }
        .loading { text-align: center; padding: 40px; color: #64748b; }
        .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .lead-details { max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        @media (max-width: 768px) { .filter-row { grid-template-columns: 1fr; } .container { padding: 10px; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>All Leads</h1>
            <div class="nav">
                <a href="/crm/dashboard">Dashboard</a>
                <a href="/crm/leads" class="active">All Leads</a>
            </div>
        </div>

        <div id="alerts"></div>

        <div class="filters">
            <h3 style="margin-bottom: 15px;">Filter Leads</h3>
            <div class="filter-row">
                <div class="filter-group">
                    <label>Search</label>
                    <input type="text" id="searchInput" placeholder="Search by name, email, or phone...">
                </div>
                <div class="filter-group">
                    <label>Status</label>
                    <select id="statusFilter">
                        <option value="all">All Status</option>
                        <option value="new">New</option>
                        <option value="contacted">Contacted</option>
                        <option value="converted">Converted</option>
                        <option value="lost">Lost</option>
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
                    <button class="btn" onclick="applyFilters()">Filter</button>
                    <button class="btn btn-secondary" onclick="clearFilters()" style="margin-top: 5px;">Clear</button>
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
                        <td class="lead-details">${lead.name || 'N/A'}</td>
                        <td class="lead-details">${lead.email || 'N/A'}</td>
                        <td>${lead.phone || 'N/A'}</td>
                        <td>
                            <span class="status-badge status-${lead.status}">${capitalizeFirst(lead.status)}</span>
                        </td>
                        <td>
                            <select class="status-select" onchange="updateStatus(${lead.id}, this.value)">
                                <option value="new" ${lead.status === 'new' ? 'selected' : ''}>New</option>
                                <option value="contacted" ${lead.status === 'contacted' ? 'selected' : ''}>Contacted</option>
                                <option value="converted" ${lead.status === 'converted' ? 'selected' : ''}>Converted</option>
                                <option value="lost" ${lead.status === 'lost' ? 'selected' : ''}>Lost</option>
                            </select>
                        </td>
                    </tr>
                `;
            });

            tableHtml += '</tbody></table>';

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

        function applyFilters() {
            currentFilters = {
                search: document.getElementById('searchInput').value,
                status: document.getElementById('statusFilter').value,
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
            document.getElementById('fromDate').value = '';
            document.getElementById('toDate').value = '';
            currentFilters = {};
            loadLeads(1);
        }

        function updateStatus(leadId, status) {
            axios.put(`/api/crm/leads/${leadId}/status`, { status })
                .then(response => {
                    showAlert('Status updated successfully!', 'success');
                    loadLeads(currentPage);
                })
                .catch(error => {
                    console.error('Error updating status:', error);
                    showAlert('Failed to update status', 'error');
                });
        }

        function capitalizeFirst(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        function showAlert(message, type) {
            const alertsDiv = document.getElementById('alerts');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.textContent = message;
            alertsDiv.appendChild(alert);
            
            setTimeout(() => alert.remove(), 5000);
        }

        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyFilters();
            }
        });
    </script>
</body>
</html>