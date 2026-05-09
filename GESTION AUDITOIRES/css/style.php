<?php
header('Content-Type: text/css; charset=UTF-8');
?>
:root {
    --bg: #f4f7fb;
    --card: #ffffff;
    --text: #293241;
    --muted: #52616b;
    --accent: #3b82f6;
    --danger: #ef4444;
    --success: #16a34a;
    --border: #d1d5db;
}
* {
    box-sizing: border-box;
}
body {
    margin: 0;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    background: var(--bg);
    color: var(--text);
}
.container {
    width: min(1100px, calc(100% - 32px));
    margin: 0 auto;
    padding: 16px 0;
}
.topbar {
    background: #1d4ed8;
    color: #fff;
    padding: 16px 0;
}
.topbar .container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
}
.topbar-grid p.topbar-subtitle {
    margin: 4px 0 0;
    color: rgba(226, 232, 240, 0.95);
    font-size: 0.95rem;
}
.topbar h1 {
    margin: 0;
    font-size: 1.3rem;
}
.topbar nav a {
    color: #e2e8f0;
    text-decoration: none;
    margin-left: 16px;
    font-weight: 600;
}
.topbar nav a:hover {
    text-decoration: underline;
}
.card-grid {
    display: grid;
    gap: 16px;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
}
.card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 18px;
    box-shadow: 0 8px 20px rgba(20, 33, 61, 0.06);
}
.card h2 {
    margin-top: 0;
}
.table-wrapper {
    overflow-x: auto;
    margin-top: 20px;
}
table {
    width: 100%;
    border-collapse: collapse;
    background: var(--card);
}
th, td {
    border: 1px solid var(--border);
    padding: 12px 10px;
    text-align: left;
}
th {
    background: #eef2ff;
    color: var(--text);
}
tr:nth-child(even) {
    background: #fbfbff;
}
.form-group {
    margin-bottom: 16px;
}
label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
}
input[type="text"], input[type="number"], select {
    width: 100%;
    padding: 10px;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: #fff;
    font-size: 0.95rem;
}
button, .button {
    border: none;
    background: var(--accent);
    color: #fff;
    padding: 10px 18px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 700;
    transition: transform 0.2s ease, background 0.2s ease;
}
button:hover, .button:hover {
    background: #2563eb;
    transform: translateY(-1px);
}
.button-link {
    background: transparent;
    color: var(--accent);
    padding: 0;
    border: none;
    font-size: 0.95rem;
    text-decoration: underline;
    cursor: pointer;
}
.button-link:hover {
    color: #1d4ed8;
}
.inline-form {
    display: inline;
}
    background: #2563eb;
}
.button-secondary {
    background: #64748b;
}
.button-danger {
    background: var(--danger);
}
.button-success {
    background: var(--success);
}
.alert {
    padding: 14px 18px;
    border-radius: 10px;
    margin-bottom: 16px;
    font-weight: 600;
}
.alert-success {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}
.alert-error {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}
.footer {
    padding: 20px 0;
    background: #e2e8f0;
    color: var(--muted);
    text-align: center;
}
.badge {
    display: inline-block;
    padding: 4px 10px;
    background: #e0f2fe;
    color: #0369a1;
    border-radius: 999px;
    font-size: 0.85rem;
}
.actions-row {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 14px;
}
.small-link {
    color: var(--accent);
    text-decoration: none;
    font-size: 0.95rem;
}
.small-link:hover {
    text-decoration: underline;
}

.login-wrapper {
    display: grid;
    place-items: center;
    min-height: calc(100vh - 150px);
    padding: 24px 0;
}
.login-card {
    width: min(480px, 100%);
    padding: 28px;
    border-radius: 18px;
    box-shadow: 0 16px 40px rgba(20, 33, 61, 0.08);
}
.login-card h2 {
    margin-top: 0;
}
.login-note {
    color: var(--muted);
    margin-bottom: 18px;
}
