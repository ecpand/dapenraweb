@extends('backend.master')
@section('title')
Otentikasi
@endsection

@section('content')
<div class="panel-header bg-primary-gradient">
    <div class="page-inner py-5">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
            <div>
                <h2 class="text-white pb-2 fw-bold">Data Otentikasi</h2>
                <h5 class="text-white op-7 mb-2">Dana Pensiun Angkasa Pura I</h5>

            </div>
            <div class="ml-md-auto py-2 py-md-0">
                <!--<button style="width: 100px" type="button" id="add" class="btn  btn-round btn-secondary ">Add</button>-->
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
                        <table id="table-otentikasi" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>NIK</th>
                                    <th>Nama Penerima</th>
                                    <th>Jenis Manfaat</th>
                                    <th>Tempat Lahir</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Alamat</th>
                                    <th>Telepon</th>
                                    <th>Telepon Kerabat</th>
                                    <th>Foto</th>
                                    <th>Status Penerima</th>
                                    <th>Status Otentikasi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('admin.otentikasi.modals.modal_otentikasi')
@endsection
@push('script')
<script>
    $('#table-otentikasi').DataTable({
        responsive: true,
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.otentikasi.index') }}",
                columns: [
                {data: 'tanggal_waktu', name: 'tanggal_waktu'},
                {data: 'rekam_nip', name: 'rekam_nip'},
                {data: 'rekam_nama', name: 'rekam_nama'},
                {data: 'rekam_jenis', name: 'rekam_jenis'},
                {data: 'rekam_tempat_lahir', name: 'rekam_tempat_lahir'},
                {data: 'rekam_tanggal_lahir', name: 'rekam_tanggal_lahir'},
                {data: 'rekam_alamat', name: 'rekam_alamat'},
                {data: 'rekam_telepon', name: 'rekam_telepon'},
                {data: 'rekam_teleponk', name: 'rekam_teleponk'},
                {data: 'foto', name: 'foto'},
                {data: 'status_penerima', name: 'status_penerima'},
                {data: 'status_otentikasi', name: 'status_otentikasi'},
                /*
                {data: 'id', name: 'id',
                "render": function (data, type, row, meta) {
                        return'<div> <a href="{{url("admin/pegawai")}}/'+data+'/edit"class="btn btn-primary btn-sm  button-update" title="Edit" data-id='+data+'><i class="fa fa-edit"></i></a>  <a href="{{url("admin/pegawai")}}/'+data+'" class="btn btn-danger btn-sm delete-confirm" title="Hapus"><i class="fa fa-times"></i></a>  </div>'
                        },
                    },*/
            ]
    });
</script>
@endpush
