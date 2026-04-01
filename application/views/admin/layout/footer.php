            </div><!-- /.page-content -->
            </main><!-- /.main-content -->
            </div><!-- /.admin-wrapper -->

            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
            <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
            <script>
                // Sidebar toggle
                document.getElementById('sidebarToggle').addEventListener('click', function() {
                    document.getElementById('sidebar').classList.toggle('collapsed');
                    document.getElementById('mainContent').classList.toggle('expanded');
                });

                // Initialize DataTables
                $(document).ready(function() {
                    if ($('.data-table').length) {
                        $('.data-table').DataTable({
                            language: {
                                search: "Cari:",
                                lengthMenu: "Tampilkan _MENU_ data",
                                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                                infoEmpty: "Tidak ada data",
                                emptyTable: "Tidak ada data tersedia",
                                paginate: {
                                    first: "Pertama",
                                    last: "Terakhir",
                                    next: "&#8250;",
                                    previous: "&#8249;"
                                }
                            },
                            lengthMenu: [
                                [10, 25, 50, 100, -1],
                                [10, 25, 50, 100, "Semua"]
                            ],
                            pageLength: 10,
                            responsive: true
                        });
                    }
                });

                // Delete confirmation
                function confirmDelete(url, name) {
                    if (confirm('Yakin ingin menghapus "' + name + '"?')) {
                        window.location.href = url;
                    }
                }
            </script>
            </body>

            </html>