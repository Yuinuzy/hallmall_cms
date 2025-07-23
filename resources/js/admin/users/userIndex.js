import $ from "jquery";
import { isLogin } from "../../auth/Authentication";

$(document).ready(function () {
    if (isLogin("Admin")) {
        // Handle flash message
        if (localStorage.getItem("alert")) {
            let alert = JSON.parse(localStorage.getItem("alert"))[0];
            let color = alert.status === "success" ? "success" : "danger";
            $("#alert").addClass(`alert alert-${color}`);
            $("#alert").append(`<div>${alert.message}</div>`);
            $("#alert").append(`<button type="button" class="btn-close text-light" data-bs-dismiss="alert" aria-label="Close"></button>`);

            setTimeout(() => {
                $("#alert").removeClass(`alert alert-${color}`);
                $("#alert").html("");
                localStorage.removeItem("alert");
            }, 3000);
        }

        let table = new DataTable('#table-user', {
            dom: "<'row'<'col-sm-12 col-md-5 btn-table'><'col-sm-12 col-md-3'<'ms-4'f>><'col-sm-12 col-md-4 pdf-button'>>" +
                "<'row mt-3'<'col-sm-12'tr>>" +
                "<'row mt-2'<'col-md-8 col-12'i><'col-md-4 col-12'p>>",
            ordering: false,
            info: true,
            filtering: false,
            searching: true,
            serverSide: true,
            processing: true,
            responsive: true,
            autoWidth: false,
            ajax: {
                url: "/api/user/table",
                method: 'GET',
                dataSrc: 'data',
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            },
            columns: [
                { data: 'id' },
                { data: 'name' }, // Changed from username to name
                { data: 'email' },
                { data: 'role' },
                {
                    data: 'status',
                    render: function (data) {
                        if (data === 'Active') {
                            return `<span class="badge bg-primary me-1">${data}</span>`;
                        } else if (data === 'Non Aktif') {
                            return `<span class="badge bg-danger me-1">${data}</span>`;
                        } else {
                            return `<span class="badge bg-warning me-1">${data}</span>`;
                        }
                    }
                },
                {
                    data: null,
                    render: function (data) {
                        return `
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="/pages/user/${data.id}/editpass"><i class='bx bxs-lock-open-alt'></i> Change Password</a>
                                    <a class="dropdown-item" href="/pages/user/${data.id}/detail"><i class='bx bxs-user-detail'></i> Detail</a>
                                    <a class="dropdown-item" href="/pages/user/${data.id}/edit"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                    <a class="dropdown-item delete-btn" data-id="${data.id}"><i class="bx bx-trash me-1"></i> Delete</a>
                                </div>
                            </div>
                        `;
                    }
                }
            ]
        });

        $(document).on("click", ".delete-btn", function () {
            let userId = $(this).data("id");
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: `/api/user/${userId}/delete`,
                        headers: {
                            'Authorization': 'Bearer ' + localStorage.getItem('token')
                        },
                        success: function () {
                            table.ajax.reload();
                            Swal.fire({
                                title: "Success!",
                                text: "Data successfully deleted",
                                icon: "success"
                            });
                        },
                        error: function (jqxhr) {
                            Swal.fire({
                                title: "Failed!",
                                text: jqxhr.responseJSON?.message || "Data deletion failed",
                                icon: "error"
                            });
                        }
                    });
                }
            });
            $(".btn-table").append('<a href="/pages/kategori/create" class="btn btn-primary">Tambah Pengguna</a>');
        });
    }
});
