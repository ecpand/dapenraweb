<div class="modal fade" id="modal-tertanggung" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="width: 600px">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <form id="tertanggung" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="nip">NIP</label>
                            <input class="form-control" type="text" name="nik" id="nik">
                        </div>
                        <div class="form-group">
                            <label for="noktp">No KTP</label>
                            <input class="form-control" type="number" name="noktp" id="noktp">
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input class="form-control" type="text" name="nama" id="nama">
                        </div>
                        <div class="form-group">
                            <label for="tempat_lahir">Tempat Lahir</label>
                            <input class="form-control" type="text" name="tempat_lahir" id="tempat_lahir">
                        </div>
                        <div class="form-group">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <input class="form-control" type="date" name="tanggal_lahir" id="tanggal_lahir">
                        </div>
                        <div class="form-group">
                            <label for="jenkel">Jenis Kelamin</label>
                            <select class="form-control" name="jenkel">
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input class="form-control" type="text" name="alamat" id="alamat">
                        </div>
                        <div class="form-group">
                            <label for="rtrw">RT / RW</label>
                            <input class="form-control" type="text" name="rtrw" id="rtrw">
                        </div>
                        <div class="form-group">
                            <label for="kelurahan">Kelurahan</label>
                            <input class="form-control" type="text" name="kelurahan" id="kelurahan">
                        </div>
                        <div class="form-group">
                            <label for="kecamatan">Kecamatan</label>
                            <input class="form-control" type="text" name="kecamatan" id="kecamatan">
                        </div>
                        <div class="form-group">
                            <label for="kota">Kota</label>
                            <input class="form-control" type="text" name="kota" id="kota">
                        </div>
                        <div class="form-group">
                            <label for="provinsi">Provinsi</label>
                            <input class="form-control" type="text" name="provinsi" id="provinsi">
                        </div>
                        <div class="form-group">
                            <label for="kodepos">Kode Pos</label>
                            <input class="form-control" type="text" name="kodepos" id="kodepos">
                        </div>
                        <div class="form-group">
                            <label for="telepon">Telepon</label>
                            <input class="form-control" type="text" name="telepon" id="telepon">
                        </div>
                        <div class="form-group">
                            <label for="stkwn">STKWN</label>
                            <select class="form-control" name="stkwn">
                                <option value="K2">K2</option>
                                <option value="K1">K1</option>
                                <option value="J1">J1</option>
                                <option value="J2">J2</option>
                                <option value="A1">A1</option>
                                <option value="A2">A2</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="hidden" name="id" id="id">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" id="btn" value="simpan" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
