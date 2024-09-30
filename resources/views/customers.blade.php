<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Laravel-CRUD-App-AJAX</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

</head>

<body>

    <div class="container my-5">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h2>Customers List
                                <span>
                                    <a href="{{ route('customers.store') }}" class="btn btn-primary float-end"
                                        data-bs-toggle="modal" data-bs-target="#addModal">
                                        Add Customer
                                    </a>
                                </span>
                            </h2>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">id</th>
                                        <th scope="col">Customer Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Address</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="customers">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Confirmation Modal Start -->
    <div class="modal fade" id="delete_confirmation_modal" tabindex="-1" aria-labelledby="deleteConfirmationLabel"
        aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteConfirmationLabel">Delete Confirmation</h1>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this customer?</p>
                    <input type="hidden" id="delete_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirm_delete_btn">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete Confirmation Modal End -->

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCustomerModalLabel">Edit Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        @method('POST')
                        @csrf
                        <input type="hidden" name="customerid" id="update-customerid">
                        <div class="mb-3">
                            <label for="update-customername" class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="update-customername" name="customername">
                            @error('customername')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                        </div>
                        <div class="mb-3">
                            <label for="update-customeremail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="update-customeremail" name="customeremail">
                            @error('customeremail')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="update-customeraddress" class="form-label">Address</label>
                            <input type="text" class="form-control" id="update-customeraddress"
                                name="customeraddress">
                            @error('customeraddress')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="update-customerimage" class="form-label">Image</label>
                            <input type="file" class="form-control" id="update-customerimage"
                                name="customerimage">

                            <img src="" alt="User Image" width="45" height="45"
                                id="update-image-preview">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="update-customer">Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="infoModalLabel">Customer Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="" id="info-customer-image" class="img-fluid" alt="customer image">
                            </div>
                            <div class="col-md-8">
                                <p id="info-customer-name"></p>
                                <p id="info-customer-email"></p>
                                <p id="info-customer-address"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Add Modal --}}
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addCustomerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCustomerModalLabel">Add Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        @csrf
                        <div class="mb-3">
                            <label for="customername" class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="customername" name="customername"
                                aria-describedby="customername">

                            @error('customername')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                        </div>
                        <div class="mb-3">
                            <label for="customeremail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="customeremail" name="customeremail"
                                aria-describedby="customeremail">

                            @error('customeremail')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="customeraddress" class="form-label">Address</label>
                            <input type="text" class="form-control" id="customeraddress" name="customeraddress"
                                aria-describedby="customeraddress">

                            @error('customeraddress')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="customerimage" class="form-label">Image</label>
                            <input type="file" class="form-control" id="customerimage" name="customerimage"
                                aria-describedby="customerimage">

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="save-customer">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            getData();

            // Add Customer
            $('#save-customer').click(function(e) {
                e.preventDefault();
                let customername = $('#customername').val();
                let customeremail = $('#customeremail').val();
                let customeraddress = $('#customeraddress').val();
                let formData = new FormData();
                formData.append('customername', customername);
                formData.append('customeremail', customeremail);
                formData.append('customeraddress', customeraddress);
                formData.append('customerimage', $('#customerimage')[0].files[0]);

                $.ajax({
                    type: "POST",
                    url: "/customers",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#addModal').modal('hide');
                        $('#customername').val('');
                        $('#customeremail').val('');
                        $('#customeraddress').val('');
                        $('#customers').empty();
                        getData();
                    }
                })

            })
            // Show Single Customer
            $(document).on('click', '.info-btn', function(e) {
                e.preventDefault();
                let customerId = $(this).closest('tr').find('.customer-id').text();
                $.ajax({
                    type: "GET",
                    url: "/customers/" + customerId,
                    success: function(response) {
                        $('#info-customer-name').text("Customer Name: " + response.customer
                            .customername);
                        $('#info-customer-email').text("Customer Email: " + response.customer
                            .customeremail);
                        $('#info-customer-address').text("Customer Address: " + response
                            .customer.customeraddress);
                        $('#info-customer-image').attr('src', '/images/' + response.customer
                            .customerimage);
                        $('#infoModal').modal('show');
                    }
                })
            });
            // Edit Customer
            $(document).on('click', '.edit-btn', function(e) {
                e.preventDefault();
                let customerId = $(this).closest('tr').find('.customer-id').text();
                $.ajax({
                    type: "GET",
                    url: "/customers/" + customerId,
                    success: function(response) {
                        $('#update-customerid').val(response.customer.id);
                        $('#update-customername').val(response.customer.customername);
                        $('#update-customeremail').val(response.customer.customeremail);
                        $('#update-customeraddress').val(response.customer.customeraddress);
                        $('#update-image-preview').attr('src', '/images/' + response.customer
                            .customerimage);
                        // $('#update-customerimage').val(response.customer.customerimage);
                        $('#editModal').modal('show');
                    }
                })
            });
            // Update Customer
            $(document).on('click', '#update-customer', function(e) {
                e.preventDefault();
                let customerId = $('#update-customerid').val();
                let formData = new FormData();

                formData.append('customername', $('#update-customername').val());
                formData.append('customeremail', $('#update-customeremail').val());
                formData.append('customeraddress', $('#update-customeraddress').val());
                console.log("Test", formData);
                if ($('#update-customerimage')[0].files[0]) {
                    formData.append('customerimage', $('#update-customerimage')[0].files[0]);
                }

                $.ajax({
                    type: "POST",
                    url: "/customers/update/" + customerId,
                    data: formData,
                    contentType: false, // Important: do not set content type
                    processData: false, // Important: do not process data
                    success: function(response) {
                        $('#editModal').modal('hide');
                        $('#update-customerid').val('');
                        $('#update-customername').val('');
                        $('#update-customeremail').val('');
                        $('#update-customeraddress').val('');
                        getData();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error updating customer:", error);
                    }
                });
            });

            // Delete Customer
            let deleteUserId;

            // Delete the User - Show Confirmation Modal
            $(document).on('click', '.delete-btn', function() {
                deleteUserId = $(this).parent().parent().find('.customer-id').text();
                $('#delete_confirmation_modal').modal('show');
            });
            $(document).on('click', '#confirm_delete_btn', function(e) {
                e.preventDefault();
                $.ajax({
                    type: "DELETE",
                    url: "/customers/" + deleteUserId,
                    success: function(response) {
                        $('#delete_confirmation_modal').modal('hide');
                        $('#customers').empty();
                        getData();
                    }
                })
            });
        })
        // Get All the Data
        function getData() {
            $.ajax({
                type: "GET",
                url: "/customers",
                data: {
                    type: 'options'
                },
                // dataType: "json/application",
                success: function(response) {
                    $('#customers').empty();
                    $.each(response.customers, function(indexInArray, valueOfElement) {
                        $('#customers').append(
                            '<tr>' +
                            '<td class="customer-id">' + valueOfElement['id'] + '</td>' +
                            '<td>' + valueOfElement['customername'] + '</td>' +
                            '<td>' + valueOfElement['customeremail'] + '</td>' +
                            '<td>' + valueOfElement['customeraddress'] + '</td>' +
                            '<td><img src="images/' + valueOfElement['customerimage'] +
                            '" alt="Customer Image" style="width: 100px; height: auto;"></td>' +
                            '<td><button id="info" class="btn btn-info info-btn">Info</button> ' +
                            '<button id="edit" class="btn btn-primary edit-btn">Edit</button> ' +
                            '<button id="delete" class="btn btn-danger delete-btn">Delete</button></td>' +
                            '</tr>'
                        );
                    });

                }
            });
        }
    </script>
</body>

</html>
