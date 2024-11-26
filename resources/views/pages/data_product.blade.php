@extends('templates.app')

@section('content-dinamis')  
    <div class="container mt-5">
        <div class="d-flex justify-content-end">
            <form class="d-flex me-3" action="{{ route('data_produk.data') }}" method="GET">
                {{-- 1. tag form harus ada action sama method
                    2. value method GET/POST
                        - GET : form yg fungsinya untuk mencari
                        - POST : form yg fungsinya untuk menambah/menghapus/mengubah
                    3. input harus ada attr name, name => mengambil data dr isian input agar bisa di proses di controller
                    4. ada button/input yg type="submit"
                    5. action
                        - form untuk mencari : action ambil route yg menampilkan halaman blade ini (return view blade ini)
                        - form bukan mencari : action terpisah dengan route return view bladenya
                 --}}
                @if (Request::get('sort_stock') == 'stock')
                    <input type="hidden" name="sort_stock" value="stock">
                @endif
                <input type="text" name="cari" placeholder="search product name..." class="form-control me-2">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
            <form action="{{ route('data_produk.data') }}" method="GET" class="me-2">
                <input type="hidden" name="sort_stock" value="stock">
                <button type="submit" class="btn btn-primary">Sort Stock</button>
            </form>
            {{-- <button class="btn btn-success">+ Tambah</button> --}}

            <a href="{{ route('data_produk.tambah')}}" class="btn btn-success">+ Add</a>
        </div>

        @if(Session::get('success'))
        <div class="alert alert-success">
            {{ Session::get('success')}}
        </div>
    @endif

        <table class="table table-stripped table-bordered mt-3 text-center">

            <thead>
                <th>#</th>
                <th>Product Name</th>
                <th>Type</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Action</th>
            </thead>
            <tbody>
                {{-- jika data obat kosong --}}
                @if (count($products) < 0)
                    <tr>
                        <td colspan="6">Product Data is Empty</td>
                    </tr>
                @else
                {{-- $products : dari compact controller nya, diakses dengan loop karna data $products banyak (array) --}}
                    @foreach ($products as $index => $item)
                        <tr>
                            <td>{{ ($products->currentPage()-1) * ($products->perpage()) + ($index+1) }}</td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['type'] }}</td>
                            <td>Rp. {{ number_format($item['price'], 0, ',', '.') }}</td>
                            <td class="{{ $item['stock'] <= 3 ? 'bg-danger text-white' : '' }}" style="cursor: pointer" onclick="showModalStock({{ $item['id'] }}, {{$item['stock'] }})">{{ $item['stock'] }}</td>
                            {{-- $item['column_di_migration'] --}}
                            <td class="d-flex justify-content-center">
                                <a href="{{ route('data_produk.ubah' , $item['id']) }}" class="btn btn-primary me-2">Edit</a>
                                {{-- showModalDelete mengirim data id untuk spesifik data yang dihapus, name untuk nama obat didalam modal --}}
                                {{-- $item dari foreach --}}
                                <button class="btn btn-danger" onclick="showModalDelete('{{ $item->id }}', '{{ $item->name}}')">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        {{-- memanggil pagination --}}
        <div class="d-flex justify-content-end my-3">
            {{ $products->links() }}
        </div>
    </div>
    <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form class="modal-content"  method="POST" action="">
        {{-- action kosong, diisi melalui js karena id dikirim ke js nya  --}}
        @csrf
        {{-- menimpa method="POST" jadi DELETE sesuai web.php http-method  --}}
        @method('DELETE')
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">DELETE PRODUCT DATA</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- konten dinamis pada teks ini bagian nama obat, sehingga nama obatnya disediakan tempat penyimpanan (tag b)--}}
            Are You Sure You Want to Delete The Data Product of <b id="nama_obat"></b>?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger">Delete</button>
        </div>
    </form>
    </div>
  </div>

  {{-- modal stock --}}
  <div class="modal fade" id="modalEditStock" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form class="modal-content"  method="POST" action="">
        {{-- action kosong, diisi melalui js karena id dikirim ke js nya  --}}
        @csrf
        {{-- menimpa method="POST" jadi DELETE sesuai web.php http-method  --}}
        @method('PATCH')
        <div class="modal-header">
          <h1 class="modal-title" id="exampleModalLabel">Edit Product Stock</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            {{-- konten dinamis pada teks ini bagian nama obat, sehingga nama obatnya disediakan tempat penyimpanan (tag b)--}}
         <div class="form-group">
            <label for="stock" class="form-label">Stock : </label>
            <input type="number" name="stock" id="stock" class="form-control">
         </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Edit</button>
        </div>
    </form>
    </div>
  </div>
@endsection

@push('script')
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    function showModalDelete(id,name) {
        // memasukan teks dari parameter ke html bagian id="nama_obat"
        $("#nama_obat").text(name); // $ yaitu jquery, mempersingkat
        // memanggil route hapus
        let url = "{{ route('data_produk.hapus', ':id') }}";
        // isi path dinamis :id dari data parameter id
        url = url.replace(':id', id);
        // action="" di form diisi dengan url diatas
        $('form').attr('action', url);
        //memunculkan modal dengan id="exampleModal"
        $("#exampleModal").modal('show');
    }


    function showModalStock(id, stock) {
        $('#stock').val(stock);
        let url = "{{ route('data_produk.ubah.stock', ':id') }}";
        url = url.replace(':id', id);
        $('form').attr('action', url);
        $('#modalEditStock').modal('show');
    }

    @if (Session::get('failed'))
    $(document).ready( function(){
        let id =  "{{ Session::get('id') }}";
        let stock =   "{{ Session::get('stock') }}";
        showModalStock(id, stock);
    })
    @endif
</script>
@endpush