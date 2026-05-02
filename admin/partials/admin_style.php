<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:'Inter',sans-serif; }
body { display:flex; background:#121212; color:#f8f9fa; min-height:100vh; }

/* Sidebar */
.sidebar {
    width: 260px; background: #1a1a1a;
    border-right: 1px solid #2a2a2a;
    display: flex; flex-direction: column;
    position: fixed; top:0; left:0; height:100vh;
}
.sidebar-logo {
    padding: 22px 25px; font-size:20px; font-weight:800; color:#ff6b00;
    border-bottom:1px solid #2a2a2a; display:flex; align-items:center; gap:10px;
}
.sidebar nav { padding:12px 0; flex:1; overflow-y:auto; }
.sidebar nav a {
    display:flex; align-items:center; gap:12px;
    padding:13px 25px; color:#a0aab2; text-decoration:none;
    font-size:14px; font-weight:500; transition:all .2s;
    border-left:3px solid transparent;
}
.sidebar nav a:hover, .sidebar nav a.aktif {
    background:rgba(255,107,0,0.08); color:#ff6b00; border-left-color:#ff6b00;
}
.sidebar-footer { padding:18px 25px; border-top:1px solid #2a2a2a; }
.sidebar-footer a { display:flex; align-items:center; gap:10px; color:#ff6b6b; text-decoration:none; font-size:14px; font-weight:500; }

/* Content */
.content { margin-left:260px; flex:1; padding:35px 40px; }
.page-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:30px; }
.page-title { font-size:26px; font-weight:800; margin-bottom:5px; }
.page-subtitle { color:#a0aab2; font-size:14px; }

/* Alerts */
.alert { padding:14px 20px; border-radius:10px; margin-bottom:20px; font-size:14px; display:flex; align-items:center; gap:10px; }
.alert-error   { background:rgba(255,50,50,0.1); border:1px solid rgba(255,50,50,.3); color:#ff6b6b; }
.alert-success { background:rgba(50,200,80,0.1); border:1px solid rgba(50,200,80,.3); color:#6bff8e; }

/* Table */
.table-wrap { background:#1a1a1a; border:1px solid #2a2a2a; border-radius:14px; overflow:hidden; }
table { width:100%; border-collapse:collapse; }
thead th { background:#1e1e1e; padding:14px 20px; text-align:left; font-size:12px; font-weight:600; color:#a0aab2; text-transform:uppercase; letter-spacing:.5px; border-bottom:1px solid #2a2a2a; }
tbody td { padding:14px 20px; border-bottom:1px solid #222; font-size:14px; color:#d0d0d0; }
tbody tr:last-child td { border-bottom:none; }
tbody tr:hover { background:rgba(255,107,0,0.03); }

/* Badges */
.badge { display:inline-block; padding:4px 12px; border-radius:20px; font-size:11px; font-weight:700; text-transform:uppercase; }
.badge-orange { background:rgba(255,107,0,0.15); color:#ff6b00; }
.badge-blue   { background:rgba(59,130,246,0.15); color:#60a5fa; }
.badge-green  { background:rgba(34,197,94,0.15);  color:#4ade80; }
.badge-gray   { background:rgba(150,150,150,0.15); color:#9ca3af; }

/* Buttons */
.btn-orange { background:linear-gradient(135deg,#ff6b00,#e65c00); color:#fff; border:none; padding:11px 22px; border-radius:8px; font-size:14px; font-weight:700; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:8px; transition:all .3s; }
.btn-orange:hover { transform:translateY(-2px); box-shadow:0 6px 15px rgba(255,107,0,.35); }
.btn-gray-btn { background:#2a2a2a; color:#a0aab2; border:1px solid #333; padding:11px 22px; border-radius:8px; font-size:14px; font-weight:600; cursor:pointer; text-decoration:none; display:inline-flex; align-items:center; gap:8px; transition:all .2s; }
.btn-gray-btn:hover { background:#333; color:#f8f9fa; }
.btn-sm { display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; text-decoration:none; font-size:13px; transition:all .2s; }
.btn-red  { background:rgba(255,50,50,.15); color:#ff6b6b; border:1px solid rgba(255,50,50,.2); }
.btn-red:hover  { background:rgba(255,50,50,.3); }
.btn-green { background:rgba(34,197,94,.15); color:#4ade80; border:1px solid rgba(34,197,94,.2); }
.btn-green:hover { background:rgba(34,197,94,.3); }
.btn-blue  { background:rgba(59,130,246,.15); color:#60a5fa; border:1px solid rgba(59,130,246,.2); }
.btn-blue:hover  { background:rgba(59,130,246,.3); }
.btn-gray  { background:rgba(150,150,150,.15); color:#9ca3af; border:1px solid rgba(150,150,150,.2); }
.btn-gray:hover  { background:rgba(150,150,150,.3); }

/* Stats */
.stats-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:20px; margin-bottom:35px; }
.stat-card { background:#1a1a1a; border:1px solid #2a2a2a; border-radius:14px; padding:25px; display:flex; align-items:center; gap:20px; transition:border-color .3s; }
.stat-card:hover { border-color:#ff6b00; }
.stat-icon { width:52px; height:52px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:20px; }
.stat-icon.orange { background:rgba(255,107,0,.15); color:#ff6b00; }
.stat-icon.blue   { background:rgba(96,165,250,.15); color:#60a5fa; }
.stat-icon.green  { background:rgba(107,255,142,.15); color:#6bff8e; }
.stat-num   { font-size:30px; font-weight:800; }
.stat-label { color:#a0aab2; font-size:13px; margin-top:2px; }
</style>
