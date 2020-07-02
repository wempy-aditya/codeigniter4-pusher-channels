<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CI 4 | PUSHER</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    </head>

    <body>

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <br><br>
                    <h2 class="text-center">Percobaan CRUD Realtime Menggunakan Pusher Channel</h2>
                    <br><br>
                    <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#ModalAdd">Tambah Produk Baru</button>
                    <div class="table-responsive"></div>
                    <table id="mytable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="show_product">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Add New Product -->
        <div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add New Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="input1">Product Name</label>
                            <input type="text" name="product_name" class="form-control name" id="input1" placeholder="Product Name">
                        </div>
                        <div class="form-group">
                            <label for="input2">Product Price</label>
                            <input type="text" name="product_price" class="form-control price" id="input2" placeholder="Product Price">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-save">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit Product -->
        <div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="input1">Product Name</label>
                            <input type="text" name="product_name" class="form-control name_edit" id="input1" placeholder="Product Name">
                        </div>
                        <div class="form-group">
                            <label for="input2">Product Price</label>
                            <input type="text" name="product_price" class="form-control price_edit" id="input2" placeholder="Product Price">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="product_id" class="id_edit">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-edit">Edit</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Delete Product -->
        <div class="modal fade" id="ModalDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            Anda yakin mau menghapus data ini?
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="product_id" class="product_id">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                        <button type="button" class="btn btn-primary btn-delete">Yes</button>
                    </div>
                </div>
            </div>
        </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> 
    <script src="https://js.pusher.com/4.4/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
    $(document).ready(function(){
        show_product();
        Pusher.logToConsole = true;

        var pusher = new Pusher('ba91a26344682a3ae594', {
            cluster: 'ap1',
            forceTLS: true
        });

        var channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) {
            if(data.message === 'success'){
                show_product();
            }
        });

        // FUNCTION SHOW PRODUCT
        function show_product(){
            $.ajax({
                url   : '<?php echo site_url("Home/get_product");?>',
                type  : 'GET',
                async : true,
                dataType : 'json',
                success : function(data){
                    var html = '';
                    var count = 1;
                    var i;
                    for(i=0; i<data.length; i++){
                        html += '<tr>'+
                                '<td>'+ count++ +'</td>'+
                                '<td>'+ data[i].product_name +'</td>'+
                                '<td>'+ data[i].product_price +'</td>'+
                                '<td>'+
                                    '<a href="javascript:void(0);" class="btn btn-sm btn-info item_edit" data-id="'+ data[i].product_id +'" data-name="'+ data[i].product_name +'" data-price="'+ data[i].product_price +'">Edit</a>'+
                                    '<a href="javascript:void(0);" class="btn btn-sm btn-danger item_delete" data-id="'+ data[i].product_id +'">Delete</a>'+
                                '</td>'+
                                '</tr>';
                    }
                    $('.show_product').html(html);
                }
            });
        }

        // CREATE NEW PRODUCT
        $('.btn-save').on('click',function(){
            var product_name = $('.name').val();
            var product_price = $('.price').val();
            $.ajax({
                url    : '<?php echo site_url("Home/create");?>',
                method : 'POST',
                data   : {product_name: product_name, product_price: product_price},
                success: function(){
                    $('#ModalAdd').modal('hide');
                    $('.name').val("");
                    $('.price').val("");
                }
            });
        });
        // END CREATE PRODUCT

        // UPDATE PRODUCT
        $('#mytable').on('click','.item_edit',function(){
            var product_id = $(this).data('id');
            var product_name = $(this).data('name');
            var product_price = $(this).data('price');
            $('#ModalEdit').modal('show');
            $('.id_edit').val(product_id);
            $('.name_edit').val(product_name);
            $('.price_edit').val(product_price);
        });
        $('.btn-edit').on('click',function(){
            var product_id = $('.id_edit').val();
            var product_name = $('.name_edit').val();
            var product_price = $('.price_edit').val();
            $.ajax({
                url    : '<?php echo site_url("Home/update");?>',
                method : 'POST',
                data   : {product_id: product_id, product_name: product_name, product_price: product_price},
                success: function(){
                    $('#ModalEdit').modal('hide');
                    $('.id_edit').val("");
                    $('.name_edit').val("");
                    $('.price_edit').val("");
                }
            });
        });
        // END EDIT PRODUCT

        // DELETE PRODUCT
        $('#mytable').on('click','.item_delete',function(){
            var product_id = $(this).data('id');
            $('#ModalDelete').modal('show');
            $('.product_id').val(product_id);
        });
        $('.btn-delete').on('click',function(){
            var product_id = $('.product_id').val();
            $.ajax({
                url    : '<?php echo site_url("Home/delete");?>',
                method : 'POST',
                data   : {product_id: product_id},
                success: function(){
                    $('#ModalDelete').modal('hide');
                    $('.product_id').val("");
                }
            });
        });
        // END DELETE PRODUCT

    });
    </script>
    
    </body>
</html>