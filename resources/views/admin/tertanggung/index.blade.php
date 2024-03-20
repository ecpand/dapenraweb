@extends('backend.master')
@section('title')
Tertanggung
@endsection

@section('content')
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-5">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <div>
                <h2 class="text-white pb-2 fw-bold">Data Tertanggung</h2>
                <h5 class="text-white op-7 mb-2">Dana Pensiun Angkasa Pura I</h5>

            </div>
            <div class="ml-md-auto py-2 py-md-0">
                <button style="width: 100px" type="button" id="add" class="btn  btn-round btn-secondary ">Add</button>
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
                        <table id="table-tertanggung" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>NIP</th>
                                    <th>Nama Pegawai</th>
                                    <th>No Urut</th>
                                    <th>Nama Tertanggung</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tempat Lahir</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Alamat</th>
                                    <th>Telepon</th>
                                    <th>Status Keluarga</th>
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
@include('admin.tertanggung.modals.modal_tertanggung')
@endsection


@push('script')


<script>
    $('#add').on('click', function() {
        $('.modal-title').html('Tambah Tertanggung');
        $('#id').val("");
        $('#nik').val("");
        $('#no_urut').val("");
        $('#nama').val("");
        $('#tempat_lahir').val("");
        $('#tanggal_lahir').val("");
        $('#alamat').val("");
        $('#telepon').val("");
        $('#modal-tertanggung').modal('show');
        $('#btn')
            .removeClass('btn-info')
            .addClass('btn-primary')
            .html('Save')
            .val('simpan');
    });
</script>

<script>
    $('#tertanggung').submit(function(e){
        e.preventDefault();
            var form = $('form');
            form.find('span').remove();
            form.find('.form-group').removeClass('is-invalid');
            form.find('.form-control').removeClass('is-invalid');
            if ($('#btn').val() == 'simpan') {
                var url = "{{route('admin.tertanggung.store')}}";
                var type = "POST";
                var text = "Successfully Create Tertanggung";
            }
            if ($('#btn').val() == 'update') {
                var id = $("#id").val();
                var type = "PUT";
                var url = "{{url('admin/tertanggung')}}/"+ id;
                var text = "Successfully Update Tertanggung";
            }

            $.ajax({
                url: url,
                type:type,
                data : form.serialize(),
                success: function (response){
                    $('#modal-tertanggung').modal('hide');
                    $('#table-tertanggung').DataTable().ajax.reload();
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
    $('#table-tertanggung').DataTable({
        responsive: true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.tertanggung.index') }}",
                columns: [
                {data: 'pegawai_nip', name: 'pegawai_nip'},
                {data: 'pegawai_nama', name: 'pegawai_nama'},
                {data: 'no_urut', name: 'no_urut'},
                {data: 'nama_tertanggung', name: 'nama_tertanggung'},
                {data: 'jenkel', name: 'jenkel'},
                {data: 'tempat_lahir', name: 'tempat_lahir'},
                {data: 'tanggal_lahir', name: 'tanggal_lahir'},
                {data: 'alamat', name: 'alamat'},
                {data: 'telepon', name: 'telepon'},
                {data: 'status', name: 'status'},
                {data: 'id', name: 'id',
                "render": function (data, type, row, meta) {
                        return'<div> <a href="{{url("admin/tertanggung")}}/'+data+'/edit"class="btn btn-primary btn-sm  button-update" title="Edit" data-id='+data+'><i class="fa fa-edit"></i></a>  <a href="{{url("admin/tertanggung")}}/'+data+'" class="btn btn-danger btn-sm delete-confirm" title="Hapus"><i class="fa fa-times"></i></a>  </div>'
                        },
                    },
            ]
    });
</script>

<script>
    $('body').on('click', '.button-update', function (event) {
        event.preventDefault();
        $('.modal-title').html('Edit Tertanggung');
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
        $.get('/admin/tertanggung/' + id + '/edit', function (data) {
            $('#id').val(data.data.id);
            $('#nik').val(data.nik);
            $('#no_urut').val(data.data.no_urut);
            $('#nama').val(data.data.nama_tertanggung);
            $('#jenkel').val(data.data.jenkel);
            $('#tempat_lahir').val(data.data.tempat_lahir);
            $('#tanggal_lahir').val(data.data.tanggal_lahir);
            $('#alamat').val(data.data.alamat);
            $('#telepon').val(data.data.telepon);
            $('#status').val(data.data.status);
            $('#modal-tertanggung').modal('show');
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
                        $('#table-tertanggung').DataTable().ajax.reload();
                        swal("Good job!", "Successfully Delete Tertanggung", {
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
