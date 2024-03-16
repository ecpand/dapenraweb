@extends('backend.master')
@section('title')
Pegawai
@endsection

@section('content')
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-5">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <div>
                <h2 class="text-white pb-2 fw-bold">Data Pegawai</h2>
                <h5 class="text-white op-7 mb-2">Dana Pensiun Angkasa Pura I</h5>

            </div>
            <div class="ml-md-auto py-2 py-md-0">
                <button style="width: 100px" type="button" id="add" class="btn  btn-round btn-secondary ">Add</button>
                <button style="width: 200px" type="button" id="addimport" class="btn  btn-round btn-secondary ">Import
                    Data Excel</button>
            </div>
        </div>
    </div>
</div>

<div class="page-inner mt--5">
    <div class="row mt--2">
        <div class="col-md-12">
            <div class="card full-height">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table-pegawai" class="display table table-striped table-hover" >
                            <thead>
                                <tr>
                                    <th>NIP</th>
                                    <th>No KTP</th>
                                    <th>Nama</th>
                                    <th>Tempat Lahir</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Jenkel</th>
                                    <th>Alamat</th>
                                    <th>RT RW</th>
                                    <th>Kel</th>
                                    <th>Kec</th>
                                    <th>Kota</th>
                                    <th>Prov</th>
                                    <th>Telepon</th>
                                    <th>Kode Pos</th>
                                    <th>stwkn</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.pegawai.modals.modal_pegawai')
@include('admin.pegawai.modals.modal_import')
@endsection


@push('script')


<script>
    $('#add').on('click', function() {
        $('.modal-title').html('Tambah Pegawai');
        $('#id').val("");
        $('#title').val("");
        $('#start_date').val("");
        $('#end_date').val("");
        $('#modal-pegawai').modal('show');
        $('#btn')
            .removeClass('btn-info')
            .addClass('btn-primary')
            .html('Save')
            .val('simpan');
    });

    $('#addimport').on('click', function() {
        $('.modal-title').html('Import Data Pegawai');
        $('#id').val("");
        $('#title').val("");
        $('#start_date').val("");
        $('#end_date').val("");
        $('#modal-import').modal('show');
        $('#btn')
            .removeClass('btn-info')
            .addClass('btn-primary')
            .html('Import')
            .val('import');
    });
</script>

<script>
    $('#pegawai').submit(function(e){
            e.preventDefault();
            var form = $('form');
            form.find('span').remove();
            form.find('.form-group').removeClass('is-invalid');
            form.find('.form-control').removeClass('is-invalid');

            if ($('#btn').val() == 'simpan') {
                var url = "{{route('admin.pegawai.store')}}";
                var type = "POST";
                var text = "Successfully Create Pegawai";
            }

            if ($('#btn').val() == 'import') {
                var url = "{{route('admin.importdata')}}";
                var type = "POST";
                var text = "Successfully Import Data Pegawai";
            }

            if ($('#btn').val() == 'update') {
                var id = $("#id").val();
                var type = "PUT";
                var url = "{{url('admin/pegawai')}}/"+ id;
                var text = "Successfully Update Pegawai";
            }

            //console.log(url);

            $.ajax({
                url: url,
                type:type,
                data : form.serialize(),

                success: function (response){
                    console.log(JSON.stringify(response));
                    $('#modal-pegawai').modal('hide');
                    $('#table-pegawai').DataTable().ajax.reload();
                    swal("Good job!", text, {
        						icon : "success",
        						buttons: {
        							confirm: {
        								className : 'btn btn-success'
        							}
						        },
					          });
                },
                error: function(xhr){
                    var res = xhr.responseJSON;
                    if ($.isEmptyObject(res) == false) {
                    $.each(res.errors, function (key, value) {
                    $('#' + key).closest('.form-group').addClass('is-invalid').append('<span class="is-invalid text-danger"><strong>'+ value
                            +'</strong></span>');
                    $('#' + key).closest('.form-control').addClass('is-invalid');
                    })
                    }
                }
            })
        });
</script>

<script>
    $('#table-pegawai').DataTable({
        responsive: true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.pegawai.index') }}",
                columns: [
                {data: 'nik', name: 'nik'},
                {data: 'noktp', name: 'noktp'},
                {data: 'nama', name: 'nama'},
                {data: 'tempat_lahir', name: 'tempat_lahir'},
                {data: 'tanggal_lahir', name: 'tanggal_lahir'},
                {data: 'jenkel', name: 'jenkel'},
                {data: 'alamat', name: 'alamat', width:100},
                {data: 'rtrw', name: 'rtrw'},
                {data: 'kelurahan', name: 'kelurahan'},
                {data: 'kecamatan', name: 'kecamatan'},
                {data: 'kota', name: 'kota'},
                {data: 'provinsi', name: 'provinsi'},
                {data: 'telepon', name: 'telepon'},
                {data: 'kodepos', name: 'kodepos'},
                {data: 'stkwn', name: 'stkwn'},
                {data: 'id', name: 'id',
                "render": function (data, type, row, meta) {
                        return'<div> <a href="{{url("admin/pegawai")}}/'+data+'/edit"class="btn btn-primary btn-sm  button-update" title="Edit" data-id='+data+'><i class="fa fa-edit"></i></a>  <a href="{{url("admin/pegawai")}}/'+data+'" class="btn btn-danger btn-sm delete-confirm" title="Hapus"><i class="fa fa-times"></i></a>  </div>'
                        },
                    },
            ]
    });
</script>

<script>
    $('body').on('click', '.button-update', function (event) {
        event.preventDefault();
        $('.modal-title').html('Edit Pegawai');
        $('#btn')
            .removeClass('btn-primary')
            .addClass('btn-info')
            .html('Update')
            .val('update');
        $('#btn-cancel')
            .removeClass('btn-outline-primary')
            .addClass('btn-outline-info')
            .text('Batal');
        let id = $(this).data('id');
        $.get('/admin/pegawai/' + id + '/edit', function (data) {
            $('#id').val(data.data.id);
            $('#nik').val(data.data.nik);
            $('#noktp').val(data.data.noktp);
            $('#nama').val(data.data.nama);
            $('#tempat_lahir').val(data.data.tempat_lahir);
            $('#tanggal_lahir').val(data.data.tanggal_lahir);
            $('#jenkel').val(data.data.jenkel);
            $('#alamat').val(data.data.alamat);
            $('#rtrw').val(data.data.rtrw);
            $('#kelurahan').val(data.data.kelurahan);
            $('#kecamatan').val(data.data.kecamatan);
            $('#kota').val(data.data.kota);
            $('#provinsi').val(data.data.provinsi);
            $('#kodepos').val(data.data.kodepos);
            $('#telepon').val(data.data.telepon);
            $('#stkwn').val(data.data.stkwn);
            $('#modal-pegawai').modal('show');
        })
    });
</script>


<script>
    $('body').on('click', '.delete-confirm', function (event) {
        event.preventDefault();

        var me = $(this),
            url = me.attr('href'),
            title = me.attr('title'),
            csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
					title: 'Are you sure?',
					text: "You won't be able to revert this!",
					type: 'warning',
					buttons:{
						confirm: {
							text : 'Yes, delete it!',
							className : 'btn btn-success'
						},
						cancel: {
							visible: true,
							className: 'btn btn-danger'
							}
						}
        }).then((Delete) => {
            if(Delete){
                $.ajax({
                    url : url,
                    type : 'POST',
                    data : {
                        '_method' : 'DELETE',
                        '_token' : csrf_token
                    },
                    success: function(response){
                        $('#table-pegawai').DataTable().ajax.reload();
                        swal("Good job!", "Successfully Delete Pegawai", {
						icon : "success",
						buttons: {
							confirm: {
								className : 'btn btn-success'
							}
						},
					});
                    },
                    error: function(xhr){
                        swal("Oops....!", "Something went wrong!", {
						icon : "error",
						buttons: {
							confirm: {
								className : 'btn btn-danger'
							}
						},
					});
                    }
                })
            }
        })

});
</script>

@endpush
