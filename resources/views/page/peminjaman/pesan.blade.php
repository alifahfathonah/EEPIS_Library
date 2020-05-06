@extends('layouts.page')

@section('content')

<div class="content-header ml-2 mr-2">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Daftar Pemesanan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item">Peminjaman</li>
                    <li class="breadcrumb-item active">Daftar Pemesanan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content ml-3 mr-3">
    <div class="container-fluid p-3 shadow-sm mb-5 bg-white rounded">
        <div class="table-responsive">
            <table id="dt_pesan" class="table table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Judul Buku</th>
                        <th>Type Buku</th>
                        <th>Nama Peminjam</th>
                        <th>Tanggal Pesan</th>
                        <th>Waktu Kedaluwarsa</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $key)
                        <tr>
                            <td>{{$key->judul}}</td>
                            <td>{{$key->type_buku}}</td>
                            <td>{{$key->nama}}</td>
                            <td>{{$key->tgl_pesan}}</td>
                            <td>{{date("Y-m-d", strtotime('+' . $batas_waktu . " days", strtotime($key->tgl_pesan)))}}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-success" onclick="pesanBuku({{$key->id_pinjam}})"><i class="fa fa-book-open mr-1"></i>Pinjam Buku</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <script>
    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
    </script>
    <script>
        $(document).ready(function() {
            $('#dt_pesan').DataTable({
                "order": []
            });
        });

        function pesanBuku(id){
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Buku akan dipinjam!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                confirmButtonText: '<i class="fa fa-book-open"> Ya, pinjam buku',
                cancelButtonText: '<i class="fa fa-times"> Batal'
            }).then((result) => {
                if(result.value){
                    var formdata = new FormData();
                    formdata.append('id', id);
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: '/peminjaman/pesan/pinjam',
                        data: formdata,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success:function(data){
                            if(data.status === 'success'){
                                Swal.fire(
                                    'Sukses!',
                                    data.reason,
                                    'success'
                                ).then(() => {
                                    location.reload(true);
                                });
                            } else {
                                Swal.fire(
                                    'Oops...',
                                    data.reason,
                                    'error'
                                )
                            }
                        }
                    });
                }
            });
        }

    </script>
@endpush