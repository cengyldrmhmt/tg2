<?php
session_start();
require_once '../config/config.php';
require_once '../includes/functions.php';

// Get all tables from database
$tables = getTables();

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: ../login.php');
    exit();
}

// Get table name from URL and validate it
$tableName = isset($_GET['table']) ? sanitizeInput($_GET['table']) : '';
if (!$tableName || !validateTableName($tableName)) {
    header('Location: dashboard.php');
    exit();
}

// Get table data
$tableData = getTableData($tableName);
if ($tableData === false) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($tableName); ?> - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/datatable.css" rel="stylesheet">
</head>
<body>
    <div class="sidebar">
        <div class="logo-details">
            <i class='bx bx-code-alt'></i>
            <span class="logo_name">Admin Panel</span>
        </div>
        <ul class="nav-links">
            <li>
                <a href="dashboard.php">
                    <i class='bx bx-grid-alt'></i>
                    <span class="link_name">Dashboard</span>
                </a>
            </li>
            <?php if ($tables): ?>
                <?php foreach ($tables as $table): 
                    $isActive = $table === $tableName ? ' active' : '';
                ?>
                    <li>
                        <a href="view_table.php?table=<?php echo urlencode($table); ?>"<?php echo $isActive; ?>>
                            <i class='bx bx-table'></i>
                            <span class="link_name"><?php echo htmlspecialchars($table); ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php endif; ?>
            <li>
                <a href="logout.php">
                    <i class='bx bx-log-out'></i>
                    <span class="link_name">Logout</span>
                </a>
            </li>
        </ul>
    </div>

    <section class="home-section">
        <div class="home-content">
            <i class='bx bx-menu'></i>
            <span class="text"><?php echo htmlspecialchars($tableName); ?></span>
            <div class="theme-switch-container">
                <label class="theme-switch">
                    <input type="checkbox" id="theme-toggle">
                    <span class="slider"></span>
                </label>
            </div>
        </div>

        <div class="container-fluid px-4">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-striped table-bordered table-hover display w-100">
                            <thead>
                                <tr>
                                    <?php foreach ($tableData['columns'] as $column): ?>
                                        <th><?php echo htmlspecialchars($column); ?></th>
                                    <?php endforeach; ?>
                                    <th style="width: 150px;">İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tableData['rows'] as $row): ?>
                                    <tr>
                                        <?php foreach ($tableData['columns'] as $column): ?>
                                            <td data-column="<?php echo htmlspecialchars($column); ?>"><?php echo htmlspecialchars($row[$column]); ?></td>
                                        <?php endforeach; ?>
                                        <td>
                                            <button class="btn btn-sm btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#editModal"><i class='bx bx-edit-alt'></i> Düzenle</button>
                                            <button class="btn btn-sm btn-danger delete-btn"><i class='bx bx-trash'></i> Sil</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kayıt Düzenle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <?php foreach ($tableData['columns'] as $column): ?>
                            <div class="mb-3">
                                <label for="edit_<?php echo htmlspecialchars($column); ?>" class="form-label"><?php echo htmlspecialchars($column); ?></label>
                                <input type="text" class="form-control" id="edit_<?php echo htmlspecialchars($column); ?>" name="<?php echo htmlspecialchars($column); ?>">
                            </div>
                        <?php endforeach; ?>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    <button type="button" class="btn btn-primary" id="updateBtn">Güncelle</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root[data-theme="light"] {
            --table-text: #1a1a1a;
            --table-bg: #ffffff;
            --table-border: #e5e7eb;
            --table-stripe: #f9fafb;
            --table-hover: #f3f4f6;
            --link-color: #2563eb;
            --link-hover: #1d4ed8;
            --input-bg: #ffffff;
            --input-text: #1a1a1a;
            --input-border: #e5e7eb;
            --input-focus-border: #2563eb;
            --input-placeholder: #6b7280;
        }

        :root[data-theme="dark"] {
            --bg-color: #1a1d21;
            --text-color: #e0e0e0;
            --card-bg: #2d2d2d;
            --card-text: #e0e0e0;
            --border-color: #404040;
            --sidebar-bg: #2d2d2d;
            --sidebar-text: #e0e0e0;
            --sidebar-hover: #3d3d3d;
            --link-color: #6ea8fe;
            --link-hover: #8bb9fe;
            --table-bg: #2d2d2d;
            --table-text: #e0e0e0;
            --table-border: #404040;
            --table-stripe: #363636;
            --table-hover: #3d3d3d;
            --input-bg: #2d2d2d;
            --input-text: #e0e0e0;
            --input-border: #404040;
            --input-focus-border: #6ea8fe;
            --input-placeholder: #808080;
            --home-section-bg: #1a1d21;
        }

        .table {
            color: var(--table-text);
            background-color: var(--table-bg);
            border-color: var(--table-border);
            font-size: 11px;
            margin-bottom: 0;
            width: 100% !important;
        }
        .table td, .table th {
            padding: 0.25rem 0.5rem;
            white-space: nowrap;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: var(--table-stripe);
        }
        .table-hover tbody tr:hover {
            background-color: var(--table-hover);
        }
        .dataTables_wrapper {
            overflow: hidden;
        }
        .home-section {
            overflow-y: auto;
            padding-bottom: 2rem;
        }
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            background-color: var(--input-bg);
            color: var(--input-text);
            border: 1px solid var(--input-border);
            border-radius: 0.375rem;
            padding: 0.375rem 0.75rem;
            transition: border-color 0.15s ease-in-out;
            background-color: var(--card-bg);
            color: var(--text-color);
            border-color: var(--border-color);
            font-size: 11px;
        }
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            color: var(--text-color) !important;
            font-size: 11px;
            padding-top: 0.5em;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            color: var(--text-color) !important;
            padding: 0.3em 0.6em;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: var(--link-color) !important;
            color: #fff !important;
            border-color: var(--link-color) !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: var(--link-hover) !important;
            color: #fff !important;
            border-color: var(--link-hover) !important;
        }
        .modal-content {
            background-color: var(--card-bg);
            color: var(--text-color);
        }
        .modal-header {
            border-bottom-color: var(--border-color);
        }
        .modal-footer {
            border-top-color: var(--border-color);
        }
        .form-control {
            background-color: var(--card-bg);
            color: var(--text-color);
            border-color: var(--border-color);
            font-size: 11px;
        }
        .form-control:focus {
            background-color: var(--card-bg);
            color: var(--text-color);
        }
        .btn-sm {
            padding: 0.2rem 0.4rem;
            font-size: 11px;
        }
        .card {
            margin-bottom: 0;
        }
        .card-body {
            padding: 1rem;
        }
    </style>
    <script>
        $(document).ready(function() {
            const table = $('#dataTable').DataTable({
                "pageLength": 100,
                "order": [],
                "scrollX": true,
                "responsive": true,
                "autoWidth": false,
                "deferRender": true,
                "processing": true,
                "stateSave": true,
                "dom": '<"top"fl>rt<"bottom"ip>',
                "columnDefs": [{
                    "targets": -1,
                    "width": "100px",
                    "orderable": false
                }],
                "language": {
                    "search": "Ara:",
                    "lengthMenu": "_MENU_ kayıt göster",
                    "info": "_TOTAL_ kayıttan _START_ - _END_ arası gösteriliyor",
                    "infoEmpty": "Kayıt yok",
                    "infoFiltered": "(_MAX_ kayıt içerisinden bulunan)",
                    "paginate": {
                        "first": "İlk",
                        "last": "Son",
                        "next": "Sonraki",
                        "previous": "Önceki"
                    }
                }
            });

            const themeToggle = document.getElementById('theme-toggle');
            const html = document.documentElement;
            const sidebar = document.querySelector('.sidebar');
            const menuBtn = document.querySelector('.bx-menu');

            // Check for saved theme preference
            const savedTheme = localStorage.getItem('theme') || 'light';
            html.setAttribute('data-theme', savedTheme);
            themeToggle.checked = savedTheme === 'dark';

            // Theme toggle handler
            themeToggle.addEventListener('change', () => {
                const newTheme = themeToggle.checked ? 'dark' : 'light';
                html.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
            });

            // Sidebar toggle
            menuBtn.addEventListener('click', () => {
                sidebar.classList.toggle('close');
                localStorage.setItem('sidebarState', sidebar.classList.contains('close') ? 'closed' : 'open');
            });

            // Check for saved sidebar state
            const savedSidebarState = localStorage.getItem('sidebarState');
            if (savedSidebarState === 'closed') {
                sidebar.classList.add('close');
            }

            // Handle responsive sidebar on page load and resize
            function handleResponsiveSidebar() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.add('close');
                } else {
                    if (savedSidebarState !== 'closed') {
                        sidebar.classList.remove('close');
                    }
                }
            }

            window.addEventListener('load', handleResponsiveSidebar);
            window.addEventListener('resize', handleResponsiveSidebar);

            // Hücre tıklama olayı
            $('#dataTable').on('click', 'td[data-column]', function() {
                const column = $(this).data('column');
                const value = $(this).text();
                const row = table.row($(this).closest('tr'));
                const id = row.data()[0];

                Swal.fire({
                    title: column + ' Düzenle',
                    input: 'text',
                    inputValue: value,
                    showCancelButton: true,
                    confirmButtonText: 'Güncelle',
                    cancelButtonText: 'İptal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = {};
                        formData[column] = result.value;

                        fetch(`table_actions.php?action=update&table=<?php echo urlencode($tableName); ?>`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                id: id,
                                data: formData
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                $(this).text(result.value);
                                Swal.fire('Başarılı!', 'Değer güncellendi', 'success');
                            } else {
                                throw new Error(data.error || 'Güncelleme başarısız');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Hata!', error.message, 'error');
                        });
                    }
                });
            });

            // Düzenle butonuna tıklama olayı
            $('#dataTable').on('click', '.edit-btn', function() {
                $('.edit-btn.active').removeClass('active');
                $(this).addClass('active');
                const row = table.row($(this).closest('tr')).data();
                <?php foreach ($tableData['columns'] as $index => $column): ?>
                    $('#edit_<?php echo htmlspecialchars($column); ?>').val(row[<?php echo $index; ?>]);
                <?php endforeach; ?>
                $('#editModal').modal('show');
            });

            // Güncelleme işlemi
            $('#updateBtn').on('click', function() {
                const formData = {};
                const activeBtn = $('.edit-btn.active');
                if (!activeBtn.length) {
                    Swal.fire('Hata!', 'Düzenlenecek kayıt seçilmedi', 'error');
                    return;
                }
                const row = table.row(activeBtn.closest('tr'));
                const id = row.data()[0];

                <?php foreach ($tableData['columns'] as $column): ?>
                    formData['<?php echo $column; ?>'] = $('#edit_<?php echo htmlspecialchars($column); ?>').val();
                <?php endforeach; ?>

                fetch(`table_actions.php?action=update&table=<?php echo urlencode($tableName); ?>`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: id,
                        data: formData
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const updatedData = [...row.data()];
                        <?php foreach ($tableData['columns'] as $index => $column): ?>
                            updatedData[<?php echo $index; ?>] = formData['<?php echo $column; ?>'];
                        <?php endforeach; ?>
                        row.data(updatedData).draw();
                        $('#editModal').modal('hide');
                        activeBtn.removeClass('active');
                        Swal.fire('Başarılı!', 'Kayıt güncellendi', 'success');
                    } else {
                        throw new Error(data.error || 'Güncelleme başarısız');
                    }
                })
                .catch(error => {
                    Swal.fire('Hata!', error.message, 'error');
                });
            });

            // Silme işlemi
            $('#dataTable').on('click', '.delete-btn', function() {
                const row = table.row($(this).closest('tr'));
                const id = row.data()[0]; // İlk sütun ID olarak varsayılıyor

                Swal.fire({
                    title: 'Emin misiniz?',
                    text: 'Bu kaydı silmek istediğinize emin misiniz?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Evet, sil',
                    cancelButtonText: 'İptal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`table_actions.php?action=delete&table=<?php echo urlencode($tableName); ?>`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                id: id
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                row.remove().draw();
                                Swal.fire('Silindi!', 'Kayıt başarıyla silindi.', 'success');
                            } else {
                                throw new Error(data.error || 'Silme işlemi başarısız');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Hata!', error.message, 'error');
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>